<?php
declare(strict_types=1);

namespace SomeNameSpace\Component\YandexMarketIntegration\Model;

use SomeNameSpace\Component\Model\FastAbstractModel;

/**
 * Данные о населенном пункте для точки продаж
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class StoreRegion extends FastAbstractModel
{
    /**
     * @var string Идентификатор точки продаж.
     */
    protected $pupId;

    /**
     * @var int Id региона.
     */
    protected $regionId;

    /**
     * @var string Назвнаие региона
     */
    protected $regionName;

    /**
     * @var int|null Id города.
     */
    protected $cityId;

    /**
     * Возвращет идентификатор точки продаж.
     *
     * @return string Идентификатор
     */
    public function getPupId(): string
    {
        return $this->pupId;
    }

    /**
     * Возвращает Id региона.
     *
     * @return int Id региона
     */
    public function getRegionId(): int
    {
        return $this->regionId;
    }

    /**
     * Возвращает название региона
     *
     * @return string Регион
     */
    public function getRegionName(): string
    {
        return $this->regionName;
    }

    /**
     * Возвращает Id города.
     *
     * @return int|null Id города.
     */
    public function getCityId(): ?int
    {
        return $this->cityId;
    }
}
