<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class UserOptions {
    
    public function handle($request, Closure $next) {
        if(!session()->has('sound')) {
            $opt = 'off';
            session()->put('sound', $opt);
        }
		
        if(!session()->has('theme')) {
            $opt = 'light';
            session()->put('theme', $opt);
        }

        return $next($request);
    }
}
