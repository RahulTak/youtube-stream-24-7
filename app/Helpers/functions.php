<?php declare(strict_types=1);
function env(string $key, ?string $default=null): ?string { static $values=null; if ($values===null) { $values=[]; $file=dirname(__DIR__,2).'/.env'; if(is_file($file)) foreach(file($file, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES) as $line) { if(!str_starts_with(trim($line),'#') && str_contains($line,'=')) { [$k,$v]=explode('=',$line,2); $values[trim($k)]=trim($v," \t\"'"); } } } return $_ENV[$key] ?? getenv($key) ?: $values[$key] ?? $default; }
function config(string $key, mixed $default=null): mixed { static $configs=[]; [$file,$path]=array_pad(explode('.', $key, 2),2,null); if(!isset($configs[$file])) $configs[$file]=require dirname(__DIR__,2)."/config/$file.php"; $value=$configs[$file]; foreach(explode('.', $path??'') as $segment) { if($segment==='' ) continue; if(!is_array($value)||!array_key_exists($segment,$value)) return $default; $value=$value[$segment]; } return $value; }
function e(?string $value): string { return htmlspecialchars($value ?? '', ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); }
function redirect(string $path): never { header('Location: '.$path, true, 302); exit; }
function view(string $template,array $data=[]): never { extract($data,EXTR_SKIP);require dirname(__DIR__,2).'/views/'.$template.'.php';exit; }
function flash(string $type,string $message): void { $_SESSION['flash'][]=['type'=>$type,'message'=>$message]; }
function consume_flashes(): array { $messages=$_SESSION['flash']??[];unset($_SESSION['flash']);return $messages; }
