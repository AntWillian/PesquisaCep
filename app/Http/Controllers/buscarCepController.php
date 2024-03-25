<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class buscarCepController extends Controller
{
    public function buscar($cep){

        $res = Http::get("viacep.com.br/ws/{$cep}/json/");

        return json_decode($res);

    }
}
