<?php
declare(strict_types=1);

namespace SomeNameSpace\Component\YandexMarketIntegration\Normalizer;

use SomeNameSpace\Component\YandexMarketIntegration\Enum\ResponseStatus;
use SomeNameSpace\Component\YandexMarketIntegration\Model\YMarketResponse;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Нормалайзер для YandexResponse
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class YandexServiceResponseNormalizer implements
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
        $errors = isset($data['errors'])? $data['errors'] : [];

        return new YMarketResponse(new ResponseStatus($data['status']), $data['errors'] ?? []);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === YMarketResponse::class;
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
