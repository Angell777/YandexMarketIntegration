<?php
declare(strict_types=1);

namespace SomeNameSpace\YandexMarketOutlet\Component\YandexMarketIntegration\Service;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareTrait;
use SomeNameSpace\Api\Store\Filter\Store\SpaceIdStoreFilter;
use SomeNameSpace\Api\Store\Repository\StoreRepository;
use Symfony\Component\Serializer\Serializer;
use SomeNameSpace\Component\YandexMarketIntegration\Source\StoreSource;
use SomeNameSpace\Component\YandexMarketIntegration\Model\Pager;
use SomeNameSpace\Component\YandexMarketIntegration\Model\YandexMarketOutlet;
use SomeNameSpace\Component\YandexMarketIntegration\Response\OutletsResponse;
use SomeNameSpace\Component\YandexMarketIntegration\Response\RegionsResponse;
use SomeNameSpace\Component\YandexMarketIntegration\Model\YandexMarketCampaign;
use SomeNameSpace\Component\YandexMarketIntegration\Exception\OutletNotFoundException;
use SomeNameSpace\Component\ApiConnector\ApiConnectorException;
use SomeNameSpace\Component\YandexMarketIntegration\Enum\ResponseStatus;
use SomeNameSpace\Component\YandexMarketIntegration\Model\YMarketResponse;
use SomeNameSpace\Component\YandexMarketIntegration\Response\YandexResponse;

