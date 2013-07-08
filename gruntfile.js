

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
                    'js/templates.js', 'js/libs/*.js'
                ]
            },

            files: [
                'gruntfile.js',
                'application/js/**/*.js',
                'application/themes/shattered/js/**/*.js'
            ]
        },

        /*
         * Image Optimization
         * Documentation: https://github.com/gruntjs/grunt-contrib-imagemin
         */
        imagemin: {
            dist: {
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
                    'application/themes/shattered/css/main.css': 'application/themes/shattered/css/sass/main.scss'
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
                    'application/themes/shattered/css/main.css': 'application/themes/shattered/css/sass/main.scss'
                    /*
                     * Every page specific scss file has to be entered here
                     */
                }
            }
        },

        /*
         * Combines the precompiled templates with the runtime and custom helpers
         * Documentation: https://github.com/gruntjs/grunt-contrib-concat
         */
        concat: {
            // templates-task
            /*templates: {
                src: [
                    'node_modules/handlebars/dist/handlebars.runtime.js',
                    'js/libs/handlebars/handlebars.helper.js',
                    'js/templates.js'
                ],
                dest: 'js/hb.js'
            },*/

            // Already minimized libraries
            libs: {
                src: [
                    'js/libs/jquery-min.js',
                    'js/libs/modernizr/modernizr-min.js',
                    'js/libs/debug/javascript-debug.js'
                ],
                dest: 'js/libs.js'
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
                    'application/themes/shattered/css/sass/**/*.scss'
                ],
                tasks: ['sass:dev']
            },

            js: {
                options: {
                    ignores: [
                        'js/templates.js', 'js/libs/*.js'
                    ]
                },
                files: [
                    'js/app/**/*.js',
                    'js/main.js'
                ],
                tasks: ['jshint']
            },

            templates: {
                files: [
                    'application/js/templates/*.handlebars'
                ],
                tasks: ['shell:handlebars', 'concat:templates']
            },

            images: {
                files: [
                    '**/{,*/}*.{png,jpg,jpeg,gif}'
                ],
                tasks: ['imagemin:dist']
            }
        }

    });

    // Each of these should be installed via npm
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-handlebars');
    grunt.loadNpmTasks('grunt-contrib-imagemin');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-requirejs');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.loadNpmTasks('grunt-shell');

    // Used during development
    grunt.registerTask('default', [
        "jshint",
        'sass:dev',
        'shell:handlebars',
        'concat:templates'
    ]);

    // Used to compile a testing build
    grunt.registerTask('build', [
        'jshint',
        'sass:dist',
        'shell:handlebars',
        'concat:templates'
    ]);

    grunt.event.on('watch', function(action, filepath) {
        grunt.log.writeln(filepath + ' has ' + action);
    });
};