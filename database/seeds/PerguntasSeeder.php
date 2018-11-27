<?php

use Illuminate\Database\Seeder;

class PerguntasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('perguntas')->insert([
            ['numero'   => 1, 'tipo' => 1, 'feedback'=> false,
                'texto' => 'O professor disponibilizou o plano do componente curricular contendo objetivos, ementa, conteúdos, metodologia, critério de avaliação e bibliografia?'],

            ['numero'   => 2, 'tipo' => 2, 'feedback'=> false,
                'texto' => 'Os conteúdos trabalhados eram coerentes com os que constavam no plano do componente curricular?'],

            ['numero'   => 3, 'tipo' => 2, 'feedback'=> false,
                'texto' => 'Os conteúdos foram trabalhados com clareza e objetividade?'],

            ['numero'   => 4, 'tipo' => 2, 'feedback'=> false,
                'texto' => 'As avaliações possuíam nível e abrangência compatíveis com os conteúdos trabalhados durante as aulas?'],

            ['numero'   => 5, 'tipo' => 2, 'feedback'=> false,
                'texto' => 'Os resultados das avaliações foram apresentados e discutidos em sala de aula com os estudantes?'],

            ['numero'   => 6, 'tipo' => 2, 'feedback'=> false,
                'texto' => 'O professor do componente curricular era pontual (chegava no horário da aula)?'],

            ['numero'   => 7, 'tipo' => 1, 'feedback'=> false,
                'texto' => 'O professor do componente curricular era assíduo (tinha frequência constante)?'],

            ['numero'   => 8, 'tipo' => 2, 'feedback'=> false,
                'texto' => 'A relação entre professor e aluno era ética e respeitosa?'],

            ['numero'   => 9, 'tipo' => 4, 'feedback'=> false,
                'texto' => 'Com base em suas respostas anteriores, o que pode ser feito para melhorar o processo de aprendizagem nesta disciplina?'],
        ]);
    }
}
