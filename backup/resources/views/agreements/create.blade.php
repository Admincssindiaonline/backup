@section('title', 'Create Agreement')
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

            <form action="{{ route('agreements.store') }}" method="POST">
                @csrf

                <b-field label="Client Name" @if ($errors->has('client_name')) type="is-danger" message="{{ $errors->first('client_name') }}" @endif>
                    <b-input type="text" name="client_name" value="{{ old('client_name') }}" maxlength="64" required autofocus></b-input>
                </b-field>

                <b-field label="Subject" @if ($errors->has('subject')) type="is-danger" message="{{ $errors->first('subject') }}" @endif>
                    <b-input type="text" name="subject" value="{{ old('subject') }}" maxlength="128" required></b-input>
                </b-field>

                <b-field label="Initial Text" @if ($errors->has('initial_text')) type="is-danger" message="{{ $errors->first('initial_text') }}" @endif>
                    <b-input type="textarea" name="initial_text" value="{{ old('initial_text') ?? 'Hi %client_name%, please accept the %subject% agreement.' }}" maxlength="1024" required></b-input>
                </b-field>

                <div v-for="(option, index) in options">
                    <b-field :label="'Option ' + (index + 1)">
                        <b-field expanded>
                            <p class="control">
                                <a class="button is-outlined" @click="removeOption(index)" :disabled="options.length === 1">
                                    <b-icon icon="delete"></b-icon>
                                </a>
                            </p>

                            <b-input type="text" :name="'options[' + index + ']'" maxlength="255" v-model="options[index]" required expanded></b-input>
                        </b-field>
                    </b-field>
                    <br />
                </div>

                <b-field>
                    <div class="control">
                        <a class="button is-outlined" @click="addOption()">
                            <b-icon icon="plus"></b-icon> <span>Add Option</span>
                        </a>
                    </div>
                </b-field>

                <b-field>
                    <div class="control">
                        <button type="submit" class="button is-success">
                            {{ __('Create') }}
                        </button>
                    </div>
                </b-field>
            </form>
        </div>
    </div>
</div>
@endsection
