<?php namespace App\Http\Controllers;

class SettingsController extends Controller {
	
    public function __construct()
    {
        parent::__construct();
    }
	
	public function switchSound() {
		if(session()->get('sound') == 'on') $opt = 'off';
		else $opt = 'on';
		session()->put('sound', $opt);
		return $opt;
	}
	
	public function switchTheme() {
		if(session()->get('theme') == 'light') $opt = 'dark';
		else $opt = 'light';
		session()->put('theme', $opt);
		return $opt;
	}
}