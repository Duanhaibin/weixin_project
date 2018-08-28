/* ! @copyright 12301 @author toma */
import Vue from 'vue'
import FastClick from 'fastclick'
import App from './App'
import router from './router'
import store from './store'
import VueResouse from 'vue-resource'

import 'NProgress/nprogress.css'
import './mixin/weixin'

import {fetchSesstion, saveSession, queryString} from './mixin/index'
import Total from './mixin/total'
import {ConfirmPlugin, ToastPlugin, LoadingPlugin} from 'vux'
require('es6-promise').polyfill()

Vue.use(ConfirmPlugin)
Vue.use(ToastPlugin)
Vue.use(LoadingPlugin)
Vue.use(VueResouse)
Vue.use(Total)

Vue.http.options.emulateJSON = true;
Vue.config.productionTip = false
FastClick.attach(document.body)


let hisCount = 0;
let hisUrl = {};

router.beforeEach((to, from, next) => {
  if (queryString().u === 'registered') {
    history.replaceState('','', location.href.replace('&u=registered', ''));
    next('/registered');
    return
  }

  // 隐藏标题栏
  if (to.meta.nonav) {
    store.commit('updateNavState', false)
  } else {
    store.commit('updateNavState', true)
  }

  // 动画切换
  const toIndex = hisUrl[to.path]
  const fromIndex = hisUrl[from.path]
  if (toIndex) {
      if (!fromIndex || toIndex > fromIndex) {
        store.commit('updateDirection', 'forward')
      } else {
        store.commit('updateDirection', 'reverse')
      }
  } else {
      ++hisCount;
      hisUrl[to.path] = hisCount;
      store.commit('updateDirection', 'forward')
  }

  next();
});


var ob = new Vue({
  router,
  store,
  methods: {
    go(url) {
      this.$router.push(url);
    }
  },
  render: h => h(App)
}).$mount('#app-box')

/**
 * 页面加载完成
 */
window.onload = function() {
  var loadingbox = document.getElementById('loading')
  loadingbox.classList.add('fadeOut');
  setTimeout(() => loadingbox.classList.add('hide'), 350)

  window.shareHandle();
}
