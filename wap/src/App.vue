<template>
  <div id="app">
    <transition :name="'vux-pop-' + (direction === 'forward' ? 'in' : 'out')">
      <keep-alive :exclude="/^(Detail)|(Assess)|(Photolist)$/">
          <router-view></router-view>
      </keep-alive>
    </transition>

    <transition name="up">
      <div class="nav vux-1px-t" v-show="showNav">
        <div class="nav_warp">
          <router-link class="nav_link" to="/">
            <i class="iconfont icon-home"></i>
            <span>首页</span>
          </router-link>
          <a href="javascript:void(0);" class="nav_link" @click="qrcode()" >
            <i class="iconfont icon-scan"></i>
            <span>扫一扫</span>
          </a>
          <router-link class="nav_link" to="/search">
            <i class="iconfont icon-searchlist"></i>
            <span>商户检索</span>
          </router-link>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  name: 'app',
  computed: {
    showNav() {
      return this.$store.state.showNav
    },
    direction() {
      return  this.$store.state.direction
    }
  },
  methods: {
    qrcode() {
      wx.scanQRCode();
    }
  }
}
</script>

<style lang="less">
@import './style/index.less';
@import '~vux/src/styles/reset.less';
@import '~vux/src/styles/1px.less';

#app {
  min-height: 100%;
  overflow-x: hidden;
}

.vux-1px-t::before,
.vux-1px-tb::before,
.vux-1px-tb::after,
.vux-1px-r:after,
.vux-1px-b:after {
  border-color: #eaeaea !important;
}

.move-enter-active,
.move-leave-active {
  transition: all 0.2s ease-in-out;
}
.move-enter-active {
  transition-delay: 0.2s;
}
.move-enter,
.move-leave-to {
  opacity: 0;
  transform: translateX(40%);
}

.up-enter-active,
.up-leave-active {
  transition: all 0.2s ease-in-out;
}
.up-enter-active {
  transition-delay: 0.2s;
}
.up-enter,
.up-leave-to {
  opacity: 0;
  transform: translateY(40%);
}


.vux-pop-out-enter-active,
.vux-pop-out-leave-active,
.vux-pop-in-enter-active,
.vux-pop-in-leave-active {
  will-change: transform;
  transition: all .26s;
  height: 100%;
  width: 100%;
  position: absolute;
  backface-visibility: hidden;
  perspective: 1000;
}
.vux-pop-out-enter {
  opacity: 0;
  transform: translate3d(-100%, 0, 0);
}
.vux-pop-out-leave-active {
  opacity: 0;
  transform: translate3d(100%, 0, 0);
}
.vux-pop-in-enter {
  opacity: 0;
  transform: translate3d(100%, 0, 0);
}
.vux-pop-in-leave-active {
  opacity: 0;
  transform: translate3d(-100%, 0, 0);
}


.nav {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  background: rgba(255,255,255, .95);
  overflow: hidden;
  z-index: 200;

  &_warp {
    display: table;
    width: 100%;
    height: 48px;
  }

  &_link {
    display: table-cell;
    width: 33.3333%;
    text-align: center;
    padding-top: 5px;
    transition: all .2s ease;
    &:active {
      background: #eaeaea;
    }

    .iconfont {
      display: block;
      line-height: 22px;
      font-size: 22px;
      color: @theme;
      margin-bottom: -2px;
    }
    span {
      font-size: 12px;
      line-height: 1;
      color: rgba(51,51,51,.75);
    }
  }
}
</style>
