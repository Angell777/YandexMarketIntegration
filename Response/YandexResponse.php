<?php
declare(strict_types=1);

namespace SomeNameSpace\YandexMarketOutlet\Component\YandexMarketIntegration\Response;

use SomeNameSpace\YandexMarketOutlet\Component\ApiConnector\Response;
use SomeNameSpace\YandexMarketOutlet\Component\ApiConnector\ApiConnectorException;

/**
 * Класс ответа данных сервиса ЯндексМаркет
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class YandexResponse extends Response
{
    /**
     * Возвращает данные ответа с сервиса
     *
     * @return string Данные
     */
    public function getData(): string
    {
        $this->checkIsOk(__FUNCTION__);

        $jsonData = $this->getBody();

        if (empty($jsonData)) {
            throw new ApiConnectorException(
                sprintf(
                    'Не удалось получить валидные данные из тела запроса. %s',
                    __METHOD__
                )
            );
        }

        return $jsonData;
    }
}
