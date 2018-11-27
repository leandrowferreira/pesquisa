<?php

namespace App\Http\Controllers;

use App\Pergunta;
use Illuminate\Http\Request;

class PerguntaController extends Controller
{
    public function index()
    {
        return Pergunta::orderBy('numero')->get();
    }
}
