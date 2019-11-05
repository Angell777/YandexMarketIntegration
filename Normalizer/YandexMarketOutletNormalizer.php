<?php
declare(strict_types=1);

namespace SomeNameSpace\Component\YandexMarketIntegration\Normalizer;

use SomeNameSpace\Api\Store\Model\StoreSchedule;
use SomeNameSpace\Component\YandexMarketIntegration\Model\YandexMarketOutlet;
use SomeNameSpace\Component\YandexMarketIntegration\Model\YandexMarketOutletAddress;
use SomeNameSpace\Component\YandexMarketIntegration\Model\YandexMarketOutletDeliveryRules;
use SomeNameSpace\Component\YandexMarketIntegration\Model\YandexMarketOutletSchedule;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use SomeNameSpace\Component\YandexMarketIntegration\Enum\StoreType;
use SomeNameSpace\Component\YandexMarketIntegration\Enum\StoreState;

/**
 * Нормалайзер для YandexMarketOutlet
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class YandexMarketOutletNormalizer implements
    DenormalizerInterface,
    NormalizerInterface,
    DenormalizerAwareInterface,
    NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;

    /**
     * @var YandexMarketOutletAddressNormalizer Нормализатор адреса
     */
    private $addressNormalizer;

    /**
     * @var YandexMarketOutletScheduleNormalizer Нормализатор расписания
     */
    private $scheduleNormalizer;

    /**
     * @var YandexMarketOutletDeliveryNormalizer Нормализатор доставки
     */
    private $deliveryNormalizer;

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        if (in_array('yandex', $context)) {
            $id = (int)$data['id'];
            $name = $data['name'];
            try {
                $type = new StoreType($data['type']);
            } catch (\UnexpectedValueException $e) {
                $type = StoreType::DEPOT();
            }
            $coords = $data['coords'];
            $isMain = $data['isMain'];
            $shopOutletCode = strtolower($data['shopOutletCode']);
            try {
                $visibility = new StoreState($data['visibility']);
            } catch (\UnexpectedValueException $e) {
                $visibility = StoreState::HIDDEN();
            }
            $phones = $data['phones'] ?? [];
            $emails = isset($data['emails'])? $data['emails'] : [];
            $dataAddress = is_array($data['address'])? $data['address'] : [];
        } elseif (in_array('store', $context)) {
            $id = null;
            $name = $data->getName();
            $type = $data->isMainStore()? StoreType::MIXED() : StoreType::DEPOT();
            $coords = $data->getCoordX() . ',' . $data->getCoordY();
            $isMain = $data->isMainStore();
            $shopOutletCode = strtolower($data->getPupSpaceId());
            $visibility = $data->isBlocked()? StoreState::HIDDEN() : StoreState::VISIBLE();
            $phones = $data->getPhones();
            $emails = [];
            $dataAddress = $data->getAddress();
        } else {
            throw new \Exception('Передан неверный контекст(объект) для денормализации.');
        }

        $address = $this->denormalizer->denormalize(
            $dataAddress,
            YandexMarketOutletAddress::class,
            $format,
            $context
        );
        $schedule = $this->denormalizer->denormalize(
            $data,
            YandexMarketOutletSchedule::class,
            $format,
            $context
        );
        $deliveryRules = $this->denormalizer->denormalize(
            $data,
            YandexMarketOutletDeliveryRules::class,
            $format,
            $context
        );

        return new YandexMarketOutlet(
            $id,
            $name,
            $type,
            $coords,
            $isMain,
            $shopOutletCode,
            $visibility,
            $address,
            $phones,
            $schedule,
            $deliveryRules,
            $emails
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === YandexMarketOutlet::class;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($outlet, $format = null, array $context = array())
    {
        return [
            'name' => $outlet->getName(),
            'type' => $outlet->getType()->getValue(),
            'coords' => $outlet->getCoords(),
            'isMain' => $outlet->getIsMain() ? "true" : "false",
            'shopOutletCode' => $outlet->getShopOutletCode(),
            'visibility' => $outlet->getVisibility()->getValue(),
            'address' => $this->normalizer->normalize($outlet->getAddress()),
            'phones' => $this->phoneFormatter($outlet->getPhones()),
            'workingSchedule' => $this->normalizer->normalize($outlet->getWorkingSchedule()),
            'deliveryRules' => [$this->normalizer->normalize($outlet->getDeliveryRules())],
            'emails' => $outlet->getEmails(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($outlet, $format = null)
    {
        return $outlet instanceof YandexMarketOutlet;
    }

    /**
     * Форматирует телефонный номер, в соответствии с требовниями Яндекс.Маркет
     *
     * @param array $phones Массив телефонных номеров
     *
     * @return string[] Массив телефонных номеров
     */
    private function phoneFormatter(array $phones): array
    {
        $optimizedPhones = [];
        foreach ($phones as $phone) {
            $phone = str_replace(['+', '(', ')', ' ', '-'], '', $phone);

            if (strlen($phone) >= 11) {
                $code = '';
                if (strstr($phone, '#')) {
                    [$phone, $code] = explode('#', $phone);
                }

                if (strlen($phone) < 11) {
                    continue;
                }

                $matches = [];
                $pattern = '/(\d)(\d{3})(\d{3})(\d{2})(\d{2})/';
                if (!preg_match($pattern, $phone, $matches)) {
                    continue;
                }

                $phone = sprintf("+7 (%s) %s-%s-%s", $matches[2], $matches[3], $matches[4], $matches[5]);
                if (strlen($code) > 0) {
                    $phone .= ' #' . $code;
                }

                $optimizedPhones[] = $phone;
            }
        }

        return count($optimizedPhones) > 0 ? $optimizedPhones : $phones;
    }
}
