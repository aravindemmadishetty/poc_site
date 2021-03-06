<?php

namespace MailPoet\Cron\Workers\KeyCheck;

if (!defined('ABSPATH')) exit;


use MailPoet\Cron\Workers\SimpleWorker;
use MailPoet\Entities\ScheduledTaskEntity;
use MailPoet\Models\ScheduledTask;
use MailPoet\Services\Bridge;
use MailPoetVendor\Carbon\Carbon;

abstract class KeyCheckWorker extends SimpleWorker {
  public $bridge;

  public function init() {
    if (!$this->bridge) {
      $this->bridge = new Bridge();
    }
  }

  public function processTaskStrategy(ScheduledTaskEntity $task, $timer) {
    try {
      $result = $this->checkKey();
    } catch (\Exception $e) {
      $result = false;
    }

    if (empty($result['code']) || $result['code'] == Bridge::CHECK_ERROR_UNAVAILABLE) {
      $parisTask = ScheduledTask::getFromDoctrineEntity($task);
      if ($parisTask) {
        $parisTask->rescheduleProgressively();
      }
      return false;
    }

    return true;
  }

  public function getNextRunDate() {
    $date = Carbon::createFromTimestamp($this->wp->currentTime('timestamp'));
    return $date->startOfDay()
      ->addDay()
      ->addHours(rand(0, 5))
      ->addMinutes(rand(0, 59))
      ->addSeconds(rand(0, 59));
  }

  public abstract function checkKey();
}
