<?php declare(strict_types=1);
namespace App\Middleware;
final class AuthMiddleware { public function enforce(): void { if(!isset($_SESSION['user_id'])) redirect('/login'); } }
