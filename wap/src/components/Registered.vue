<template>
    <div class="page">
      <div class="reg-title vux-1px-b">商家申请入驻</div>
      <group gutter="10px">
        <selector class="put_item_select" title="商家分类" :options="typeList" v-model.number="form.type"></selector>
        <x-input class="put_item" title="统一信用代码" placeholder="请输入统一信用代码" v-model.trim="form.business_license"></x-input>
        <x-input class="put_item" title="商家名称" placeholder="请输入商家营业执照上全称" v-model.trim="form.name"></x-input>
        <x-input type="tel" class="put_item" title="商家电话" placeholder="请输入商家电话" v-model.trim="form.tel"></x-input>
        <x-input class="put_item" title="营业地址" placeholder="请输入营业地址" v-model.trim="form.address"></x-input>
        <x-input class="put_item" title="营业时间" placeholder="请输入时间" v-model.trim="form.business_hours"></x-input>
        <x-textarea class="put_item" title="商家简介" placeholder="请输入商家简介" v-model.trim="form.introduce"></x-textarea>
        <x-input type="tel" class="put_item" title="管理员手机号" placeholder="管理员手机号,审核需联系" v-model.trim="form.phone"></x-input>
        <x-input class="put_item" title="管理员姓名" placeholder="管理员姓名，审核需联系" v-model.trim="form.shop_keeper"></x-input>
        <cell-box class="file_box_item">
          <upload-files name="店铺全景照" :files="files_1_cate" @change="files => this.files_1 = files" :count="1" business="1"></upload-files>
          <upload-files name="营业执照" :files="files_2_cate" @change="files => this.files_2 = files" :count="3" business="5"></upload-files>
          <upload-files name="其他图片" :files="files_3_cate" @change="files => this.files_3 = files" :count="6" business="4"></upload-files>
          <span class="min_tip">注：上传对应图片有利于信息审核，请按照要求上传信息。</span>
        </cell-box>
      </group>
      <div class="btn-group">
        <button type="button" class="theme_btn" @click="save" :disabled="disabled">提交</button>
      </div>

    </div>
</template>

<script>
import {
  Cell,
  Group,
  Selector,
  XInput,
  XTextarea,
  CellBox
} from 'vux'
import UploadFiles from './UploadFiles'
import NProgress from 'NProgress'

export default {
    name: 'Registered',
    components: {
        Cell,
        Group,
        Selector,
        XInput,
        XTextarea,
        CellBox,
        UploadFiles
    },
    data() {
      return {
        disabled: false,
        defaultForm: {},

        typeList: [
          {key: 0, value: '请选择分类'},
          {key: 1, value: '旅行社'},
          {key: 2, value: '旅游住宿'},
          {key: 3, value: '旅游景区'},
          {key: 4, value: '旅游购物'},
          {key: 5, value: '旅游餐饮'},
          {key: 6, value: '旅游交通'}
        ],

        res_id: '',

        files_1: [],
        files_2: [],
        files_3: [],

        files_1_cate: [],
        files_2_cate: [],
        files_3_cate: [],
        form: {
          name: '',
          type: 0,
          address: '',
          business_hours: '',
          shop_keeper: '',
          phone: '',
          tel: '',
          introduce: '',
          business_license: ''
        }
      }
    },
    created() {
      this.defaultForm = JSON.parse(JSON.stringify(this.form));
    },
    methods: {
      save() {
        // 验证
        if (!this.form.type) {
          this.$vux.toast.show({
            type: 'cancel',
            text: '商家分类不能为空'
          })
          return
        }

        if (!this.form.business_license) {
          this.$vux.toast.show({
            type: 'cancel',
            text: '统一信用代码不能为空'
          })
          return
        }

        if (!this.form.name) {
          this.$vux.toast.show({
            type: 'cancel',
            text: '商家名称不能为空'
          })
          return
        }

        if (!/^\d+$/.test(this.form.tel)) {
          this.$vux.toast.show({
            type: 'cancel',
            text: '请填写正确的商家电话'
          })
          return
        }

        if (!this.form.address) {
          this.$vux.toast.show({
            type: 'cancel',
            text: '营业地址不能为空'
          })
          return
        }

        if (!this.form.business_hours) {
          this.$vux.toast.show({
            type: 'cancel',
            text: '营业时间不能为空'
          })
          return
        }

        if (!this.form.introduce) {
          this.$vux.toast.show({
            type: 'cancel',
            text: '商家简介不能为空'
          })
          return
        }

        if (!/^1[3-9]\d{9}$/.test(this.form.phone)) {
          this.$vux.toast.show({
            type: 'cancel',
            text: '请填写正确的管理员手机号'
          })
          return
        }

        if (!this.form.shop_keeper) {
          this.$vux.toast.show({
            type: 'cancel',
            text: '管理员姓名不能为空'
          })
          return
        }

        this.disabled = true;
        NProgress.start();
        this.$http.post(this.$con.api('starapi/newBusiness'), this.form, {timeout: 20000}).then(result => {
          NProgress.done();
          if (result.body.errorcode != '0000') {
            this.$vux.toast.show({
              type: 'cancel',
              text: result.body.errormsg
            })
            this.disabled = false;
            return;
          }

          this.res_id = result.body.data.id;
          this.saveFiles()
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
       * 保存 图片
       * @return {[type]} [description]
       */
      saveFiles() {
        var len = 0, count = 0, files = [];

        files = files.concat(
          this.files_1.map(url => {
            return {
              source_url: url,
              id: this.res_id,
              type: 1
            }
          }),
          this.files_2.map(url => {
            return {
              source_url: url,
              id: this.res_id,
              type: 5
            }
          }),
          this.files_3.map(url => {
            return {
              source_url: url,
              id: this.res_id,
              type: 4
            }
          })
        );

        len = files.length;
        if (!len) {
          this.form = JSON.parse(JSON.stringify(this.defaultForm));
          this.disabled = false;
          this.$router.push('/regsuccess');
          return
        }

        for (var i in files) {
          NProgress.start();
          this.$http.post(this.$con.api('starapi/addBusinessFiles'), files[i], {timeout: 20000}).then(result => {
            NProgress.done();
            if (result.body.errorcode != '0000') {
              this.$vux.toast.show({
                type: 'cancel',
                text: result.body.errormsg
              })
              return;
            }

            count++;

            if (count >= len) {
              this.form = JSON.parse(JSON.stringify(this.defaultForm));
              this.disabled = false;
              this.files_1_cate = [];
              this.files_2_cate = [];
              this.files_3_cate = [];
              this.$router.push('/regsuccess');
            }
          }, error => {
            NProgress.done();
            this.disabled = false;
            console.log(error);
            this.$vux.toast.show({
              type: 'cancel',
              text: '连接服务器发生错误, 请检查网络或重试'
            })
          })
        }

      }

    }
}
</script>

<style lang="less">
@import '../style/mixin.less';

.reg-title {
  background: #fff;
  padding: 10px 12px;
  font-size: 17px;
}

.put_item_select {
  .weui-select {
    height: 48px;
    line-height: 48px;
  }
}

.put_item {
  padding: 12px 15px !important;
}
.file_box_item {
  display: block !important;
  .files-upload-hook {
    margin-bottom: 8px;
    &:last-child {
      margin-bottom: 0;
    }
  }
}

.min_tip {
  font-size: 12px;
  color: red;
}
.btn-group {
  padding: 12px;
}
</style>
