<?php

use App\Instituicao;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    protected $users;
    protected $profs;

    protected $cam_prof_nao_encontrado;

    private function encontra($user)
    {
        $res = null;
        foreach ($this->profs as $prof) {
            $prof = explode(';', $prof);
            if ($prof[0] == $user[1]) {
                $dist = levenshtein(iconv('utf-8', 'ascii//TRANSLIT', $prof[1]), iconv('utf-8', 'ascii//TRANSLIT', $user[2]));
                if ($dist < 2) {
                    // echo iconv('utf-8', 'ascii//TRANSLIT', $user[1]) . ' - ' . iconv('utf-8', 'ascii//TRANSLIT', $user[2]) . ' - ' . iconv('utf-8', 'ascii//TRANSLIT', $prof[1]) . ' = ' . $dist . "\n";
                    return preg_replace('/[^0-9]/', '', $prof[2]);
                }
            }
        }
        // echo '--- Não encontrado: ' . $user[2] . ';' . $user[1] . "\n";
        file_put_contents($this->cam_prof_nao_encontrado, $user[2] . ';' . $user[1] . "\n", FILE_APPEND | FILE_TEXT);
        return $user[0];
    }

    public function run()
    {
        $arquivo = database_path('seeds/dados/users.csv');
        $arqCpf = database_path('seeds/dados/const/professor-cpf.csv');

        $this->cam_prof_nao_encontrado = database_path('seeds/dados/professor-nao-encontrado.csv');

        if (!file_exists($arquivo)) {
            return false;
        }

        if (file_exists($this->cam_prof_nao_encontrado)) {
            unlink($this->cam_prof_nao_encontrado);
        }

        $this->users = file($arquivo);
        $this->profs = file($arqCpf);

        DB::beginTransaction();

        foreach ($this->users as $user) {
            $user = explode(';', trim($user));

            //Se for professor, tenta encontrar o CPF
            if ($user[3] == 'P') {
                $user[0] = $this->encontra($user);
            }

            DB::table('users')->insert([
                'cpf'            => trim($user[0]),
                'instituicao_id' => Instituicao::where('sigla', $user[1])->first()->id,
                'nome'           => trim($user[2]),
                'tipo'           => trim($user[3]),
                'md5'            => md5(trim($user[2])),
            ]);
        }

        DB::commit();
    }
}
