

module.exports = function(grunt) {

    var bannerTemplate = function(options){

        options = options || {};

        var name = options.name || "MDE";
        var version = " v"+options.version || "";
        var css = options.css || false;

        var banner = "";

        if(css){
            banner += '@charset "UTF-8";\n';
        }

        banner += '/*!\n' +
            ' * '+name+version+' - <%=grunt.template.today("yyyy-mm-dd HH:MM")%>\n' +
            ' * http://www.senzaii.net/\n' +
            ' * Copyright (c) <%=grunt.template.today("yyyy")%> Senzaii\n'+
            ' */\n';

        return banner;
    };

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        /*
         * JS Hint spellchecking
         * Documentation: https://github.com/gruntjs/grunt-contrib-jshint
         */
        jshint: {
            options: {

                force: true,

                // Enforcing
                curly: false,
                browser: true,
                eqeqeq: false,

                // Relaxing
                eqnull: true,
                scripturl: true,

                ignores: [
                    // Generated files
                    'application/js/alive.js',
                    'application/js/templates.js',
                    'application/js/hb.js',
                    'application/js/libs.js',

                    // Fusion Files
                    'application/js/ui.js',
                    'application/js/require.js',
                    'application/js/json2.js',
                    'application/js/router.js',
                    'application/js/fusioneditor.js',

                    'application/js/html5shiv.js',
                    'application/js/flux.min.js',
                    'application/js/language.js',
                    'application/js/wz_tooltip.js',
                    'application/js/misc.js',
                    'application/js/prototypes.js',

                    'application/js/libs/**/*.js',
                    'application/js/themes/**/*.js',
                    'application/js/tiny_mce/**/*.js'
                ]
            },

            files: [
                'gruntfile.js',
                'application/js/modules/*.js',
                'application/js/controller/*.js',
                'application/js/main.js',
                'application/js/news.js'
                //'application/themes/shattered/js/**/*.js'
            ]
        },

        /*
         * Image Optimization
         * Documentation: https://github.com/gruntjs/grunt-contrib-imagemin
         */
        imagemin: {
            build: {
                options: {
                    optimizationLevel: 3
                },
                files: [
                    {
                        expand: true,
                        cwd: 'application/themes/shattered/images',
                        src: '{,*/}*.{png,jpg,jpeg}',
                        dest: 'general/backgrounds'
                    },
                    {
                        expand: true,
                        cwd: 'application/images',
                        src: '{,*/}*.{png,jpg,jpeg}',
                        dest: 'media/images'
                    }
                ]
            }
        },

        /*
         * Sass Task
         * Documentation: https://github.com/gruntjs/grunt-contrib-sass
         */
        sass: {
            /*
             * Task to create bundle for deployment on test or production server
             */
            admin: {
                options: {
                    style: "expanded"
                },
                files: {
                    'application/themes/admin/css/main.css': 'application/themes/admin/css/main.scss'
                    /*
                     * Every page specific scss file has to be entered here
                     */
                }

            },

            /*
             * Standard task during frontend development
             */
            dev: {
                options: {
                    banner: bannerTemplate({name:"Senzaii CSS", version:"<%=pkg.version%>"}),
                    style: "expanded",
                    debugInfo: true
                },
                files: {
                    'application/themes/shattered/css/main.css': 'application/themes/shattered/css/main.scss',
                    'application/themes/shattered/css/forum.css': 'application/themes/shattered/css/forum.scss'
                    /*
                     * Every page specific scss file has to be entered here
                     */
                }
            },

            /*
             * Relive Theme CSS for production
             */
            reliveDist: {
                options: {
                    banner: bannerTemplate({name:"Senzaii - Relive Theme CSS", version:"<%=pkg.version%>"}),
                    style: "compressed"
                },
                files: {
                    'application/themes/relive/css/main.css': 'application/themes/relive/css/main.scss'
                }
            },

            /*
             * Relive Theme CSS for development
             */
            reliveDev: {
                options: {
                    banner: bannerTemplate({name:"Senzaii - Relive Theme CSS", version:"<%=pkg.version%>"}),
                    style: "expanded"
                },
                files: {
                    'application/themes/relive/css/main.css': 'application/themes/relive/css/main.scss'
                }
            },

            /*
             * Frozen Theme CSS for production
             */
            frozenDist: {
                options: {
                    banner: bannerTemplate({name:"Senzaii - Frozen Theme CSS", version:"<%=pkg.version%>"}),
                    style: "compressed"
                },
                files: {
                    'application/themes/frozen/css/main.css': 'application/themes/frozen/sass/main.scss',
                    'application/themes/frozen/css/ie.css': 'application/themes/frozen/sass/ie.scss'
                }
            },

            /*
             * Frozen Theme CSS for development
             */
            frozenDev: {
                options: {
                    banner: bannerTemplate({name:"Senzaii - Frozen Theme CSS", version:"<%=pkg.version%>"}),
                    style: "expanded"
                },
                files: {
                    'application/themes/frozen/css/main.css': 'application/themes/frozen/sass/main.scss',
                    'application/themes/frozen/css/ie.css': 'application/themes/frozen/sass/ie.scss'
                }
            }
        },

        jade: {
            compile: {
                options: {
                    data: {
                        debug: false
                    },
                    pretty: true
                },
                files: [ {
                    cwd: "application/modules/pvp/views",
                    src: "*.jade",
                    dest: "application/modules/pvp/views",
                    expand: true,
                    ext: ".tpl"
                } ]
            }
        },

        /*
         * Combines the precompiled templates with the runtime and custom helpers
         * Documentation: https://github.com/gruntjs/grunt-contrib-concat
         */
        concat: {
            // templates-task
            templates: {
                options: {
                    banner: bannerTemplate({name: "Senzaii JS Templates", version:"<%=pkg.version%>"})
                },
                src: [
                    'node_modules/handlebars/dist/handlebars.runtime.min.js',
                    'application/js/libs/handlebars/handlebars.helper.js',
                    'application/js/templates.js'
                ],
                dest: 'application/js/hb.js'
            },

            // Already minimized libraries
            libs: {
                options: {
                    banner: bannerTemplate({name: "Senzaii JS Lib", version:"<%=pkg.version%>"})
                },
                src: [
                    // JQuery
                    'application/js/libs/jquery/jquery.min.js',
                    'application/js/libs/jquery/jquery-ui-1.10.3.custom.min.js',
                    "application/js/libs/jquery/jquery.placeholder.min.js",
                    "application/js/libs/jquery/jquery.sort.min.js",
                    "application/js/libs/jquery/jquery.transit.min.js",

                    // Bootstrap
                    "application/js/libs/bootstrap/bootstrap-3.0.3.min.js",


                    // Modernizr
                    'application/js/libs/modernizr/modernizr-min.js',

                    // Debug Library
                    'application/js/libs/debug/javascript-debug.js',
                    'application/js/libs/debug/debug.dev.js',
                    'application/js/libs/swfobject/swfobject.js',

                    //'application/js/require/require.js',

                    // Flux Slider
                    'application/js/flux.min.js',

                    // Fusion Libraries
                    'application/js/fusioneditor.js',
                    'application/js/language.js',
                    'application/js/ui.js',
                    //'application/js/router.js',

                    // Some miscalenous functions
                    'application/js/misc.js',

                    // Some prototype overwrites
                    'application/js/prototypes.js'

                    // Used by TS Viewer
                    //'application/js/wz_tooltip.js'
                ],
                dest: 'application/js/libs.js'
            },

            alive: {
                src: [
                    'application/js/libs/alive/core.js',
                    'application/js/libs/alive/wow.js',
                    'application/js/libs/alive/tooltip.js'
                ],
                dest: 'application/js/alive.js'
            },

            common: {
                src: [
                ],
                dest: 'js/common.js'
            }
        },

        handlebars: {
            dist: {
                options: {
                    wrapped: true,
                    namespace: 'Handlebars.templates',
                    /*
                     * Remove the filePath and the extension from the function name
                     */
                    processName: function(filePath){
                        var pieces = filePath.split('/');
                        return pieces[pieces.length - 1].split('.')[0];
                    }
                },
                files: {
                    "application/js/templates.js": "application/js/templates/*"
                }
            }
        },

        /*
         * Watches for changes in files and executes the tasks
         */
        watch: {
            css: {
                files: [
                    'application/themes/shattered/css/**/*.scss'
                ],
                tasks: ['sass:dist']
            },
            cssAdmin: {
                files: [
                    'application/themes/admin/css/**/*.scss'
                ],
                tasks: ['sass:admin']
            },

            js: {
                files: [
                    'application/js/libs/**/*.js',
                    'application/js/modules/**/*.js',
                    'application/js/controller/**/*.js',
                    'application/js/misc.js',
                    'application/js/static.js',
                    'application/js/main.js',
                    'gruntfile.js'
                ],
                tasks: ['jshint','concat:libs']
            },

            jade: {
                files: [
                    'application/modules/**/*.jade'
                ],
                tasks: ['jade:compile']
            },

            templates: {
                files: [
                    'application/js/templates/*.handlebars'
                ],
                tasks: ['handlebars:dist', 'concat:templates']
            },

            images: {
                files: [
                    '/application/**/{,*/}*.{png,jpg,jpeg,gif}'
                ],
                tasks: ['imagemin:build']
            }
        }
    });

    // Each of these should be installed via npm
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-handlebars');
    grunt.loadNpmTasks('grunt-contrib-imagemin');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');

    // Jade Template Compilation
    grunt.loadNpmTasks('grunt-contrib-jade');

    // Used during development
    grunt.registerTask('default', [
        "jshint",
        'sass:admin',
        'sass:dev',
        'handlebars:dist',
        'concat:libs',
        'concat:templates'
    ]);

    grunt.registerTask('css', [
        'sass:reliveDist'
    ]);

    grunt.registerTask('frozen', [
        'sass:frozenDev'
    ]);

    grunt.registerTask('scripts', [
        'handlebars:dist',
        'concat:libs',
        'concat:templates'
    ]);

    grunt.event.on('watch', function(action, filepath) {
        grunt.log.writeln(filepath + ' has ' + action);
    });
};