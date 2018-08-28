<template>
  <div>
    <msg :title="('提交成功')" :description="('您录入的内容已经提交成功， 等待审核后即可发布， 敬请关注')"  :icon="icon"></msg>
  </div>
</template>



<script>
import { Msg, Divider, XButton } from 'vux'

export default {
  components: {
    Msg,
    Divider,
    XButton
  },
  methods: {
    changeIcon () {
      if (!this.icon || this.icon === 'success') {
        this.icon = 'warn'
        return
      }
      if (this.icon === 'warn') {
        this.icon = 'info'
        return
      }
      if (this.icon === 'info') {
        this.icon = 'waiting'
        return
      }
      if (this.icon === 'waiting') {
        this.icon = 'success'
      }
    }
  },
  data () {
    return {
      description: 'msg description',
      icon: '',
      buttons: [{
        type: 'primary',
        text: 'Primary button',
        onClick: this.changeIcon.bind(this)
      }, {
        type: 'default',
        text: 'Secondary button',
        link: '/demo'
      }]
    }
  }
}
</script>
<style lang='less'>
#app .weui-msg{
  padding-top: 50%;
  .weui-msg__desc{
    line-height: .5rem;

  }
}
</style>
