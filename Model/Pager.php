<?php
declare(strict_types=1);

namespace SomeNameSpace\Component\YandexMarketIntegration\Model;

/**
 * Пагинатор ответа сервиса ЯндексМаркета
 *
 * Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class Pager
{
    /**
     * @var int Порядковый номер текущей страницы результатов.
     */
    private $currentPage;

    /**
     * @var int Порядковый номер первой записи на странице относительно общих результатов поиска.
     */
    private $from;

    /**
     * @var int Количество записей на текущей странице.
     */
    private $pageSize;

    /**
     * @var int Порядковый номер последней записи на странице относительно общих результатов поиска.
     */
    private $to;

    /**
     * Конструктор
     *
     * @param int $currentPage Порядковый номер текущей страницы результатов.
     * @param int $from Порядковый номер первой записи на странице относительно общих результатов поиска.
     * @param int $pageSize Количество записей на текущей странице.
     * @param int $to Порядковый номер последней записи на странице относительно общих результатов поиска.
     */
    public function __construct(int $currentPage, int $from, int $pageSize, int $to)
    {
        $this->currentPage = $currentPage;
        $this->from = $from;
        $this->pageSize = $pageSize;
        $this->to = $to;
    }

    /**
     * Возвращает порядковый номер текущей страницы результатов.
     *
     * @return int Порядковый номер текущей страницы результатов.
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * Возвращает порядковый номер первой записи на странице относительно общих результатов поиска.
     *
     * @return int Порядковый номер первой записи на странице относительно общих результатов поиска.
     */
    public function getFrom(): int
    {
        return $this->from;
    }

    /**
     * Возвращает количество записей на текущей странице.
     *
     * @return int Количество записей на текущей странице.
     */
    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     * Возвращает порядковый номер последней записи на странице относительно общих результатов поиска.
     *
     * @return int Порядковый номер последней записи на странице относительно общих результатов поиска.
     */
    public function getTo(): int
    {
        return $this->to;
    }
}
