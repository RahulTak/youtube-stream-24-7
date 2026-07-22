<?php declare(strict_types=1);
namespace App\Services;
use App\Repositories\ChannelRepository;
final class ChannelService {
    public function __construct(private ChannelRepository $channels,private LogService $log){}
    public function all():array{return $this->channels->all();}
    public function create(array $d):void{$this->validate($d);$this->channels->create($this->clean($d));$this->log->info('Channel created',['name'=>$d['name']]);}
    public function update(int $id,array $d):void{$existing=$this->find($id);if(!$existing)throw new \InvalidArgumentException('Channel not found.');if(trim((string)($d['stream_key']??''))==='')$d['stream_key']=$existing['stream_key'];$this->validate($d);$this->channels->update($id,$this->clean($d));$this->log->info('Channel updated',['id'=>$id]);}
    public function delete(int $id):void{$this->channels->delete($id);$this->log->info('Channel deleted',['id'=>$id]);}
    public function find(int $id):?array{return $this->channels->find($id);}
    private function validate(array $d):void{foreach(['name','rtmp_url','stream_key'] as $key)if(trim((string)($d[$key]??''))==='')throw new \InvalidArgumentException('All required channel fields must be supplied.');if(!filter_var($d['rtmp_url'],FILTER_VALIDATE_URL))throw new \InvalidArgumentException('RTMP URL must be a valid URL.');}
    private function clean(array $d):array{return ['name'=>trim((string)$d['name']),'description'=>trim((string)($d['description']??'')),'rtmp_url'=>trim((string)$d['rtmp_url']),'stream_key'=>trim((string)$d['stream_key'])];}
}
