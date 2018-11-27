<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    protected $table = 'disciplinas';

    protected function pretty($n)
    {
        $n = explode(' ', mb_convert_case($n, MB_CASE_TITLE));
        foreach ($n as $i => $p) {
            if (strlen($p) <= 2) {
                $n[$i] = mb_convert_case($p, MB_CASE_LOWER);
            }

            if (strpos('i ii Iii iv v vi Vii Viii ix x', $p) !== false) {
                $n[$i] = mb_convert_case($p, MB_CASE_UPPER);
            }
        }
        return implode(' ', $n);
    }

    public function getNomeAttribute($n)
    {
        return $this->pretty($n);
    }

    public function getProfessorAttribute($n)
    {
        return $this->pretty($n);
    }

    //Grava as respostas e retorna um código
    public function grava($request)
    {
        //Grava as respostas
        foreach ($request->all() as $num => $resposta) {
            $pergunta = Pergunta::where('numero', $num)->first();
            $resposta = Resposta::novo($this, $pergunta, $resposta);

            if (!$pergunta || !$resposta) {
                return 402;
            }
        }

        //Marca a disciplina como respondida
        Auth::user()->disciplinas()->updateExistingPivot($this->id, ['respondido' => true, 'ip' => $request->ip()]);

        return 200;
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot(['respondido', 'ip']);
    }

    public function professores()
    {
        return $this->belongsToMany(User::class, 'disciplina_professor', 'disciplina_id');
    }
}
