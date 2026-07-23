<?php declare(strict_types=1);
namespace App\Streaming;
final class FfmpegSupervisor {
    public function run(string $command, callable $onOutput, callable $shouldStop): array {
        $process=proc_open($command,[0=>['pipe','r'],1=>['pipe','w'],2=>['pipe','w']],$pipes);
        if(!is_resource($process))throw new \RuntimeException('Unable to start FFmpeg.');
        fclose($pipes[0]);stream_set_blocking($pipes[1],false);stream_set_blocking($pipes[2],false);
        $status=proc_get_status($process);$pid=(int)$status['pid'];$stopped=false;
        try { while(($status=proc_get_status($process))['running']) { foreach([1,2]as$i){$line=stream_get_contents($pipes[$i]);if($line!=='')$onOutput($line);} if($shouldStop()){$stopped=true;$this->stop($process);break;} sleep((int)config('stream.poll_seconds')); } foreach([1,2]as$i){$line=stream_get_contents($pipes[$i]);if($line!=='')$onOutput($line);if(is_resource($pipes[$i]))fclose($pipes[$i]);} return ['pid'=>$pid,'exit_code'=>proc_close($process),'stopped'=>$stopped]; } finally { foreach([1,2]as$i)if(isset($pipes[$i])&&is_resource($pipes[$i]))fclose($pipes[$i]);if(is_resource($process))$this->stop($process); }
    }
    private function stop(mixed $process):void { if(!is_resource($process))return;proc_terminate($process,15);$until=time()+(int)config('stream.terminate_timeout_seconds');while(proc_get_status($process)['running']&&time()<$until)usleep(200000);if(proc_get_status($process)['running'])proc_terminate($process,9); }
}
