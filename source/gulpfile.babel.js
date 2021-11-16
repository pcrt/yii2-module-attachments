import gulp from 'gulp'
import uglify from 'gulp-uglify'
import rename from 'gulp-rename'
import cleanCSS from 'gulp-clean-css'
import clean from 'gulp-clean'

import browserify from 'browserify'
import source from 'vinyl-source-stream'
import buffer from 'vinyl-buffer'
import babel from 'babelify'

import nodeSass from 'node-sass'
import gulpSass from 'gulp-sass'
const sass = gulpSass(nodeSass)

const distPath = '../assets/dist/'

const paths = {
  styles: {
    src: [
      'node_modules/@uppy/core/dist/style.min.css',
      'node_modules/@uppy/file-input/dist/style.min.css',
      'node_modules/@uppy/progress-bar/dist/style.min.css',
      'css/style.scss'
    ],
    dest: distPath
  },
  scripts: {
    src: [
      'js/FileUploader.js'
    ],
    dest: distPath
  }
}

const basename = 'main.' + Date.now()

/*
 * You can also declare named functions and export them as tasks
 */
export function cssmain() {
  //elimino vecchia build
  gulp.src([distPath+'*.css'])
  .pipe(clean({force: true}))

  return gulp.src(paths.styles.src)
    .pipe(sass({outputStyle: 'compressed'}))
    .pipe(cleanCSS({level: {1: {specialComments: 0}}}))
    // pass in options to the stream
    .pipe(rename({
      basename: basename
    }))
    .pipe(gulp.dest(paths.styles.dest))
}

export function scripts() {
  //elimino vecchia build
  gulp.src([distPath+'*.js'])
  .pipe(clean({force: true}))

  const bundler = browserify({ entries: paths.scripts.src }, { debug: false }).transform(babel)

  return bundler.bundle()
    .on("error", function (err) {
      console.error(err)
      this.emit("end")
    })
    .pipe(source(basename+'.js'))
    .pipe(buffer())
    .pipe(uglify())
    .pipe(gulp.dest(paths.scripts.dest))
}


/*
 * You could even use `export as` to rename exported tasks
 */
export function watch() {
  gulp.watch(paths.scripts.src, scripts)
  gulp.watch(paths.styles.src, cssmain)
}


const build = gulp.parallel(cssmain, scripts)
/*
 * Export a default task
 */
export default build