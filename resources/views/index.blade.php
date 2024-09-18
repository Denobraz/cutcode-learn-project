@extends('layouts.auth')

@section('content')
    @auth
        <form method="post" action="{{ route('logout') }}">
            @csrf
            @method('delete')

            <button type="submit">Выйти</button>
        </form>
    @endauth
@endsection
