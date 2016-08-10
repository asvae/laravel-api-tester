var elixir = require('laravel-elixir')

var paths = {
    js: [],
    css: [],
}

// JSONEditor
paths.js.push('./node_modules/jsoneditor/dist/jsoneditor.js')
paths.css.push('./node_modules/jsoneditor/dist/jsoneditor.min.css')

elixir(function (mix) {
    mix.sass('api-tester.sass', './resources/assets/tmp')
    paths.css.push('./resources/assets/tmp/api-tester.css')

    mix.browserify('api-tester.js', './resources/assets/tmp/app.js')
    paths.js.push('./resources/assets/tmp/app.js')

    mix.copy('./node_modules/font-awesome/fonts/**', './resources/assets/build/fonts')

    mix.scripts(paths.js, './resources/assets/build/api-tester.js', './')
    mix.styles(paths.css, './resources/assets/build/api-tester.css', './')
});

