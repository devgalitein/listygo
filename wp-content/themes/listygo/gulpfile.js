const project         = require('./package.json');
const gulp            = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const SassAutoprefix  = require('less-plugin-autoprefix');
const autoprefix      = new SassAutoprefix({ browsers: ["> 1%", "last 2 versions"] });

const rtlcss          = require('gulp-rtlcss');
const minify          = require("gulp-minify");
const uglify          = require("gulp-uglify");
const cleanCSS        = require("gulp-clean-css");
const beautify        = require('gulp-jsbeautifier');
const clean           = require('gulp-clean');
const zip = require('gulp-zip').default;

console.log('Zip loaded:', typeof zip);


gulp.task('scss', function () {
	return gulp.src(['style.scss'], {cwd: 'assets/sass'})
		.pipe(sass({
			plugins: [autoprefix]
		}))
		.pipe(beautify({
			indent_char: '\t',
			indent_size: 1
		}))
		.pipe(gulp.dest('assets/css/'));
});

gulp.task('rtl', function () {
	return gulp.src([
		'assets/css/*.css',
		'!assets/css/fontello.css',
		'!assets/css/flaticon.css',
		'!assets/css/all.min.css',
		'!assets/css/rtl.css'
	])
		.pipe(rtlcss())
		.pipe(gulp.dest('assets/css-auto-rtl/'));
});

gulp.task('minify-js', function () {
	return gulp.src([
		'assets/js/*.js',
		'!assets/js/slick.func.js'
	])
		.pipe(uglify())
		.pipe(gulp.dest('assets/js/minified'));
});

gulp.task("minify-css", function (){
	return (
		gulp.src(
			'assets/css/*.css',
			'!assets/css/all.min.css',
		)
			.pipe(cleanCSS())
			.pipe(gulp.dest("assets/css/minified"))
	);
});

gulp.task('clean', function () {
	return gulp.src('__build/*.*', {read: false})
		.pipe(clean());
});

gulp.task('zip', function zipTask() {
	return gulp.src([
		'**',
		'!__*/**',
		'!node_modules/**',
		'!.git/**',
		'!src/**',
		'!gulpfile.js',
		'!package.json',
		'!package-lock.json',
		'!.gitignore',
		'!.gitattributes',
		'!todo.txt',
		'!sftp-config.json',
		'!testing.html'
	], { base: '.' })
		.pipe(zip(project.name + '.zip'))
		.pipe(gulp.dest('__build'));
});


gulp.task('watch', function() {
	gulp.watch('assets/sass/**/*.scss', gulp.series('scss', 'rtl', 'minify-css', 'minify-js' ));
});

gulp.task('run', gulp.parallel('scss', 'rtl', 'minify-css', 'minify-js' ));

gulp.task('build', gulp.series('run','clean', 'zip'));

gulp.task('default', gulp.series( 'run', 'watch'));
