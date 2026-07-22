<?php declare(strict_types=1);
namespace App\Repositories;
use App\Infrastructure\Database; use PDO;
abstract class AbstractRepository { public function __construct(protected Database $database){} protected function pdo(): PDO{return $this->database->connection();} }
