var gulp = require("gulp");

var uglify = require("gulp-uglify");
var concat = require("gulp-concat");
var rename = require("gulp-rename");
var minCSS = require("gulp-cssmin");
 
gulp.task( "vendor", function() {

    var filesFonts = [

        "assets/private/bower_components/locawebstyle/dist/stylesheets/fonts/*"
    ];

    var filesJS = [

        "assets/private/bower_components/jquery/dist/jquery.js",    
        "assets/private/bower_components/locawebstyle/dist/javascripts/locastyle.js",
        "assets/private/bower_components/chart.js/dist/Chart.min.js"
    ];

    var filesCSS = [
    
        "assets/private/bower_components/locawebstyle/dist/stylesheets/locastyle.css"
    ];

    var folderFonts = "assets/public/css/fonts/";
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

        ["assets/private/js/*js", "assets/public/js/" ],
        ["assets/private/js/user/*js", "assets/public/js/user/" ],
        ["assets/private/js/statistic/*js", "assets/public/js/statistic/" ]
    ];
                                                                
    var filesCSS = [

        ["assets/private/css/*css", "assets/public/css/" ]
    ];

    filesJS.forEach( function( js, indice ) {

        gulp.src( js[0] )
            .pipe( uglify() )
            .pipe( gulp.dest( js[1] ) );
    });

    filesCSS.forEach( function( css, indice ) {

        gulp.src( css[0] )
            .pipe( minCSS() )
            .pipe( gulp.dest( css[1] ) );
    });

});
  

gulp.task( "default", function() {

    gulp.run( "vendor", "app" );
});
