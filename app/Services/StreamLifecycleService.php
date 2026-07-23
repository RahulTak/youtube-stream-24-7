<?php declare(strict_types=1);
namespace App\Services;
use App\Constants\StreamStatus;use App\Repositories\StreamRepository;
final class StreamLifecycleService {
    private const TRANSITIONS=[StreamStatus::QUEUED=>[StreamStatus::STARTING,StreamStatus::STOPPING,StreamStatus::FAILED],StreamStatus::STARTING=>[StreamStatus::RUNNING,StreamStatus::STOPPING,StreamStatus::FAILED],StreamStatus::RUNNING=>[StreamStatus::RESTARTING,StreamStatus::STOPPING,StreamStatus::FAILED],StreamStatus::RESTARTING=>[StreamStatus::RUNNING,StreamStatus::STOPPING,StreamStatus::FAILED],StreamStatus::STOPPING=>[StreamStatus::STOPPED,StreamStatus::FAILED],StreamStatus::STOPPED=>[],StreamStatus::FAILED=>[]];
    public function __construct(private StreamRepository $streams,private LogService $log){}
    public function transition(array $stream,string $next,array $data=[]):void { $current=$stream['status'];if(!in_array($next,self::TRANSITIONS[$current]??[],true))throw new \LogicException("Invalid stream transition {$current} → {$next}");$this->streams->update((int)$stream['id'],array_merge(['status'=>$next],$data));$this->log->info('Stream state changed',['stream_id'=>$stream['id'],'from'=>$current,'to'=>$next]); }
    public function fail(array $stream,string $reason):void { $this->transition($stream,StreamStatus::FAILED,['pid'=>null,'last_error'=>$reason]); }
}
