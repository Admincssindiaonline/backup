@section('title', __('Reset Password'))
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="card-header-title">{{ __('Reset Password') }}</div>
        </div>

        <div class="card-content">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <b-field label="Email Address" @if ($errors->has('email')) type="is-danger" message="{{ $errors->first('email') }}" @endif>
                    <b-input type="email" name="email" value="{{ $email ?? old('email') }}" maxlength="255" required autofocus></b-input>
                </b-field>

                <b-field label="Password" @if ($errors->has('password')) type="is-danger" message="{{ $errors->first('password') }}" @endif>
                    <b-input type="password" name="password" required></b-input>
                </b-field>

                <b-field label="Confirm Password">
                    <b-input type="password" name="password_confirmation" required></b-input>
                </b-field>

                <div class="field">
                    <div class="control">
                        <button type="submit" class="button is-success">
                            {{ __('Reset Password') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
