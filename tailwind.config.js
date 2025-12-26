/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
            // 1. BRAND COLORS (Extracted from your CSS variables)
            colors: {
                // Primary: Deep Purple / Plum
                'primary': '#7a4d78',      
                // Secondary: Bright Cyan / Aqua (Used for accents, links, and highlights)
                'secondary': '#07e6fa',    
                // Dark Text/Background
                'dark-text': '#2c3e50',    
                // Accent: Bright Amber/Orange (Useful for warnings, deals, or badges)
                'brand-accent': '#f39c12',  
                // Light Background
                'light-bg': '#f8f9fa',
            },
            // 2. Custom Keyframes (for loading spinners)
            animation: {
                'spin-slow': 'spin 3s linear infinite',
            },
        },
    },
  plugins: [
    require('@tailwindcss/forms'),
        function ({ addUtilities }) {
            addUtilities({
                // BUTTON 1: CARD ACTION / SECONDARY ACTION (Cyan BG, Primary Text,'View Package', 'Select Variant' on cards.)
                    '.btn-secondary': {
                    '@apply bg-secondary text-primary font-semibold py-3 px-6 rounded-full transition duration-300 ease-in-out hover:bg-secondary/80 active:bg-secondary/70 focus:ring-4 focus:ring-secondary/50 shadow-md hover:shadow-lg': {},
                },
                
                // BUTTON 2: BOLD ACTION / MAIN ACTION (Primary BG, White Text)
                // Used for 'Search', 'Book Now', 'Pay Now'.
                '.btn-primary': {
                    '@apply bg-primary text-white font-semibold py-3 px-6 rounded-full transition duration-300 ease-in-out hover:bg-primary/90 active:bg-primary/80 focus:ring-4 focus:ring-primary/50 shadow-md hover:shadow-lg': {},
                },
                
                // BUTTON 3: OUTLINE (Unchanged)
                '.btn-outline': {
                    // Matches your .btn-outline (Primary border, transparent background)
                    '@apply border-2 border-primary text-primary font-semibold py-3 px-6 rounded-full transition duration-300 ease-in-out hover:bg-primary hover:text-white': {},
                },

                // OTHER UTILITIES (Unchanged)
                '.text-gradient': {
                    '@apply bg-clip-text text-transparent bg-gradient-to-r from-primary to-secondary': {},
                },
                '.card-shadow': {
                    '@apply shadow-lg hover:shadow-xl transition-shadow duration-300 ease-in-out rounded-xl overflow-hidden bg-white': {},
                },
                '.tab-active': {
                    '@apply text-primary border-b-2 border-secondary font-bold': {},
                },
                '.page-bg-wipe': {
                    '@apply relative z-10 min-h-screen bg-light-bg': {},
                }
            })
        }
  ],
}

