<?php

use Illuminate\Database\Seeder;

class InstituicoesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('instituicoes')->insert([
            ['sigla' => 'poli', 'nome' => 'Escola Politécnica de Pernambuco'],
            ['sigla' => 'fcap', 'nome' => 'Faculdade de Ciências da Administração de Pernambuco'],
        ]);
    }
}
