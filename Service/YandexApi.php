<?php
declare(strict_types=1);

namespace SomeNameSpace\Component\YandexMarketIntegration\Service;

use SomeNameSpace\Component\Profiler\ProfilingStopwatchInterface;
use SomeNameSpace\Component\SimpleStorage\SimpleStorageInterface;
use Psr\Log\LoggerInterface;
use SomeNameSpace\Component\ApiConnector\AbstractApi;
use SomeNameSpace\Component\ApiConnector\Response;
use SomeNameSpace\Component\YandexMarketIntegration\Request\GetOutletsRequest;
use SomeNameSpace\Component\YandexMarketIntegration\Request\FindCityIdRequest;
use SomeNameSpace\Component\YandexMarketIntegration\Request\CreateOutletRequest;
use SomeNameSpace\Component\YandexMarketIntegration\Request\UpdateOutletRequest;
use SomeNameSpace\Component\YandexMarketIntegration\Request\DeleteOutletRequest;
use SomeNameSpace\Component\YandexMarketIntegration\Response\YandexResponse;

/**
 * Класс для работы с api ЯндексМаркета
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class YandexApi extends AbstractApi
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'yandex-market-integration-api';
    }

    /**
     * Возвращает точки продаж указанного пространства
     *
     * @param int $campaignId Id кампании
     * @param int $page Номер страницы
     * @param int $pageSize Количество элементов на странице
     *
     * @return YandexResponse Response
     */
    public function getOutlets(int $campaignId, int $page, int $pageSize): YandexResponse
    {
        return new YandexResponse(
            $this->request(new GetOutletsRequest($campaignId, $page, $pageSize), __METHOD__)->getResponse()
        );
    }

    /**
     * Возвращает данные для населенного пункта точки продаж
     *
     * @param string $storeCity Наименование населенного пункта
     * @param int $page Страница результатов поиска
     *
     * @return YandexResponse Response
     */
    public function findCityId(string $storeCity, int $page): YandexResponse
    {
        return new YandexResponse(
            $this->request(new FindCityIdRequest($storeCity, $page), __METHOD__)->getResponse()
        );
    }

    /**
     * Возвращаает результат создания точек продаж
     *
     * @param int $campaignId Id кампании
     * @param array $outletData Данные точки продаж
     *
     * @return YandexResponse Response
     */
    public function createOutlets(int $campaignId, array $outletData): YandexResponse
    {
        return new YandexResponse(
            $this->request(new CreateOutletRequest($campaignId, $outletData), __METHOD__)->getResponse()
        );
    }

    /**
     * Возвращаает результат обновления точки продаж
     *
     * @param int $campaignId Id кампании
     * @param int $outletId Id точки продаж
     * @param array $outletData Данные точки продаж
     *
     * @return YandexResponse Response
     */
    public function updateOutlets(int $campaignId, int $outletId, array $outletData): YandexResponse
    {
        return new YandexResponse(
            $this->request(
                new UpdateOutletRequest($campaignId, $outletId, $outletData),
                __METHOD__
            )->getResponse()
        );
    }

    /**
     * Возвращаает результат удаления точки продаж
     *
     * @param int $campaignId Id кампании
     * @param int $outletId Id точки продаж
     *
     * @return YandexResponse Response
     */
    public function deleteOutlets(int $campaignId, int $outletId): Response
    {
        return new YandexResponse(
            $this->request(new DeleteOutletRequest($campaignId, $outletId), __METHOD__)->getResponse()
        );
    }
}
