<?php
declare(strict_types=1);

namespace SomeNameSpace\YandexMarketOutlet\Component\YandexMarketIntegration\Request;

use SomeNameSpace\YandexMarketOutlet\Component\ApiConnector\AbstractRequest;
use SomeNameSpace\YandexMarketOutlet\Component\ApiConnector\Enum\HttpMethod;

/**
 * Класс запроса для обновления точки продаж
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class UpdateOutletRequest extends AbstractRequest
{
    /**
     * Конструктор.
     *
     * @param int $campaignId Id компании
     * @param int $outletId Id точки продаж
     * @param array $outletData Массив с информацией о точке продаж
     */
    public function __construct(int $campaignId, int $outletId, array $outletData)
    {
        parent::__construct();

        $this->addPathParam('campaignId', $campaignId);
        $this->addPathParam('outletId', $outletId);
        $this->setJson($outletData);
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod(): HttpMethod
    {
        return HttpMethod::PUT();
    }

    /**
     * {@inheritdoc}
     */
    public function getPathTemplate(): string
    {
        return 'campaigns/{campaignId}/outlets/{outletId}.json';
    }
}
