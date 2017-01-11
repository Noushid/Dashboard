var gulp = require('gulp'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename');

var config = {
    nodeDir: 'node_modules'
};

gulp.task('scripts', function () {
    return gulp.src([
        'resource/js/*.js',
        'resource/js/controller/admin/*.js',
        'resource/js/controller/*.js'
    ])
        .pipe(concat('angularApp.js'))
        .pipe(gulp.dest('public/assets/admin/js/'))
        .pipe(rename('angularApp.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('public/assets/admin/js/'));
});

gulp.task('mix', function () {
    return gulp.src([
        'resource/js/dist/angular.min.js',
        'resource/js/dist/angular-route.min.js',
        'resource/js/dist/ui-bootstrap-tpls-0.12.1.min.js'
    ])
        .pipe(concat('angular-bootstrap.min.js'))
        .pipe(gulp.dest('public/assets/admin/js/'))
});


gulp.task('default', ['scripts','mix']);