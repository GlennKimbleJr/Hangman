@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-default">
                <div class="card-header"></div>

                <div class="card-body">
                    <h1>{{ $phrase }}</h1>
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card-body bg-info">
                <form method="post" action="{{ route('guess-letter') }}">
                    {{ csrf_field() }}
                    <input type="text" class="form-control" name="guess" maxlength="1" required><br>
                    <button class="btn">Guess A Letter</button>
                </form>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-body bg-warning text-right">
                <form method="post" action="{{ route('guess-phrase') }}">
                    {{ csrf_field() }}
                    <input type="text" class="form-control" name="guess" required><br>
                    <button class="btn">Guess The Phrase</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
