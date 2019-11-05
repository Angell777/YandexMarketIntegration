<?php
declare(strict_types=1);

namespace SomeNameSpace\Component\YandexMarketIntegration\Model;

/**
 * Данные о населенному пункте точки продаж, объект ЯндексМаркета
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class YandexMarketRegion
{
    /**
     * @var int Идентификатор региона.
     */
    private $id;

    /**
     * @var string Название региона.
     */
    private $name;

    /**
     * @var string Тип региона.
     */
    private $type;

    /**
     * @var YandexMarketRegion|null Информация о родительском регионе.
     */
    private $parent;

    /**
     * Конструктор
     *
     * @param int $id Идентификатор региона.
     * @param string $name Название региона.
     * @param string $type Тип региона.
     * @param YandexMarketRegion|null $parent Информация о родительском регионе.
     */
    public function __construct(int $id, string $name, string $type, ?YandexMarketRegion $parent)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->parent = $parent;
    }

    /**
     * Возвращет идентификатор региона.
     *
     * @return int Идентификатор региона.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Возвращает название региона.
     *
     * @return string Название региона
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Возвращает тип региона.
     *
     * @return string Тип региона.
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Информация о родительском регионе.
     *
     * @return YandexMarketRegion|null Массив родительских регионов.
     */
    public function getParent(): ?YandexMarketRegion
    {
        return $this->parent;
    }
}
