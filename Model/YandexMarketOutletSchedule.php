<?php
declare(strict_types=1);

namespace SomeNameSpace\Component\YandexMarketIntegration\Model;

/**
 * Список режимов работы точки продаж
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class YandexMarketOutletSchedule
{
    /**
     * @var bool Признак, работает ли точка продаж в дни государственных праздников
     */
    private $workInHoliday;

    /**
     * @var YandexMarketOutletScheduleItems[] Список расписаний работы точки продаж
     */
    private $scheduleItems;

    /**
     * Конструктор
     *
     * @param bool $workInHoliday Признак, работает ли точка продаж в дни государственных праздников
     * @param YandexMarketOutletScheduleItems[] $scheduleItems Список расписаний работы точки продаж
     */
    public function __construct(
        bool $workInHoliday,
        array $scheduleItems
    ) {
        $this->workInHoliday = $workInHoliday;
        $this->scheduleItems = $scheduleItems;
    }

    /**
     * Возвращает признак, работает ли точка продаж в дни государственных праздников
     *
     * @return bool Признак работы точки в о время государственных праздников
     */
    public function getWorkInHoliday(): bool
    {
        return $this->workInHoliday;
    }

    /**
     * Возвращает список расписаний работы точки продаж
     *
     * @return YandexMarketOutletScheduleItems[] Массив расписания работы точки продаж
     */
    public function getScheduleItems(): array
    {
        return $this->scheduleItems;
    }
}
