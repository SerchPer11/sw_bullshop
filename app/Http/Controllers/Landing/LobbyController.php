<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LobbyController extends Controller
{
    protected String $source;
    
    public function __construct()
    {
        $this->source = 'Landing/';
    }

    public function index()
    {
        return Inertia::render("{$this->source}Lobby");
    }
}
