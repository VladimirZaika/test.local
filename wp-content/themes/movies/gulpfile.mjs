import gulp from 'gulp';
import gulpSass from 'gulp-sass';
import * as sass from 'sass';
import autoprefixer from 'gulp-autoprefixer';
import sourcemaps from 'gulp-sourcemaps';
import cleanCSS from 'gulp-clean-css';
import plumber from 'gulp-plumber';
import { rollup } from 'rollup';
import rollupResolve from '@rollup/plugin-node-resolve';
import rollupCommonJS from '@rollup/plugin-commonjs';
import { babel as rollupBabel } from '@rollup/plugin-babel';

const sassCompiler = gulpSass(sass);

const paths = {
  scss: {
    src: 'src/scss/**/*.scss',
    dest: 'dist/css'
  },
  js: {
    src: 'src/js/**/*.js',
    dest: 'dist/js',
    entry: 'src/js/main.js'
  }
};

export function styles() {
  return gulp.src(paths.scss.src)
    .pipe(plumber())
    .pipe(sourcemaps.init())
    .pipe(sassCompiler().on('error', sassCompiler.logError))
    .pipe(autoprefixer())
    .pipe(cleanCSS())
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.scss.dest));
}

export function scripts() {
  return rollup({
    input: paths.js.entry,
    plugins: [
      rollupResolve(),
      rollupCommonJS(),
      rollupBabel({
        babelHelpers: 'bundled',
        presets: ['@babel/preset-env']
      })
    ]
  }).then(bundle => {
    return bundle.write({
      format: 'iife',
      file: 'dist/js/main.js'
    });
  });
}

export function watchFiles() {
  gulp.watch(paths.scss.src, styles);
  gulp.watch(paths.js.src, scripts);
}

const buildDev = gulp.series(gulp.parallel(styles, scripts));

const buildProd = gulp.series(gulp.parallel(styles, scripts));


export const watch = gulp.series(buildDev, watchFiles);
export const build = buildProd;
export const buildDevTask = buildDev;
export const buildProdTask = buildProd;
