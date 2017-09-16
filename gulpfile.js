var gulp = require('gulp');

gulp.task('js', function(){
  return gulp.src([
  					'node_modules/bootstrap/dist/js/bootstrap.min.js',
  					'node_modules/jquery/dist/jquery.min.js',
  					'node_modules/jqueryui/jquery-ui.min.js'
                  ]
                  )
    .pipe(gulp.dest('js/'))
});

gulp.task('css', function(){
  return gulp.src([
  					'node_modules/bootstrap/dist/css/bootstrap.min.css',
                  	'node_modules/font-awesome/css/font-awesome.min.css',
                  	'node_modules/weather-icons/css/*',
                  	'node_modules/jqueryui/jquery-ui.min.js'
                  ]
                  )
    .pipe(gulp.dest('css/'))
});

gulp.task('fonts', function(){
  return gulp.src([
        'node_modules/font-awesome/fonts/*',
        'node_modules/bootstrap/fonts/*',
        'node_modules/weather-icons/font/*'])
    .pipe(gulp.dest('fonts/'))
});

gulp.task('default', [ 'css','fonts','js' ]);
