<?php
declare(strict_types=1);

namespace SomeNameSpace\YandexMarketOutlet\Component\YandexMarketIntegration\Response;

use SomeNameSpace\YandexMarketOutlet\Component\YandexMarketIntegration\Model\YandexMarketOutlet;
use SomeNameSpace\YandexMarketOutlet\Component\YandexMarketIntegration\Model\Pager;

/**
 * Ответ ЯндексМаркета, на запрос зарегистрированных точек продаж на сервисе
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class OutletsResponse
{
    /**
     * @var Pager|null Пагинатор
     */
    private $pager;

    /**
     * @var YandexMarketOutlet[] Точки продаж зарегистрированные на ЯндекМаркете
     */
    private $outlets;

    /**
     * Конструктор
     *
     * @param Pager|null $pager Пагинация
     * @param YandexMarketOutlet[] $outlets Точки продаж
     */
    public function __construct(?Pager $pager, array $outlets = [])
    {
        $this->pager = $pager;
        $this->outlets = $outlets;
    }

    /**
     * Возвращает точки продаж, зарегистрированных на ЯндекМаркете
     *
     * @return YandexMarketOutlet[] Точки продаж
     */
    public function getOutlets(): array
    {
        return $this->outlets;
    }

    /**
     * Возвращает пагинатор
     *
     * @return Pager|null Пагинатор
     */
    public function getPager(): ?Pager
    {
        return $this->pager;
    }
}
