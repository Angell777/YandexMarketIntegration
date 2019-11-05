<?php
declare(strict_types=1);

namespace SomeNameSpace\Component\YandexMarketIntegration\Enum;

use MyCLabs\Enum\Enum;

/**
 * Состояние точки продаж
 *
 * @method static StoreState VISIBLE()
 * @method static StoreState HIDDEN()
 *
 * Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class StoreState extends Enum
{
    /**
     * Тточка продаж включена
     */
    private const VISIBLE = 'VISIBLE';

    /**
     * Точка продаж выключена
     */
    private const HIDDEN = 'HIDDEN';
}
