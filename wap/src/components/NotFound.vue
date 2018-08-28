<template>
    <div class="success-page">
        <icon type="info" is-msg class="success-ico"></icon>
        <h4 class="tit">未找到该页面</h4>
        <p class="desc">错误代码 404</p>
        <div class="success-group">
          <button type="button" class="theme_btn" @click="back">返回</button>
        </div>
    </div>
</template>

<script>
import { Icon, XButton } from 'vux'
export default {
    name: 'NotFound',
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

.success-page {
    padding-top: 70px;
    text-align: center;
    .success-ico {
        margin-bottom: 26px;
        color: @theme;
        font-size: 60px;
    }
    .tit {
        font-weight: 400;
        font-size: 18px;
        margin-bottom: 5px;
    }
    .desc {
        font-size: 14px;
        color: #999;
        margin-bottom: 30px;
    }
    .success-group {
        width: 50%;
        margin: 0 auto 90px;
    }
    .minlogo {
        margin-bottom: 30px;
        &.bottom {
          position: absolute;
          left: 50%;
          margin-left: -50px;
          bottom: 20px;
        }
    }
}
</style>
