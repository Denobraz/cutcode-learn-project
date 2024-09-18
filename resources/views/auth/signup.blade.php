@extends('layouts.auth')

@section('title', 'Регистрация')
@section('content')
    <x-forms.auth-form method="post" title="Регистрация" action="{{ route('register') }}">
        @csrf

        <x-forms.text-input
            :isError="$errors->has('name')"
            name="name"
            type="text"
            placeholder="Имя"
            value="{{ old('name') }}"
            required
        />
        @error('name')
        <x-forms.error>{{ $message }}</x-forms.error>
        @enderror

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
            value="{{ old('password') }}"
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
            value="{{ old('password_confirmation') }}"
            required
        />
        @error('password_confirmation')
        <x-forms.error>{{ $message }}</x-forms.error>
        @enderror

        <x-forms.primary-button>Зарегистрироваться</x-forms.primary-button>

        <x-slot:buttons>
            <div class="space-y-3 mt-5 text-center">
                <div class="text-xxs md:text-xs">
                    <a href="{{ route('login') }}" class="text-white hover:text-white/70 font-bold">Войти в аккаунт</a>
                </div>
            </div>
        </x-slot:buttons>
    </x-forms.auth-form>
@endsection
