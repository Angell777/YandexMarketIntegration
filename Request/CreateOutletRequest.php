<?php
declare(strict_types=1);

namespace SomeNameSpace\Component\YandexMarketIntegration\Request;

use SomeNameSpace\Component\ApiConnector\AbstractRequest;
use SomeNameSpace\Component\ApiConnector\Enum\HttpMethod;

/**
 * Класс запроса для создания точки продаж
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class CreateOutletRequest extends AbstractRequest
{
    /**
     * Конструктор.
     *
     * @param int $campaignId Id кампании
     * @param array $outletData Массив с информацией о точке продаж
     */
    public function __construct(int $campaignId, array $outletData)
    {
        parent::__construct();

        $this->addPathParam('campaignId', $campaignId);
        $this->setJson($outletData);
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod(): HttpMethod
    {
        return HttpMethod::POST();
    }

    /**
     * {@inheritdoc}
     */
    public function getPathTemplate(): string
    {
        return 'campaigns/{campaignId}/outlets.json';
    }
}
