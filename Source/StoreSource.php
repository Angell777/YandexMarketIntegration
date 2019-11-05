<?php
declare(strict_types=1);

namespace SomeNameSpace\Component\YandexMarketIntegration\Source;

use SomeNameSpace\Component\Model\ModelCreatorTrait;
use SomeNameSpace\Component\YandexMarketIntegration\Dao\OutletsDao;
use SomeNameSpace\Component\YandexMarketIntegration\Model\StoreRegion;
use SomeNameSpace\Component\YandexMarketIntegration\Model\YandexMarketCampaign;

/**
 * Объект для работы с данными базы данных
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class StoreSource
{
    use ModelCreatorTrait;

    /**
     * @var OutletsDao Дао для работы с базой
     */
    private $dao;

    /**
     * Конструктор
     *
     * @param OutletsDao $outletsDao Dao для работы с базой
     */
    public function __construct(OutletsDao $outletsDao)
    {
        $this->dao = $outletsDao;
    }

    /**
     * Возвращает идентификаторы региона и населенного пункта для каждой точки из указанного пространства
     *
     * @param string $spaceId Идентификатор пространства
     *
     * @return StoreRegion[] Идентификаторы
     */
    public function getStoresDataBySpace(string $spaceId): array
    {
        $regionsData = $this->dao->getStoresDataBySpace($spaceId);
        $regions = $this->createModels($regionsData, StoreRegion::class);

        $result = [];
        foreach ($regions as $region) {
            $name = $region->getPupId();
            $result[$name] = $region;
        }

        return $result;
    }

    /**
     * Возвращает соотнесенные пространства и соответствуюих им компаний на ЯндексМаркет
     *
     * @throws \Exception Исключение, если база не вернула данные из таблицы
     *
     * @return YandexMarketCampaign[] Массив объектов соотвенсенных пространств (space_id => кампания ЯндексМаркет)
     */
    public function getCampaignsList(): array
    {
        $rawCampaignsData = $this->dao->getCampaignsList();
        if (empty($rawCampaignsData)) {
            throw new \Exception('Не был получен список пространств из базы данных.');
        }

        return $this->createModels($rawCampaignsData, YandexMarketCampaign::class);
    }

    /**
     * Сохраняет поле идентификатора населенного пункта точки продаж
     *
     * @param string $storeId Идентификатор точки продаж
     * @param int $cityId Идентификатор населенного пункта точки продаж
     */
    public function updateOutletCityId(string $storeId, int $cityId): void
    {
        $this->dao->updateOutletCityId($storeId, $cityId);
    }
}
