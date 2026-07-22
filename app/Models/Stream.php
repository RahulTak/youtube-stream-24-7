<?php declare(strict_types=1);
namespace App\Models;
final readonly class Stream { public function __construct(public int $id,public int $channelId,public int $playlistId,public string $status,public int $restartCount) {} }
