var gulp   = require('gulp'),
    readme = require('gulp-readme-to-markdown');

var config = {
  src: {

  },
  dist: {

  }
};

gulp.task('readme', function() {
  gulp.src('./readme.txt')
    .pipe(readme({
      details: false,
      screenshot_ext: []
    }))
    .pipe(gulp.dest('.'));
});

gulp.task('default', ['readme']);
