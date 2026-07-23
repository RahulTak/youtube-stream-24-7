<?php declare(strict_types=1);
namespace App\Controllers;
use App\Services\{ChannelService,HealthCheckService,ImportService,LogService,PlaylistService,SettingsService,StreamDiagnosticsService,StreamService,VideoService};
final class ManageController extends Controller {
    public function __construct(private ChannelService $channels, private PlaylistService $playlists, private VideoService $videos, private StreamService $streams, private ImportService $imports, private SettingsService $settings, private LogService $log, private HealthCheckService $healthChecks, private StreamDiagnosticsService $streamDiagnostics) {}
    public function channels(): never { $this->view('channels',['channels'=>$this->channels->all()]); }
    public function createChannel(): never { $this->channels->create($_POST); redirect('/channels'); }
    public function updateChannel(): never { $this->channels->update((int)$_POST['channel_id'],$_POST); redirect('/channels'); }
    public function deleteChannel(): never { $this->channels->delete((int)$_POST['channel_id']); redirect('/channels'); }
    public function videos(): never { $this->view('videos',['videos'=>$this->videos->all(),'imports'=>$this->imports->recent()]); }
    public function upload(): never { $this->videos->upload($_FILES['video']??[]); redirect('/videos'); }
    public function deleteVideo(): never { $this->videos->delete((int)$_POST['video_id']); redirect('/videos'); }
    public function import(): never { $this->imports->queue($this->input('url')); redirect('/videos'); }
    public function playlists(): never { $playlists=$this->playlists->all();$playlistVideos=[];foreach($playlists as $playlist)$playlistVideos[$playlist['id']]=$this->playlists->videos((int)$playlist['id']);$this->view('playlists',['playlists'=>$playlists,'videos'=>$this->videos->all(),'playlistVideos'=>$playlistVideos]); }
    public function createPlaylist(): never { $this->playlists->create($this->input('name'),$this->input('description')); redirect('/playlists'); }
    public function updatePlaylist(): never { $this->playlists->update((int)$_POST['playlist_id'],$this->input('name'),$this->input('description')); redirect('/playlists'); }
    public function deletePlaylist(): never { $this->playlists->delete((int)$_POST['playlist_id']); redirect('/playlists'); }
    public function addPlaylistVideo(): never { $this->playlists->addVideo((int)$_POST['playlist_id'],(int)$_POST['video_id']); redirect('/playlists'); }
    public function removePlaylistVideo(): never { $this->playlists->removeVideo((int)$_POST['playlist_id'],(int)$_POST['video_id']); redirect('/playlists'); }
    public function reorderPlaylist(): never { $this->playlists->reorder((int)$_POST['playlist_id'],array_map('intval',$_POST['video_ids']??[])); header('Content-Type: application/json'); echo '{"ok":true}'; exit; }
    public function settings(): never { $this->view('settings',['settings'=>$this->settings->all()]); }
    public function saveSettings(): never { $this->settings->save($_POST); redirect('/settings'); }
    public function logs(): never { $this->view('logs',['entries'=>$this->log->recent()]); }
    public function diagnostics(): never { $stream=$this->streams->active();$this->view('diagnostics',['report'=>$this->healthChecks->report($stream),'streamDiagnostics'=>$this->streamDiagnostics->read($stream)]); }
    public function start(): never { $this->streams->start((int)$_POST['channel_id'],(int)$_POST['playlist_id'],$this->input('scheduled_start'),$this->input('scheduled_end')); redirect('/'); }
    public function stop(): never { $this->streams->stop((int)$_POST['stream_id']); redirect('/'); }
    public function restart(): never { $this->streams->restart((int)$_POST['stream_id']); redirect('/'); }
}
