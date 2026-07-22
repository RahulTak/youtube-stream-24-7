<?php declare(strict_types=1);
namespace App\Services;
final class RateLimitService {
    public function exceeded(string $key,int $maxAttempts,int $windowSeconds): bool { $now=time();$attempts=$_SESSION['limits'][$key]??[];$attempts=array_values(array_filter($attempts,fn(int $at):bool=>$at>$now-$windowSeconds));$_SESSION['limits'][$key]=$attempts;return count($attempts)>=$maxAttempts; }
    public function hit(string $key): void { $_SESSION['limits'][$key][] = time(); }
    public function clear(string $key): void { unset($_SESSION['limits'][$key]); }
}
