module.exports = function(grunt) {
	grunt.initConfig({
		clean: {
			dist: {
				src : '../public/compiled-assets/*',
//				filter: 'isFile'
			},

			options: {
				force: true
			}
		}
	});

	require('load-grunt-tasks')(grunt, { scope: 'devDependencies' });

	//default task
	grunt.registerTask('default', ['clean']);
};