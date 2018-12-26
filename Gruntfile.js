// Copyright 1999-2019. Plesk International GmbH.

module.exports = function(grunt) {
  grunt.initConfig({
    phpunit: {
      classes: {},
      options: {}
    },
    watch: {
      test: {
        files: ['tests/*Test.php', 'src/**/*.*'],
        tasks: ['phpunit']
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-phpunit');
  grunt.registerTask('default', ['phpunit']);
};
