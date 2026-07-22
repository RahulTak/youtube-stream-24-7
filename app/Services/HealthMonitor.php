<?php declare(strict_types=1);
namespace App\Services;
use App\Repositories\PlaylistRepository;
final class HealthMonitor {
    public function __construct(private PlaylistRepository $playlists) {}
    public function snapshot(?array $stream): array {
        $diskFree=disk_free_space(dirname(__DIR__,2))?:0;$diskTotal=disk_total_space(dirname(__DIR__,2))?:0;
        $process=$this->process((int)($stream['pid']??0));$runtime=$stream&&$stream['started_at']?max(0,time()-strtotime($stream['started_at'])):0;
        return ['stream'=>$stream,'connection_status'=>$process['running']?'connected':'disconnected','runtime_seconds'=>$runtime,'current_video'=>$stream?$this->currentVideo((int)$stream['playlist_id'],$runtime):null,'restart_count'=>(int)($stream['restart_count']??0),'disk_free_bytes'=>$diskFree,'disk_used_percent'=>$diskTotal?round((1-($diskFree/$diskTotal))*100,1):0,'load_average'=>sys_getloadavg()[0]??null,'process'=>$process,'checked_at'=>gmdate('c')];
    }
    private function currentVideo(int $playlistId,int $elapsed): ?array { $videos=$this->playlists->videos($playlistId);$duration=array_sum(array_map(fn(array $v):int=>max(0,(int)($v['duration_seconds']??0)),$videos));if(!$videos||$duration===0)return null;$point=$elapsed%$duration;foreach($videos as $video){$length=max(0,(int)($video['duration_seconds']??0));if($point<$length)return ['id'=>(int)$video['id'],'title'=>$video['title'],'position_seconds'=>$point,'duration_seconds'=>$length];$point-=$length;}return null; }
    private function process(int $pid): array { if($pid<1||!is_dir('/proc/'.$pid))return ['running'=>false];$status=(string)file_get_contents('/proc/'.$pid.'/status');preg_match('/VmRSS:\s+(\d+)/',$status,$rss);$cpu=trim((string)shell_exec('ps -p '.(int)$pid.' -o %cpu='));return ['running'=>true,'pid'=>$pid,'memory_bytes'=>isset($rss[1])?(int)$rss[1]*1024:null,'cpu_percent'=>is_numeric($cpu)?(float)$cpu:null]; }
}
