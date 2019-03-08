const { mix } = require('laravel-mix');

mix.options({
    postCss: [
        require('autoprefixer')({
            grid: true,
            browsers: ['last 2 versions', 'IE 9', 'Safari 9']
        })
    ]
});


mix.js('js/app.js', './');
mix.sass('sass/style.scss', './');