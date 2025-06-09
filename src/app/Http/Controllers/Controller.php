<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController; // <-- ¡MUY IMPORTANTE ESTA LÍNEA!

class Controller extends BaseController // <-- Y esta extensión
{
    use AuthorizesRequests, ValidatesRequests; // <-- Y estos traits
}