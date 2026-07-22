<?php declare(strict_types=1);
namespace App\Services;
use App\Repositories\PlaylistRepository;
final class PlaylistService { public function __construct(private PlaylistRepository $playlists,private LogService $log){} public function all():array{return $this->playlists->all();} public function create(string $name,string $description):int{if(trim($name)==='')throw new \InvalidArgumentException('Playlist name is required.');$id=$this->playlists->create(trim($name),trim($description));$this->log->info('Playlist created',['id'=>$id]);return $id;} public function find(int $id):?array{return $this->playlists->find($id);} public function videos(int $id):array{return $this->playlists->videos($id);} public function addVideo(int $p,int $v):void{$this->playlists->addVideo($p,$v);} }
