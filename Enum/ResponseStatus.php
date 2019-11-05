<?php
declare(strict_types=1);

namespace SomeNameSpace\Component\YandexMarketIntegration\Enum;

use MyCLabs\Enum\Enum;

/**
 * Результат выполнения запроса
 *
 * @method static ResponseStatus OK()
 * @method static ResponseStatus ERROR()
 *
 * Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class ResponseStatus extends Enum
{
    /**
     * Запрос выполнен успешно
     */
    public const OK = "OK";

    /**
     * Во время запроса произошла ошибка
     */
    public const ERROR = "ERROR";
}
