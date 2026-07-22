<?php declare(strict_types=1);
namespace App\Controllers;
use App\Services\AuthService;
final class AuthController extends Controller { public function __construct(private AuthService $auth){} public function login():never{$this->view('auth/login');} public function authenticate():never{if($this->auth->login($this->input('email'),$_POST['password']??'')){redirect('/');}$this->view('auth/login',['error'=>'Invalid email or password.']);} public function logout():never{$this->auth->logout();redirect('/login');} }
