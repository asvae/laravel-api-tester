var elixir = require('laravel-elixir')
require('laravel-elixir-webpack-ex')

elixir(function (mix) {
    mix.webpack('api-tester.js', require('./webpack.config.js'), 'src/resources/assets/build/', 'src/resources/assets/js/' )

    var filesToCopy = [
        './node_modules/material-design-lite/dist/material.min.js',
        './node_modules/material-design-lite/dist/material.cyan-red.min.css',
        './node_modules/jsoneditor/dist/jsoneditor.min.js',
        './node_modules/jsoneditor/dist/jsoneditor.min.css',
    ]
    mix.copy(filesToCopy, 'src/resources/assets/build')
});

