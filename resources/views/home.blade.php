@extends('layouts.app')

@section('content')
<div class="container">
    <div class="jumbotron pt-0">
        <div class="row">
            <div class="col-md-8">
                <h1 class="display-5">Pesquisa Institucional<br>Campus Benfica</h1>
            </div>
            <div class="col-md-2 col-sm-6 col-6">
                <img class="img-fluid" src="img/fcap.png">
            </div>
            <div class="col-md-2 col-sm-6 col-6">
                <img class="img-fluid" src="img/poli.png">
            </div>
        </div>

        <hr class="my-4">

        <p>
            Durante o período de 29 de novembro a 07 de dezembro estará no ar
            a pesquisa de <strong>Avaliação da Prática Pedagógica Docente - Campus
            Benfica 2018.2</strong>, incluindo as unidades de educação FCAP
            (Administração e Direito) e POLI (Engenharias e Física de Materiais).
        </p>
        <p>
            Sua opinião é muito importante, pois é a partir dela que
            conseguiremos identificar pontos passíveis de ajustes em nossas
            práticas pedagógicas, facilitando, assim, a busca pela melhoria do
            ensino e da aprendizagem.
        </p>
        <p>
            A pesquisa é totalmente sigilosa. Suas respostas não serão associadas
            a você. A única informação que guardamos a seu respeito é se você
            respondeu ou não a pesquisa. Sua participação é importantíssima
            para que possamos construir uma Universidade cada vez mais adequada
            às suas expectativas e às necessidades acadêmicas e do mercado.
        </p>


        @auth
        <a href="/disciplinas" class="btn btn-success btn-lg">Responder à pesquisa</a>
        @endauth
        <hr class="my-4">

        @auth
        <a href="/logout" class="btn btn-link mt-2">Sair da pesquisa</a>
        @endauth
        @guest
        <a href="/login" class="btn btn-primary md-2">Acessar</a>
        @endguest


    </div>

</div>
@endsection
