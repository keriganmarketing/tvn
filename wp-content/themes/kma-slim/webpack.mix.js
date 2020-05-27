const mix = require('laravel-mix')
const webpack = require('webpack')

mix.js('js/app.js', './');
mix.sass('sass/style.scss', './');