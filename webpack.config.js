var Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

const webpack = require('webpack');

const CopyWebpackPlugin = require('copy-webpack-plugin');

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    .autoProvidejQuery()
    .addPlugin(new CopyWebpackPlugin([
        // copies to {output}/static
        {from: './assets/static', to: 'static'}
    ]))
    // o summmernote tem esta dependência, mas não é necessária
    .addPlugin(new webpack.IgnorePlugin(/^codemirror$/))
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('Turismo/veiculoList', './assets/js/Turismo/veiculoList.js')
    .addEntry('Turismo/motoristaList', './assets/js/Turismo/motoristaList.js')
    .addEntry('Turismo/agenciaList', './assets/js/Turismo/agenciaList.js')
    .addEntry('Turismo/itinerarioList', './assets/js/Turismo/itinerarioList.js')
    .addEntry('Turismo/viagemList', './assets/js/Turismo/viagemList.js')
    .addEntry('Turismo/viagem_form', './assets/js/Turismo/viagem_form.js')
    .addEntry('Turismo/viagemPassageiroForm', './assets/js/Turismo/viagemPassageiroForm.js')

    .addEntry('Turismo/App/form_passagem_pesquisarViagens', './assets/js/Turismo/App/form_passagem_pesquisarViagens.js')
    .addEntry('Turismo/App/form_passagem_selecionarPoltronas', './assets/js/Turismo/App/form_passagem_selecionarPoltronas.js')
    .addEntry('Turismo/App/form_passagem_cadastroCliente', './assets/js/Turismo/App/form_passagem_cadastroCliente.js')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    // .enableSingleRuntimeChunk()
    .disableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

// enables Sass/SCSS support
//.enableSassLoader()

// uncomment if you use TypeScript
//.enableTypeScriptLoader()

// uncomment to get integrity="..." attributes on your script & link tags
// requires WebpackEncoreBundle 1.4 or higher
//.enableIntegrityHashes(Encore.isProduction())

// uncomment if you're having problems with a jQuery plugin
//.autoProvidejQuery()

// uncomment if you use API Platform Admin (composer req api-admin)
//.enableReactPreset()
//.addEntry('admin', './assets/js/admin.js')
;

module.exports = Encore.getWebpackConfig();

