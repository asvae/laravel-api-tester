var elixir = require('laravel-elixir')

elixir(function (mix) {
    mix.browserify('api-tester.js', './resources/assets/build/api-tester.js')

    var filesToCopy = [
        './node_modules/material-design-lite/dist/material.min.js',
        './node_modules/material-design-lite/dist/material.cyan-red.min.css',
        './node_modules/jsoneditor/dist/jsoneditor.min.js',
        './node_modules/jsoneditor/dist/jsoneditor.min.css',
    ]

    mix.copy(filesToCopy, 'resources/assets/build')
});

