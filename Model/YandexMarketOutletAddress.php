<?php
declare(strict_types=1);

namespace SomeNameSpace\Component\YandexMarketIntegration\Model;

/**
 * Адреса точки продаж
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class YandexMarketOutletAddress
{
    /**
     * @var int|null Идентификатор региона
     */
    private $regionId;

    /**
     * @var string Название региона
     */
    private $region;

    /**
     * @var int|null Идентификатор города или населенного пункта
     */
    private $cityId;

    /**
     * @var string Название города
     */
    private $city;

    /**
     * @var string Улица
     */
    private $street;

    /**
     * @var string Номер дома
     */
    private $number;

    /**
     * @var string Строение
     */
    private $building;

    /**
     * @var string Владение
     */
    private $estate;

    /**
     * @var string Корпус
     */
    private $block;

    /**
     * @var string Дополнительная информация
     */
    private $additional;

    /**
     * @var int|null Порядковый номер километра дороги, на котором располагается точка продаж, если отсутствует улица.
     */
    private $km;

    /**
     * Конструктор
     *
     * @param int|null $regionId Идентификатор региона
     * @param string $region Название региона
     * @param int|null $cityId Идентификатор города или населенного пункта.
     * @param string $city Наименование города или населенного пункта.
     * @param string $street Улица
     * @param string $number Номер дома
     * @param string $building Номер строения
     * @param string $estate Номер владения
     * @param string $block Корпус или строени
     * @param string $additional Дополнительная информация
     * @param int|null $km Километр дороги, на котором располагается точка продаж
     */
    public function __construct(
        ?int $regionId,
        string $region,
        ?int $cityId,
        string $city,
        string $street,
        string $number,
        string $building,
        string $estate,
        string $block,
        string $additional,
        ?int $km
    ) {
        $this->regionId = $regionId;
        $this->region = $region;
        $this->cityId = $cityId;
        $this->city = $city;
        $this->street = $street;
        $this->number = $number;
        $this->building = $building;
        $this->estate = $estate;
        $this->block = $block;
        $this->additional = $additional;
        $this->km = $km;
    }

    /**
     * Возвращает идентификатор региона
     *
     * @return int|null Идентификатор
     */
    public function getRegionId(): ?int
    {
        return $this->regionId;
    }

    /**
     * Устанавливает идентификатор региона
     *
     * @param int|null $regionId Идентификатор
     */
    public function setRegionId(?int $regionId)
    {
        $this->regionId = $regionId;
    }

    /**
     * Возвращает название региона
     *
     * @return string Название региона
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * Возвращает идентификатор города или населенного пункта.
     *
     * @return int|null Идентификатор города или населенного пункта.
     */
    public function getCityId(): ?int
    {
        return $this->cityId;
    }

    /**
     * Устанавливает идентификатор города или населенного пункта.
     *
     * @param int $cityId Идентификатор города или населенного пункта.
     */
    public function setCityId(int $cityId)
    {
        $this->cityId = $cityId;
    }

    /**
     * Возвращает название населенного пункта
     *
     * @return string Населенный пункт
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * Возвращает назввание улицы
     *
     * @return string Название улицы
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * Возвращает номер дома
     *
     * @return string Номер дома
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * Возвращает номер строения
     *
     * @return string Номер строения
     */
    public function getBuilding(): string
    {
        return $this->building;
    }

    /**
     * Возвращает номер владения
     *
     * @return string Номер владения
     */
    public function getEstate(): string
    {
        return $this->estate;
    }

    /**
     * Возвращает номер корпуса
     *
     * @return string Номер корпуса
     */
    public function getBlock(): string
    {
        return $this->block;
    }

    /**
     * Возвращает дополнительную информацию.
     *
     * @return string Дополнительная информация
     */
    public function getAdditional(): string
    {
        return $this->additional;
    }

    /**
     * Возвращает порядковый номер километра дороги, на котором располагается точка продаж, если отсутствует улица.
     *
     * @return int|null Номер километра
     */
    public function getKm(): ?int
    {
        return $this->km;
    }
}
