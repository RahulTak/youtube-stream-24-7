<?php declare(strict_types=1);
namespace App\Infrastructure;
use PDO;
final class Database { private PDO $pdo; public function __construct() { $c=config('database'); $this->pdo=new PDO("mysql:host={$c['host']};port={$c['port']};dbname={$c['database']};charset={$c['charset']}",$c['username'],$c['password'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]); } public function connection(): PDO { return $this->pdo; } }
