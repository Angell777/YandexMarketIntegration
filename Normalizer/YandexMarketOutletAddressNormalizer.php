<?php
declare(strict_types=1);

namespace SomeNameSpace\Component\YandexMarketIntegration\Normalizer;

use SomeNameSpace\Component\YandexMarketIntegration\Model\YandexMarketOutlet;
use SomeNameSpace\Component\YandexMarketIntegration\Model\YandexMarketOutletAddress;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use SomeNameSpace\Api\Store\Model\Address;

/**
 * Нормалайзер для YandexMarketOutletAddress
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class YandexMarketOutletAddressNormalizer implements
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
    public function denormalize($addressData, $class, $format = null, array $context = array())
    {
        if (in_array('store', $context)) {
            if ($addressData->getCity() === '') {
                throw new \Exception('Не заполнен адресс.');
            }

            return new YandexMarketOutletAddress(
                null,
                $addressData->getRegion(),
                null,
                $addressData->getCity()?: '',
                $addressData->getStreet()?: '',
                $addressData->getHouse()?: '',
                $addressData->getBuilding()?: '',
                $addressData->getPosession()?: '',
                $addressData->getHouseblock()?: '',
                $addressData->getNote()? str_replace('"', '\'', $addressData->getNote()) : '',
                $addressData->getDimension()
            );
        } elseif (in_array('yandex', $context)) {
            $addressData = (array)$addressData;

            return new YandexMarketOutletAddress(
                null,
                '',
                isset($addressData['regionId'])? (int)$addressData['regionId'] : null,
                '',
                isset($addressData['street'])? (string)$addressData['street'] : '',
                isset($addressData['number'])? (string)$addressData['number'] : '',
                isset($addressData['building'])? (string)$addressData['building'] : '',
                isset($addressData['estate'])? (string)$addressData['estate'] : '',
                isset($addressData['block']) ? (string)$addressData['block'] : '',
                isset($addressData['additional']) ?
                    str_replace('"', '\'', (string)$addressData['additional']) : '',
                isset($addressData['km'])? (int)$addressData['km'] : null
            );
        } else {
            throw new \Exception('Не верный формат данных поля адреса.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === YandexMarketOutletAddress::class;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($outletAddress, $format = null, $context = array())
    {
        $addressData = [];

        $addressData['regionId'] = $outletAddress->getCityId();
        if ($outletAddress->getStreet() !== '') {
            $addressData['street'] = $outletAddress->getStreet();
        }
        if ($outletAddress->getNumber() !== '') {
            $addressData['number'] = $outletAddress->getNumber();
        }
        if ($outletAddress->getBuilding() !== '') {
            $addressData['building'] = $outletAddress->getBuilding();
        }
        if ($outletAddress->getEstate() !== '') {
            $addressData['estate'] = $outletAddress->getEstate();
        }
        if ($outletAddress->getBlock() !== '') {
            $addressData['block'] = $outletAddress->getBlock();
        }
        if ($outletAddress->getAdditional() !== '') {
            $addressData['additional'] = $outletAddress->getAdditional();
        }
        if ($outletAddress->getKm() !== null) {
            $addressData['km'] = $outletAddress->getKm();
        }

        return $addressData;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($outlet, $format = null)
    {
        return $outlet instanceof YandexMarketOutletAddress;
    }
}
