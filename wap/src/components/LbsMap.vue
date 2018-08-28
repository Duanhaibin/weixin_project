<template>
    <div class="fixed">
      <iframe :src="url" id="iframe" class="iframe"></iframe>
    </div>
</template>

<script>
import { throttle, queryString } from '../mixin/index'

export default {
    props: [ 'start' ],
    data() {
      return {
        url: '',
        loc: ''
      }
    },
    mounted() {
      var url = 'https://m.amap.com/picker/?key=dc04c3e3308bf1336bcc6192ef756cfc&amp;zoom=14&keywords=景区,写字楼';
      this.loc = queryString().loc;

      if (this.loc) {
        url = url + '&center=' + this.loc
      }

      this.url = url;
      this.$nextTick(() => this.restart());
    },
    watch: {
      start() {
        this.$nextTick(() => this.restart());
      }
    },
    methods: {
      restart() {
        var _this = this;
        var iframe = document.getElementById('iframe').contentWindow;

        document.getElementById('iframe').onload = function(){
          iframe.postMessage('hello','https://m.amap.com/picker/');
        };

        window.addEventListener("message", function(e){
          var location = e.data.location
          if (location) {
            throttle(() => getlocation(e.data))
          }
        }, false);

        var geocoder;
        AMap.service('AMap.Geocoder',function(){
          geocoder = new AMap.Geocoder();
        })

        function getlocation(data) {
          var lnglatXY = data.location.split(',')
          geocoder.getAddress(lnglatXY, function(status, result) {
              if (status === 'complete' && result.info === 'OK') {
                var regeocode = result.regeocode;
                // 提交地址
                _this.$emit('address', {
                  address: data.address,
                  province: regeocode.addressComponent.province,
                  city: regeocode.addressComponent.city
                });
              }else{
                _this.$emit('address', {})
                _this.$vux.toast.show({
                  type: 'cancel',
                  text: '获取地址失败, 请重试'
                })
              }
          });
        }

      }
    }
}
</script>

<style lang="less">
@import '../style/mixin.less';

.iframe {
  border: 0;
  width: 100%;
  height: 100%;
}
</style>
