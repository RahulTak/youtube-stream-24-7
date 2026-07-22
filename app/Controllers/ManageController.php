<?php declare(strict_types=1);
namespace App\Controllers;
use App\Services\{ChannelService,ImportService,LogService,PlaylistService,SettingsService,StreamService,VideoService};
final class ManageController extends Controller {
    public function __construct(private ChannelService $channels, private PlaylistService $playlists, private VideoService $videos, private StreamService $streams, private ImportService $imports, private SettingsService $settings, private LogService $log) {}
    public function channels(): never { $this->view('channels',['channels'=>$this->channels->all()]); }
    public function createChannel(): never { $this->channels->create($_POST); redirect('/channels'); }
    public function videos(): never { $this->view('videos',['videos'=>$this->videos->all(),'imports'=>$this->imports->recent()]); }
    public function upload(): never { $this->videos->upload($_FILES['video']??[]); redirect('/videos'); }
    public function import(): never { $this->imports->queue($this->input('url')); redirect('/videos'); }
    public function playlists(): never { $this->view('playlists',['playlists'=>$this->playlists->all(),'videos'=>$this->videos->all()]); }
    public function createPlaylist(): never { $this->playlists->create($this->input('name'),$this->input('description')); redirect('/playlists'); }
    public function addPlaylistVideo(): never { $this->playlists->addVideo((int)$_POST['playlist_id'],(int)$_POST['video_id']); redirect('/playlists'); }
    public function settings(): never { $this->view('settings',['settings'=>$this->settings->all()]); }
    public function saveSettings(): never { $this->settings->save($_POST); redirect('/settings'); }
    public function logs(): never { $this->view('logs',['entries'=>$this->log->recent()]); }
    public function start(): never { $this->streams->start((int)$_POST['channel_id'],(int)$_POST['playlist_id'],$this->input('scheduled_start'),$this->input('scheduled_end')); redirect('/'); }
    public function stop(): never { $this->streams->stop((int)$_POST['stream_id']); redirect('/'); }
}
