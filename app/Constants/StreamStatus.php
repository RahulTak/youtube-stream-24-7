<?php declare(strict_types=1);
namespace App\Constants;
final class StreamStatus { public const QUEUED='queued';public const STARTING='starting';public const RUNNING='running';public const RESTARTING='restarting';public const STOPPING='stopping';public const STOPPED='stopped';public const FAILED='failed';public const ACTIVE=[self::QUEUED,self::STARTING,self::RUNNING,self::RESTARTING,self::STOPPING]; }
