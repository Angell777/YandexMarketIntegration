<?php
declare(strict_types=1);

namespace SomeNameSpace\Component\YandexMarketIntegration\Normalizer;

use SomeNameSpace\Component\YandexMarketIntegration\Model\YandexMarketRegion;
use SomeNameSpace\Component\YandexMarketIntegration\Response\RegionsResponse;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Нормалайзер для Region
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class YandexResponseRegionNormalizer implements
    DenormalizerInterface,
    NormalizerInterface,
    DenormalizerAwareInterface,
    NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function denormalize($regionsData, $class, $format = null, array $context = array())
    {
        $regions = [];
        foreach ($regionsData['regions'] as $region) {
            $parent = null;
            if (isset($region['parent']) && is_array($region['parent']) && count($region['parent']) > 0) {
                $parent = $this->getParentRegion($region['parent']);
            }

            $regions[] = new YandexMarketRegion($region['id'], $region['name'], $region['type'], $parent);
        }

        return new RegionsResponse($regions);
    }

    /**
     * Метод рекурсивной нормализации Region, вложенных в поле parents
     *
     * @param array $parentData Данные о родительский хегионах
     *
     * @return YandexMarketRegion Родительские регионы
     */
    private function getParentRegion(array $parentData)
    {
        $parent = null;
        if (isset($parentData['parent']) && is_array($parentData['parent']) && count($parentData['parent']) > 0) {
            $parent = $this->getParentRegion($parentData['parent']);
        }

        return new YandexMarketRegion($parentData['id'], $parentData['name'], $parentData['type'], $parent);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === RegionsResponse::class;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return false;
    }
}
