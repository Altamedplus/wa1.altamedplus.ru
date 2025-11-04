const HtmlWebpackPlugin = require("html-webpack-plugin");
const { webpack } = require("./vendor/pet/framework/Frontend/webpack");
 // API WIDGET
webpack.entry['api'] = './api/js/nalogform.tsx'
const path = require("path");

webpack.plugins.push(new HtmlWebpackPlugin({
    filename: './view/api/head.php',
    template: './head.php',
    entry: path.join('/', "src", '/api/js/nalogform.tsx'),
    chunks: ['root','api'],
    minify: { collapseWhitespace: true }
}))
module.exports = [webpack]
