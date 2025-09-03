import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
         screens: {
            sm: '640px',
            md: '768px',
            lg: '1024px',
            xl: '1280px'
          },
          fontSize: {
              'exs':'0.40rem',
              'xxs': '0.60rem',
              'sm': '0.9rem',
              'base': '1rem',
              'xl': '1.25rem',
              '2xl': '1.563rem',
              '3xl': '1.953rem',
              '4xl': '2.441rem',
              '5xl': '3.052rem',
            },
        extend: {
             fontFamily: {
                monteserat: ["monteserat", "sans-serif"],
                josefin: ["josefin", "sans-serif"],
              },
            colors: {
                appTheme: '#F4F5FA', 
                primaryColor: '#008f3f',
                darkPrimaryColor: '#006400',
              },
        },
    },

    plugins: [forms],
};
