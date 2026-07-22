<?php declare(strict_types=1);
namespace App\Services;
use App\Repositories\PlaylistRepository;
final class PlaylistService {
    public function __construct(private PlaylistRepository $playlists,private LogService $log){}
    public function all():array{return $this->playlists->all();}
    public function create(string $name,string $description):int{if(trim($name)==='')throw new \InvalidArgumentException('Playlist name is required.');$id=$this->playlists->create(trim($name),trim($description));$this->log->info('Playlist created',['id'=>$id]);return $id;}
    public function update(int $id,string $name,string $description):void{if(trim($name)==='')throw new \InvalidArgumentException('Playlist name is required.');$this->playlists->update($id,trim($name),trim($description));}
    public function delete(int $id):void{$this->playlists->delete($id);}
    public function find(int $id):?array{return $this->playlists->find($id);}
    public function videos(int $id):array{return $this->playlists->videos($id);}
    public function addVideo(int $p,int $v):void{$this->playlists->addVideo($p,$v);}
    public function removeVideo(int $p,int $v):void{$this->playlists->removeVideo($p,$v);}
    public function reorder(int $p,array $ids):void{$valid=array_column($this->videos($p),'id');if(count($ids)!==count($valid)||array_diff($ids,$valid))throw new \InvalidArgumentException('Invalid video order.');$this->playlists->reorder($p,$ids);}
}
