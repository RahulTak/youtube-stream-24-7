<?php declare(strict_types=1);
namespace App\Middleware;
final class CsrfMiddleware { public function enforce(array $input): void { if(!hash_equals($_SESSION['csrf']??'',(string)($input['_csrf']??''))) throw new \RuntimeException('Invalid request token.'); } }
