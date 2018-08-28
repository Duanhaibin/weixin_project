<template>
  <div class="files-upload-hook">
    <div class="files-upload">
        <div class="file-item" v-for="(item, $index) in catchFiles"
          :class="classlist(item)" @click="showImage(item)"
          :key="$index">
          <div class="img" v-if="item.isImg" :style="'background-image:url('+ item.src +')'"></div>
          <a href="javascript:" class="filename" v-else>{{item.filename}}</a>
          <i v-if="!disabled" class="delete" @click.stop="remove(item)">&times;</i>
          <inline-loading class="loading-ico"></inline-loading>
        </div>
        <label v-if="!disabled" v-show="catchFiles.length < count" class="file-btn">
          <span class="up_name">{{name || ''}}</span>
          <input type="file" multiple @change="change($event)" accept="image/*">
        </label>
        <toast v-model="errorPop" type="cancel">{{errorText}}</toast>
    </div>

    <div v-transfer-dom>
      <previewer :list="imglist" ref="previewer"></previewer>
    </div>
  </div>
</template>

<script>
import { Toast, InlineLoading, Previewer, TransferDom } from 'vux'
import NProgress from 'NProgress'

// 创建文件
function createObjectURL(blob, callback) {
  var reader = new FileReader();
  reader.readAsDataURL(blob);

  reader.onload = function () {
    var result = reader.result;
    callback(result)
  };
}

export default {
    directives: {
      TransferDom
    },
    components: {
      Toast,
      InlineLoading,
      Previewer
    },
    /**
     * count 允许上传的个数
     */
    props: ['files', 'disabled', 'count', 'name', 'business'],
    data() {
        return {
            catchFiles: this.files || [],
            errorPop: false,
            errorText: '',
            len: 0,
            btn: ''
        }
    },
    computed: {
      imglist() {
        var _count = 0;
        return this.catchFiles.filter((t, i) => {
          if (t.isImg) {
            t.__index = _count;
            _count++
          }
          return t.isImg
        })
      }
    },
    watch: {
      '$route': 'close',
      files(v) {
        this.catchFiles = v
      }
    },
    methods: {
      /**
       * 添加文件
       * @param event
       */
      change(event) {
        var filelist = event.target.files;
        this.btn = event.target;
        this.len = filelist.length;

        // 文件数量限制 count
        if (this.catchFiles.length + this.len > (this.count || 100)) {
          this.btn.value = '';
          this.$vux.toast.show({
            type: 'cancel',
            text: '累计上传数量应控制在' + this.count + '个以内'
          })
          return
        }

        NProgress.start();
        [].forEach.call(filelist, file => {
          // 如果文件大于 50mb 跳过
          if (file.size > 51200000) {
            this.$vux.toast.show({
              type: 'cancel',
              text: '文件最大不能超过50MB'
            })
            return
          }

          var cate = {
            isImg: false,
            isLoad: true,
            src: '',
            filename: '',
            url: ''
          };

          if (file.type.indexOf('image') > -1) {
              cate.isImg = true;
              createObjectURL(file, src => cate.src = src);
          }

          cate.filename = file.name;

          this.catchFiles.push(cate)
          this.subFile(file, cate);
        });
      },

      /**
       * 上传文件
       * @param file
       * @param cate
       */
      subFile(file, cate) {

        var _this = this;
        var data = new FormData();
        var xhr = new XMLHttpRequest();
        data.append('file', file);
        data.append('token', "oh, it's ok");

        xhr.open('post', _this.$con.api('upload'), true);
        xhr.onreadystatechange = function () {
          if (xhr.readyState == 4 && xhr.status >= 200) {

            var result = JSON.parse(xhr.responseText);
            var id = '';
            for (var key in result.tmpFileIds) {
              id = result.tmpFileIds[key].id
            }

            // 生成服务器ID
            _this.$http.post(_this.$con.api('upload/save'), {files: [id]}).then(res => {
              // 如果是商家申请入驻
              if (_this.business) {
                cate.relUrl = res.body.urls[0];

                cate.isLoad = false;
                _this.update();
                _this.len--;

                // 最后一个
                if (!_this.len) {
                  NProgress.done();
                  _this.btn.value = '';
                }

                return
              }

              _this.$http.post(_this.$con.api('starapi/addScoreFiles'), {source_url: res.body.urls[0] }).then(addres => {

                cate.relUrl = addres.body.data.id

                cate.isLoad = false;
                _this.update();
                _this.len--;

                // 最后一个
                if (!_this.len) {
                  NProgress.done();
                  _this.btn.value = '';
                }
              });
            });

          }
        };

        xhr.send(data);
      },

      /**
       * 移除文件
       */
      remove(item) {
        for (let i in this.catchFiles) {
          if (this.catchFiles[i] == item) {
            this.catchFiles.splice(i, 1);
            this.update()

            return
          }
        }
      },

      /**
       * 更新文件
       */
      update() {
        this.$emit('change', this.catchFiles.map(t => {
          return t.relUrl;
        }))
      },

      /**
       * 图片浏览器
       * @param index
       */
      show(index) {
        if (index === undefined) {
          return
        }

        this.$refs.previewer.show(index)
      },

      showImage(item) {
        wx.previewImage({
            current: item.src,
            urls: this.catchFiles.map(t => t.src)
        });
      },

      /**
       * 关闭图片播放器
       * @return {[type]} [description]
       */
      close() {
        try {
          this.$refs.previewer.close()
        } catch (err) {

        }
      },

      /**
       * 样式控制
       * @param item
       */
      classlist(item) {
        var list = '';
        item.isLoad && (list += 'load ');
        !item.isImg && (list += 'file');
        return list
      }

    }
}
</script>

