<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $agreement->subject }}</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />

    <script src="{{ asset('js/app.js') }}" defer></script>

    <link rel="dns-prefetch" href="https://fonts.gstatic.com" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito" />
</head>
<body>
    <div id="app">
        <div class="container">
            <div class="card">
                <div class="card-content">
                    <form action="{{ route('agreements.update', ['agreement' => $agreement->token]) }}" method="POST">
                        @method('PUT')

                        @csrf

                        <div class="has-text-centered">
                            <img src="{{ asset('/img/mint-logo.jpg') }}" alt="Mint Financial Planning" width="180px" />
                        </div>
                        <br />

                        <p>{{ strtr($agreement->initial_text, [
                            '%client_name%' => $agreement->client_name,
                            '%subject%' => $agreement->subject
                        ]) }}</p><br />

                        @foreach ($agreement->options as $option)
                            <b-field>
                                <b-checkbox name="option[{{ $option->id }}]">{{ $option->prompt }}</b-checkbox>
                            </b-field>
                        @endforeach

                        <b-field label="Notes">
                            <b-input type="textarea" placeholder="Please enter any concerns or queries you may have." name="notes"></b-input>
                        </b-field>

                        <b-field>
                            <div class="control">
                                <button type="submit" class="button is-success" role="button">Submit</button>
                            </div>
                        </b-field>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
