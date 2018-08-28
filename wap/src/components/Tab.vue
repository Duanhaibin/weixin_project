<template>
    <div class="s_tab ">
      <div class="s_tab_item" v-for="(item, $index) in category" :style="{
          width: (100/category.length) + '%'
        }"
        @click="index = $index"
        :class="{'active': $index === index}">{{item}}</div>

        <div class="pop_line" :style="{
          position: 'absolute',
          left: (100/category.length)*index + '%',
          bottom: 0,
          width: (100/category.length) + '%',
        }"><div class="line"></div></div>
    </div>
</template>

<script>

export default {
    name: 'Tab',
    props: ['category', 'value'],
    data() {
      return {
        index: 0
      }
    },
    watch: {
      index(v) {
        this.$emit('input', v);
      },
      value(v) {
        this.index = v;
      }
    }
}
</script>

<style lang="less">
@import '../style/mixin.less';

.s_tab {
  font-size: 0;
  position: relative;

  &_item {
    display: inline-block;
    font-size: 14px;
    height: 44px;
    line-height: 44px;
    text-align: center;
    transition: all .2s ease;
    &:active {
      background: #f4f4f4;
    }
    &.active {
      color: @theme;
    }
  }
  .pop_line {
    transition: all .3s ease;
    .line {
      width: 50px;
      max-width: 100%;
      height: 2px;
      background: @theme;
      margin: 0 auto;
    }
  }
}
</style>
