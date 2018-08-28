<template>
  <div class="page success-page">
      <icon type="success" is-msg class="success-ico"></icon>
      <h4 class="tit">保存成功</h4>
      <p class="desc">您的入驻申请已提交，请耐心等待</p>
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
