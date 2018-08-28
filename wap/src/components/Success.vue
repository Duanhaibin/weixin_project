<template>
  <div class="page success-page">
      <icon type="success" is-msg class="success-ico"></icon>
      <h4 class="tit">评价成功</h4>
      <p class="desc">您的评价是其他用户的重要参考！</p>
      <div class="success-group">
        <button type="button" class="theme_btn" @click="back">返回</button>
      </div>
  </div>
</template>

<script>
import { Icon, XButton } from 'vux'
export default {
    name: 'Success',
    components: {
        Icon
    },
    methods: {
      back() {
        history.back();
      }
    }
}
</script>

<style lang="less">
@import '../style/mixin.less';


</style>
