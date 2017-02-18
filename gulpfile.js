var gulp = require('gulp'),
    cleanCSS = require('gulp-minify-css'),
    rename = require('gulp-rename');

gulp.task('styles', function () {
    return gulp.src(['./template/**/*.css', '!./template/**/*.min.css'])
        .pipe(cleanCSS({
            keepSpecialComments: 1,
            level: 2
        }))
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(function (file) {
            return file.base;
        }));
});


gulp.task('watch', function () {
    gulp.watch(['./template/**/*.css', '!./template/**/*.min.css'], ['styles']);
});
