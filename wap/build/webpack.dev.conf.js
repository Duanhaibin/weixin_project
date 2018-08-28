var utils = require('./utils')
var webpack = require('webpack')
var config = require('../config')
var merge = require('webpack-merge')
var baseWebpackConfig = require('./webpack.base.conf')
var HtmlWebpackPlugin = require('html-webpack-plugin')
var FriendlyErrorsPlugin = require('friendly-errors-webpack-plugin')

// add hot-reload related code to entry chunks
Object.keys(baseWebpackConfig.entry).forEach(function (name) {
  baseWebpackConfig.entry[name] = ['./build/dev-client'].concat(baseWebpackConfig.entry[name])
})


var data = `
  var SITE_URL = "http://localhost";
	var IMG_PATH = "/Public/Home/images";
	var STATIC_PATH = "/Public/static";
	var WX_APPID = "wx9ed8167beb9897a7";
	var WXJS_TIMESTAMP = '1523155426';
	var NONCESTR = 'hxWgWiPhoHbiJxRM';
	var SIGNATURE = '1040fa152da9850659fc42f2d0bf8dd626d59ac9';
  var AJAXURL = 'http://192.168.0.77:1203/';
  var MCOMPLAINURL = 'http://tousuc.12301e.com/';
  var OPENID = "{$openid}";
  var	TOKEN='{$token}';
  var	NICKNAME='人生导师大猪蹄子';
  var	HEADIMG='http://img.zcool.cn/community/00ca395a7b2170a801219231d78b10.jpg@80w_80h_1c_1e_1o_100sh.jpg';
`;

module.exports = merge(baseWebpackConfig, {
  module: {
    rules: utils.styleLoaders({ sourceMap: config.dev.cssSourceMap })
  },
  // cheap-module-eval-source-map is faster for development
  devtool: '#cheap-module-eval-source-map',
  plugins: [
    new webpack.DefinePlugin({
      'process.env': config.dev.env
    }),
    // https://github.com/glenjamin/webpack-hot-middleware#installation--usage
    new webpack.HotModuleReplacementPlugin(),
    new webpack.NoEmitOnErrorsPlugin(),
    // https://github.com/ampedandwired/html-webpack-plugin
    new HtmlWebpackPlugin({
      filename: 'index.html',
      template: 'index.html',
      chunks: ['app', 'manifest', 'vendor'],
      data: data,
      inject: true
    }),
    new FriendlyErrorsPlugin()
  ]
})
