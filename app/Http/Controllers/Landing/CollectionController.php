<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CollectionController extends Controller
{
    protected String $source;

    public function __construct()
    {
        $this->source = 'Landing/Collection/';
    }

    public function index()
    {
        Return Inertia::render("{$this->source}Index");
    }
}
