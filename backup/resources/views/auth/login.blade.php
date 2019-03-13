@section('title', __('Login'))
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="card-header-title">{{ __('Login') }}</div>
        </div>

        <div class="card-content">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <b-field label="Email Address" @if ($errors->has('email')) type="is-danger" message="{{ $errors->first('email') }}" @endif>
                    <b-input type="text" name="email" value="{{ old('email') }}" maxlength="255" required autofocus></b-input>
                </b-field>

                <b-field label="Password" @if ($errors->has('password')) type="is-danger" message="{{ $errors->first('password') }}" @endif>
                    <b-input type="password" name="password" required></b-input>
                </b-field>

                <b-field>
                    <div class="control">
                        <button type="submit" class="button is-success">
                            {{ __('Login') }}
                        </button>

                        @if (Route::has('password.request'))
                            <a class="button is-link is-inverted" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </b-field>
            </form>
        </div>
    </div>
</div>
@endsection
