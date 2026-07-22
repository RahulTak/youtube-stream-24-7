<?php declare(strict_types=1);
namespace App\Controllers;
abstract class Controller { protected function view(string $template,array $data=[]):never {view($template,$data);} protected function input(string $key):string{return trim((string)($_POST[$key]??''));} }
