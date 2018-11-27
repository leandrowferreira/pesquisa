@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="POST" action="{{ route('login') }}">
                <div class="card">
                    <div class="card-header">Acesso à pesquisa</div>
                    <div class="card-body">
                        @csrf

                        <div class="form-group row">
                            <label for="cpf" class="col-sm-4 col-form-label text-md-right">CPF</label>

                            <div class="col-md-6">
                                <the-mask value="" id="cpf" type="text" class="form-control{{ $errors->has('cpf') ? ' is-invalid' : '' }}" name="cpf" value="{{ old('cpf') }}" mask="###.###.###-##" required autofocus>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('cpf') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="nome" class="col-md-4 col-form-label text-md-right">Primeiro nome</label>

                            <div class="col-md-6">
                                <input value="" id="nome" type="text" class="form-control{{ $errors->has('nome') ? ' is-invalid' : '' }}" name="nome" required>

                                @if ($errors->has('nome'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('nome') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            Acessar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
