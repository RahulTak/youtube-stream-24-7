<?php declare(strict_types=1);
namespace App\Services;
use App\Repositories\SettingsRepository;
final class SettingsService {
    private ?array $values=null;
    public function __construct(private SettingsRepository $settings, private LogService $log) {}
    public function all(): array { return $this->values ??= array_replace(['ffmpeg_path'=>config('ffmpeg.path'),'ytdlp_path'=>config('youtube.ytdlp_path'),'default_resolution'=>config('stream.default_resolution'),'default_bitrate'=>config('stream.default_bitrate'),'auto_restart'=>(string)(int)config('stream.auto_restart'),'timezone'=>config('app.timezone'),'theme'=>'light'],$this->settings->all()); }
    public function save(array $input): void { foreach(['ffmpeg_path','ytdlp_path','default_resolution','default_bitrate','timezone','theme'] as $key) { if(isset($input[$key])) $this->settings->save($key,trim((string)$input[$key])); } $this->settings->save('auto_restart',isset($input['auto_restart'])?'1':'0'); $this->values=null; $this->log->info('Settings updated'); }
}