/**
 * Сервис для работы с точками продаж на сервисе Яндекс.Маркет
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class OutletsService
{
    use LoggerAwareTrait;

    /**
     * @var YandexApi Сервис запросов для ЯндексМаркет
     */
    private $yandexService;

    /**
     * @var StoreRepository Хранилище магазинов
     */
    private $storeRepository;

    /**
     * @var StoreSource Источник данных из базы для магазинов
     */
    private $storeSource;

    /**
     * @var Serializer Сериализатор
     */
    private $serializer;

    /**
     * Время задержки между запросами
     */
    private const REQUEST_PAUSE_TIME = 1;

    /**
     * Максимальное количество страниц для определения идентификатора населенного пункта
     */
    private const FIND_CITY_ID_REQUEST_MAX_PAGES = 15;

    /**
     * Конструктор.
     *
     * @param YandexApi $yandexApi Сервис запросов для ЯндексМаркет
     * @param StoreRepository $storeRepository Хранилище магазинов
     * @param StoreSource $storeSource Источник данных из базы для магазинов
     * @param LoggerInterface $logger Логгер
     * @param Serializer $serializer Сериализатор YandexMarketOutlet
     */
    public function __construct(
        YandexApi $yandexApi,
        StoreRepository $storeRepository,
        StoreSource $storeSource,
        LoggerInterface $logger,
        Serializer $serializer
    ) {
        $this->yandexService = $yandexApi;
        $this->storeRepository = $storeRepository;
        $this->storeSource = $storeSource;
        $this->setLogger($logger);
        $this->serializer = $serializer;
    }

    /**
     * Получает активные точки продаж заданного пространства из базы
     *
     * @param string $spaceId Идентификатор пространства Ситилинк
     *
     * @return YandexMarketOutlet[] Массив объектов точек продаж
     */
    public function getBySpaceId(string $spaceId): array
    {
        $storeRegionData = $this->storeSource->getStoresDataBySpace($spaceId);
        $stores = $this->storeRepository->filter(new SpaceIdStoreFilter([$spaceId]))->findAll();

        $outlets = [];
        foreach ($stores as $store) {
            try {
                $outlet = $this->serializer->denormalize($store, YandexMarketOutlet::class, null, ['store']);
            } catch (\Exception $e) {
                $this->logger->warning(
                    'Не удалось десериализовать данные точки продаж. ',
                    ['pup_id' => $store->getStoreId()->getPupSpaceId(), 'message' => $e->getMessage()]
                );
                continue;
            }
            $outletId = $outlet->getShopOutletCode();

            if (!isset($storeRegionData[$outletId])) {
                continue;
            }
            $region = $storeRegionData[$outletId];
            $outlet->getAddress()->setRegionId($region->getRegionId());
            $outlet->getAddress()->setCityId($region->getCityId());
            $outlets[$outletId] = $outlet;
        }

        return $outlets;
    }

    /**
     * Возвращает соотнесенные пространства и соответствуюих им кампаний на ЯндексМаркет
     *
     * @return YandexMarketCampaign[] Массив объектов соотвенсенных пространств (space_id => кампания ЯндексМаркет)
     */
    public function getCampaignsList(): array
    {
        return $this->storeSource->getCampaignsList();
    }

    /**
     * Возвращает список точек продаж, зарегистрированных на ЯндексМаркет, в заданной зоне.
     *
     * @param YandexMarketCampaign $campaign Идентификатор зоны, на ЯндексМаркет.
     * @throws \Exception В случае ошибки обмена имформации
     *
     * @return YandexMarketOutlet[] Массив объектов с информацией о точках продаж
     */
    public function getOutlets(YandexMarketCampaign $campaign): array
    {
        $outletsData = [];

        $page = 1;
        $pageSize = 50;
        while (true) {
            /** @var YandexResponse $response */
            $response = $this->yandexService->getOutlets($campaign->getCampaignId(), $page, $pageSize);
            if (!$response->isOk()) {
                throw new \Exception(
                    sprintf(
                        'Проблема при выполнении запроса получения точек. ' .
                        'Пространство: %s, домен: %s, campaign: %s, описание ошибки %s',
                        $campaign->getSpaceId(),
                        $campaign->getDomain(),
                        $campaign->getCampaignId(),
                        $response->getResponse()->getBody()->getContents()
                    )
                );
            }
            try {
                /** @var OutletsResponse $responseData */
                $responseData = $this->serializer->deserialize(
                    $response->getData(),
                    OutletsResponse::class,
                    'json',
                    ['yandex']
                );
            } catch (\Exception $e) {
                throw new \Exception(
                    sprintf(
                        'Ошибка десериализации информации с сервиса Яндекс.Маркет. Кампания: %s, ошибка: %s',
                        $campaign->getDomain(),
                        $e->getMessage()
                    )
                );
            }

            /** @var Pager $pager */
            $pager = $responseData->getPager();
            if ($pager == null) {
                throw new OutletNotFoundException(
                    printf(
                        'Ошибка с получением данных из Яндекс.Маркета для магазина %s.\n',
                        $campaign->getDomain()
                    )
                );
            }
            $outletsData = array_merge($outletsData, $responseData->getOutlets());

            if ($pager->getPageSize() === 0) {
                break;
            }
            $page++;
        }

        if (count($outletsData) === 0) {
            return [];
        }

        $outlets = [];
        foreach ($outletsData as $outlet) {
            $storeId = $outlet->getShopOutletCode();
            $outlets[$storeId] = $outlet;
        }

        return $outlets;
    }

    /**
     * Определяет идентификатор населенного пункта точки продаж
     *
     * @param YandexMarketOutlet $store Точка продаж
     * @throws \Exception В случае ошибки обмена имформации
     *
     * @return int|null Идентификатор населенного пункта
     */
    public function findYandexCityId(YandexMarketOutlet $store): ?int
    {
        $storeCity = $store->getAddress()->getCity();
        $cityId = null;
        $storeRealRegionId = $store->getAddress()->getRegionId();

        for ($page = 0; $page < self::FIND_CITY_ID_REQUEST_MAX_PAGES; $page++) {
            /** @var YandexResponse $response */
            $response = $this->yandexService->findCityId($storeCity, $page);
            if (!$response->isOk()) {
                return $storeRealRegionId;
            }

            try {
                /** @var RegionsResponse $regionsData */
                $regionsData = $this->serializer->deserialize(
                    $response->getData(),
                    RegionsResponse::class,
                    'json'
                );
            } catch (\Exception $e) {
                $this->logger->warning(
                    'Ошибка десериализации ответа сервиса для получения кода города точки продаж (магазина)' .
                    ' на сервис Яндекс.Маркет. ',
                    ['pup_id' => $store->getShopOutletCode(), 'exception' => $e->getMessage()]
                );

                return $storeRealRegionId;
            }

            $regions = $regionsData->getRegions();
            if (count($regions) === 0) {
                return $storeRealRegionId;
            }

            foreach ($regions as $region) {
                if ($storeRealRegionId === $region->getId()) {
                    $cityId = $storeRealRegionId;
                    break;
                }

                $parents = [];
                $parent = $region->getParent();
                if ($parent === null) {
                    continue;
                }

                do {
                    $parents[(int)$parent->getId()] = $parent->getName();
                    $parent = $parent->getParent();
                } while ($parent !== null);

                $parentIds = array_keys($parents);
                $parentsNames = array_values($parents);
                if (in_array($storeRealRegionId, $parentIds) ||
                    in_array($store->getAddress()->getRegion(), $parentsNames)
                ) {
                    $cityId = $region->getId();
                    break;
                }
            }
            if ($cityId !== null) {
                break;
            }
            sleep(self::REQUEST_PAUSE_TIME);
        }

        return $cityId !== null ? $cityId : $storeRealRegionId;
    }

    /**
     * Создает новую точку продаж на ЯндексМаркете
     *
     * @param int $campaignId Идентификатор кампании, на ЯндексМаркет.
     * @param YandexMarketOutlet $outlet Объект точки продаж для создания на ЯндексМаркет.
     *
     * @return bool Успешность выполнения запроса
     */
    public function createOutlet(int $campaignId, YandexMarketOutlet $outlet): bool
    {
        $outletData = $this->serializer->normalize($outlet, 'json');
        /** @var YandexResponse $response */
        $response = $this->yandexService->createOutlets($campaignId, $outletData);
        if (!$response->isOk()) {
            $this->logger->warning(
                'Ошибка отправки информации создания точки продаж (магазина) на сервис Яндекс.Макрет.',
                [
                    'campaignId' => $campaignId,
                    'pup_id' => $outlet->getShopOutletCode(),
                    'message' => $response->getResponse()->getBody()->getContents(),
                ]
            );

            return false;
        }

        try {
            /** @var YMarketResponse $serviceResponse */
            $serviceResponse = $this->serializer->deserialize(
                $response->getData(),
                YMarketResponse::class,
                'json'
            );
        } catch (ApiConnectorException $e) {
            $this->logger->warning(
                'Ошибка десериализации ответа сервиса о создании точки продаж (магазина).',
                ['exception' => $e->getMessage(), 'campaignId' => $campaignId, 'pup_id' => $outlet->getShopOutletCode()]
            );

            return false;
        }

        if ($serviceResponse->getStatus()->getValue() === ResponseStatus::ERROR) {
            $errors = $serviceResponse->getErrors();
            foreach ($errors as $error) {
                $this->logger->error(
                    'Ошибка при создании точки продаж на сервисе ЯндексМаркет',
                    ['error' => $error['message'], 'code' => $error['code']]
                );
            }

            return false;
        }

        return true;
    }

    /**
     * Оновление информации о магазине на ЯндексМаркете
     *
     * @param int $campaignId Идентификатор кампании, на ЯндексМаркет.
     * @param int $yandexId Идентификатор точки продаж, присвоенный Яндекс.Маркет.
     * @param YandexMarketOutlet $outlet Объект точки продаж для создания на ЯндексМаркет.
     *
     * @return bool Успешность выполнения запроса
     */
    public function updateOutlet(int $campaignId, int $yandexId, YandexMarketOutlet $outlet): bool
    {
        $outletData = $this->serializer->normalize($outlet, 'json');
        /** @var YandexResponse $response */
        $response = $this->yandexService->updateOutlets($campaignId, $yandexId, $outletData);
        if (!$response->isOk()) {
            $this->logger->warning(
                'Ошибка отправки информации создания точки продаж (магазина) на сервис Яндекс.Макрет.',
                [
                    'campaignId' => $campaignId,
                    'pup_id' => $outlet->getShopOutletCode(),
                    'message' => $response->getResponse()->getBody()->getContents(),
                ]
            );

            return false;
        }

        try {
            /** @var YMarketResponse $serviceResponse */
            $serviceResponse = $this->serializer->deserialize(
                $response->getData(),
                YMarketResponse::class,
                'json'
            );
        } catch (ApiConnectorException $e) {
            $this->logger->error(
                'Ошибка десериализации ответа сервиса об обновлении точки продаж (магазина).',
                ['exception' => $e->getMessage(), 'campaignId' => $campaignId, 'pup_id' => $outlet->getShopOutletCode()]
            );

            return false;
        }


        if ($serviceResponse->getStatus()->getValue() === ResponseStatus::ERROR) {
            $errors = $serviceResponse->getErrors();
            foreach ($errors as $error) {
                $this->logger->error(
                    'Ошибка при обновлении точки продаж на сервисе ЯндексМаркет',
                    ['message' => $error['message'], 'code' => $error['code']]
                );
            }

            return false;
        }

        return true;
    }

    /**
     * Удаление точки в Яндекс.Маркете
     *
     * @param int $campaignId Идентификатор кампании, на ЯндексМаркет.
     * @param int $yandexId Идентификатор точки продаж, присвоенный Яндекс.Маркетом.
     *
     * @return bool Успешность выполнения запроса
     */
    public function deleteOutlet(int $campaignId, int $yandexId): bool
    {
        /** @var YandexResponse $response */
        $response = $this->yandexService->deleteOutlets($campaignId, $yandexId);
        if (!$response->isOk()) {
            $this->logger->warning(
                'Ошибка отправки информации удаления точки продаж (магазина) на сервис Яндекс.Макрет.',
                [
                    'campaignId' => $campaignId,
                    'yandexId' => $yandexId,
                    'message' => $response->getResponse()->getBody()->getContents(),
                ]
            );

            return false;
        }

        try {
            /** @var YMarketResponse $serviceResponse */
            $serviceResponse = $this->serializer->deserialize(
                $response->getData(),
                YMarketResponse::class,
                'json'
            );
        } catch (\Exception $e) {
            $this->logger->warning(
                'Ошибка десериализации ответа сервиса об удалении точки продаж (магазина).',
                ['message' => $e->getMessage(), 'campaignId' => $campaignId, 'yandexId' => $yandexId]
            );

            return false;
        }

        if ($serviceResponse->getStatus()->getValue() === ResponseStatus::ERROR) {
            $errors = $serviceResponse->getErrors();
            foreach ($errors as $error) {
                $this->logger->error(
                    'Ошибка при удалении точки продаж на сервисе ЯндексМаркет',
                    ['message' => $error['message'], 'code' => $error['code']]
                );
            }

            return false;
        }

        return true;
    }

    /**
     * Сохраняет в базу поле идентификатора населенного пункта для точки продаж
     *
     * @param YandexMarketOutlet $outlet Объект точки продаж
     */
    public function updateOutletCityId(YandexMarketOutlet $outlet): void
    {
        $cityId = $outlet->getAddress()->getCityId();
        if ($cityId === null) {
            return;
        }
        $storeId = $outlet->getShopOutletCode();
        $this->storeSource->updateOutletCityId($storeId, $cityId);
    }
}
