<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class processaAtas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'atas:processa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processa os arquivos-texto das atas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $disciplinas = [];
        $users = [];
        $disciplina_user = [];
        $disciplina_professor = [];

        $prof_idx = 0;

        $pags = [];
        $tmp = [];

        //Texto inútil a remover
        $remover = [
            "\fCÓDIGO DA DISCIPLINA\n",
            "CÓDIGO DA DISCIPLINA\n",
            "FUNDAÇÃO UNIVERSIDADE DE PERNAMBUCO\n",
            "Ata de\n",
            "ORD\n",
            "NOTA\n",
            "CPF\n",
            "NOME DO(A) ALUNO(A)\n",
            "OBSERVAÇÃO: É vedado acrescentar nome de aluno a esta lista.\n",
            "PERÍODO\n",
            "TURMA VAGAS PÁGINA\n",
            "ASSINATURA-REALIZAÇÃO\n",
            "ASSINATURA/DATA-DEVOLUÇÃO\n",
            "2018.2\n",
        ];

        //Lista de arquivos a processar
        $arquivos = [
            ['inst' => 'poli', 'nome' => 'basico_18.2.txt'],
            ['inst' => 'poli', 'nome' => 'civil_18.2.txt'],
            ['inst' => 'poli', 'nome' => 'comput_18.2.txt'],
            ['inst' => 'poli', 'nome' => 'contAut_18.2.txt'],
            ['inst' => 'poli', 'nome' => 'eletricas_18.2.txt'],
            ['inst' => 'poli', 'nome' => 'mec_18.2.txt'],
            ['inst' => 'fcap', 'nome' => 'administracao.txt'],
            ['inst' => 'fcap', 'nome' => 'direito.txt'],
        ];

        //Processa cada um dos arquivos
        foreach ($arquivos as $arquivo) {
            //Obtém o arquivo
            $ata = file(storage_path('app/atas/' . $arquivo['inst'] . '/' . $arquivo['nome']));

            $pags = [];
            $tmp = [];

            //Limpa o texto, removendo linhas desnecessárias
            foreach ($ata as $i => $s) {
                if (strpos($s, 'CÓDIGO DA DISCIPLINA') !== false) {
                    if (sizeof($tmp)) {
                        $pags[] = $tmp;
                        $tmp = [];
                    }
                }

                if (trim($s) &&
                    strpos($s, 'DATA: ') === false &&
                    strpos($s, 'HORA: ') === false &&
                    strpos($s, 'COORD. ') !== 0 &&
                    (strlen($s) >= 5 || ($i > 0 && $ata[$i - 1] == "TURMA VAGAS PÁGINA\n")) &&
                    !preg_match('/^[0-9]+\/[0-9]+$/', $s)
                ) {
                    $retira = false;
                    foreach ($remover as $linha) {
                        if ($linha === $s) {
                            $retira = true;
                            break;
                        }
                    }
                    if (!$retira) {
                        $tmp[] = $s;
                    }
                }
            }
            if ($tmp) {
                $pags[] = $tmp;
            }

            //Percorre as páginas para mapear as disciplinas, os alunos, os professores e as relações
            foreach ($pags as $np => $pag) {
                $cod = '';
                $disc = '';
                $prof = '';

                $us_disc = [];

                foreach ($pag as $n => $linha) {
                    //Busca a turma da disciplina
                    if (strlen($linha) < 5) {
                        $cod = trim($linha) . '/';
                    }

                    //Busca o nome e o código da disciplina
                    elseif ($linha == "NOME DA DISCIPLINA\n") {
                        $disc = $pag[$n + 1];
                        if ($pag[$n + 2] != "PROFESSOR (ES)\n") {
                            $disc .= (' ' . $pag[$n + 2]);
                        }

                        if (!preg_match('/^[0-9]{3}.[0-9]{3}.[0-9]{3}/', $pag[$n - 2])) {
                            $cod = $cod . $pag[$n - 2];
                        } else {
                            $cod = $cod . $pag[0];
                        }
                        $cod = preg_replace(['/\n+/', '/\s+/'], ['', ' '], trim($cod));
                    }

                    //Busca o nome do professor
                    elseif ($linha == "PROFESSOR (ES)\n") {
                        for ($i = $n + 1; $i < sizeof($pag); $i++) {
                            if (!preg_match('/^[0-9]{3}.[0-9]{3}.[0-9]{3}/', $pag[$i])) {
                                $prof .= (' ' . $pag[$i]);
                            }
                        }
                        $profs = explode(' , ', preg_replace(['/\n+/', '/\s+/'], ['', ' '], trim($prof)));
                        foreach ($profs as $prof) {
                            $cpf = md5($prof);

                            //Insere o professor na lista
                            if (!array_key_exists($cpf, $users)) {
                                $users[$cpf] = [
                                    'cpf'  => $cpf,
                                    'inst' => $arquivo['inst'],
                                    'nome' => $prof,
                                    'tipo' => 'P',
                                ];
                            }

                            //Insere o professor como um dos professores da disciplina
                            if (!isset($disciplina_professor[$cod])) {
                                $disciplina_professor[$cod] = [];
                            }
                            if (!in_array($cpf, $disciplina_professor[$cod])) {
                                $disciplina_professor[$cod][] = $cpf;
                            }

                            $us_disc[] = $cpf;
                        }
                    }

                    //Encontrou um aluno
                    elseif (preg_match('/^[0-9]{3}.[0-9]{3}.[0-9]{3}\/[0-9]{2}/', $linha)) {
                        $cpf = preg_replace('/[^0-9]/', '', substr($linha, 0, 14));
                        $nome = preg_replace(['/\n+/', '/\s+/'], ['', ' '], trim(substr($linha, 14)));
                        if (!isset($users[$cpf])) {
                            $users[$cpf] = [
                                'cpf'  => $cpf,
                                'inst' => $arquivo['inst'],
                                'nome' => $nome,
                                'tipo' => 'A',
                            ];
                        }
                        $us_disc[] = $cpf;
                    }
                }

                //Grava a disciplina
                if (!isset($disciplinas[$cod])) {
                    $disciplinas[$cod] = [
                        'cod'       => $cod,
                        'inst'      => $arquivo['inst'],
                        'nome'      => preg_replace(['/\n+/', '/\s+/'], ['', ' '], trim($disc)),
                        // 'professor' => preg_replace(['/\n+/', '/\s+/'], ['', ' '], trim($prof)),
                    ];
                }

                //Grava as relações user-disciplina
                foreach ($us_disc as $us) {
                    $disciplina_user[] = $us . ';' . $cod;
                }
            }
        }
        //Organiza os arrays
        usort($disciplinas, function ($a, $b) {return strcmp(iconv('utf-8', 'ascii//TRANSLIT', $a['nome']), iconv('utf-8', 'ascii//TRANSLIT', $b['nome']));});
        usort($users, function ($a, $b) {
            if (strlen($a['cpf']) < strlen($b['cpf'])) {
                return -1;
            } elseif (strlen($a['cpf']) > strlen($b['cpf'])) {
                return 1;
            } else {
                return strcmp(iconv('utf-8', 'ascii//TRANSLIT', $a['nome']), iconv('utf-8', 'ascii//TRANSLIT', $b['nome']));
            }
        });
        $disciplina_user = array_unique($disciplina_user);
        ksort($disciplina_professor);

        //Gera os arquivos para seeding
        $arqDisc = database_path('seeds/dados/disciplinas.csv');
        $arqUser = database_path('seeds/dados/users.csv');
        $arqDiUs = database_path('seeds/dados/disciplina_user.csv');
        $arqDiPr = database_path('seeds/dados/disciplina_professor.csv');
        if (file_exists($arqDisc)) {
            unlink($arqDisc);
        }
        if (file_exists($arqUser)) {
            unlink($arqUser);
        }
        if (file_exists($arqDiUs)) {
            unlink($arqDiUs);
        }
        if (file_exists($arqDiPr)) {
            unlink($arqDiPr);
        }

        $hd = fopen($arqDisc, 'w');
        $hu = fopen($arqUser, 'w');
        $hdu = fopen($arqDiUs, 'w');
        $hdp = fopen($arqDiPr, 'w');

        foreach ($disciplinas as $i => $disciplina) {
            fwrite($hd, $disciplina['cod'] . ';' . $disciplina['inst'] . ';' . str_replace(';', ',', $disciplina['nome']) . "\n");
        }

        foreach ($users as $i => $user) {
            fwrite($hu, $user['cpf'] . ';' . $user['inst'] . ';' . str_replace(';', ',', $user['nome']) . ';' . $user['tipo'] . "\n");
        }

        foreach ($disciplina_user as $du) {
            fwrite($hdu, $du . "\n");
        }

        foreach ($disciplina_professor as $disc => $dp) {
            foreach ($dp as $prof) {
                fwrite($hdp, $disc . ';' . $prof . "\n");
            }
        }

        fclose($hd);
        fclose($hu);
        fclose($hdu);
    }
}
