module.exports = function (grunt) {
	var pkg = grunt.file.readJSON("package.json");

	console.log(pkg.title + " - " + pkg.version);

	// Files to include/exclude in a release.
	var distFiles = [
		"includes/**",
		"simple-calendar-blog-feed.php",
		"readme.txt",
		"license.txt",
		"i18n/**",
	];

	grunt.initConfig({
		pkg: pkg,

		checktextdomain: {
			options: {
				text_domain: "simple-calendar-blog-feed",
				correct_domain: false,
				keywords: [
					"__:1,2d",
					"_e:1,2d",
					"_x:1,2c,3d",
					"esc_html__:1,2d",
					"esc_html_e:1,2d",
					"esc_html_x:1,2c,3d",
					"esc_attr__:1,2d",
					"esc_attr_e:1,2d",
					"esc_attr_x:1,2c,3d",
					"_ex:1,2c,3d",
					"_n:1,2,4d",
					"_nx:1,2,4c,5d",
					"_n_noop:1,2,3d",
					"_nx_noop:1,2,3c,4d",
				],
			},
			files: {
				src: ["includes/**/*.php", "simple-calendar-blog-feed.php"],
				expand: true,
			},
		},

		clean: {
			main: ["build"],
		},

		compress: {
			main: {
				options: {
					mode: "zip",
					archive: "./build/simple-calendar-blog-feed-<%= pkg.version %>.zip",
				},
				expand: true,
				src: distFiles,
				dest: "/simple-calendar-blog-feed",
			},
		},

		copy: {
			main: {
				expand: true,
				src: distFiles,
				dest: "build/simple-calendar-blog-feed",
			},
		},
	});

	require("load-grunt-tasks")(grunt);

	grunt.registerTask("build", ["checktextdomain", "clean", "copy", "compress"]);

	grunt.util.linefeed = "\n";
};
