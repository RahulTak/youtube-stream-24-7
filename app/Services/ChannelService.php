<?php declare(strict_types=1);
namespace App\Services;
use App\Repositories\ChannelRepository;
final class ChannelService { public function __construct(private ChannelRepository $channels,private LogService $log){} public function all():array{return $this->channels->all();} public function create(array $d):void{foreach(['name','rtmp_url','stream_key'] as $k)if(empty(trim($d[$k]??'')))throw new \InvalidArgumentException('All required channel fields must be supplied.');if(!filter_var($d['rtmp_url'],FILTER_VALIDATE_URL))throw new \InvalidArgumentException('RTMP URL must be a valid URL.');$this->channels->create(array_map('trim',$d));$this->log->info('Channel created',['name'=>$d['name']]);} public function find(int $id):?array{return $this->channels->find($id);} }
