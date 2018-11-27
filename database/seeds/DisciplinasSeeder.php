<?php

use App\Instituicao;
use Illuminate\Database\Seeder;

class DisciplinasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arquivo = database_path('seeds/dados/disciplinas.csv');
        if (!file_exists($arquivo)) {
            return false;
        }

        $disciplinas = file($arquivo);

        DB::beginTransaction();

        foreach ($disciplinas as $disciplina) {
            $disciplina = explode(';', $disciplina);
            DB::table('disciplinas')->insert([
                'codigo'         => trim($disciplina[0]),
                'instituicao_id' => Instituicao::where('sigla', $disciplina[1])->first()->id,
                'nome'           => trim($disciplina[2]),
            ]);
        }

        DB::commit();
    }
}
