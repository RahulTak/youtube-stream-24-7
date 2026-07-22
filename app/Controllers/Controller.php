<?php declare(strict_types=1);
namespace App\Controllers;
abstract class Controller { protected function view(string $template,array $data=[]):never {extract($data,EXTR_SKIP);require dirname(__DIR__,2).'/views/'.$template.'.php';exit;} protected function input(string $key):string{return trim((string)($_POST[$key]??''));} }
