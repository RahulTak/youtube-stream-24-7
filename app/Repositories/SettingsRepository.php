<?php declare(strict_types=1);
namespace App\Repositories;
final class SettingsRepository extends AbstractRepository {
    public function all(): array { return $this->pdo()->query('SELECT `key`,`value` FROM settings')->fetchAll(\PDO::FETCH_KEY_PAIR); }
    public function save(string $key, string $value): void { $s=$this->pdo()->prepare('INSERT INTO settings (`key`,`value`) VALUES (?,?) ON DUPLICATE KEY UPDATE `value`=VALUES(`value`)'); $s->execute([$key,$value]); }
}
