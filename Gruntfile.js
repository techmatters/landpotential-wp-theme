module.exports = function( grunt ) {
	var sass = require( 'sass' );

	// Load all grunt tasks
	require( 'matchdep' ).filterDev( 'grunt-*' ).forEach( grunt.loadNpmTasks );
	grunt.loadNpmTasks( '@lodder/grunt-postcss' );

	// Project configuration
	grunt.initConfig( {
		pkg: grunt.file.readJSON( 'package.json' ),

		concat: {
			options: {
				stripBanners: true,
				sourceMap: true
			},
			newsletter: {
				src: [ 'wp-content/plugins/lpks-newsletter/assets/js/src/newsletter.js' ],
				dest: 'wp-content/plugins/lpks-newsletter/assets/js/newsletter.src.js'
			},
			styles: {
				src: [ 'wp-content/plugins/lpks-styles/assets/js/src/main.js' ],
				dest: 'wp-content/plugins/lpks-styles/assets/js/main.src.js'
			}
		},

		uglify: {
			all: {
				files: {
					'wp-content/plugins/lpks-newsletter/assets/js/newsletter.min.js': [
						'wp-content/plugins/lpks-newsletter/assets/js/newsletter.src.js'
					],
					'wp-content/plugins/lpks-styles/assets/js/main.min.js': [
						'wp-content/plugins/lpks-styles/assets/js/main.src.js'
					]
				},
				options: {
					mangle: {
						reserved: [ 'jQuery' ]
					},
					sourceMap: false
				}
			}
		},

		eslint: {
			src: [ 'Gruntfile.js', 'wp-content/plugins/lpks-**/assets/js/src/**/*.js' ],
			options: {
				overrideConfigFile: 'eslint.config.mjs',
				fix: true
			}
		},

		sass: {
			newsletter: {
				options: {
					implementation: sass,
					outputStyle: 'expanded',
					sourceMap: true
				},
				files: [
					{
						expand: true,
						cwd: 'wp-content/plugins/lpks-newsletter/assets/css/src',
						src: [ '*.scss', '!_*.scss' ],
						dest: 'wp-content/plugins/lpks-newsletter/assets/css',
						ext: '.src.css'
					}
				]
			},
			styles: {
				options: {
					implementation: sass,
					outputStyle: 'expanded',
					sourceMap: true
				},
				files: [
					{
						expand: true,
						cwd: 'wp-content/plugins/lpks-styles/assets/css/src',
						src: [ '*.scss', '!_*.scss' ],
						dest: 'wp-content/plugins/lpks-styles/assets/css',
						ext: '.src.css'
					}
				]
			},
			theme: {
				options: {
					implementation: sass,
					imagePath: 'wp-content/themes/landpks/assets/images',
					outputStyle: 'expanded',
					sourceMap: true
				},
				files: [
					{
						expand: true,
						cwd: 'wp-content/themes/landpks/assets/css/src',
						src: [ '*.scss', '!_*.scss' ],
						dest: 'wp-content/themes/landpks/assets/css',
						ext: '.src.css'
					}
				]
			}
		},

		/*
		 * Runs postcss plugins
		 */
		postcss: {
			newsletter: {
				options: {
					map: false,
					processors: [ require( 'autoprefixer' )() ]
				},
				files: [
					{
						expand: true,
						cwd: 'wp-content/plugins/lpks-newsletter/assets/css',
						src: [ '*.src.css' ],
						dest: 'wp-content/plugins/lpks-newsletter/assets/css',
						ext: '.src.css'
					}
				]
			},
			styles: {
				options: {
					map: false,
					processors: [ require( 'autoprefixer' )() ]
				},
				files: [
					{
						expand: true,
						cwd: 'wp-content/plugins/lpks-styles/assets/css',
						src: [ '*.src.css' ],
						dest: 'wp-content/plugins/lpks-styles/assets/css',
						ext: '.src.css'
					}
				]
			},
			theme: {
				options: {
					map: false,
					processors: [ require( 'autoprefixer' )() ]
				},
				files: [
					{
						expand: true,
						cwd: 'wp-content/themes/landpks/assets/css',
						src: [ '*.src.css' ],
						dest: 'wp-content/themes/landpks/assets/css',
						ext: '.src.css'
					}
				]
			}
		},

		cssmin: {
			newsletter: {
				files: [
					{
						expand: true,
						cwd: 'wp-content/plugins/lpks-newsletter/assets/css',
						src: [ '*.src.css' ],
						dest: 'wp-content/plugins/lpks-newsletter/assets/css',
						ext: '.min.css'
					}
				]
			},
			styles: {
				files: [
					{
						expand: true,
						cwd: 'wp-content/plugins/lpks-styles/assets/css',
						src: [ '*.src.css' ],
						dest: 'wp-content/plugins/lpks-styles/assets/css',
						ext: '.min.css'
					}
				]
			},
			theme: {
				files: [
					{
						expand: true,
						cwd: 'wp-content/themes/landpks/assets/css',
						src: [ '*.src.css' ],
						dest: 'wp-content/themes/landpks/assets/css',
						ext: '.min.css'
					}
				]
			}
		},

		watch: {
			php: {
				files: [
					'wp-content/**/*.php',
					'wp-content/**/template-parts/**/*.php',
					'wp-content/**/includes/**/*.php',
					'!vendor/**',
					'!node_modules/**'
				],
				tasks: [ 'phplint', 'phpcbf' ]
			},

			css: {
				files: [ 'wp-content/**/assets/css/src/**/*.scss' ],
				tasks: [ 'css' ],
				options: {
					debounceDelay: 500
				}
			},

			scripts: {
				files: [ 'wp-content/**/assets/js/src/**/*.js' ],
				tasks: [ 'js' ],
				options: {
					debounceDelay: 500
				}
			}
		},

		phplint: {
			phpArgs: {
				'-lf': null
			},
			files: [
				'wp-content/**/*.php',
				'wp-content/**/template-parts/**/*.php',
				'wp-content/**/includes/**/*.php'
			]
		},

		git_modified_files: {
			options: {
				diffFiltered: 'ACMRTUXB', // Optional: default is 'AMC',
				regexp: /\.php$/ // Optional: default is /.*/
			}
		},

		phpcs: {
			application: {
				src: '<%= gmf.filtered %>'
			},
			options: {
				bin: 'vendor/bin/phpcs'
			}
		},

		phpcbf: {
			options: {
				bin: 'vendor/bin/phpcbf',
				noPatch: false
			},
			files: {
				src: [
					'wp-content/**/*.php',
					'wp-content/**/template-parts/**/*.php',
					'wp-content/**/includes/**/*.php'
				]
			}
		}
	} );

	// Set a default, so if phpcs is run directly it scans everything
	grunt.config.set( 'gmf.filtered', [
		'**/*.php',
		'!vendor/**',
		'!node_modules/**'
	] );
	grunt.registerTask( 'precommit', [ 'git_modified_files', 'maybe-phpcs' ] );
	grunt.registerTask(
		'maybe-phpcs',
		'Only run phpcs if git_modified_files has found changes.',
		function() {

			// Check all, because there's no default set for all and we can see if we have files
			var allModified = grunt.config.get( 'gmf.all' );
			var matches = allModified.filter( function( str ) {
				return -1 !== str.search( /\.php$/ );
			} );

			if ( ! matches.length ) {
				grunt.log.writeln( 'No php files to sniff. Skipping phpcs.' );
			} else {
				grunt.task.run( 'phpcs' );
			}
		}
	);

	// PHP Only
	grunt.registerTask( 'php', [ 'phplint', 'phpcs' ] );

	// JS Only
	grunt.registerTask( 'js', [ 'eslint', 'concat', 'uglify' ] );

	// CSS Only
	grunt.registerTask( 'css', [ 'sass', 'postcss', 'cssmin' ] );

	// CSS & JS Only
	grunt.registerTask( 'css-js', [ 'css', 'js' ] );

	// Default task.
	grunt.registerTask( 'default', [ 'js', 'css', 'php' ] );
};
