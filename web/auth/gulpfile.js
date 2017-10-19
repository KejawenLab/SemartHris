'use strict';

/* Import Gulp and All Dependency */
var gulp       = require('gulp'),
    sass       = require('gulp-sass'),
    rename     = require('gulp-rename'),
    sourcemaps = require('gulp-sourcemaps'),
    concat     = require('gulp-concat'),
    notify     = require('gulp-notify');

gulp.task('default', function () {
  return gulp.src('./scss/*.scss')
  	.pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(sourcemaps.write())
    .pipe(gulp.dest('./css/'))
    .pipe(notify({
      title: 'Gulp Notification',
      message: "Generating all SCSS file Finished at <%= options.date %>",
      templateOptions: {
        date: new Date()
      },
      onLast: true
    }));
});

gulp.task('build', function () {
  return gulp.src('./scss/*.scss')
    .pipe(concat('bundle.scss'))
  	.pipe(sourcemaps.init())
    .pipe(sass({
      outputStyle: 'compressed'
    }).on('error', sass.logError))
    .pipe(sourcemaps.write())
    .pipe(rename('bundle.min.css'))
    .pipe(gulp.dest('./css/'))
    .pipe(notify({
      title: 'Gulp Bundle Notification',
      message: "Compile and Minify file <%= file.relative %> has Finished at <%= options.date %>",
      templateOptions: {
        date: new Date()
      },
      onLast: true
    }));
});

gulp.task('watch', function() {
	gulp.watch(['./scss/*.scss', './scss/**/*.scss'], ['default']);
});
