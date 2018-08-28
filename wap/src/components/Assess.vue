<template>
    <div class="page page_assess">
      <div class="assess_header theme vux-1px-b">
        商户诚信评星调研
      </div>

      <div class="content_header vux-1px-tb">
        综合评分
      </div>
      <emoji class="mb" v-model="form.overall_score"></emoji>

      <div class="content_header vux-1px-tb">
        各项评分
      </div>
      <div class="pf_box mb vux-1px-b">
        <div class="pf_item">
          <div class="table-show">
            <span class="cell txt">{{score_1_name}}</span>
            <star class="cell selstar" v-model="form.score_1" @input="setOverall" k="1" sel="1"></star>
          </div>
        </div>
        <div class="pf_item">
          <div class="table-show">
            <span class="cell txt">{{score_2_name}}</span>
            <star class="cell selstar" v-model="form.score_2" @input="setOverall" k="1" sel="1"></star>
          </div>
        </div>
        <div class="pf_item">
          <div class="table-show">
            <span class="cell txt">{{score_3_name}}</span>
            <star class="cell selstar" v-model="form.score_3" @input="setOverall" k="1" sel="1"></star>
          </div>
        </div>
      </div>

      <div class="common_box vux-1px-tb mb">
        <x-textarea class="textarea" autosize v-model.trim="form.comment"
          :rows="4" placeholder="输入您的评价内容，如商户服务如何？设施是否存在安全隐患？"></x-textarea>
      </div>

      <group class="a_group mb" gutter="0">
        <x-input title="消费金额" label-width="80px" type="number" placeholder="请输入消费金额" v-model="form.price"></x-input>
        <x-input title="手机号" label-width="80px" type="tel" placeholder="请输入您的手机号" v-model="form.phone"></x-input>
      </group>

      <div class="imglist_box vux-1px-tb mb">
        <upload-files ref="upfiles" :files="files" @change="files => this.form.files = files" :count="9"></upload-files>
        <span class="tips">上传发票或收据等图片，提高评价准确性</span>
      </div>

      <div class="btn_group">
        <button type="button" class="theme_btn" @click="save" :disabled="disabled">提交</button>
      </div>

    </div>
</template>

<script>
import Emoji from './Emoji.vue'
import Star from './Star.vue'
import UploadFiles from './UploadFiles.vue'
import NProgress from 'NProgress'
import {
  XTextarea,
  Group,
  XInput
} from 'vux'

export default {
    name: 'Assess',
    components: {
      Emoji,
      Star,
      UploadFiles,
      XTextarea,
      Group,
      XInput
    },
    data() {
      return {
        disabled: false,
        files: [],
        score_1_name: '产品性价',
        score_2_name: '服务水平',
        score_3_name: '配套设施',
        form: {
          id: '',
          overall_score: 0,
          score_1: 0,
          score_2: 0,
          score_3: 0,
          comment: '',
          price: '',
          phone: '',
          files: [],
          headimgurl: HEADIMG,
          nickname: NICKNAME,
          openid: OPENID
        }
      }
    },
    created() {
      this.start()
    },
    watch: {
      $route() {
        this.start();
      }
    },
    methods: {
      start() {
        document.documentElement.scrollTop = 0;
        document.body.scrollTop = 0;
        var id = this.$route.params.id;
        id && (this.form.id = id);
        var type = this.$route.query.type;
        this.getScoreName(type);
      },

      /**
       * 保存
       * @return {[type]} [description]
       */
      save(e) {
        // 验证
        if (!this.form.score_1 || !this.form.score_2 || !this.form.score_3) {
          this.$vux.toast.show({
            type: 'cancel',
            text: '各项评分不能为空'
          })
          return
        }

        if (!this.form.comment) {
          this.$vux.toast.show({
            type: 'cancel',
            text: '评价不能为空'
          })
          return
        }

        if (this.form.price === '') {
          this.$vux.toast.show({
            type: 'cancel',
            text: '消费金额不能为空'
          })
          return
        }

        if (!/^1[3-9]\d{9}$/.test(this.form.phone)) {
          this.$vux.toast.show({
            type: 'cancel',
            text: '手机号不正确'
          })
          return
        }

        this.disabled = true;
        NProgress.start();
        this.$http.post(this.$con.api('starapi/setScoreComment'), this.form, {timeout: 20000}).then(result => {
          this.disabled = false;
          NProgress.done();
          if (result.body.errorcode != '0000') {
            this.$vux.toast.show({
              type: 'cancel',
              text: result.body.errormsg
            })
            return;
          }

          this.$router.replace('/success');

        }, error => {
          NProgress.done();
          this.disabled = false;
          console.log(error);
          this.$vux.toast.show({
            type: 'cancel',
            text: '连接服务器发生错误, 请检查网络或重试'
          })
        })
      },

      /**
       * 获取评星名称
       * @param  {[type]} type [description]
       * @return {[type]}      [description]
       */
      getScoreName(type) {
        this.$http.post(this.$con.api('starapi/getScoreName'), { type }, {timeout: 20000}).then(result => {

          if (result.body.errorcode != '0000') {
            this.$vux.toast.show({
              type: 'cancel',
              text: result.body.errormsg
            })
            return;
          }

          var data = result.body.data.data;
          this.score_1_name = data.score_1_name;
          this.score_2_name = data.score_2_name;
          this.score_3_name = data.score_3_name;

        }, error => {
          console.log(error);
          this.$vux.toast.show({
            type: 'cancel',
            text: '连接服务器发生错误, 请检查网络或重试'
          })
        })
      },

      /**
       * 获取平均值
       */
      setOverall() {
        if (!this.form.score_1 || !this.form.score_2 || !this.form.score_3) {
          return
        }

        this.form.overall_score = Math.round((this.form.score_1 + this.form.score_2 + this.form.score_3)/3);
      }

    }
}
</script>

<style lang="less">
@import '../style/mixin.less';

.assess_header {
  text-align: center;
  height: 44px;
  line-height: 44px;
  font-size: 16px;
  background: #fff;
  margin-bottom: 12px;
}

.mb {
  margin-bottom: 12px;
}

.pf_box {
  background: #fff;
  padding: 12px;

  .pf_item {
    margin-bottom: 6px;
    &:last-child {
      margin: 0;
    }
    .txt {
      white-space: nowrap;
      line-height: 36px;
      padding-right: 18px;
    }
    .selstar {
      width: 100%;
    }
  }
}

.common_box {
  background: #fff;
  .weui-cell {
     padding: 10px 12px;
  }
  textarea {
    font-size: 16px;
  }
}

.a_group {
  .weui-cells {
    font-size: 16px !important;
  }
  .weui-cells:before,
  .weui-cell:before,
  .weui-cells:after {
    border-color: #eaeaea !important;
  }
}

.imglist_box {
  position: relative;
  background: #fff;
  padding: 12px 12px 8px 12px;

  .tips {
    display: block;
    font-size: 12px;
    color: #888;
    padding-top: 8px;
  }
}

.btn_group {
  padding: 0 12px;
  margin-bottom: 30px;
}

.theme_btn {
  display: block;
  width: 100%;
  background: @theme;
  color: #fff;
  font-size: 17px;
  border: none;
  border-radius: 3px;
  height: 44px;
  outline: none;
  &:active {
    background: #0365a5;
  }
}
</style>
