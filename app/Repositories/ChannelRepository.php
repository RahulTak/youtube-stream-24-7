<?php declare(strict_types=1);
namespace App\Repositories;
final class ChannelRepository extends AbstractRepository {
    public function all(): array { return $this->pdo()->query('SELECT id,name,description,rtmp_url,status,created_at FROM channels ORDER BY created_at DESC')->fetchAll(); }
    public function find(int $id): ?array { $s=$this->pdo()->prepare('SELECT * FROM channels WHERE id=?');$s->execute([$id]);return $s->fetch()?:null; }
    public function create(array $d): void { $this->pdo()->prepare('INSERT INTO channels(name,description,rtmp_url,stream_key)VALUES(?,?,?,?)')->execute([$d['name'],$d['description'],$d['rtmp_url'],$d['stream_key']]); }
    public function update(int $id,array $d): void { $this->pdo()->prepare('UPDATE channels SET name=?,description=?,rtmp_url=?,stream_key=? WHERE id=?')->execute([$d['name'],$d['description'],$d['rtmp_url'],$d['stream_key'],$id]); }
    public function delete(int $id): void { $this->pdo()->prepare('DELETE FROM channels WHERE id=?')->execute([$id]); }
    public function setStatus(int $id,string $status): void { $this->pdo()->prepare('UPDATE channels SET status=? WHERE id=?')->execute([$status,$id]); }
}
