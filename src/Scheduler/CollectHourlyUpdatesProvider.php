<?php

namespace App\Scheduler;

use App\Scheduler\Message\CollectHourlyUpdatesMessage;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

#[AsSchedule('default')]
class CollectHourlyUpdatesProvider implements ScheduleProviderInterface
{
    public function getSchedule(): Schedule
    {
        return (new Schedule())->add(
            RecurringMessage::every('15 seconds', new CollectHourlyUpdatesMessage(0))
        );
    }
}