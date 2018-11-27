# Pesquisa Institucional
Formalmente Avaliação da Prática Pedagógica Docente
#
Site em Laravel+VueJS para aplicação de pesquisa institucional nas unidades FCAP e POLI da UPE

Trata-se de uma solução simples desenvolvida para realizar uma pesquisa anônima a respeito das diversas disciplinas do Câmpus Benfica da UPE.

As informações iniciais (dados dos alunos, disciplinas e professores) foram coletadas de arquivos txt (pdftotext) provenientes das atas geradas pelo sistema acadêmico Sig@. Para isto, foi desenvolvido um parser simples, exposto sob a forma de comando do Artisan (atas:processa).

Para executar o sistema, são necessários os seguintes passos:
- rodar as migrações (php artisan migrate);
- inserir os dados no formato csv no diretório /database/seeds/dados;
- executar o seed (php artisan db:seed).
