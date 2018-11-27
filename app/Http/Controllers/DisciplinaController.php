<?php

namespace App\Http\Controllers;

use App\Pergunta;
use App\Disciplina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisciplinaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('disciplinas');
    }

    public function show(Disciplina $disciplina)
    {
        //Verifica se já foi respondida
        $user = Auth::user();
        if ($user->disciplinas->find($disciplina->id)->pivot->respondido) {
            return redirect('/disciplinas');
        }

        $perguntas = Pergunta::orderBy('numero')->get();

        return view('disciplina', compact('disciplina', 'perguntas'));
    }

    public function store(Disciplina $disciplina, Request $request)
    {
        if (!$disciplina) {
            return response('', 401);
        }
        return response('', $disciplina->grava($request));
    }
}
