const colors = require('tailwindcss/colors')

module.exports = {
  purge: [
    './resources/**/*.html',
    './resources/**/*.vue',
    './resources/**/*.jsx',
    './resources/**/*.php',
		],
  darkMode: false,
  theme: {
    extend: {
		colors: {
			bluegray: colors.blueGray,
			truegreen: colors.green,
			fucsia: colors.fuchsia,
			orange: colors.orange,
				},
    backgroundImage: {
	  'bg1-texture': "url('/images/background1.png')",
    },
			},
		},
  variants: {
    extend: {},
			},
  plugins: [],
}
