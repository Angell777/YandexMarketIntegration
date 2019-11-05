<?php
declare(strict_types=1);

namespace SomeNameSpace\Component\YandexMarketIntegration\Model;

/**
 * Правила доставки
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class YandexMarketOutletDeliveryRules
{
    /**
     * Идентификатор службы доставки товаров в точку продаж (99 - Собственная служба)
     *
     * {@see https://tech.yandex.ru/market/partner/doc/dg/reference/post-order-accept-docpage/}
     */
    public const DELIVERY_SERVICE_ID = 99;

    /**
     * Максимальный срок доставки товаров в точку продаж
     */
    public const MAX_DELIVERY_DAYS = 2;

    /**
     * Час, до которого покупателю нужно сделать заказ, чтобы он был доставлен в точку продаж
     */
    public const ORDER_BEFORE = 24;

    /**
     * Цена товара, начиная с которой действует бесплатный самовывоз товара из точки продаж
     */
    public const PRICE_FREE_PICKUP = 0;

    /**
     * @var int Минимально возможный срок (в рабочих днях) доставки товаров в точку продаж.
     */
    private $minDeliveryDays;

    /**
     * @var float Стоимость самовывоза из точки продаж.
     */
    private $cost;

    /**
     * @var  int Идентификатор службы доставки товаров в точку продаж.
     */
    private $deliveryServiceId;

    /**
     * @var int Час, до которого покупателю нужно сделать заказ
     */
    private $maxDeliveryDays;

    /**
     * @var int Час, до которого покупателю нужно сделать заказ, чтобы он был доставлен в точку продаж
     * в сроки от min-delivery-days до max-delivery-days.
     */
    private $orderBefore;

    /**
     * @var float Цена товара, начиная с которой действует бесплатный самовывоз товара из точки продаж.
     */
    private $priceFreePickup;

    /**
     * Конструктор
     *
     * @param int $minDeliveryDays Минимально возможный срок (в рабочих днях) доставки товаров в точку продаж
     * @param float $cost Стоимость самовывоза из точки продаж
     * @param int $deliveryServiceId Идентификатор службы доставки товаров в точку продаж
     * @param int $maxDeliveryDays Максимально возможный срок (в рабочих днях) доставки товаров в точку продаж
     * @param int $orderBefore Час, до которого покупателю нужно сделать заказ
     * @param float $priceFreePickup Цена товара, с которой действует бесплатный самовывоз товара из точки продаж
     */
    public function __construct(
        int $minDeliveryDays,
        float $cost,
        int $deliveryServiceId,
        int $maxDeliveryDays,
        int $orderBefore,
        float $priceFreePickup
    ) {
        $this->minDeliveryDays = $minDeliveryDays;
        $this->cost = $cost;
        $this->deliveryServiceId = $deliveryServiceId;
        $this->maxDeliveryDays = $maxDeliveryDays;
        $this->orderBefore = $orderBefore;
        $this->priceFreePickup = $priceFreePickup;
    }

    /**
     * Возвращает минимально возможный срок (в рабочих днях) доставки товаров в точку продаж.
     *
     * @return int Минимально возможный срок
     */
    public function getMinDeliveryDays(): int
    {
        return $this->minDeliveryDays;
    }

    /**
     * Возвращает стоимость самовывоза из точки продаж.
     *
     * @return float Стоимость товара
     */
    public function getCost(): float
    {
        return $this->cost;
    }

    /**
     * Возвращает идентификатор службы доставки товаров в точку продаж
     *
     * @return int Идентификатор службы доставки товаров
     */
    public function getDeliveryServiceId(): int
    {
        return $this->deliveryServiceId;
    }

    /**
     * Возавращает максимально возможный срок (в рабочих днях) доставки товаров в точку продаж.
     *
     * @return int Максимально возможный срок (в рабочих днях)
     */
    public function getMaxDeliveryDays(): int
    {
        return $this->maxDeliveryDays;
    }

    /**
     * Возвращает Час, до которого покупателю нужно сделать заказ, чтобы он был доставлен в точку продаж в срок.
     *
     * Срок от min-delivery-days до max-delivery-days
     *
     * @return int Час, до которого покупателю нужно сделать заказ
     */
    public function getOrderBefore(): int
    {
        return $this->orderBefore;
    }

    /**
     * Возвращает цену товара, начиная с которой действует бесплатный самовывоз товара из точки продаж.
     *
     * @return float Цена товара
     */
    public function getPriceFreePickup(): float
    {
        return $this->priceFreePickup;
    }
}
