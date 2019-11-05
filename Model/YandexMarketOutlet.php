<?php
declare(strict_types=1);

namespace SomeNameSpace\Component\YandexMarketIntegration\Model;

use SomeNameSpace\Component\YandexMarketIntegration\Enum\StoreType;
use SomeNameSpace\Component\YandexMarketIntegration\Enum\StoreState;
use SomeNameSpace\Component\YandexMarketIntegration\Model\YandexMarketOutletAddress;
use SomeNameSpace\Component\YandexMarketIntegration\Model\YandexMarketOutletDeliveryRules;
use SomeNameSpace\Component\YandexMarketIntegration\Model\YandexMarketOutletSchedule;
use SomeNameSpace\Component\YandexMarketIntegration\Normalizer\YandexMarketOutletAddressNormalizer;

/**
 * Точка продаж
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class YandexMarketOutlet
{
    /**
     * @var int|null Идентификатор точки продаж, присвоенный Яндекс.Маркетом.
     */
    private $id;

    /**
     * @var string Название точки продаж.
     */
    private $name;

    /**
     * @var StoreType Тип точки продаж.
     */
    private $type;

    /**
     * @var string Координаты точки продаж.
     *
     * Формат: долгота, широта. Разделители: запятая и / или пробел. Например, 20.4522144, 54.7104264.
     */
    private $coords;

    /**
     * @var bool Признак основной точки продаж.
     */
    private $isMain;

    /**
     * @var string Идентификатор точки продаж (наш pup_id)
     */
    private $shopOutletCode;

    /**
     * @var StoreState Состояние точки продаж.
     */
    private $visibility;

    /**
     * @var YandexMarketOutletAddress Адрес точки продаж.
     */
    private $address;

    /**
     * @var array Номера телефонов точки продаж.
     *
     */
    private $phones;

    /**
     * @var YandexMarketOutletSchedule Режимы работы.
     */
    private $workingSchedule;

    /**
     * @var YandexMarketOutletDeliveryRules Информация об условиях доставки для данной точки продаж.
     */
    private $deliveryRules;

    /**
     * @var string[] Адреса электронной почты точки продаж.
     */
    private $emails = [];

    /**
     * Конструктор
     *
     * @param int|null $id Идентификатор точки продаж, присвоенный Яндекс.Маркетом.
     * @param string $name Название точки
     * @param StoreType $type Тип точки продаж.
     * @param string $coords Координаты точки продаж.
     * @param bool $isMain Признак основной точки продаж.
     * @param string $shopOutletCode Идентификатор точки продаж, присвоенный магазином.
     * @param StoreState $visibility Состояние точки продаж.
     * @param YandexMarketOutletAddress $address Адрес точки продаж.
     * @param array $phones Номера телефонов точки продаж.
     * @param YandexMarketOutletSchedule $workingSchedule Список режимов работы точки продаж.
     * @param YandexMarketOutletDeliveryRules $deliveryRules Информация об условиях доставки для данной точки продаж.
     * @param string[] $emails Адреса электронной почты точки продаж.
     */
    public function __construct(
        ?int $id,
        string $name,
        StoreType $type,
        string $coords,
        bool $isMain,
        string $shopOutletCode,
        StoreState $visibility,
        YandexMarketOutletAddress $address,
        array $phones,
        YandexMarketOutletSchedule $workingSchedule,
        YandexMarketOutletDeliveryRules $deliveryRules,
        array $emails
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->coords = $coords;
        $this->isMain = $isMain;
        $this->shopOutletCode = $shopOutletCode;
        $this->visibility = $visibility;
        $this->address = $address;
        $this->phones = $phones;
        $this->workingSchedule = $workingSchedule;
        $this->deliveryRules = $deliveryRules;
        $this->emails = $emails;
    }

    /**
     * Возвращает идентификатор точки продаж, присвоенный Яндекс.Маркетом.
     *
     * @return int|null Идентификатор точки продаж, присвоенный Яндекс.Маркетом.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Возвращает название точки продаж.
     *
     * @return string Название точки продаж.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Возвращает тип точки продаж.
     *
     * @return StoreType Тип точки продаж.
     */
    public function getType(): StoreType
    {
        return $this->type;
    }

    /**
     * Возващает координаты точки продаж.
     *
     * @return string Координаты точки продаж.
     */
    public function getCoords(): string
    {
        return $this->coords;
    }

    /**
     * Возвращает признак основной точки продаж.
     *
     * @return bool Признак основной точки продаж.
     */
    public function getIsMain(): bool
    {
        return $this->isMain;
    }

    /**
     * Возвращает идентификатор точки продаж, присвоенный магазином.
     *
     * @return string Идентификатор точки продаж, присвоенный магазином.
     */
    public function getShopOutletCode(): string
    {
        return $this->shopOutletCode;
    }


    /**
     * Возвращает состояние точки продаж.
     *
     * @return StoreState Состояние точки продаж.
     */
    public function getVisibility(): StoreState
    {
        return $this->visibility;
    }

    /**
     * Возвращает адрес точки продаж.
     *
     * @return YandexMarketOutletAddress Объект адреса точки продаж.
     */
    public function getAddress(): YandexMarketOutletAddress
    {
        return $this->address;
    }

    /**
     * Возвращает номера телефонов точки продаж.
     *
     * @return string[] Номера телефонов точки продаж.
     */
    public function getPhones(): array
    {
        return $this->phones;
    }

    /**
     * Возвращает расписание работы точки продаж.
     *
     * @return YandexMarketOutletSchedule Объект расписания работы точки продаж.
     */
    public function getWorkingSchedule(): YandexMarketOutletSchedule
    {
        return $this->workingSchedule;
    }

    /**
     * Возвращает информацию об условиях доставки для данной точки продаж.
     *
     * @return YandexMarketOutletDeliveryRules Информация об условиях доставки для данной точки продаж.
     */
    public function getDeliveryRules(): YandexMarketOutletDeliveryRules
    {
        return $this->deliveryRules;
    }

    /**
     * Адреса электронной почты точки продаж.
     *
     * Пока у нас таких нет, по этому возвращать будет только мустой массив
     *
     * @return string[] Адреса электронной почты точки продаж.
     */
    public function getEmails(): array
    {
        return $this->emails;
    }
}
