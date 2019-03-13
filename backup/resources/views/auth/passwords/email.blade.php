@section('title', __('Reset Password'))
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="card-header-title">{{ __('Reset Password') }}</div>
        </div>

        <div class="card-content">
            @if (session('status'))
                <article class="message is-success">
                    <div class="message-body">{{ session('status') }}</div>
                </article>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <b-field label="Email Address" @if ($errors->has('email')) type="is-danger" message="{{ $errors->first('email') }}" @endif>
                    <b-input type="text" name="email" value="{{ $email ?? old('email') }}" maxlength="255" required autofocus></b-input>
                </b-field>

                <b-field>
                    <div class="control">
                        <button type="submit" class="button is-success">
                            {{ __('Send Password Reset Link') }}
                        </button>
                    </div>
                </b-field>
            </form>
        </div>
    </div>
</div>
@endsection
