<?php
declare(strict_types=1);

namespace SomeNameSpace\Component\YandexMarketIntegration\Model;

/**
 * Расписание работы точки продаж
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class YandexMarketOutletScheduleItems
{
    /**
     * @var string Точка продаж работает с указанного дня недели.
     */
    private $startDay;

    /**
     * @var string Точка продаж работает до указанного дня недели.
     */
    private $endDay;

    /**
     * @var string Точка продаж работает c указанного часа.
     */
    private $startTime;

    /**
     * @var string Точка продаж работает до указанного часа.
     */
    private $endTime;

    /**
     * Конструктор
     *
     * @param string $startDay Точка продаж работает с указанного дня недели
     * @param string $endDay Точка продаж работает до указанного дня недели
     * @param string $startTime Точка продаж работает c указанного часа
     * @param string $endTime Точка продаж работает до указанного часа
     */
    public function __construct(
        string $startDay,
        string $endDay,
        string $startTime,
        string $endTime
    ) {
        $this->startDay = $startDay;
        $this->endDay = $endDay;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }

    /**
     * Возвращает день недели, с которого работает точка
     *
     * @return string День недели
     */
    public function getStartDay(): string
    {
        return $this->startDay;
    }

    /**
     * Возвращает день недели, до которого работает точка
     *
     * @return string День недели
     */
    public function getEndDay(): string
    {
        return $this->endDay;
    }

    /**
     * Задает день недели, до которого работает точка
     *
     * @param string $endDay День недели
     */
    public function setEndDay(string $endDay): void
    {
        $this->endDay = $endDay;
    }

    /**
     * Возвращает время, с которого работает точка в заданный период
     *
     * @return string Время с которого работает точка
     */
    public function getStartTime(): string
    {
        return $this->startTime;
    }

    /**
     * Возвращает время, до которого работает точка в заданный период
     *
     * @return string Время до которого работает точка
     */
    public function getEndTime(): string
    {
        return $this->endTime;
    }
}
