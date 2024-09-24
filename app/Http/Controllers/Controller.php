<?php

namespace App\Http\Controllers;

use XBase\TableReader;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected static $status = [
        'SUKSES' => '00',
        'GAGAL' => '01',
        'PENDING' => '02',
        'NOT_FOUND' => '401',
        'BAD_REQUEST' => '400'
    ];
}
