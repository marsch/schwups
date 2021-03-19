const Encore = require('@symfony/webpack-encore');

Encore
  .setOutputPath('public/vanandern-theme')
  .setPublicPath('/vanandern-theme')
  .addEntry('vananderntheme', './themes/vanandern/assets/app.js')
  .disableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableSassLoader()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction());

const config = Encore.getWebpackConfig();
config.name = 'vanandernTheme';

module.exports = config;