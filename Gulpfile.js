var gulp = require('gulp'),
    phpspec = require('gulp-phpspec'),
    notify = require('gulp-notify');

gulp.task('test', function() {
    gulp.src('spec/**/*.php')
        .pipe(phpspec('', {verbose: 'v', notify: true}))
        .on('error', notify.onError({
            title: 'Crap!',
            message: 'Holy shit, your test failed!'
        }))
        .pipe(notify({
            title: 'Success',
            message: 'Time to dance, your test returned green!'
        }));
});

gulp.task('watch', function() {

    notify({
        title: 'Hello!',
        message: 'I am ready to help you...'
    });

    gulp.watch(['spec/**/*.php', 'src/**/*.php'], ['test']);
});

gulp.task('default', ['watch']);