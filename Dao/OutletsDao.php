<?php
declare(strict_types=1);

namespace SomeNameSpace\Component\YandexMarketIntegration\Dao;

use SomeNameSpace\Database\AbstractDao;

/**
 * DAO получения информации из базы для работы с ЯндексМаркет
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class OutletsDao extends AbstractDao
{
    /**
     * Возвращает соотнесенные пространства и соответствуюих им кампаний на ЯндексМаркет
     *
     * @return array Массив соотнесенных пространств и соответствуюих им кампаний на ЯндексМаркет
     */
    public function getCampaignsList(): array
    {
        $query = $this->exec('Yandex.get_campaigns_list');

        $queryResult = $this->mapResult($this->query($query, __METHOD__), [
            'campaignId' => ['name' => 'yandex_campaign_id', 'type' => 'int'],
            'domain' => ['name' => 'yandex_campaign_domain', 'type' => 'string'],
            'spaceId' => ['name' => 'space_id', 'type' => 'string'],
        ]);

        return $queryResult->all() ? : [];
    }

    /**
     * Возвращает идентификаторы региона и населенного пункта для каждого магазина из указанного пространства
     *
     * @param string $spaceId Идентификатор пространства
     *
     * @return array Идентификаторы
     */
    public function getStoresDataBySpace(string $spaceId): array
    {
        $query = $this->exec('Yandex.get_store_region_data')
            ->param('space_id', $spaceId);

        $queryResult = $this->mapResult($this->query($query, __METHOD__), [
            'pupId' => [
                'name' => 'pup_id',
                'cb' => function ($field, &$row) {
                    return strtolower($row[$field]);
                },
            ],
            'regionId' => ['name' => 'yandex_region_id', 'type' => 'int'],
            'regionName' => ['name' => 'address_region', 'type' => 'string'],
            'cityId' => ['name' => 'yandex_city_id', 'type' => 'int'],
        ]);

        return $queryResult->all() ? : [];
    }

    /**
     * Обновляет идентификатор населенного пункта
     *
     * @param string $pupId Идентификатор точки продаж
     * @param int $cityId Идентификатор населенного пункта точки продаж
     */
    public function updateOutletCityId(string $pupId, int $cityId): void
    {
        $query = $this->exec('Yandex.set_yandex_city_id')
            ->param('pup_id', $pupId)
            ->param('yandex_city_id', $cityId);

        $this->query($query, __METHOD__);
    }
}
