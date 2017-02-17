var gulp = require('gulp'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename'),

    bower = require('gulp-bower'),
    flatten = require('gulp-flatten'),
    minifycss = require('gulp-minify-css'),
    sass = require('gulp-sass'),
    connect = require('gulp-connect'),
    notify = require("gulp-notify"),
    prefixer = require('gulp-autoprefixer'),
    livereload = require('gulp-livereload'),
    imagemin = require('gulp-imagemin'),
    imageminJpegtran = require('imagemin-jpegtran'),
    pngquant = require('imagemin-pngquant');

var config = {
    nodeDir: 'node_modules',
    publicDir:'public/',
    bowerDir: 'bower_components',
    sassDir: 'resource/sass',
    jsDir: 'resource/scripts',
    fontDir: 'resource/fonts',
    imageDir: 'resource/images',
    htmlDir: 'application/views'
};


gulp.task('scripts', function () {
    return gulp.src([
        'resource/js/*.js',
        'resource/js/controller/admin/*.js',
        'resource/js/controller/*.js'
    ])
        .pipe(concat('angularApp.js'))
        .pipe(gulp.dest(config.publicDir + 'assets/admin/js/'))
        .pipe(rename('angularApp.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest(config.publicDir + 'assets/admin/js/'));
});

// Copy admin bower scripts to public js folder
gulp.task('adminscript', function() {
    return gulp.src([
        config.bowerDir + '/jquery/dist/jquery.js',
        config.bowerDir + '/bootstrap-sass/assets/javascripts/bootstrap.js',
        config.jsDir + '/admin.js',
        config.jsDir + '/custom.js'
    ])
        .pipe(flatten())
        .pipe(concat('appjs.js'))
        .pipe(uglify())
        .pipe(gulp.dest(config.publicDir + 'assets/admin/js/'))
        .pipe(livereload());
});


gulp.task('mix', function () {
    return gulp.src([
        'resource/js/dist/angular.min.js',
        'resource/js/dist/angular-route.min.js',
        'resource/js/dist/ui-bootstrap-tpls-0.12.1.min.js'
    ])
        .pipe(concat('angular-bootstrap.min.js'))
        .pipe(gulp.dest(config.publicDir + 'assets/admin/js/'))
});




// Install bower
gulp.task('bower', function() {
    return bower()
        .pipe(gulp.dest(config.bowerDir));
});

// Move font to public folder
gulp.task('icons', function() {
    return gulp.src([
        config.bowerDir + '/bootstrap-sass/assets/fonts/bootstrap/**.*',
        config.bowerDir + '/font-awesome/fonts/**.*'
    ])
        .pipe(gulp.dest(config.publicDir + 'assets/admin/fonts'));
});

gulp.task('fonts', function() {
    return gulp.src([
        config.fontDir + '/**.*'
    ])
        .pipe(gulp.dest(config.publicDir + 'assets/admin/fonts'));
});

gulp.task('images', function() {
    return gulp.src([
        config.imageDir + '/**.*'
    ])
        .pipe(pngquant({quality: '55-65', speed: 3})())
        .pipe(imageminJpegtran({progressive: true})())
        .pipe(gulp.dest(config.publicDir + 'assets/admin/img'));
});

/*Styles to admin*/
gulp.task('adminstyles', function() {
    return gulp.src('resource/sass/admin.scss')
        .pipe(sass())
        .on("error", notify.onError(function (error) {
            return "Error: " + error.message;
        }))
        .pipe(prefixer({
            browsers: ['last 8 versions'],
            cascade: false
        }))
        .pipe(concat('styleapp.css'))
        .pipe(minifycss({compatibility: 'ie8'}))
        .pipe(gulp.dest(config.publicDir + 'assets/admin/css/'))
        //.pipe(livereload());
});


gulp.task('watch', function() {
    var reload = livereload();
    livereload.listen();
    gulp.watch(config.jsDir + '/*.js', ['scripts', 'adminscript']);
    gulp.watch(config.sassDir + '/**/*.scss', ['adminstyles']);
    gulp.watch(config.bowerDir + '/bootstrap-sass/assets/stylesheets/*.scss', ['adminstyles']);
    gulp.watch(config.bowerDir + '/bootstrap-sass/assets/stylesheets/**/*.scss', ['adminstyles']);
    gulp.watch(config.sassDir + '/*.scss', ['adminstyles']);
    // gulp.watch(htmlDir + '/*.php', livereload.reload);
    // livereload();
});


gulp.task('install', ['bower', 'icons', 'images', 'fonts', 'scripts', 'adminstyles', 'adminscript']);
gulp.task('admin', ['adminstyles', 'adminscript']);
gulp.task('default', ['scripts', 'mix', 'scripts', 'adminstyles', 'adminscript']);
gulp.task('adminjs', ['scripts', 'mix']);