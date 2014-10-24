// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

module.exports = function(grunt) {
  grunt.initConfig({
    phpunit: {
      classes: {},
      options: {
        configuration: 'phpunit.xml'
      }
    },
    watch: {
      test: {
        files: ['*Test.php', '../src/**/*.*'],
        tasks: ['phpunit']
      }
    }
  });


  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-phpunit');
  grunt.registerTask('default', ['phpunit']);
};
