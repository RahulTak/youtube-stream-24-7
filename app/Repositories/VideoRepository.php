<?php declare(strict_types=1);
namespace App\Repositories;
final class VideoRepository extends AbstractRepository {
    public function all(): array { return $this->pdo()->query("SELECT * FROM videos WHERE status='ready' ORDER BY created_at DESC")->fetchAll(); }
    public function find(int $id): ?array { $s=$this->pdo()->prepare('SELECT * FROM videos WHERE id=?');$s->execute([$id]);return $s->fetch()?:null; }
    public function create(array $d): int { $s=$this->pdo()->prepare('INSERT INTO videos(title,filename,original_filename,mime_type,size_bytes,duration_seconds,status) VALUES (?,?,?,?,?,?,?)');$s->execute([$d['title'],$d['filename'],$d['original_filename'],$d['mime_type'],$d['size_bytes'],$d['duration_seconds']??null,$d['status']]);return (int)$this->pdo()->lastInsertId(); }
    public function delete(int $id): void { $this->pdo()->prepare('DELETE FROM videos WHERE id=?')->execute([$id]); }
}
