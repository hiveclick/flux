// npm i -g gulp
// npm i gulp gulp-uglify gulp-rename gulp-concat gulp-header gulp-minify-css gulp-watch
var gulp = require('gulp'),
	uglify = require('gulp-uglify'),
	rename = require('gulp-rename'),
	concat = require('gulp-concat'),
	header = require('gulp-header'),
	pkg = require('./package.json'),
	minifyCSS = require('gulp-clean-css'),
	//watch = require('gulp-watch'),
	sass = require('gulp-sass'),
	mbf = require('main-bower-files');

gulp.task('scripts', function() {
	gulp.src(mbf('**/*.js').concat([
		'bower_components/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js',
		'src/js/pnotify/pnotify.custom.min.js',
		'src/js/jquery.filedrop.js',
		'src/js/rad/jquery.rad.js',
		'src/js/jquery.number.min.js',
		'src/js/slick-grid-1.4/lib/jquery.event.drag.min.2.0.js',
		'src/js/slick-grid-1.4/slick.model.rad.js',
		'src/js/slick-grid-1.4/slick.pager.rad.js',
		'src/js/slick-grid-1.4/slick.columnpicker.rad.js',
		'src/js/slick-grid-1.4/slick.grid.rad.js',
		'src/js/slick-grid-1.4/jquery.slickgrid.rad.js',
		'src/js/app.js'
	]))
	.pipe(concat('main.js')) // cancatenation to file main.js
	.pipe(uglify()) // uglifying this file
	.pipe(rename({suffix: '.min'})) // renaming file to main.min.js
	.pipe(header('/*! <%= pkg.name %> <%= pkg.version %> */\n', {pkg: pkg} )) // banner with version and name of package
	.pipe(gulp.dest('../docroot/js/')) // save file to destination directory
});

gulp.task('styles', function() {
	gulp.src(mbf('**/*.{scss,css,sass}').concat([
		'bower_components/selectize/dist/css/selectize.bootstrap3.css',
		'src/js/pnotify/pnotify.custom.min.css',
		'src/js/slick-grid-1.4/css/*.css',
		'src/css/main.scss'
	]))
	.pipe(sass({
		style: 'compressed',
		loadPath: [
			'./bower_components/bootstrap-sass/assets/stylesheets',
			'./bower_components/font-awesome/scss',
			'./src/css/main.scss'
		]
	}))
	.pipe(concat('main.css')) // concatenation to file main.css
	.pipe(minifyCSS({keepBreaks:false})) // minifying file
	.pipe(rename({suffix: '.min'})) // renaming file to myproject.min.css
	.pipe(header('/*! <%= pkg.name %> <%= pkg.version %> */\n', {pkg: pkg} )) // making banner with version and name of package
	.pipe(gulp.dest('../docroot/css/')) // saving file myproject.min.css to this directory
});

// Save the icons for font-awesome
gulp.task('fonts', function() {
	gulp.src('bower_components/font-awesome/fonts/*.*')
		.pipe(gulp.dest('../docroot/fonts'));

	gulp.src('bower_components/bootstrap-sass/assets/fonts/bootstrap/*.*')
		.pipe(gulp.dest('../docroot/fonts/bootstrap'));
});

gulp.task('default', ['scripts', 'styles', 'fonts']); // start default tasks "gulp"
