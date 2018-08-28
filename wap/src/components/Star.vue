<template>
    <div class="star">
      <div class="star_inner">

        <div class="item" v-for="(item, $index) in starlist" :class="item" @click="updateNum($index + 1)">
          <i class="iconfont icon-favorfill"  :style="{
            'font-size': !sel ? '14px' : '23px',
            'margin': !sel ? '0 .06em': '0 .22em',
          }"></i>
        </div>

        <span class="key pull-right" v-if="k">{{value}} 分</span>
      </div>
    </div>
</template>

<script>
export default {
    name: 'star',
    props: ['num', 'size', 'value', 'k', 'sel'],
    data() {
      return {
        starlist: [],
        vvalue: 0
      }
    },
    created() {
      this.init();
    },
    watch: {
      value(v) {
        this.vvalue = v;
      },
      vvalue() {
        this.init();
      }
    },
    methods: {
      init() {
        var num = this.vvalue = this.num || this.value || 0;
        this.starlist = [];

        for (var i = 0; i < 5; i++) {
          if (num >= 0.7) {
            this.starlist.push('s1')
          }else if (num > 0.2 && num < 0.7) {
            this.starlist.push('s2')
          } else {
            this.starlist.push('')
          }
          num--
        }

      },

      updateNum(num) {
        this.vvalue = num;
        this.$emit('input', num);
      }
    }
}
</script>

<style lang="less">
@import '../style/mixin.less';

.star {
  vertical-align: middle;
  .item {
    display: inline-block;
    color: #ddd;

    &.s1 {
      color: #ff6301
    }
    &.s2 {
      i {
        display: block;
        position: relative;
      }
      i::after {
        display: block;
        content: "\E64B";
        position: absolute;
        left: 0;
        top: 0;
        color: #ff6301;
        width: 50%;
        overflow: hidden;
      }
    }
  }
  .key {
    line-height: 36px;
  }
}
</style>