<style lang="less">
@import '../style/mixin.less';


.files-upload {
  font-size: 0;
  overflow: hidden;
}
.file-item {
  position: relative;
  display: inline-block;
  vertical-align: top;
  width: 75px;
  height: 75px;
  border: 1px solid #ddd;
  border-radius: 2px;
  box-sizing: border-box;
  margin: 0 5px 5px 0;
  text-align: center;
  overflow: hidden;

  .img {
    position: absolute;
    width: 100%;
    height: 100%;
    -webkit-background-size: cover;
    background-size: cover;
  }

  &.load {
    .img, .filename {
      opacity: .2;
    }
    .loading-ico {
      display: block;
    }
  }
  &.file {
    line-height: 1.4;
    font-size: 12px;
    padding: 5px;
    .filename {
      display: block;
      text-align: left;
      width: 100%;
      height: 100%;
      color: #333;
    }
  }

  .delete {
    position: absolute;
    display: block;
    top: 0;
    right: 0;
    width: 22px;
    height: 22px;
    line-height: 20px;
    background: rgba(0,0,0,.7);
    color:#fff;
    font-size: 16px;
    z-index: 10;
    font-style: normal;
  }
  .loading-ico {
    display: none;
    position: absolute;
    top: 50%;
    left: 50%;
    margin-top: -10px;
    margin-left: -10px;
    z-index: 20;
  }
}
.file-btn {
  position: relative;
  display: inline-block;
  width: 75px;
  height: 75px;
  border: 1px solid #ddd;
  border-radius: 2px;
  box-sizing: border-box;
  overflow: hidden;

  &::before {
    content: "";
    display: block;
    position: absolute;
    top: 50%;
    left: 50%;
    margin-top: -1px;
    margin-left: -25%;
    width: 50%;
    height: 1px;
    background: #ddd;
    border-radius: 2px;
  }
  &::after {
    content: "";
    display: block;
    position: absolute;
    top: 50%;
    left: 50%;
    margin-left: -1px;
    margin-top: -25%;
    width: 1px;
    height: 50%;
    background: #ddd;
    border-radius: 2px;
  }
  input {
    opacity: 0;
    width: 100%;
    height: 100%;
  }
}

.up_name {
  position: absolute;
  display: block;
  width: 100%;
  font-size: 12px;
  text-align: center;
  color: #888;
  top: 55px;
}
</style>
