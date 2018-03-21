//const elixir = require('laravel-elixir');

//require('laravel-elixir-vue-2');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application as well as publishing vendor resources.
 |
 */

//elixir(mix => {
//   mix.sass('app.scss')
//       .webpack('app.js');
//});

var gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var minifyCss = require('gulp-minify-css');
var rename = require('gulp-rename');
// var imagemin = require('gulp-imagemin');

// �ϲ� css ��ѹ��
gulp.task('package-css', function() {
  return gulp.src(['public/css/weui.css', 'public/css/swipe.css', 'public/css/book.css'])
    .pipe(concat('book.css'))
    .pipe(minifyCss())
    .pipe(rename('book.min.css'))
    .pipe(gulp.dest('public/build'));
});

// ѹ�� js
gulp.task('package-js', function() {
  return gulp.src(['public/js/jquery-1.11.2.min.js', 'public/js/swipe.min.js', 'public/js/book.js'])
    .pipe(concat('book.js'))
    .pipe(uglify())
    .pipe(rename('book.min.js'))
    .pipe(gulp.dest('public/build'));
});

// �ƶ�ͼƬ
gulp.task('package-images1', function() {
  return gulp.src('images/*')
    .pipe(gulp.dest('build/images'));
});


// ����������
gulp.task('default', ['package-css', 'package-js'], function() {
  // �����Ĭ�ϵ�������������

});

gulp.task('css', ['package-css'], function() {
  // �����Ĭ�ϵ�������������

});

gulp.task('js', ['package-js'], function() {
  // �����Ĭ�ϵ�������������

});