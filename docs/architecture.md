Welcome to the j-laravel-vue-boilerplate wiki!, This is my custom boilerplate for small to large enterprise application.

## .vscode

# Laravel - Backend

by default refer to the laravel docs regarding directory structure
https://laravel.com/docs/6.x/structure

however in this boilerplate additional directory structures were added

## app/Traits/

The `app/Traits/` directory contains the php traits of your application.

## app/Entities/

The `app/Entities` directory contains the Eloquent Model Entities, You may want to put all the Eloquent Model in this folder instead of the laravel default location

## app/Repositories

The `app/Repositories` directory contains the repositories generated from l5-repository package

## app/Validators

The `app/Validators` directory contains the validator l5-validators implementation

## app/Parsers

The `app/Parsers` directory contains parsers, by default an abstract implementation of search parsers is provided by default.

## app/Http/Controllers/Api

The `app/Http/Controllers/Api` contains the controllers for api-endpoints that returns `application/json` content, by default an abstract implementation of api controller is provided by default for any api to extends on.

## app/Http/Controllers/Web

The `app/Http/Controllers/Web` contains the controllers for web-endpoints, which do not return `application/json` by default an abstract implementation of web controller is provided by default for any controller to extends on.

Settings and extensions specific to this project, check for Visual Studio Code. See the editors doc for more

##docs
You found me

##`_app`

# VueJS - Frontend
