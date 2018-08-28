<template>
    <div class="assess_emoji">
      <div class="box vux-1px-b">

        <div class="item vux-1px-r" @click="pullset(1)" :class="{'active': num === 1}">
          <p class="tit">差</p>
          <i class="iconfont icon-frownface"></i>
        </div>
        <div class="item vux-1px-r" @click="pullset(2)" :class="{'active': num === 2}">
          <p class="tit">一般</p>
          <i class="iconfont icon-straighface"></i>
        </div>
        <div class="item vux-1px-r" @click="pullset(3)" :class="{'active': num === 3}">
          <p class="tit">还不错</p>
          <i class="iconfont icon-smileface"></i>
        </div>
        <div class="item vux-1px-r" @click="pullset(4)" :class="{'active': num === 4}">
          <p class="tit">很满意</p>
          <i class="iconfont icon-talkface"></i>
        </div>
        <div class="item" @click="pullset(5)" :class="{'active': num === 5}">
          <p class="tit">强烈推荐</p>
          <i class="iconfont icon-loveface"></i>
        </div>

      </div>
    </div>
</template>

<script>
export default {
    name: 'Emoji',
    props: [ 'value' ],
    data() {
      return {
        num: 0
      }
    },
    created() {
      this.num = this.value;
    },
    watch: {
      value(v) {
        this.update(v)
      }
    },
    methods: {
      update(v) {
        this.num = v;
        this.$emit('input', v);
      },
      pullset() {
        return
      }
    }
}
</script>

<style lang="less">
@import '../style/mixin.less';

.assess_emoji {
  .box {
    display: table;
    background: #fff;
    height: 80px;
    width: 100%;
  }
  .item {
    display: table-cell;
    width: 20%;
    text-align: center;
    padding-top: 10px;
    transition: all .2s ease;

    &:active {
      background: #eaeaea;
    }

    &.active {
      i {
        color: @theme
      }
    }

    .iconfont {
      font-size: 36px;
      line-height: 1.15;
      color: #ddd;
    }
    .tit {
      font-size: 12px;
    }
  }
}
</style>
