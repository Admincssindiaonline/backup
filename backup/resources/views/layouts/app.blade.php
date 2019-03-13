<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />

    <script>let user_id = {{ auth()->id() ?? 'null' }};</script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.4/clipboard.min.js" integrity="sha256-FiZwavyI2V6+EXO1U+xzLG3IKldpiTFf3153ea9zikQ=" crossorigin="anonymous" defer></script>

    <link rel="dns-prefetch" href="https://fonts.gstatic.com" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito" />
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/3.2.89/css/materialdesignicons.min.css">
</head>
<body>
    <div id="app">
        <nav class="navbar" role="navigation">
            <div class="container">
                <div class="navbar-brand">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('/img/mint-logo.jpg') }}" alt="Mint Financial Planning" height="70px" />
                    </a>

                    <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" @click="showNav = !showNav" :class="{'is-active': showNav}">
                        <span aria-hidden="true"></span>
                        <span aria-hidden="true"></span>
                        <span aria-hidden="true"></span>
                    </a>
                </div>

                <div id="main-navbar" class="navbar-menu" :class="{'is-active': showNav}">
                    <div class="navbar-start">
                        @auth
                            <a class="navbar-item{{ Route::is('home') ? ' active' : '' }}" href="{{ route('home') }}"><span>{{ __('Dashboard') }}</span></a>
                            <a class="navbar-item{{ Route::is('agreements.create') ? ' active' : '' }}" href="{{ route('agreements.create') }}"><span>{{ __('Create Agreement') }}</span></a>
                        @endif
                    </div>

                    <div class="navbar-end">
                        @guest
                            <a class="navbar-item" href="{{ route('login') }}"><span>{{ __('Login') }}</span></a>
                            @if (Route::has('register'))
                                <a class="navbar-item" href="{{ route('register') }}"><span>{{ __('Register') }}</span></a>
                            @endif
                        @else
                            <div class="navbar-item has-dropdown is-hoverable">
                                <a class="navbar-link">{{ Auth::user()->name }}</a>
                                <div class="navbar-dropdown">
                                    <a class="navbar-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>
