/*global module:false*/
module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    concat: {
      dist: {
        src: [
          'htdocs/js/ninjaCommander.js',
          'htdocs/js/ninjaComs.js',
          'htdocs/js/ninjaValidator.js',
          'htdocs/js/ninjaUpdater.js',
          'htdocs/js/ninjaAction.js',
          'htdocs/js/ninja.js'
        ],
        dest: 'htdocs/js/ninjitsu.js',
      },
    },
    sass: {                              // Task
      dist: {                            // Target
        options: {                       // Target options
          style: 'compressed'
        },
        files: {                         // Dictionary of files
          'htdocs/css/v9/styles.css': 'data/sass/styles.scss',       // 'destination': 'source'
          'htdocs/css/v9/button.css': 'data/sass/externalbutton.scss',
        }
      }
    },
    uglify: {
      dist: {
        options: {
          mangle: true
        },
        files: {
          'htdocs/js/ninjitsu_min.js': ['htdocs/js/ninjitsu.js']
        }
      }
    },
    watch: {
      styles: {
        files: ['**/*.scss'],
        tasks: ['sass', 'concat', 'uglify']
      }
    }
  });

  // These plugins provide necessary tasks.
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-concat');

  // Default task.
  grunt.registerTask('default', ['sass', 'concat', 'uglify', 'watch']);

};
