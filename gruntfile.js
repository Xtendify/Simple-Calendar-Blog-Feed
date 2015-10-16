module.exports = function( grunt ) {

	var pkg = grunt.file.readJSON( 'package.json' );

	console.log( pkg.title + ' - ' + pkg.version );

	// Files to include in a release.
	var distFiles =  [
		'includes/**',
		'languages/**',
		'license.txt',
		'readme.txt',
		'simple-calendar-blog-feed.php'
	];

	grunt.initConfig( {

		pkg: pkg,

		checktextdomain: {
			options: {
				text_domain   : 'simple-calendar-blog-feed',
				correct_domain: false,
				keywords      : [
					'__:1,2d',
					'_e:1,2d',
					'_x:1,2c,3d',
					'esc_html__:1,2d',
					'esc_html_e:1,2d',
					'esc_html_x:1,2c,3d',
					'esc_attr__:1,2d',
					'esc_attr_e:1,2d',
					'esc_attr_x:1,2c,3d',
					'_ex:1,2c,3d',
					'_n:1,2,4d',
					'_nx:1,2,4c,5d',
					'_n_noop:1,2,3d',
					'_nx_noop:1,2,3c,4d',
					' __ngettext:1,2,3d',
					'__ngettext_noop:1,2,3d',
					'_c:1,2d',
					'_nc:1,2,4c,5d'
				]
			},
			files  : {
				src   : [
					'includes/**/*.php',
					'simple-calendar-blog-feed.php'
				],
				expand: true
			}
		},

		makepot: {
			target: {
				options: {
					cwd            : '',
					domainPath     : '/languages',
					potFilename    : 'simple-calendar-blog-feed.pot',
					mainFile       : 'simple-calendar-blog-feed.php',
					include        : [],
					exclude        : [
						'assets/',
						'build/',
						'languages/',
						'node_modules',
						'svn',
						'tests',
						'tmp'
					],
					potComments    : '',
					potHeaders     : {
						poedit                 : true,
						'x-poedit-keywordslist': true,
						'language'             : 'en_US',
						'report-msgid-bugs-to' : 'https://github.com/moonstonemedia/Simple-Calendar-Blog-Feed/issues',
						'last-translator'      : 'Phil Derksen <pderksen@gmail.com>',
						'language-Team'        : 'Phil Derksen <pderksen@gmail.com>'
					},
					type           : 'wp-plugin',
					updateTimestamp: true,
					updatePoFiles  : true,
					processPot     : null
				}
			}
		},

		clean: {
			build: [ 'build' ]
		},

		copy: {
			main: {
				expand: true,
				src   : distFiles,
				dest  : 'build/simple-calendar-blog-feed'
			}
		},

		compress: {
			main: {
				options: {
					mode   : 'zip',
					archive: './build/simple-calendar-blog-feed-<%= pkg.version %>.zip'
				},
				expand : true,
				src    : distFiles,
				dest   : '/simple-calendar-blog-feed'
			}
		},

		wp_deploy: {
			deploy: {
				options: {
					plugin_slug     : 'simple-calendar-blog-feed',
					plugin_main_file: 'simple-calendar-blog-feed.php',
					build_dir       : 'build/simple-calendar-blog-feed',
					max_buffer      : 400 * 1024
				}
			}
		}

	} );

	require( 'load-grunt-tasks' )(grunt);

	grunt.loadNpmTasks( 'grunt-composer' );

	grunt.registerTask( 'localize', ['checktextdomain', 'makepot'] );
	grunt.registerTask( 'build',    ['clean:build', 'copy', 'compress'] );
	grunt.registerTask( 'deploy',   ['localize', 'build', 'wp_deploy'] );

	grunt.util.linefeed = '\n';
};
