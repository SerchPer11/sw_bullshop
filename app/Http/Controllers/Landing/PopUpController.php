<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PopUpController extends Controller
{
    public function index()
    {
        return Inertia::render('Landing/PopUp/Index');
    }
}
