const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig({
    output: {
        filename: '[name].js',
        chunkFilename: (mix.inProduction() ? 
            'assets/js/chunks/[name].[chunkhash].js' : 
            'assets/js/chunks/[name].js'
        ),
    }
})

mix.ts('resources/assets/ts/app.ts', 'public/assets/js')
    .vue()
    .sass('resources/assets/sass/app.sass', 'public/assets/css/app.min.css')
    .options({
        hmrOptions: {
            host: 'localhost',
            port: 8080
        }
    })

if (mix.inProduction()) {
    mix.extract()
    mix.version()
}

mix.disableNotifications()
