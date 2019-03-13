@section('title', __('Register'))
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="card-header-title">{{ __('Register') }}</div>
        </div>

        <div class="card-content">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <b-field label="Name" @if ($errors->has('name')) type="is-danger" message="{{ $errors->first('name') }}" @endif>
                    <b-input type="text" name="name" value="{{ old('name') }}" maxlength="255" required autofocus></b-input>
                </b-field>

                <b-field label="Email Address" @if ($errors->has('email')) type="is-danger" message="{{ $errors->first('email') }}" @endif>
                    <b-input type="email" name="email" value="{{ old('email') }}" maxlength="255" required></b-input>
                </b-field>

                <b-field label="Password" @if ($errors->has('password')) type="is-danger" message="{{ $errors->first('password') }}" @endif>
                    <b-input type="password" name="password" required></b-input>
                </b-field>

                <b-field label="Confirm Password">
                    <b-input type="password" name="password_confirmation" required></b-input>
                </b-field>

                <b-field>
                    <div class="control">
                        <button type="submit" class="button is-success">
                            {{ __('Register') }}
                        </button>
                    </div>
                </b-field>
            </form>
        </div>
    </div>
</div>
@endsection
