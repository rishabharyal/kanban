@extends('layouts.app')

@section('content')
<div class="container">
    <div class="auth-form">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="auth-form__input-group">
                <label for="name" class="auth-form__input-group__label">{{ __('Name') }}</label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
            </div>

            <div class="auth-form__input-group">
                <label for="email" class="auth-form__input-group__label">{{ __('E-Mail Address') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
            </div>

            <div class="auth-form__input-group">
                <label for="password" class="auth-form__input-group__label">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
            </div>

            <div class="auth-form__input-group">
                <label for="password-confirm" class="auth-form__input-group__label">{{ __('Confirm Password') }}</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
            </div>

            <div class="auth-form__input-group auth-form__input-group--button">
                <button type="submit" class="button button--quarter">
                    {{ __('Register') }}
                </button>

                <a href="/login">Login</a>
            </div>
        </form>
    </div>
</div>
@endsection
