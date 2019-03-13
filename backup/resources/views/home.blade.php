@section('title', 'Dashboard')
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-content">
            @if (session('status'))
                <article class="message is-success">
                    <div class="message-body">{{ session('status') }}</div>
                </article>
            @endif

            <dashboard></dashboard>
        </div>
    </div>
</div>

@if (session()->has('token'))
@php ($token = session()->get('token'))
@php ($route = route('agreements.show', ['token' => $token]))
@php ($route_nomark = route('agreements.show', ['token' => $token, 'nomark']))
<b-modal :active="modalActive" :on-cancel="closeModal">
    <div class="card">
        <div class="card-header">
            <p class="card-header-title">
                Agreement Created
            </p>
        </div>

        <div class="card-content">
            <p>Your new agreement was created succesfully. Send the link below to your clients, but don't open it yourself, or it will be marked as "Seen"!</p><br />
            <p>
                Click
                <a role="button" class="button is-outlined is-small" href="{{ $route_nomark }}" target="_blank">
                    <b-icon icon="eye" size="is-small"></b-icon> <span>View</span>
                </a>
                if you want to see how an agreement looks.
            </p><br />

            <b-field expanded>
                <b-input type="text" id="created_token" value="{{ $route }}" readonly required expanded></b-input>

                <p class="control">
                    <button type="button" class="button is-outlined" data-clipboard-text="{{ $route }}">
                        <b-icon icon="link-variant" size="is-small"></b-icon> <span>Copy</span>
                    </button>
                </p>
            </b-field>
        </div>
    </div>
</b-modal>
@endif
@endsection
