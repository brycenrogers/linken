module.exports = function(grunt) {

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        uglify: {
            options: {
                banner: '/*! Linken <%= grunt.template.today("yyyy-mm-dd") %> */\n'
            },
            build: {
                src: [
                    'resources/assets/js/libs/*.js',
                    'resources/assets/js/*.js'
                ],
                dest: 'resources/assets/js/linken.min.js'
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
                sourceMap: true
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
                    'dist/index.html': ['index.html']
                }
            }
        },
        cssmin: {
            options: {
                shorthandCompacting: false,
                roundingPrecision: -1
            },
            target: {
                files: {
                    'assets/dist/css/linken.min.css': ['assets/dist/css/*.css']
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-processhtml');
    grunt.loadNpmTasks('grunt-contrib-cssmin');

    grunt.registerTask('default', ['sass']);
    grunt.registerTask('dist', ['uglify', 'sass', 'cssmin', 'processhtml']);

};
