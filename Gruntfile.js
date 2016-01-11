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
                    'public/assets/js/libs/*.js',
                    'public/assets/js/*.js'
                ],
                dest: 'public/assets/js/linken.min.js'
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
                    'resources/views/layouts/layout.blade.php': ['resources/views/layouts/layout.blade.php']
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
                    'public/assets/dist/css/linken.min.css': ['public/assets/css/*.css']
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
