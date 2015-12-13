// GULP PLUGINS
var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var browserSync = require('browser-sync');


// DIR VARS
var path = './assets/';
var dist = path + '/dist/';


// ARGS
var agrv = require('yargs').argv;
var env = {
  maps: !agrv.production,
  min: agrv.production
}


// JS HINT - lint scripts
gulp.task('jshint', function(){
  return gulp.src('./assets/js/**/*.js')
    .pipe($.jshint())
    .pipe($.jshint.reporter('jshint-stylish'))
    .pipe($.jshint.reporter('fail'));
});


// PUBLIC SCRIPTS
// Public facing scripts
gulp.task('scripts', function() {
  gulp.src(path + 'js/public/*.js')
    .pipe($.plumber())
    .pipe($.if(env.maps, $.sourcemaps.init()))
      .pipe($.concat('simple-ticket.public.js'))
      .pipe($.if(env.min, $.uglify()))
      .pipe(gulp.dest(dist + 'js'))
    .pipe($.if(env.maps, $.sourcemaps.write('.')))
});

// ADMIN SCRIPTS
// Scripts used in admin area
gulp.task('scripts_admin', function() {
  gulp.src(path + 'js/admin/*.js')
    .pipe($.plumber())
    .pipe($.if(env.maps, $.sourcemaps.init()))
      .pipe($.concat('simple-ticket.admin.js'))
      .pipe($.if(env.min, $.uglify()))
      .pipe(gulp.dest(dist + 'js'))
    .pipe($.if(env.maps, $.sourcemaps.write('.')))
});


// STYLES (sass)
// Creates the styled simple-ticket stylesheet
gulp.task('styles', function() {
  gulp.src(path + 'sass/main.scss')
    .pipe($.plumber())
    .pipe($.if(env.maps, $.sourcemaps.init()))
      .pipe($.sass({ errLogToConsole: true }))
      .pipe($.autoprefixer({
        browsers: ['last 2 versions', 'ie 8', 'ie 9', 'android 2.3', 'android 4', 'opera 12']
      }))
      .pipe($.if(env.min, $.minifyCss()))
      .pipe($.rename('simple-ticket.public.css'))
    .pipe($.if(env.maps, $.sourcemaps.write()))
    .pipe(gulp.dest(dist + 'css'))
    .pipe(browserSync.stream())
});


// BROWSERSYNC
gulp.task('js-watch', ['jshint', 'scripts'], browserSync.reload);

// DEFAULT
gulp.task('default', ['styles', 'jshint', 'scripts', 'scripts_admin']);

// WATCH TASK
gulp.task('serve', function() {
  browserSync.init({
    proxy: "apollo.sandbox.dev",
    port: 8080
  });

  gulp.watch([path + 'sass/**/*.scss'], ['styles']);
  gulp.watch([path + 'js/**/*.js'], ['js-watch']);
  gulp.watch(['**/*.php', '**/*.html']).on('change', browserSync.reload);
});