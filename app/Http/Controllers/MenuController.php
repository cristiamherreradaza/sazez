<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Menu;

class MenuController extends Controller
{
    public function listado()
    {
        $menus = Menu::get();
        return view('menu.listado')->with(compact('menus'));
    }
}
