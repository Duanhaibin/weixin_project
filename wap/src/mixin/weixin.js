/**
 * weixin
 */

window.share_config = {
  "imgUrl": SITE_URL + '/Public/static/wap/static/img/banner.jpg',
  "desc": '长白山放心游邀您来评价',
  "title": '长白山放心游'
};


window.shareHandle = (url) => {
  wx.config({
    debug: false,
    appId: WX_APPID,
    timestamp: WXJS_TIMESTAMP,
    nonceStr: NONCESTR,
    signature: SIGNATURE,
    jsApiList: [
      'checkJsApi',
      'onMenuShareTimeline',
      'onMenuShareAppMessage',
      'onMenuShareQQ',
      'onMenuShareWeibo',
      'onMenuShareQZone',
      'hideMenuItems',
      'showMenuItems',
      'hideAllNonBaseMenuItem',
      'showAllNonBaseMenuItem',
      'translateVoice',
      'startRecord',
      'stopRecord',
      'onVoiceRecordEnd',
      'playVoice',
      'onVoicePlayEnd',
      'pauseVoice',
      'stopVoice',
      'uploadVoice',
      'downloadVoice',
      'chooseImage',
      'previewImage',
      'uploadImage',
      'downloadImage',
      'getNetworkType',
      'openLocation',
      'getLocation',
      'hideOptionMenu',
      'showOptionMenu',
      'closeWindow',
      'scanQRCode',
      'chooseWXPay',
      'openProductSpecificView',
      'addCard',
      'chooseCard',
      'openCard'
    ]
  });

  var cfg = Object.assign({}, share_config, {"link": url || location.href})
  wx.onMenuShareAppMessage(cfg);
  wx.onMenuShareTimeline(cfg);
  wx.onMenuShareQQ(cfg);
}


wx.ready(function(){
  window.shareHandle();
});
