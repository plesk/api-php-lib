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
