<?php
declare(strict_types=1);

namespace SomeNameSpace\Component\YandexMarketIntegration\Normalizer;

use SomeNameSpace\Component\YandexMarketIntegration\Model\YandexMarketOutletSchedule;
use SomeNameSpace\Component\YandexMarketIntegration\Model\YandexMarketOutletScheduleItems;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Нормалайзер для YandexMarketOutletSchedule
 *
 * @author Konstantin Vaganov <vaganov.k@citilink.ru>
 */
class YandexMarketOutletScheduleNormalizer implements
    DenormalizerInterface,
    NormalizerInterface,
    DenormalizerAwareInterface,
    NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;

    /**
     * @var array Массив с названиями дней недели. Используется для перевода номера дня недели.
     */
    private $scheduleDays = [1 => 'MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY'];

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, $context = array())
    {
        if (in_array('store', $context)) {
            $storeSchedule = $data->getSchedule();

            $workInHoliday = $data->isMainStore() ? true : false;
            $scheduleItems = [];
            foreach ($storeSchedule->getWorkDays() as $item) {
                $startDay = $item->getDayOfWeek()->getValue();
                $endDay = $item->getDayOfWeek()->getValue();
                $startTime = sprintf(
                    "%'02d:%'02d",
                    (int)$item->getOpen()->getHour(),
                    (int)$item->getOpen()->getMinute()
                );
                $endTime = sprintf(
                    "%'02d:%'02d",
                    (int)$item->getClose()->getHour(),
                    (int)$item->getClose()->getMinute()
                );
                if ($startDay == null || $endDay == null || $startTime == null || $endTime == null) {
                    continue;
                }

                $scheduleItemIndex = end($scheduleItems);
                if ($scheduleItemIndex !== false
                    && $scheduleItemIndex->getStartTime() === $startTime
                    && $scheduleItemIndex->getEndTime() === $endTime
                ) {
                    $scheduleItemIndex->setEndDay($this->scheduleDays[$endDay]);
                    continue;
                }

                $scheduleItems[] = new YandexMarketOutletScheduleItems(
                    $this->scheduleDays[$startDay],
                    $this->scheduleDays[$endDay],
                    $startTime,
                    $endTime
                );
            }

            if (count($scheduleItems) === 0) {
                throw new \Exception('Не заполнено расписание работы точки продаж(магазина).');
            }

            return new YandexMarketOutletSchedule($workInHoliday, $scheduleItems);
        }

        if (in_array('yandex', $context)) {
            $storeSchedule = $data['workingSchedule'];

            $workInHoliday = $storeSchedule['workInHoliday'];

            $scheduleItems = [];
            foreach ($storeSchedule['scheduleItems'] as $scheduleItem) {
                $startDay = $scheduleItem['startDay'];
                $endDay = $scheduleItem['endDay'];
                $startTime = $scheduleItem['startTime'];
                $endTime = $scheduleItem['endTime'];

                $scheduleItems[] = new YandexMarketOutletScheduleItems($startDay, $endDay, $startTime, $endTime);
            }

            return new YandexMarketOutletSchedule($workInHoliday, $scheduleItems);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === YandexMarketOutletSchedule::class;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($outletSchedule, $format = null, $context = array())
    {
        return [
            "workInHoliday" => $outletSchedule->getWorkInHoliday()? "true" : "false",
            "scheduleItems" => $this->normalizer->normalize($outletSchedule->getScheduleItems()),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($outletSchedule, $format = null)
    {
        return $outletSchedule instanceof YandexMarketOutletSchedule;
    }
}
