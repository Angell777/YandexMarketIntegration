<?php
declare(strict_types=1);

namespace SomeNameSpace\Component\YandexMarketIntegration\Enum;

use MyCLabs\Enum\Enum;

/**
 * Тип точки продаж.
 *
 * @method static StoreType DEPOT()
 * @method static StoreType MIXED()
 * @method static StoreType RETAIL()
 *
 * Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class StoreType extends Enum
{
    /**
     * Пункт выдачи заказов
     */
    public const DEPOT = 'DEPOT';

    /**
     * Смешанный тип точки продаж (торговый зал и пункт выдачи заказов)
     */
    public const MIXED = 'MIXED';

    /**
     * Розничная точка продаж (торговый зал)
     */
    public const RETAIL  = 'RETAIL';
}
