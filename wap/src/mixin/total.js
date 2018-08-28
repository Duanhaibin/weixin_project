/**
 * Created by Toma on 2017/9/24.
 */
import { dateFormat } from 'vux'

const install = (Vue, options) => {
  // 全局事件管理器
  Vue.prototype.$hub = new Vue();

  // 注册全局常量
  Vue.prototype.$con = {
    sex: {
      1: '男',
      2: '女'
    },
    today: dateFormat(new Date(), 'YYYY-MM-DD'),

    go(url) {
      window.location.href = url;
    },

    api(url) {
      return AJAXURL + url
    },

    imgrep(e) {
      var src = '/Public/static/wap/static/img/cbs.jpg';;
      if (process.env.NODE_ENV === 'development') {
        src = '/static/img/cbs.jpg';
      }

      e.target.src = src;
    }
  }

  Vue.filter('formate', value => {
    return dateFormat(new Date(value), 'YYYY-MM-DD hh:mm:ss')
  })

}

export default { install }
