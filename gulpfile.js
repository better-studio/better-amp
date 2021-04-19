var gulp = require('gulp'),
    cleanCSS = require('gulp-minify-css'),
    rename = require('gulp-rename'),
    postcss = require('gulp-postcss'),
    sass = require('gulp-sass'),
    rtlcss = require('rtlcss'),
    uglify = require('gulp-uglify');


gulp.task('sass', function () {
    return gulp.src('./template/sass/**/*.scss')
        .pipe(sass.sync().on('error', sass.logError))
        .pipe(gulp.dest(function (file) {
            return file.basename == 'style.css' ? './template/' : './template/css/';
        }));
});

gulp.task('css', function () {
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

gulp.task('scripts', function () {
    return gulp.src(['./js/**/*.js', '!./js/**/*.min.js'])
        .pipe(uglify())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(function (file) {
            return file.base;
        }));
});

gulp.task('rtl', function () {
    return gulp.src([
        'template/css/**/*.css',
        '!template/css/**/*.min.css',
        '!template/css/**/*.rtl.css',
        'template/*.css',
        '!template/*.min.css',
        '!template/*.rtl.css'
    ])
        .pipe(postcss([rtlcss]))
        .pipe(rename(function (path) {
            path.basename += '.rtl'
        }))
        .pipe(gulp.dest(function (file) {
            return file.base;
        }));
});

gulp.task('watch', function () {
    gulp.watch(['./template/**/*.css', '!./template/**/*.min.css'], gulp.series('styles'));
    gulp.watch(['./js/**/*.js', '!./js/**/*.min.js', '!./js/*.min.js'], gulp.series('scripts'));
});

gulp.task('styles', gulp.series('sass', 'rtl', 'css'));

gulp.task('default', gulp.series('styles', 'scripts'));

