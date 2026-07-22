<?php declare(strict_types=1);
namespace App\Controllers;
use App\Services\{ChannelService,HealthMonitor,PlaylistService,StreamService,VideoService};
final class DashboardController extends Controller { public function __construct(private StreamService $streams,private ChannelService $channels,private PlaylistService $playlists,private VideoService $videos,private HealthMonitor $health){} public function index():never{$this->view('dashboard',['active'=>$this->streams->active(),'channels'=>$this->channels->all(),'playlists'=>$this->playlists->all(),'videos'=>$this->videos->all(),'health'=>$this->health->snapshot($this->streams->active())]);} public function health():never{header('Content-Type: application/json');echo json_encode($this->health->snapshot($this->streams->active()));exit;} }
