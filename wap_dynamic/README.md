# my-project

> A Vue.js project

## Build Setup

``` bash
# install dependencies
npm install

# serve with hot reload at localhost:8080
npm run dev

# build for production with minification
npm run build

# build for production and view the bundle analyzer report
npm run build --report

# run unit tests
npm run unit

# run e2e tests
npm run e2e

# run all tests
npm test
```

For a detailed explanation on how things work, check out the [guide](http://vuejs-templates.github.io/webpack/) and [docs for vue-loader](http://vuejs.github.io/vue-loader).



景区时讯 ： localhost:8080 
导游 ： 
安全提醒 ：http://localhost:8080/security.html#/



wap文件夹下 config 文件夹 index.js 中配置 
    proxyTable: {
        '/inspect/*': {
            target: 'http://127.0.0.1:9090'
        }
    },
    代理。

    同： build 对象中 配置 输出路径






routes/dynamic.js  下配置 多页面路由： 
  1.  module.exports.autoroute = {
        "get" :{
            "/dynamic": index,
            "/security": security //安全提醒
        },

  2. function security(req, res) {
        res.render("dynamic/security", {
            layout: false
        });
    }

webpack.prod.conf.js 中配置 build 打包多入口： 
  1. filename: process.env.NODE_ENV === 'testing'
        ? 'security.html'
        : config.build.security,
      template: 'index.html',
      inject: true,
      minify: {
        removeComments: true,
        collapseWhitespace: true,
        removeAttributeQuotes: true
        // more options:
        // https://github.com/kangax/html-minifier#options-quick-reference
      },
      // necessary to consistently work with multiple chunks via CommonsChunkPlugin
      chunks: ['security', 'manifest', 'vendor'],
      chunksSortMode: 'dependency'
    }),




     public:    http://localhost:8989/dynamic#/
     local: http://localhost:8080/security.html#/listsearch