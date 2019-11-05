<?php
declare(strict_types=1);

namespace SomeNameSpace\Component\YandexMarketIntegration;

use Nette\Neon\Exception;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareTrait;
use SomeNameSpace\Component\YandexMarketIntegration\Service\OutletsService;
use SomeNameSpace\Component\YandexMarketIntegration\Exception\OutletNotFoundException;
use SomeNameSpace\Component\YandexMarketIntegration\Model\YandexMarketOutlet;

/**
 * Обработчик точек продаж ЯндексМаркет
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class OutletsProcessor
{
    use LoggerAwareTrait;

    /**
     * @var OutletsService Сервис для работы с точеками продаж на сервисе Яндекс маркет
     */
    private $outletsService;

    /**
     * Время задержки между запросами
     */
    private const REQUEST_PAUSE_TIME = 1;

    /**
     * Конструктор.
     *
     * @param OutletsService $outletsService Сервис обработки точек
     * @param LoggerInterface $logger Логгер
     */
    public function __construct(OutletsService $outletsService, LoggerInterface $logger)
    {
        $this->outletsService = $outletsService;
        $this->setLogger($logger);
    }

    /**
     * Метод обработки всех точек продаж по зонам
     */
    public function processQueue(): void
    {
        $this->logger->info('Обработка точек продаж');

        try {
            $campaigns = $this->outletsService->getCampaignsList();
        } catch (Exception $e) {
            $this->logger->error(
                'Ошибка при получении списка кампаний ЯндексМаркета',
                ['exception' => $e->getMessage()]
            );

            return;
        }

        foreach ($campaigns as $campaign) {
            if ($campaign->getSpaceId() !== 'msk_cl') {
                continue;
            }

            $this->logger->info(
                'Обработка точек продаж для простанства.',
                ['space_id' => $campaign->getSpaceId()]
            );

            try {
                $outlets = $this->outletsService->getOutlets($campaign);
            } catch (OutletNotFoundException | \Exception $e) {
                $this->logger->error('Не получены точки продаж с сервиса', ['exception' => $e->getMessage()]);
                continue;
            }

            $stores = $this->outletsService->getBySpaceId($campaign->getSpaceId());
            if (count($stores) === 0) {
                $this->logger->info('Для данного пространства отсутствуют точки продаж.');
                continue;
            }
            foreach ($stores as $item => $store) {
                if ($store->getAddress()->getCityId() == null) {
                    $cityId = $this->outletsService->findYandexCityId($store);
                    if ($cityId === null) {
                        $this->logger->error(
                            'Для точки продаж не получены данные о населенном пункте.',
                            ['pup_id' => $store->getShopOutletCode()]
                        );
                        unset($stores[$item]);
                        continue;
                    }
                    $store->getAddress()->setCityId($cityId);
                    $this->outletsService->updateOutletCityId($store);
                }
            }
            $this->processCampaignOutlets($campaign->getCampaignId(), $stores, $outlets);

            $this->logger->info(
                'Обработка точек продаж для пространства завершена.',
                ['space_id' => $campaign->getSpaceId()]
            );
        }

        $this->logger->info('Обработка точек продаж завершена.');
    }

    /**
     * Обработка точек продаж для заданной зоны
     *
     * @param int $campaignId Идентификатор пространства, на ЯндексМаркет.
     * @param YandexMarketOutlet[] $stores Массив точек продаж
     * @param YandexMarketOutlet[] $outlets Массив точек продаж зарегистрированных на ЯндексМаркет
     */
    private function processCampaignOutlets(int $campaignId, array $stores, array $outlets): void
    {
        foreach ($stores as $storeId => $store) {
            if (!isset($outlets[$storeId])) {
                $this->outletsService->createOutlet($campaignId, $store);
                continue;
            }
            $outlet = $outlets[$storeId];

            if ($outlet->getId() !== null) {
                $yandexId = $outlet->getId();
                $this->outletsService->updateOutlet($campaignId, $yandexId, $store);
            }
            unset($outlets[$storeId]);
            sleep(self::REQUEST_PAUSE_TIME);
        }

        if (!empty($outlets)) {
            $this->logger->info(
                'В текущей кампании на ЯндексМаркете присутствуют следующие точки, которые невозможно соотнести:',
                ['outlets' => implode(', ', array_keys($outlets))]
            );

            foreach ($outlets as $outlet) {
                $yandexId = $outlet->getId();
                if ($yandexId === null) {
                    continue;
                }
                $this->outletsService->deleteOutlet($campaignId, $yandexId);
                sleep(self::REQUEST_PAUSE_TIME);
            }

            $this->logger->info('Не соотнесенные точки продаж удалены с ЯндексМаркета.');
        }
    }
}
