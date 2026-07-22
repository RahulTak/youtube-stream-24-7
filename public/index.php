<?php declare(strict_types=1);
use App\Http\Router;use App\Infrastructure\Container;
require dirname(__DIR__).'/vendor/autoload.php';require dirname(__DIR__).'/app/Helpers/functions.php';
date_default_timezone_set(config('app.timezone'));session_name(config('app.session_name'));session_set_cookie_params(['httponly'=>true,'secure'=>(($_SERVER['HTTPS']??'')==='on'),'samesite'=>'Lax']);session_start();$_SESSION['csrf']??=bin2hex(random_bytes(32));
set_exception_handler(function(Throwable $e):void{error_log((string)$e);if(PHP_SAPI!=='cli'&&!headers_sent()&&!str_starts_with($_SERVER['REQUEST_URI']??'','/api/')){flash('danger',config('app.debug')?$e->getMessage():'We could not complete that action.');redirect($_SERVER['HTTP_REFERER']??'/');}http_response_code(500);echo config('app.debug')?e($e->getMessage()):'An unexpected error occurred.';});
$router=new Router(new Container());require dirname(__DIR__).'/routes/web.php';$router->dispatch();
