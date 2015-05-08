var gulp            = require('gulp'),
    del             = require('del'),
    uglify          = require('gulp-uglify'),
    rename          = require('gulp-rename'),
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
    del(['build/js'], cb);
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
    gulp.src('*.php')
        .pipe(gulp.dest('./build'))
        .pipe(notify({ message: 'PHP task complete' }));
});

gulp.task('watch', function() {
    gulp.watch('*.php', ['php']);
});

gulp.task('default', ['clean', 'js', 'php', 'watch']);