<?php
declare(strict_types=1);

namespace SomeNameSpace\Component\YandexMarketIntegration\Normalizer;

use SomeNameSpace\Component\YandexMarketIntegration\Model\Pager;
use SomeNameSpace\Component\YandexMarketIntegration\Model\YandexMarketOutlet;
use SomeNameSpace\Component\YandexMarketIntegration\Response\OutletsResponse;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Нормалайзер для YandexMarketOutlet
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class YandexResponseOutletsNormalizer implements
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
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        /** @var YandexMarketOutlet[] $outlets */
        $outlets = $this->denormalizer->denormalize(
            $data['outlets'],
            YandexMarketOutlet::class . '[]',
            $format,
            $context
        );
        /** @var Pager $pager */
        $pager = $this->denormalizer->denormalize($data['pager'], Pager::class, $format, $context);

        return new OutletsResponse($pager, $outlets);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === OutletsResponse::class;
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
