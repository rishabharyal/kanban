@extends('layouts.app')

@section('content')
<div class="container">
    <div class="auth-form">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="auth-form__input-group">
                <label for="email" class="auth-form__input-group__label">{{ __('E-Mail Address') }}</label>
                <input id="email" type="email" class="input @error('email') input--is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            </div>

            <div class="auth-form__input-group">
                <label for="password" class="auth-form__input-group__label">{{ __('Password') }}</label>
                <input id="password" type="password" class="input @error('password') input--is-invalid @enderror" name="password" required autocomplete="current-password">
            </div>

            <div class="auth-form__input-group auth-form__input-group--button">
                <button type="submit" class="button button--quarter">
                    {{ __('Login') }}
                </button>

                <a href="/register">Register</a>
            </div>
        </form>
    </div>
</div>
@endsection
