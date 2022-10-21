const mix = require('laravel-mix');
const path = require('path');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig({
    resolve: {
        alias: {
            'Components': path.resolve(__dirname, 'resources/js/components/'),
            'Helpers': path.resolve(__dirname, 'resources/js/helpers/'),
            'Constants': path.resolve(__dirname, 'resources/js/constants/'),
            'Views': path.resolve(__dirname, 'resources/js/views/'),
        }
    },
    output: {
        chunkFilename: mix.inProduction() ? "js/prod/chunks/[name]?id=[chunkhash].js" : "js/dev/chunks/[name].js"
    }
});

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css');

mix.js('resources/js/main.js', 'public/js');