@extends('layouts.auth')

@section('title', 'Восстановление пароля')
@section('content')
    <x-forms.auth-form method="post" title="Восстановление пароля" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

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

        <x-forms.text-input
            :isError="$errors->has('password')"
            name="password"
            type="password"
            placeholder="Пароль"
            required
        />
        @error('password')
        <x-forms.error>{{ $message }}</x-forms.error>
        @enderror

        <x-forms.text-input
            :isError="$errors->has('password_confirmation')"
            name="password_confirmation"
            type="password"
            placeholder="Подтверждение пароля"
            required
        />
        @error('password_confirmation')
        <x-forms.error>{{ $message }}</x-forms.error>
        @enderror

        <x-forms.primary-button>Обновить пароль</x-forms.primary-button>

        <x-slot:buttons>
            <div class="space-y-3 mt-5 text-center">
                <div class="text-xxs md:text-xs">
                    <a href="{{ @route('login') }}" class="text-white hover:text-white/70 font-bold">Войти в аккаунт</a>
                </div>
            </div>
        </x-slot:buttons>
    </x-forms.auth-form>
@endsection
