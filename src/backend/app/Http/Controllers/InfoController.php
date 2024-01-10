<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;

class InfoController extends Controller
{

    public function phpinfo(Request $request) {
        echo phpinfo();
        exit;
    }
}
