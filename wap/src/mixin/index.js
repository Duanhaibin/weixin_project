

export function saveSession(key, value) {
    var x = window.sessionStorage._12301_
    !x ? (x = {}) : (x = JSON.parse(x));
    x[key] = value;
    sessionStorage._12301_ = JSON.stringify(x);
}

export function fetchSesstion(key, def) {
    def = def === undefined ? {} : def;
    var x = window.sessionStorage._12301_
    if (!x) return def;
    x = JSON.parse(x);
    if (!key) return x;
    return x[key] || def;
}

/**
 * 获取url信息
 * @return {obj} url字段
 */
export function queryString() {
  let data = {};
  try {
    location.search.split('?')[1].replace(/#\/.*/, '').split('&').forEach(t => {
      let a = t.split('=');
      data[a[0]] = a[1]
    })
  } catch (err) {}
  return data
}


/**
 * dataURL转换为Blob对象
 * @return {Blob}
 */
export function dataURLtoBlob(dataurl) {
  var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
      bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
  while(n--){
      u8arr[n] = bstr.charCodeAt(n);
  }
  return new Blob([u8arr], {type:mime});
}


let timer;
/**
 * 截流
 * @param  {Function} fn          [description]
 * @param  {Number}   [delay=100] [description]
 * @param  {[type]}   ctx         [description]
 * @param  {[type]}   arg         [description]
 * @return {[type]}               [description]
 */
export function throttle(fn, delay = 100, ctx, arg) {
    clearTimeout(timer)
    timer = setTimeout(() => fn.call(ctx, arg), delay)
}


/**
 * time
 * @param  {[type]} date [description]
 * @param  {[type]} fmt  [description]
 * @return {[type]}      [description]
 */
export function formatDate(date, fmt) {
  if (/(y+)/.test(fmt)) {
    fmt = fmt.replace(RegExp.$1, (date.getFullYear() + '').substr(4 - RegExp.$1.length));
  }
  let o = {
    'M+': date.getMonth() + 1,
    'd+': date.getDate(),
    'h+': date.getHours(),
    'm+': date.getMinutes(),
    's+': date.getSeconds()
  }
  for (let k in o) {
    if (new RegExp(`(${k})`).test(fmt)) {
      let str = o[k] + '';
      fmt = fmt.replace(RegExp.$1, (RegExp.$1.length === 1) ? str : padleftzero(str));
    }
  }
  return fmt;
};

function padleftzero(str) {
  return ('00' + str).substr(str.length);
}
