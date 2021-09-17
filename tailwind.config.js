const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
	mode: 'jit',
	purge: [
		'./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
		'./vendor/laravel/jetstream/**/*.blade.php',
		'./storage/framework/views/*.php',
		'./resources/views/**/*.blade.php',
		'./vendor/spatie/laravel-support-bubble/config/**/*.php',
		'./vendor/spatie/laravel-support-bubble/resources/views/**/*.blade.php',
	],

	theme: {
		extend: {
			fontFamily: {
				sans: ['Inter var', ...defaultTheme.fontFamily.sans],
			},
			zIndex: {
				'-10': '-10'
			},
			gridTemplateColumns: {
				'audit': '100px minmax(0, 1fr)',
			}
		},
	},

	variants: {
		extend: {
			opacity: ['disabled'],
		},
	},

	plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
