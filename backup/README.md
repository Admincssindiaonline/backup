# email-agreement-system

## Installation

Grab all the required dependencies:

* `npm install`
* `composer install`

Ensure that the assets are compiled and up-to-date:

* `npm run production`

## Redis

Redis is required for pushing events to the websockets server. You can easily build it from source and run it as follows:

* `wget http://download.redis.io/redis-stable.tar.gz && tar xvzf redis-stable.tar.gz`
* `cd redis-stable`
* `make distclean`
* `make`
* `sudo make install`
* `rm redis-stable.tar.gz`
* `redis-server`

If you'd like to run redis in a screen, use `screen -dmLS redis redis-server`.

## Websockets

laravel-echo-server is required for hosting the socket.io server which Laravel Echo connects to for live updates. You can install and run it as follows:

* `npm install -g laravel-echo-server`
* `laravel-echo-server start`

If you'd like to run the websocket server in a screen, use `screen -dmLS websockets laravel-echo-server start`.
