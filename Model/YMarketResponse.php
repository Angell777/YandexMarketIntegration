<?php
declare(strict_types=1);

namespace SomeNameSpace\Component\YandexMarketIntegration\Model;

use SomeNameSpace\Component\YandexMarketIntegration\Enum\ResponseStatus;

/**
 * Ответ сервиса при изменении данных
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class YMarketResponse
{
    /**
     * @var ResponseStatus Статус выполнения запроса
     */
    private $status;

    /**
     * @var array Список ошибок при выполнении запроса
     */
    private $errors = [];

    /**
     * Конструктор
     *
     * @param ResponseStatus $status Статус выполнения запроса
     * @param array $errors Список ошибок при выполнении запроса
     */
    public function __construct(ResponseStatus $status, array $errors = [])
    {
        $this->status = $status;
        $this->errors = $errors;
    }

    /**
     * Возвращает список ошибок при выполнении запроса
     *
     * @return array Список ошибок
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Возвращает список ошибок при выполнении запроса
     *
     * @return ResponseStatus Список ошибок
     */
    public function getStatus(): ResponseStatus
    {
        return $this->status;
    }
}
