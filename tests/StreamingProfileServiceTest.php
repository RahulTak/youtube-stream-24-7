<?php declare(strict_types=1);
require dirname(__DIR__).'/vendor/autoload.php';require dirname(__DIR__).'/app/Helpers/functions.php';
$profiles=new App\Services\StreamingProfileService();
$low=$profiles->select(['width'=>1280,'height'=>720,'fps'=>30]);$high=$profiles->select(['width'=>1920,'height'=>1080,'fps'=>60]);$ultra=$profiles->select(['width'=>3840,'height'=>2160,'fps'=>30]);
assert($low['resolution']==='1280x720'&&$low['preset']==='veryfast');assert($high['resolution']==='1280x720'&&$high['fps']===30&&$high['preset']==='superfast');assert($ultra['resolution']==='1280x720'&&$ultra['preset']==='superfast');echo "Streaming profile selection passed\n";
