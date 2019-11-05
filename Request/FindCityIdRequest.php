<?php
declare(strict_types=1);

namespace SomeNameSpace\YandexMarketOutlet\Component\YandexMarketIntegration\Request;

use SomeNameSpace\YandexMarketOutlet\Component\ApiConnector\AbstractRequest;
use SomeNameSpace\YandexMarketOutlet\Component\ApiConnector\Enum\HttpMethod;

/**
 * Класс запроса для получения Id населенного пункта точки продаж
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class FindCityIdRequest extends AbstractRequest
{
    /**
     * Конструктор.
     *
     * @param string $storeCity Наименование города
     * @param int $page Страница результатов поиска
     */
    public function __construct(string $storeCity, int $page)
    {
        parent::__construct();

        $this->addQueryParam('name', $storeCity);
        $this->addPathParam('page', $page);
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod(): HttpMethod
    {
        return HttpMethod::GET();
    }

    /**
     * {@inheritdoc}
     */
    public function getPathTemplate(): string
    {
        return 'regions.json';
    }
}
