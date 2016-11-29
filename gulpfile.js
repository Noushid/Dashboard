var gulp = require('gulp'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify');

var config = {
    nodeDir: 'node_modules'
};

gulp.task('scripts', function () {
    return gulp.src([
        'resource/js/*.js',
        'resource/js/controller/*.js'
    ])
        .pipe(concat('angularApp.js'))
        //.pipe(uglify())
        .pipe(gulp.dest('public/js/'));
});

gulp.task('default', ['scripts']);