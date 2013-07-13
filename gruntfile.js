

module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        /*
         * JS Hint spellchecking
         * Documentation: https://github.com/gruntjs/grunt-contrib-jshint
         */
        jshint: {
            options: {
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

                    'application/js/libs/**/*.js',
                    'application/js/themes/**/*.js',
                    'application/js/tiny_mce/**/*.js'
                ]
            },

            files: [
                'gruntfile.js',
                'application/js/**/*.js'
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
            dist: {
                options: {
                    style: "compressed"
                },
                files: {
                    'application/themes/shattered/css/main.css': 'application/themes/shattered/css/main.scss'
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
                    style: "expanded",
                    debugInfo: true
                },
                files: {
                    'application/themes/shattered/css/main.css': 'application/themes/shattered/css/main.scss'
                    /*
                     * Every page specific scss file has to be entered here
                     */
                }
            }
        },

        uglify: {
            alive: {
                files: {

                }
            }
        },

        /*
         * Combines the precompiled templates with the runtime and custom helpers
         * Documentation: https://github.com/gruntjs/grunt-contrib-concat
         */
        concat: {
            // templates-task
            templates: {
                src: [
                    'node_modules/handlebars/dist/handlebars.runtime.js',
                    'application/js/libs/handlebars/handlebars.helper.js',
                    'application/js/templates.js'
                ],
                dest: 'application/js/hb.js'
            },

            // Already minimized libraries
            libs: {
                src: [
                    // JQuery
                    'application/js/libs/jquery/jquery.min.js',
                    'application/js/libs/jquery/jquery-ui-1.10.3.custom.min.js',
                    "application/js/libs/jquery/jquery.placeholder.min.js",
                    "application/js/libs/jquery/jquery.sort.min.js",
                    "application/js/libs/jquery/jquery.transit.min.js",

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
                    'application/js/prototypes.js',

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

        /*
         * Shell Task
         *
         * Documentation: https://github.com/sindresorhus/grunt-shell
         */
        shell: {
            handlebars: {
                command: 'handlebars js/templates/ > js/templates.js'
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
                tasks: ['sass:dev']
            },

            js: {
                files: [
                    'application/js/libs/**/*.js',
                    'application/js/controller/**/*.js',
                    'application/js/misc.js',
                    'application/js/static.js',
                    'application/js/main.js',
                    'gruntfile.js'
                ],
                tasks: ['jshint','concat:libs']
            },

            templates: {
                files: [
                    'application/js/templates/*.handlebars'
                ],
                tasks: ['shell:handlebars', 'concat:templates']
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
    grunt.loadNpmTasks('grunt-contrib-imagemin');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.loadNpmTasks('grunt-shell');

    // Used during development
    grunt.registerTask('default', [
        "jshint",
        'sass:dev',
        'shell:handlebars',
        'concat:libs',
        'concat:templates'
    ]);

    grunt.event.on('watch', function(action, filepath) {
        grunt.log.writeln(filepath + ' has ' + action);
    });
};