<?php declare(strict_types=1);
namespace App\Services;
final class HealthMonitor { public function snapshot(?array $stream):array { $disk=disk_free_space(dirname(__DIR__,2));$total=disk_total_space(dirname(__DIR__,2));return ['stream'=>$stream,'disk_free_bytes'=>$disk?:0,'disk_used_percent'=>$total?round((1-($disk/$total))*100,1):0,'memory_limit'=>ini_get('memory_limit'),'checked_at'=>gmdate('c')]; } }
