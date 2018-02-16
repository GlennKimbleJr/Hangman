@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-default">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (Auth::user()->hasGameInProgress())
                        <a href="{{ route('play') }}" class="btn btn-lg btn-info">Continue Game</a>
                    @else
                        <form method="POST" action="{{ route('new-game') }}">
                            {{ csrf_field() }}
                            <button class="btn btn-lg btn-info">Start Game</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
