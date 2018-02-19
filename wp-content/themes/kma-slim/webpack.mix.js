const { mix } = require('laravel-mix');

mix.js('js/app.js', './')
    .sass('sass/style.scss', './');

mix.options({
    postCss: [
        require('autoprefixer')({
            grid: true,
            browsers: ['last 2 versions', 'IE 9', 'Safari 9']
        })
    ]
});