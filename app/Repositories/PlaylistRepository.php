<?php declare(strict_types=1);
namespace App\Repositories;
final class PlaylistRepository extends AbstractRepository {
    public function all():array{return $this->pdo()->query('SELECT p.*,COUNT(pv.video_id) video_count FROM playlists p LEFT JOIN playlist_videos pv ON pv.playlist_id=p.id GROUP BY p.id ORDER BY p.created_at DESC')->fetchAll();}
    public function find(int $id):?array{$s=$this->pdo()->prepare('SELECT * FROM playlists WHERE id=?');$s->execute([$id]);return $s->fetch()?:null;}
    public function create(string $name,string $description):int{$s=$this->pdo()->prepare('INSERT INTO playlists(name,description)VALUES(?,?)');$s->execute([$name,$description]);return (int)$this->pdo()->lastInsertId();}
    public function update(int $id,string $name,string $description):void{$this->pdo()->prepare('UPDATE playlists SET name=?,description=? WHERE id=?')->execute([$name,$description,$id]);}
    public function delete(int $id):void{$this->pdo()->prepare('DELETE FROM playlists WHERE id=?')->execute([$id]);}
    public function videos(int $id):array{$s=$this->pdo()->prepare('SELECT v.*,pv.position FROM playlist_videos pv JOIN videos v ON v.id=pv.video_id WHERE pv.playlist_id=? ORDER BY pv.position');$s->execute([$id]);return $s->fetchAll();}
    public function addVideo(int $playlistId,int $videoId):void{$s=$this->pdo()->prepare('INSERT IGNORE INTO playlist_videos(playlist_id,video_id,position) VALUES(?,?,(SELECT COALESCE(MAX(position),0)+1 FROM (SELECT * FROM playlist_videos) x WHERE playlist_id=?))');$s->execute([$playlistId,$videoId,$playlistId]);}
    public function removeVideo(int $playlistId,int $videoId):void{$this->pdo()->prepare('DELETE FROM playlist_videos WHERE playlist_id=? AND video_id=?')->execute([$playlistId,$videoId]);}
    public function reorder(int $playlistId,array $videoIds):void{$this->pdo()->beginTransaction();try{$s=$this->pdo()->prepare('UPDATE playlist_videos SET position=? WHERE playlist_id=? AND video_id=?');foreach(array_values($videoIds) as $p=>$id)$s->execute([$p+1,$playlistId,(int)$id]);$this->pdo()->commit();}catch(\Throwable $e){$this->pdo()->rollBack();throw $e;}}
}
