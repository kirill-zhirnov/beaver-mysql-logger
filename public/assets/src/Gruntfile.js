/*!
 * Bootstrap's Gruntfile
 * http://getbootstrap.com
 * Copyright 2013-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 */

module.exports = function(grunt) {
	'use strict';

	grunt.util.linefeed = '\n';

	RegExp.quote = function (string) {
		return string.replace(/[-\\^$*+?.()|[\]{}]/g, '\\$&');
	};

	grunt.initConfig({
		assets : '../compiled/',

		cssFile : '<%=assets%>css/styles.css',
		jsFile : '<%=assets%>js/scripts.js',
		jsMinFile : '<%=assets%>js/scripts.min.js',

		bootstrapPkg : grunt.file.readJSON('bootstrap/package.json'),

		banner: '/*!\n' +
			' * Bootstrap v<%= bootstrapPkg.version %> (<%= bootstrapPkg.homepage %>)\n' +
			' * Copyright 2011-<%= grunt.template.today("yyyy") %> <%= bootstrapPkg.author %>\n' +
			' * Licensed under <%= bootstrapPkg.license.type %> (<%= bootstrapPkg.license.url %>)\n' +
			' */\n',

		jqueryCheck: 'if (typeof jQuery === \'undefined\') { throw new Error(\'Bootstrap\\\'s JavaScript requires jQuery\') }\n\n',

		clean: {
			dist: {
				src : '<%=assets%>*'
			},

			options: {
				force: true
			}
		},

		less : {
			compile: {
				options: {
					strictMath: true,
					sourceMap: true,
					outputSourceFiles: true,
					sourceMapURL: 'styles.css.map',
					sourceMapFilename: '<%=assets%>css/styles.css.map'
				},
				files: {
					'<%=cssFile%>': 'less/styles.less'
				}
			}
		},

		autoprefixer: {
			options: {
				browsers: [
					'Android 2.3',
					'Android >= 4',
					'Chrome >= 20',
					'Firefox >= 24', // Firefox 24 is the latest ESR
					'Explorer >= 8',
					'iOS >= 6',
					'Opera >= 12',
					'Safari >= 6'
				]
			},
			core: {
				options: {
					map: true
				},
				src: '<%=cssFile%>'
			}
		},

		csslint: {
			options: {
				csslintrc: 'bootstrap/less/.csslintrc'
			},
			src: [
				'<%=cssFile%>'
			]
		},

		usebanner: {
			options: {
				position: 'top',
				banner: '<%= banner %>'
			},
			files: {
				src: '<%=assets%>css/*.css'
			}
		},

		csscomb: {
			options: {
				config: 'bootstrap/less/.csscomb.json'
			},
			dist: {
				expand: true,
				cwd: '<%=assets%>css/',
				src: ['*.css', '!*.min.css'],
				dest: '<%=assets%>css/'
			}
		},

		concat: {
			options: {
				banner: '<%= banner %>',
				stripBanners: false
			},
			scripts: {
				src: [
					//jquery
					'js/jquery-2.1.1.min.js',

					//bootstrap
					'bootstrap/js/transition.js',
					'bootstrap/js/alert.js',
					'bootstrap/js/button.js',
					'bootstrap/js/carousel.js',
					'bootstrap/js/collapse.js',
					'bootstrap/js/dropdown.js',
					'bootstrap/js/modal.js',
					'bootstrap/js/tooltip.js',
					'bootstrap/js/popover.js',
					'bootstrap/js/scrollspy.js',
					'bootstrap/js/tab.js',
					'bootstrap/js/affix.js',

					//jquery plugins
					'js/jquery/jquery.form.js',

					//project libs and files:
					'js/extend.js',
					'js/kz/*'
				],
				dest: '<%= jsFile %>'
			}
		},

		uglify: {
			options: {
				preserveComments: 'some'
			},
			bootstrap: {
				src: '<%= concat.scripts.dest %>',
				dest: '<%= jsMinFile %>'
			}
		},

		watch: {
			less: {
				files: 'less/*.less',
				tasks: 'less'
			},

			js : {
				files: 'js/**/*.js',
				tasks: 'concat'
			}
		},

		copy: {
			fonts: {
				cwd : 'bootstrap/fonts/',
				expand: true,
				src: '*',
				dest: '<%=assets%>/fonts/'
			}
		}
	});

	require('load-grunt-tasks')(grunt, { scope: 'devDependencies' });

	//CSS
	grunt.registerTask('less-compile', ['less:compile']);
	grunt.registerTask('dist-css', ['less-compile', 'autoprefixer', 'usebanner', 'csscomb']);

	// JS distribution task.
	grunt.registerTask('dist-js', ['concat']);

	//default task
	grunt.registerTask('default', ['clean', 'dist-css', 'dist-js', 'copy:fonts']);
};