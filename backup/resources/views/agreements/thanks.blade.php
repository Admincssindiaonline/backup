<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>{{ $agreement->subject }}</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />

    <link rel="dns-prefetch" href="https://fonts.gstatic.com" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito" />
</head>
<body>
    <div id="app">
        <div class="container">
            <div class="card">
                <div class="card-content">
                    <div class="has-text-centered">
                        <img src="{{ asset('/img/mint-logo.jpg') }}" alt="Mint Financial Planning" width="180px" /><br /><br />

                        <p>Thanks <strong>{{ $agreement->client_name }}</strong>! We will be in touch.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
