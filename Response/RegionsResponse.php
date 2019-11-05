<?php
declare(strict_types=1);

namespace SomeNameSpace\YandexMarketOutlet\Component\YandexMarketIntegration\Response;

use SomeNameSpace\YandexMarketOutlet\Component\YandexMarketIntegration\Model\YandexMarketRegion;

/**
 * Класс ответа ЯндексМаркета, запрос определения Id населенного пункта точки продаж
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class RegionsResponse
{
    /**
     * @var YandexMarketRegion[] Массив регионов найденых для outlet
     */
    private $regions;

    /**
     * Конструктор
     *
     * @param YandexMarketRegion[] $regions Массив регионов найденых для точки продаж
     */
    public function __construct(array $regions)
    {
        $this->regions = $regions;
    }

    /**
     * Возвращает массив регионов найденых для outlet
     *
     * @return YandexMarketRegion[] Массив регионов найденых для точки продаж
     */
    public function getRegions(): array
    {
        return $this->regions;
    }
}
