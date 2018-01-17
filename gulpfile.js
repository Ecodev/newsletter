var gulp = require('gulp');
var shell = require('gulp-shell');

var paths = {
    js: [
        'Resources/Public/JavaScript/**/*.js',
        '!Resources/Public/JavaScript/NVD3/*',
        '!Resources/Public/JavaScript/Override/*',
    ],
    php: [
        '**/*.php',
        '!3dparty/*',
        '!vendor/*',
    ],
};

gulp.task('default', ['composer', 'lint-js', 'lint-php'], function () {
    // place code for your default task here
});

gulp.task('lint-js', function () {
    var jshint = require('gulp-jshint');
    var jscs = require('gulp-jscs');

    return gulp.src(paths.js)
        .pipe(jscs({configPath: '.jscs.json'}))
        .pipe(jshint())
        .pipe(jshint.reporter());
});

gulp.task('lint-php', ['composer'], shell.task([
    './vendor/bin/php-cs-fixer fix . --diff',
]));

gulp.task('composer', function () {
    var composer = require('gulp-composer');
    return composer('install', {});
});

gulp.task('watch', function () {
    gulp.watch(paths.js, ['lint-js']);
    gulp.watch(paths.php, ['lint-php']);
});

