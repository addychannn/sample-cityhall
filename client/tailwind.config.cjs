  /** @type {import('tailwindcss').Config} */
  module.exports = {
    darkMode: "class",
    content: [
      "./index.html", "./src/**/*.{vue,js,ts,jsx,tsx}",
    ],
    theme: {
      fontFamily: {
        sans: [
          "Lexend",
        ]
      },
      screens: {
        'ss':'280px',
        
        'xs': '360px',

        'sm': '740px',
      
        'md': '968px',
        
        'lg': '1024px',

        '2lg': '1280px',
      
        'xl': '1366px',
      
        '2xl': '1920px',
        
      },
      extend: {
        animation: {
          shake: "shake 0.5s cubic-bezier(.36,.07,.19,.97) both",
          ripple: "ripple 0.5s linear infinite",
          collapse: "collapse 0.5s ease-in",
          unprogress: "unprogress 3s linear infinite",
          fadeIn: "fadeIn 2s ease-in forwards"
        },
        keyframes: {
          shake: {
            "10%, 90%": {
              transform: "translate3d(-1px, 0, 0)",
            },

            "20%, 80%": {
              transform: "translate3d(2px, 0, 0)",
            },

            "30%, 50%, 70%": {
              transform: "translate3d(-4px, 0, 0)",
            },

            "40%, 60%": {
              transform: "translate3d(4px, 0, 0)",
            },
          },
          ripple: {
            "0%": { width: "0px", height: "0px", opacity: 0.5 },
            "100%": { width: "500px", height: "500px", opacity: 0 },
          },
          collapse: {
            from: { "max-height": "100%" },
            to: { "max-height": "0%" },
          },
          unprogress: {
            "0%": { left: "-100%", transform: "scaleX(1)" },
            "49%": { left: "150%", visibility: "hidden" },
            "50%": {
              left: "-100%",
              transform: "scaleX(2.25)",
              visibility: "hidden",
            },
            "51%": { visibility: "visible" },
            "75%": { transform: "scaleX(2.25)" },
            "100%": { left: "100%", transform: "scaleX(0.25)" },
          },
          fadeIn:{
            "0%": {opacity: 0},
            "100%": {opacity: 1}
          },
          variants: {
            animation: ["motion-safe"]
          },
        },
        backgroundImage: {
          glossy:
            "linear-gradient(to bottom,rgba(255,255,255,.3),rgba(255,255,255,0) 50%,rgba(0,0,0,.12) 51%,rgba(0,0,0,.04))!important",

          },
        colors: {
          primary: "hsl(var(--color-primary) / <alpha-value>)",
          secondary: "hsl(var(--color-secondary) / <alpha-value>)",
          accent: "hsl(var(--color-accent) / <alpha-value>)",

          dark: "hsl(var(--color-dark) / <alpha-value>)",
          light: "hsl(var(--color-light) / <alpha-value>)",
          "dark-page": "hsl(var(--color-dark-page) / <alpha-value>)",
          "light-page": "hsl(var(--color-light-page) / <alpha-value>)",

          positive: "hsl(var(--color-positive) / <alpha-value>)",
          negative: "hsl(var(--color-negative) / <alpha-value>)",
          info: "hsl(var(--color-info) / <alpha-value>)",
          warning: "hsl(var(--color-warning) / <alpha-value>)",
        },
      },
    },
    safelist: [
      {
        pattern:
          /-(primary|secondary|accent|dark|light-page|light|dark|dark-page|positive|negative|info|warning)/,
    
      },
    ],
    plugins: [
      require('@tailwindcss/line-clamp'),
    ],
  };
