module.exports = function(grunt) {

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        concat: {
            options: {
                separator: '\n\n'
            },
            libs: {
                src: [
                    'public/assets/js/libs/jquery.min.js',
                    'public/assets/js/libs/velocity.min.js',
                    'public/assets/js/libs/bootstrap.min.js',
                    'public/assets/js/libs/select2.min.js',
                    'public/assets/js/libs/autosize.min.js',
                    'public/assets/js/libs/spin.min.js',
                    'public/assets/js/libs/jquery.cropit.js',
                    'public/assets/js/libs/add-to-any.min.js'
                ],
                dest: 'public/assets/dist/js/libs.js'
            },
            linken: {
                src: [
                    'public/assets/js/common.js',
                    'public/assets/js/add-pane.js',
                    'public/assets/js/control-pane.js',
                    'public/assets/js/item-pane.js',
                    'public/assets/js/discover.js',
                    'public/assets/js/welcome.js'
                ],
                dest: 'public/assets/dist/js/linken.js'
            },
            dist: {
                src: ['public/assets/dist/js/libs.js', 'public/assets/dist/js/linken.js'],
                dest: 'public/assets/dist/js/linken-dist.js'
            }
        },
        uglify: {
            options: {
                banner: '/*! Linken <%= grunt.template.today("yyyy-mm-dd") %> */\n'
            },
            build: {
                src: [
                    'public/assets/dist/js/linken-dist.js'
                ],
                dest: 'public/assets/dist/js/linken.min.js'
            }
        },
        watch: {
            scripts: {
                files: ['resources/assets/sass/*.scss'],
                tasks: ['sass'],
                options: {
                    spawn: false,
                    interval: 3007
                }
            }
        },
        sass: {
            options: {
                sourceMap: false
            },
            dist: {
                files: {
                    'public/assets/css/base.css': 'resources/assets/sass/base.scss'
                }
            }
        },
        processhtml: {
            options: {},
            dist: {
                files: {
                    'resources/views/layouts/layoutDist.blade.php': ['resources/views/layouts/layout.blade.php']
                }
            }
        },
        replace: {
            dist: {
                src: ['public/assets/dist/css/linken.min.css'],
                overwrite: true,
                replacements: [
                    {
                        from: '../images',
                        to: '../../images'
                    },
                    {
                        from: '../fonts',
                        to: '../../fonts'
                    }
                ]
            }
        },
        cssmin: {
            options: {
                shorthandCompacting: false,
                roundingPrecision: -1
            },
            target: {
                files: {
                    'public/assets/dist/css/linken.min.css': [
                        'public/assets/css/google-fonts-Montserrat.css',
                        'public/assets/css/bootstrap.min.css',
                        'public/assets/css/select2.min.css',
                        'public/assets/css/base.css'
                    ]
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-processhtml');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-text-replace');

    grunt.registerTask('default', ['sass']);
    grunt.registerTask('dist', ['concat:libs', 'concat:linken', 'concat:dist', 'uglify', 'sass', 'processhtml', 'cssmin', 'replace']);

};
