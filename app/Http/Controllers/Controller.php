<?php

namespace App\Http\Controllers;

// 1. Importamos la clase base de las rutas de Laravel
use Illuminate\Routing\Controller as BaseController; 
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

// 2. Hacemos que extienda de BaseController
abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}