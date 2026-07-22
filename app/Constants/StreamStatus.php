<?php declare(strict_types=1);
namespace App\Constants;
final class StreamStatus { public const STOPPED='stopped'; public const STARTING='starting'; public const RUNNING='running'; public const FAILED='failed'; public const RESTARTING='restarting'; public const ALL=[self::STOPPED,self::STARTING,self::RUNNING,self::FAILED,self::RESTARTING]; }
