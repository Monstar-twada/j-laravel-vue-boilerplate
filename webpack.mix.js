const mix = require("laravel-mix");
const path = require("path");
const webpack = require("webpack");
const VueLoaderPlugin = require("vue-loader/lib/plugin");

const config = {
    module: {
        rules:[
            {
                test:'/\.vue$/',
                loader:'vue-loader',
            }
        ]

    },
    plugins: [
        new VueLoaderPlugin()
        //new webpack.optimize.UglifyJsPlugin({/* options here */}),
        //new webpack.optimize.ModuleConcatenationPlugin(),
    ],
    output: {
        filename: "[name].js",
        chunkFilename: "js/[id]-[hash].js",
        publicPath: "/"
    },
    resolve: {
        alias: {
            "@src": path.resolve(__dirname, "resources/js"),
            "@views": path.resolve(__dirname, "resources/js", "views"),
            "@layout": path.resolve(__dirname, "resources/js", "layout"),
            "@api": path.resolve(__dirname, "resources/js", "api"),
            "@core": path.resolve(__dirname, "resources/js", "core"),
        }
    }
};
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
const vendors = [
    "vue",
    "vue-router",
    "vee-validate",
    "vuex",
    "vuex-persistedstate",
    "vue-bus",
    "vue-svgicon",
    "moment",
    "filesize",
    "cleave.js",
    "vue-resource",
    "./resources/js/core/index.js",
    "./resources/js/core/api-builder/index.js",
    "./resources/js/core/store-builder/index.js"
];

mix.webpackConfig(config)
    .extract(vendors, "public/js/vendor.js")
    .js("resources/js/app.js", "public/js/app.js")
    .sass("resources/sass/app.scss", "public/css/app.css");

if (mix.inProduction()) {
    mix.version();
}
