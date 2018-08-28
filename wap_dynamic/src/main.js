// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'

import store from './store'
import router from './router'
import FastClick from 'fastclick'
import VueResouse from 'vue-resource'
import './mixin/weixin'
import Total from './mixin/total'




require('es6-promise').polyfill()
Vue.use(VueResouse)
Vue.use(Total)




Vue.http.options.emulateJSON = true;
FastClick.attach(document.body)
Vue.config.productionTip = false

router.beforeEach((to, from, next,d) => {
  if (to.meta.title) {
    document.title = to.meta.title
  }
  next()
});



/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  components: { App },
  template: '<App/>'
})


window.onload = function() {
  window.weixinInit()
};