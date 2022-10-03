const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors')

module.exports = {
	content: [
		'./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
		'./vendor/laravel/jetstream/**/*.blade.php',
		'./storage/framework/views/*.php',
		'./resources/views/**/*.blade.php',
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
			},
            colors: {
                current: 'currentColor',
                green: colors.emerald,
                yellow: colors.amber,
                purple: colors.violet,
                gray: colors.neutral,
            }
		},
	},

	plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
