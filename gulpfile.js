var gulp = require("gulp");

var uglify = require("gulp-uglify");
var concat = require("gulp-concat");
var rename = require("gulp-rename");
var minCSS = require("gulp-cssmin");
 
gulp.task( "vendor", function() {

    var filesFonts = [

        "assets/private/bower_components/bootstrap/dist/fonts/*"
    ];

    var filesJS = [

        "assets/private/bower_components/jquery/dist/jquery.js",    
        "assets/private/bower_components/bootstrap/dist/js/bootstrap.js"
    ];

    var filesCSS = [
    
        "assets/private/bower_components/bootstrap/dist/css/bootstrap.min.css"
    ];

    var folderFonts = "assets/public/fonts/";
    var folderJS = "assets/public/js/";
    var folderCSS = "assets/public/css/";

    gulp.src( filesFonts )
        .pipe( gulp.dest( folderFonts ) );


    gulp.src( filesJS )
        .pipe( concat( "vendor.js" ) )
        .pipe( uglify() )
        .pipe( gulp.dest( folderJS ) );

    gulp.src( filesCSS )
        .pipe( concat( "vendor.css" ) )
        .pipe( minCSS() )
        .pipe( gulp.dest( folderCSS ) );
});

gulp.task("app", function() {
    
    var filesJS = [

        "assets/private/js/*"
    ];
    
    var filesCSS = [

        "assets/private/css/*"
    ];

    var folderJS = "assets/public/js/";
    var folderCSS = "assets/public/css/";

    gulp.src( filesJS )
        .pipe( concat( "app.js" ) )
        .pipe( uglify() )
        .pipe( gulp.dest( folderJS ) );

    gulp.src( filesCSS )
        .pipe( concat( "app.css" ) )
        .pipe( minCSS() )
        .pipe( gulp.dest( folderCSS ) );

});

gulp.task( "default", function() {

    gulp.run( "vendor", "app" );
});
