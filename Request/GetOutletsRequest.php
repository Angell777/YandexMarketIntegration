<?php
declare(strict_types=1);

namespace SomeNameSpace\YandexMarketOutlet\Component\YandexMarketIntegration\Request;

use SomeNameSpace\YandexMarketOutlet\Component\ApiConnector\AbstractRequest;
use SomeNameSpace\YandexMarketOutlet\Component\ApiConnector\Enum\HttpMethod;

/**
 * Класс запроса для получения точек продаж
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class GetOutletsRequest extends AbstractRequest
{
    /**
     * Конструктор.
     *
     * @param int $campaignId Id компании
     * @param int $page Номер страницы запросы
     * @param int $pageSize Размер элементов на странице запроса
     */
    public function __construct(int $campaignId, int $page, int $pageSize)
    {
        parent::__construct();

        $this->addPathParam('campaignId', $campaignId);
        $this->addQueryParam('page', $page);
        $this->addQueryParam('pageSize', $pageSize);
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod(): HttpMethod
    {
        return HttpMethod::GET();
    }

    /**
     * {@inheritdoc}
     */
    public function getPathTemplate(): string
    {
        return 'campaigns/{campaignId}/outlets.json';
    }
}
