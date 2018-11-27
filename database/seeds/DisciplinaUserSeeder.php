<?php

use App\User;
use App\Disciplina;
use Illuminate\Database\Seeder;

class DisciplinaUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arquivo = database_path('seeds/dados/disciplina_user.csv');
        if (!file_exists($arquivo)) {
            return false;
        }

        $userDiscs = file($arquivo);

        DB::beginTransaction();

        foreach ($userDiscs as $userDisc) {
            $userDisc = explode(';', $userDisc);

            $user = User::where('cpf', trim($userDisc[0]))->orWhere('md5', $userDisc[0])->first();
            $disciplina = Disciplina::where('codigo', trim($userDisc[1]))->firstOrFail();

            DB::table('disciplina_user')->insert([
                'user_id'       => $user->id,
                'disciplina_id' => $disciplina->id,
            ]);
        }

        DB::commit();
    }
}
