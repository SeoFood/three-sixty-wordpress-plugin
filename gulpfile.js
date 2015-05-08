var gulp            = require('gulp'),
    del             = require('del'),
    uglify          = require('gulp-uglify'),
    rename          = require('gulp-rename'),
    less            = require('gulp-less'),
    concat          = require('gulp-concat'),
    filter          = require('gulp-filter'),
    bower           = require('gulp-bower'),
    mainBowerFiles  = require('main-bower-files'),
    notify          = require('gulp-notify');

var config = {
    bowerDir: './bower_components'
};

gulp.task('bower', function() {
    return bower()
        .pipe(gulp.dest(config.bowerDir))
});

gulp.task('clean', function (cb) {
    del(['build/js', 'build/css'], cb);
});

// Compress JS
gulp.task('js', ['bower'], function() {

    gulp.src(mainBowerFiles())
    .pipe(filter('*.js'))
        .pipe(concat('plugin.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('build/js'))
        .pipe(notify({ message: 'Compression task complete' }));
});

gulp.task('php', function() {
    gulp.src('src/*.php')
        .pipe(gulp.dest('./build'))
        .pipe(notify({ message: 'PHP task complete' }));
});

gulp.task('less', function () {
    gulp.src('src/less/main.less')
        .pipe(less())
        .pipe(concat('plugin.min.css'))
        .pipe(gulp.dest('build/css'))
        .pipe(notify({ message: 'Less task complete' }));
});

gulp.task('watch', function() {
    gulp.watch('src/*.php', ['php']);
    gulp.watch('src/**/*.less', ['less']);
});

gulp.task('default', ['clean', 'js', 'php', 'less', 'watch']);