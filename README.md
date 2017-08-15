# guidap-mystery-number
Guess the number app for Guidap tech test (symfony 3, Vue.js 2)

@update 2017-08-15: Some server test have been added (we discussed tests in interview).

## Pre requisites
* PHP 7.1
* Composer
* npm
* phpunit

## Installation
1. Clone the repo
1. go into the folder
1. composer install
1. Set the trusted_client_referers paramter in `app/config/parameters.yml`. It's an array of authorized referers.
1. npm install
1. npm run build-dev OR build-prod to test prod setup
1. php bin/console server:run OR use your prefered server to test prod setup. In this case, remember to add the app adress to the trusted_client_referers array or the client will be rejected by the server API.

## Building blocks
* A simple vue application in `/web/assets` built with webpack-encore;
* A single bundle `AppBundle` for the symfony application:
  * A mystery number service which hold the game logic;
  * A mystery number controller which consist in one API endpoint;
  * An app controller which serve the base html and the vue application;
  * A play event fired when a number is tested against the mystery number;
  * A play listener which log tested number into a file;
  * Tests for service and controllers;
