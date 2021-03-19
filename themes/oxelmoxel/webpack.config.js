const Encore = require('@symfony/webpack-encore');

Encore
  .setOutputPath('public/oxelmoxel-theme')
  .setPublicPath('/oxelmoxel-theme')
  .addEntry('oxelmoxeltheme', './themes/oxelmoxel/assets/app.js')
  .disableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableSassLoader()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction());

const config = Encore.getWebpackConfig();
config.name = 'oxelmoxelTheme';

module.exports = config;