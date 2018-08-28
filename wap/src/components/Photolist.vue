<template>
<div class="face page_photolist">

  <div class="figure" v-for="item in list" :key="item.id" @click="showImage(item)">
    <div class="imgbox" :style="{
      backgroundImage: 'url('+ item.source_url +')'
    }"></div>
  </div>

  <div v-if="!list.length" class="no-data">暂无商户图片</div>

</div>
</template>

<script>
import NProgress from 'NProgress'
import {
  LoadMore
} from 'vux'

export default {
  name: 'Photolist',
  components: {
    LoadMore,
  },
  data() {
    return {
      info: {},
      list: []
    }
  },
  created() {
    this.fetchData();
  },
  watch: {
    '$route': 'fetchData'
  },
  methods: {
    fetchData() {
      var id = this.$route.params.id;
      if (!id) return;

      NProgress.start();
      this.$http.post(this.$con.api('starapi/businessListFiles'), { id, type: 4 }, {timeout: 20000}).then(result => {

        NProgress.done();
        if (result.body.errorcode != '0000') {
          this.$vux.toast.show({
            type: 'cancel',
            text: result.body.errormsg
          })
          return;
        }

        this.list = result.body.data;

      }, error => {
        NProgress.done();
        console.log(error);
        this.$vux.toast.show({
          type: 'cancel',
          text: '连接服务器发生错误, 请检查网络或重试'
        })
      })

    },

    showImage(item) {
      wx.previewImage({
          current: item.source_url,
          urls: this.list.map(t => t.source_url)
      });
    }
  }
}
</script>

<style lang="less">
@import '../style/mixin.less';

.page_photolist {
  width: 100%;
  font-size: 0;
  padding: 12px 0 12px 12px;
  box-sizing: border-box;

  .figure {
    position: relative;
    display: inline-block;
    width: 50%;
    padding-right: 12px;
    box-sizing: border-box;
    margin-bottom: 12px;
  }
  .imgbox {
    width: 100%;
    padding-top: 86.0%;
    -webkit-background-size: cover;
    background-size: cover;
    background-position: center;
    background-color: #fff;
    border-radius: 3px;
    box-shadow: 0 3px 5px rgba(0,0,0,.1);
  }

  .no-data {
    font-size: 16px;
    margin-left: -12px;
  }
}
</style>
