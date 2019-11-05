<?php
declare(strict_types=1);

namespace SomeNameSpace\Component\YandexMarketIntegration\Normalizer;

use SomeNameSpace\Component\YandexMarketIntegration\Model\YandexMarketOutletDeliveryRules;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Нормалайзер для YandexMarketOutletDeliveryRules
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class YandexMarketOutletDeliveryNormalizer implements
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
    public function denormalize($deliveryData, $class, $format = null, array $context = array())
    {
        if (in_array('store', $context)) {
            $deliveryRules = new YandexMarketOutletDeliveryRules(
                (int)$deliveryData->isMainStore(),
                floatval($deliveryData->getDeliveryPrice()),
                YandexMarketOutletDeliveryRules::DELIVERY_SERVICE_ID,
                YandexMarketOutletDeliveryRules::MAX_DELIVERY_DAYS,
                YandexMarketOutletDeliveryRules::ORDER_BEFORE,
                YandexMarketOutletDeliveryRules::PRICE_FREE_PICKUP
            );
        } elseif (in_array('yandex', $context)) {
            $data = $deliveryData['deliveryRules'][0];
            $deliveryRules = new YandexMarketOutletDeliveryRules(
                $data['minDeliveryDays'] ?? 1,
                $data['cost'] ?? 0,
                $data['deliveryServiceId'] ?? 0,
                $data['maxDeliveryDays'] ?? 0,
                $data['orderBefore'] ?? 0,
                $data['priceFreePickup'] ?? 0
            );
        } else {
            throw new \Exception('Не верный формат данных поля доставки.');
        }

        return $deliveryRules;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === YandexMarketOutletDeliveryRules::class;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($outlet, $format = null, $context = array())
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($outlet, $format = null)
    {
        return false;
    }
}
