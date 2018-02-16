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

    @if ($guesses->count())
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-body bg-danger">
                @foreach ($guesses as $guess)
                    <span class="badge badge-pill badge-warning">{{ $guess->guess }}</span>
                @endforeach
            </div>
        </div>
    </div>
    <br>
    @endif

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
    <br>

    @if ($rounds->count())
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-header bg-dark text-white">Results</div>
            <div class="card-body bg-secondary">
                @foreach ($rounds as $round)
                    @if ($round->isComplete())
                        <span class="badge badge-pill {{ $round->won ? 'badge-success' : 'badge-danger' }}">{{ $round->phrase->text }}</span>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    <br>
    @endif
</div>
@endsection
