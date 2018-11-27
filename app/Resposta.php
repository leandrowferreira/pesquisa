<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Resposta extends Model
{
    protected $table = 'respostas';
    protected $fillable = ['disciplina_id', 'pergunta_id', 'tipo', 'resposta', 'feedback', 'nome', 'email'];
    public $timestamps = false;

    public static function novo(Disciplina $disciplina, Pergunta $pergunta, $resposta)
    {
        switch ($pergunta->tipo) {
            case 1:
            case 2:
                $resposta = self::create([
                    'disciplina_id' => $disciplina->id,
                    'pergunta_id'   => $pergunta->id,
                    'tipo'          => Auth::user()->tipo,
                    'resposta'      => $resposta,
                ]);
                break;

            case 4:
                if (strlen(trim($resposta['resposta']))) {
                    $resposta = self::create([
                        'disciplina_id' => $disciplina->id,
                        'pergunta_id'   => $pergunta->id,
                        'tipo'          => Auth::user()->tipo,
                        'resposta'      => $resposta['resposta'],
                        'feedback'      => $resposta['feedback'],
                        'nome'          => $resposta['feedback'] ? $resposta['nome'] : null,
                        'email'         => $resposta['feedback'] ? $resposta['email'] : null,
                    ]);
                }
                break;
        }

        return $resposta;
    }
}
