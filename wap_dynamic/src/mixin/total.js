/**
 * Created by Toma on 2017/9/24.
 */
 import { dateFormat } from 'vux'

const install = (Vue, options) => {
  // 全局事件管理器
  Vue.prototype.$hub = new Vue();

  // 注册全局常量
  Vue.prototype.$con = {
    api: function(url) {
      return AJAXURL + url
    },
    sex: {
      1: '男',
      2: '女'
    },
    today: dateFormat(new Date(), 'YYYY-MM-DD'),
    
  }

}


export default { install }
