<?php declare(strict_types=1);
namespace App\Repositories;
final class ImportJobRepository extends AbstractRepository {
    public function create(string $url): int { $s=$this->pdo()->prepare('INSERT INTO import_jobs(source_url,status) VALUES (?,?)');$s->execute([$url, \App\Constants\ImportStatus::QUEUED]);return (int)$this->pdo()->lastInsertId(); }
    public function next(): ?array { $this->pdo()->beginTransaction(); try { $job=$this->pdo()->query("SELECT * FROM import_jobs WHERE status='queued' ORDER BY id LIMIT 1 FOR UPDATE")->fetch(); if($job) $this->pdo()->prepare("UPDATE import_jobs SET status='importing',started_at=UTC_TIMESTAMP() WHERE id=?")->execute([$job['id']]); $this->pdo()->commit(); return $job?:null; } catch(\Throwable $e) {$this->pdo()->rollBack();throw $e;} }
    public function complete(int $id,int $videoId): void { $s=$this->pdo()->prepare("UPDATE import_jobs SET status='ready',video_id=?,completed_at=UTC_TIMESTAMP() WHERE id=?");$s->execute([$videoId,$id]); }
    public function fail(int $id,string $error): void { $s=$this->pdo()->prepare("UPDATE import_jobs SET status='failed',error_message=?,completed_at=UTC_TIMESTAMP() WHERE id=?");$s->execute([mb_substr($error,0,2000),$id]); }
    public function recent(): array { return $this->pdo()->query('SELECT * FROM import_jobs ORDER BY id DESC LIMIT 20')->fetchAll(); }
}
