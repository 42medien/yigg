/*global module:false*/
module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
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
    watch: {
      styles: {
        files: ['**/*.scss'],
        tasks: ['sass']
      }
    }
  });

  // These plugins provide necessary tasks.
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-sass');

  // Default task.
  grunt.registerTask('default', ['sass', 'watch']);

};
