<?php
declare(strict_types=1);

namespace SomeNameSpace\Component\YandexMarketIntegration\Model;

use SomeNameSpace\Component\Model\FastAbstractModel;

/**
 * Класс кампании на Яндекс Маркете.
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class YandexMarketCampaign extends FastAbstractModel
{
    /**
     * @var int Идентификатор кампании.
     */
    protected $campaignId;

    /**
     * @var string Доменное название кампании.
     */
    protected $domain;

    /**
     * @var string Пространство.
     */
    protected $spaceId;

    /**
     * Возвращает id кампании
     *
     * @return int Id магазина
     */
    public function getCampaignId(): int
    {
        return $this->campaignId;
    }

    /**
     * Возвращает доменное название кампании.
     *
     * @return string доменное название магазина.
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * Возвращает пространство
     *
     * @return string Пространство
     */
    public function getSpaceId(): string
    {
        return $this->spaceId;
    }
}
