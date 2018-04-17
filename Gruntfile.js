module.exports = function(grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

    banner: '/**\n' +
    '<%= pkg.title %>\n' +
    'Author: <%= pkg.author.name %>\n' +
    'Verison: <%= pkg.version %>\n' +
    'Date: <%= grunt.template.today("yyyy-mm-dd") %>\n' +
    '*/\n',

    dir: {
			dist: 'assets',
			src: 'src',
      js: 'js',
      css: 'css',
      sass: 'sass',
      img: 'img'
    },

		imagemin: {
			dynamic: {
				files: [{
					expand: true,
					cwd: '<%= dir.dist %>/<%= dir.img %>/',
          src: '{,*/}*.{png,jpg,jpeg}',
					dest: '<%= dir.dist %>/<%= dir.img %>/'
				}]
			}
		},

		concat: {
			options: {
				seperator: ";",
				stripBanner: true,
        banner: '<%= banner %>'
			},
			js: {
				src: ['<%= dir.src %>/<%= dir.js %>/**/*.js'],
				dest: '<%= dir.dist %>/<%= dir.js %>/main.js'
			}
		},

		uglify: {
		  dist: {
        options: {
          banner: '<%= banner %>',
          mangle: false
        },
        files: [{
          expand: true,
          src: ['assets/js/*.js', '!assets/js/*.min.js'],
          dest: 'assets/js',
          cwd: '.',
          rename: function (dst, src) {
            return src;
          }
        }]
      }
		},

    sass: {
      dist: {
        options: {
          style: 'expanded',
          loadPath: ['node_modules/bulma'],
        },
        files: {
          '<%= dir.dist %>/<%= dir.css %>/global.css': '<%= dir.src %>/<%= dir.sass %>/global.scss',
          '<%= dir.dist %>/<%= dir.css %>/admin-login.css': '<%= dir.src %>/<%= dir.sass %>/admin-login.scss',
        }
      }
    },

		cssmin: {
			my_target: {
				files: [{
					expand: true,
					cwd: '<%= dir.dist %>/<%= dir.css %>/',
					src: ['*.css', '!*.min.css'],
					dest: '<%= dir.dist %>/<%= dir.css %>/',
					ext: '.min.css'
				}]
			}
		},

    copy: {
      main: {
        src: '<%= dir.dist %>/<%= dir.css %>/global.min.css',
        dest: 'style.css',
      },
    },

		watch: {
      sass: {
        files: '<%= dir.src %>/<%= dir.sass %>/**/*',
        tasks: [
        	'sass:dist',
          'cssmin',
          'copy'
				]
      },

      js: {
        files: '<%= concat.js.src %>',
        tasks: ['concat', 'uglify']
      },
		}
	});

  grunt.registerTask('default', [
    'concat:js',
    'uglify:dist',
    'sass:dist',
  	'cssmin',
    'copy'
	]);

  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-imagemin');
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-watch');
};