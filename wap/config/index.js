// see http://vuejs-templates.github.io/webpack for documentation.
var path = require('path')

module.exports = {
    build: {
        env: require('./prod.env'),
        index: path.resolve(__dirname, '../../Addons/CbsBusiness/View/CbsBusinessWap/', 'index.html'),
        assetsRoot: path.resolve(__dirname, '../../Public/static/wap/'),
        assetsSubDirectory: 'static',
        assetsPublicPath: '/Public/static/wap/',
        productionSourceMap: false,
        productionGzip: false,
        productionGzipExtensions: ['js', 'css'],
        bundleAnalyzerReport: process.env.npm_config_report
    },
    dev: {
        env: require('./dev.env'),
        port: 8888,
        autoOpenBrowser: false,
        assetsSubDirectory: 'static',
        assetsPublicPath: '/',
        proxyTable: {},
        cssSourceMap: true
    }
}
