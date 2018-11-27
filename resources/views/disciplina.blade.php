@extends('layouts.app')

@section('content')
<div class="container">
    <div class="alert alert-primary" role="alert">
        <h4 class="mb-0 alert-heading">{{$disciplina->nome}}</h4>
        @foreach($disciplina->professores as $professor)
            <span class="text-muted small">{{$professor->nome}}</span><br>
        @endforeach
    </div>

    <pesq-perguntas disc-id="{{$disciplina->id}}"></pesq-perguntas>

    <div class="row">
        <div class="col-12 text-center">
            <a class="btn btn-link mt-2" href="/disciplinas">Sair sem salvar</a>
        </div>
    </div>

</div>
@endsection