@extends('layouts.auth')

@section('title', 'Забыли пароль')
@section('content')
    <x-forms.auth-form method="post" title="Забыли пароль" action="{{ route('password.request') }}">
        @csrf
        <x-forms.text-input
            :isError="$errors->has('email')"
            name="email"
            type="email"
            placeholder="Email"
            value="{{ old('email') }}"
            required
        />
        @error('email')
        <x-forms.error>{{ $message }}</x-forms.error>
        @enderror

        <x-forms.primary-button>Отправить</x-forms.primary-button>

        <x-slot:buttons>
            <div class="space-y-3 mt-5 text-center">
                <div class="text-xxs md:text-xs">
                    <a href="{{ @route('login') }}" class="text-white hover:text-white/70 font-bold">Вспомнил пароль</a>
                </div>
            </div>
        </x-slot:buttons>
    </x-forms.auth-form>
@endsection
