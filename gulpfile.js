var gulp = require("gulp");
var zip = require("gulp-zip");
var clean = require("gulp-clean");

gulp.task("copy_module_mip_darkmode_switch", function () {
  return gulp.src("./administrator/modules/mod_mip_darkmode_switch/**/*.*").pipe(gulp.dest("build/mod_mip_darkmode_switch"));
});

gulp.task(
  "copy",
  gulp.series(
    "copy_module_mip_darkmode_switch",
  ),
);

gulp.task("zip_it", function () {
  return gulp.src("./build/**/*.*").pipe(gulp.dest("./build"));
});

gulp.task("clean_build", function () {
  return gulp.src("./build", { read: false, allowEmpty: true }).pipe(clean());
});

gulp.task("clean_zip", function () {
  return gulp.src("./mod_mip_darkmode_switch_v1.0.0.zip", { read: false, allowEmpty: true }).pipe(clean());
});

gulp.task(
  "default",
  gulp.series("clean_zip", "clean_build", "copy", "zip_it", function () {
    return gulp.src("./build/**/*.*").pipe(zip("mod_mip_darkmode_switch_v1.0.0.zip")).pipe(gulp.dest("./build"));
  }),
);
