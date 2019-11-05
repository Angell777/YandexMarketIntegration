<?php
declare(strict_types=1);

namespace SomeNameSpace\Component\YandexMarketIntegration\Request;

use SomeNameSpace\Component\ApiConnector\AbstractRequest;
use SomeNameSpace\Component\ApiConnector\Enum\HttpMethod;

/**
 * Класс запроса для удаления точки продаж
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class DeleteOutletRequest extends AbstractRequest
{
    /**
     * Конструктор.
     *
     * @param int $campaignId Id компании
     * @param int $outletId Id точки продаж
     */
    public function __construct(int $campaignId, int $outletId)
    {
        parent::__construct();

        $this->addPathParam('campaignId', $campaignId);
        $this->addPathParam('outletId', $outletId);
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod(): HttpMethod
    {
        return HttpMethod::DELETE();
    }

    /**
     * {@inheritdoc}
     */
    public function getPathTemplate(): string
    {
        return 'campaigns/{campaignId}/outlets/{outletId}.json';
    }
}
