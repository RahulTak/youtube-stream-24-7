<?php declare(strict_types=1);
namespace App\Repositories;
final class UserRepository extends AbstractRepository { public function findByEmail(string $email): ?array { $s=$this->pdo()->prepare('SELECT * FROM users WHERE email=? LIMIT 1');$s->execute([$email]);return $s->fetch()?:null; } public function count(): int{return (int)$this->pdo()->query('SELECT COUNT(*) FROM users')->fetchColumn();} public function create(string $email,string $hash): void{$s=$this->pdo()->prepare('INSERT INTO users (email,password_hash,role) VALUES (?,?,?)');$s->execute([$email,$hash,'admin']);} }
