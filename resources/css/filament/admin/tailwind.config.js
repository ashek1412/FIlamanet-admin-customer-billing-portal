import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
      './vendor/diogogpinto/filament-auth-ui-enhancer/resources/**/*.blade.php',



    ],
  theme: {
    extend: {

      fontSize: {
        xs: ['11px', '14px'],
        base: ['12px', '16px'],
        sm: ['13px', '18px'],
        md: ['15px', '22px'],
        lg: ['18px', '26px'],
        xl: ['24px', '32px'],
      },
      height: {
        128: '40rem',
      },
      spacing: {
        40: '10rem',
        84: '21rem',
        90: '22rem',
      },


    },
  },
}
