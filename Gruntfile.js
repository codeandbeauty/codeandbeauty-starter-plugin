/* global require */

/**
 * When grunt command does not execute try these steps:
 *
 * - delete folder 'node_modules' and run command in console:
 *   $ npm install
 *
 * - Run test-command in console, to find syntax errors in script:
 *   $ grunt hello
 */

module.exports = function(grunt) {
 	// Show elapsed time at the end.
 	require( 'time-grunt' )(grunt);

 	// Load all grunt tasks.
 	require( 'load-grunt-tasks' )(grunt);

 	var buildtime, conf, pkg, banner, plugin_name, plugin_folder, plugin_slug, plugin_domain, plugin_copy_config;

 	buildtime = new Date().toISOString();

 	conf = {
 	    js_folder: 'assets/js/',
 	    js_files: [
 	        'Gruntfile.js',
 	        'assets/js/src/common/request.js'
 	        // Write your js files here that will require validation
 	    ],
 	    js_files_concat: {
 	        'assets/js/admin.js': [
 	            'assets/js/src/common/request.js'
 	        ]
 	    },
 	    sass_folder: 'assets/sass/',
 	    css_folder: 'assets/css/',
 	    css_files: {
 	        'assets/css/admin.css': 'assets/sass/admin.scss',
 	        'assets/css/front.css': 'assets/sass/front.scss'
 	    },
 	    php_files: [
 	        '*.php',
 	        'inc/*.php',
 	        'inc/**/*.php',
 	        'templates/*.php',
 	        'templates/**/*.php',
 	        'tests/*.php',
 	        'tests/php/*.php',
 	        'tests/php/**/*.php'
 	    ],
 	    translation: {
 	        dir: 'language/',
 	        ignore_files: [
                '(^.php)',	  // Ignore non-php files.
 				'tests/', // Unit tests
 				'node_modules/',
 				'.sass-cache' // In case .sass-cache get's generated
 	        ],
 	        textdomain: 'cad'
 	    }
 	};
 	pkg = grunt.file.readJSON('package.json');

 	banner = '/*! <%= pkg.title %> - v<%= pkg.version %>\n' +
             ' * Author: <%= pkg.author %> <<%= pkg.author_email%>>\n' +
             ' * Copyright (c) <%= grunt.template.today("yyyy") %>;\n' +
             ' * Licensed: GNU General Public License v2 or later\n' +
             ' */\n';

    plugin_copy_config = {
	    expand: true,
		src: [
		    '*',
		    '**',
		    '!node_modules/*',
		    '!node_modules/**',
		    '!node_modules/',
		    '!logs/*',
		    '!logs/',
		    '!.sass-cache/*',
		    '!.sass-cache/**',
		    '!.sass-cache/',
		    '!.idea'
		],
		dest: '',
		noEmpty: true,
		options: {
		    process: function(content) {
		        var rep_text;

                // Change `plugin_url`
                content = content.replace( 'plugins_url( \'codeandbeauty-starter-plugin/\' )', 'plugins_url( \'' + plugin_folder + '/\' )' );

                // Change the included main class file
                //content = content.replace( 'inc/class-codeandbeauty.php', 'inc/class-' + plugin_folder + '.php');                // Change the filname of included classes

                content = content.replace( /CodeAndBeauty/g, plugin_name );
                content = content.replace( /precodeandbeauty/g, plugin_slug );

                // Change the filename of included files
                rep_text = plugin_slug.replace(/_/g, '-');
                //regex = new RegExp( 'inc/class-' + plugin_slug, 'g' );
                content = content.replace( /inc\/class-codeandbeauty/g, 'inc/class-' + rep_text );

                // Change text domain
                content = content.replace( /TEXTDOMAIN/g, plugin_domain );

                // Finally remove text guide???
                content = content.replace( /\/\*d: \*[^\*]+\* d:\*\//mg, '');

                return content;
		    }
		},
		rename: function( dest, src_path ) {
		    if ( 'codeandbeauty.php' === src_path ) {
		        src_path = src_path.replace(/codeandbeauty/g, plugin_folder );
		    } else {
		        var rep_text = plugin_slug.replace(/_/g, '-');

		        src_path = src_path.replace( /codeandbeauty/g, rep_text );
		    }

		    return dest + '/' + src_path;
		}
	};

 	grunt.initConfig({
 	    pkg: pkg,
 	    conf: conf,
        // JS: Validate JS files (1).
		jsvalidate: {
			all: conf.js_files
		},

		// JS: Validate JS files (2).
		jshint: {
			all: conf.js_files,
			options: {
				curly:   true,
				browser: true,
				eqeqeq:  true,
				immed:   true,
				latedef: true,
				newcap:  true,
				noarg:   true,
				sub:     true,
				undef:   true,
				boss:    true,
				eqnull:  true,
				unused:  true,
				quotmark: 'single',
				predef: [
					'jQuery',
					'Backbone',
					'_'
				],
				globals: {
					exports: true,
					module:  false
				}
			}
		},

		// Concatenate js files
		concat: {
		    options: {
		        stripBanners: true
		    },
		    scripts: {
		        files: conf.js_files_concat
		    }
		},

        // JS: Compile/minify js files.
		uglify: {
			all: {
				files: [{
					expand: true,
					src: ['assets/js/*.js', '!*assets/js/*.min.js'],
					ext: '.min.js',
					extDot: 'last'
				}],
				options: {
					banner: banner,
					mangle: {
						except: ['jQuery']
					}
				}
			}
		},

        // CSS: Compile .scss into .css files.
		sass:   {
			all: {
				options: {
					'sourcemap=none': true, // 'sourcemap': 'none' does not work...
					unixNewlines: true,
					style: 'expanded'
				},
				files: conf.css_files
			}
		},

        // CSS: Add browser-specific CSS prefixes to css files.
		autoprefixer: {
			options: {
				browsers: ['last 2 version', 'ie 8', 'ie 9'],
				diff: false
			},
			single_file: {
				files: [{
					expand: true,
					src: ['*.css', '!*.min.css'],
					cwd: conf.css_folder,
					dest: conf.css_folder,
					ext: '.css',
					extDot: 'last'
				}]
			}
		},

		// CSS: Minify css files (create a .min.css file).
		cssmin: {
			options: {
				banner: banner
			},
			minify: {
				expand: true,
				src: ['*.css', '!*.min.css'],
				cwd: conf.css_folder,
				dest: conf.css_folder,
				ext: '.min.css',
				extDot: 'last'
			}
		},

        // POT: Create the .pot translation index.
		makepot: {
			target: {
				options: {
					cwd: '',
					domainPath: conf.translation.dir,
					exclude: conf.translation.ignore_files,
					mainFile: pkg.name,
					potFilename: conf.translation.textdomain + '.pot',
					potHeaders: {
						'poedit': true, // Includes common Poedit headers.
						'language-team': pkg.author + ' <' + pkg.author_email + '>',
						'report-msgid-bugs-to': pkg.author_uri,
						'last-translator': pkg.author + ' <' + pkg.author_email + '>',
						'x-generator': 'grunt-wp-i18n',
						'x-poedit-keywordslist': true // Include a list of all possible gettext functions.
					},
					type: 'wp-theme' // wp-plugin or wp-theme
				}
			}
		},

		// Create a generated compressed file for production
		compress: {
		    release: {
		        options: {
		            mode: 'zip',
		            archive: '../' + pkg.name + '-' + pkg.version + '.zip'
		        },
		        expand: true,
		        dest: pkg.name,
		        src: [
		            '*',
		            '**',
		            '!Gruntfile.js',
		            '!Gulpfile.js',
		            '!package.json',
		            '!.gitignore',
		            '!.git/*',
		            '!.git/**',
		            '!.git',
		            '!node_modules/',
		            '!node_modules/*',
		            '!node_modules/**',
		            '!assets/sass/',
		            '!assets/sass/*',
		            '!assets/sass/**',
		            '!.sass-cache/',
		            '!.idea/', // PHPStorm config
		            '!.idea/*', // PHPStorm config
		            '!.idea/**', // PHPStorm config
		            '!log/',
		            '!log/*',
		            '!log/**',
		            '!tmp/',
		            '!tmp/*',
		            '!tmp/**',
		            '!tests/*',
		            '!tests/**',
		            '!tests/'
		        ]
		    }
		},

        // PHP: Validate file syntax.
		phplint: {
			src: conf.php_files,
			options: {
				swapPath: '/tmp'  // Make sure this folder exists; its for caching to speed up the task.
			}
		},

		// PHP: Code Sniffer to validate WP Coding Standards.
		phpcs: {
			sniff: {
				src: conf.php_files,
				options: {
					bin: '../../../../../../phpcs/bin/phpcs',
					standard: 'WordPress-Core',
					verbose: true
				}
			}
		},

		// PHP: Unit tests.
		phpunit: {
			classes: {
				dir: ''
			},
			options: {
				bootstrap: 'tests/bootstrap.php',
				testsuite: 'default',
				configuration: 'tests/phpunit.xml',
				colors: true,
				staticBackup: false,
				noGlobalsBackup: false
			}
		},

		copy: {
		    all: plugin_copy_config
		}
 	});

    // Validate and compile js files
 	grunt.registerTask( 'js', ['jsvalidate', 'jshint', 'concat', 'uglify'] );

 	// Validate and compile sass files
 	grunt.registerTask( 'css', ['sass', 'autoprefixer', 'cssmin'] );

    // Validate PHP files
    grunt.registerTask( 'php', ['phplint', 'phpcs'] );

 	// Generate translation
 	grunt.registerTask( 'makepot', ['makepot'] );

 	// Generate a compress production copy
 	// Ensure that validation runs first before generating the release
 	// Ensure that the language gets regenerated
 	grunt.registerTask( 'generate-zip', ['js', 'css', 'makepot', 'compress'] );

 	grunt.registerTask( 'create-plugin', 'Generating new plugin...', function() {
 	    var folder, name, slug, domain;

 	    folder = grunt.option('folder');
 	    name = grunt.option('name');
 	    slug = grunt.option('slug');
 	    domain = grunt.option('domain');

 	    plugin_copy_config.dest = '../' + folder;

 	    // Ensure that the name and slug doesn't contain spaces
 	    name = name.replace( / /g, '' );
 	    slug = slug.replace( / /g, '' );

        plugin_folder = folder;
 	    plugin_name = name;
 	    plugin_slug = slug;
 	    plugin_domain = domain ? domain : slug;

 	    grunt.task.run( ['copy:all'] );
 	});
};