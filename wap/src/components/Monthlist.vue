<template>
    <div class="page">

      <div class="content_header vux-1px-tb">
        <span class="tip">优</span> {{name}}优质商家
      </div>
      <div class="content_body">

        <div class="pre vux-1px-b" @click="$con.go('#/s/' + item.id)" v-for="item in list">
          <div class="table-show">
            <div class="cell img_wrap">
              <img :src="item.attachment" @error="$con.imgrep($event)" width="90" height="80" alt="IMG">
              <span class="cx" v-if="item.grade">{{item.grade}}</span>
            </div>
            <div class="cell pre_content">
              <div class="head">
                <star class="right-star" :value="item.overall_score"></star>
                {{item.name}}
              </div>
              <div class="desc">
                {{item.introduce}}
              </div>
              <div class="foot">
                <i class="iconfont icon-locationfill"></i>
                {{item.address}}
              </div>
            </div>
          </div>
        </div>

        <div v-show="!list.length && !load" class="no-data">暂无商家</div>
        <div class="showmore" v-show="list.length && !load && totalpage >= form.page"
          @click="fetchData()">加载更多</div>
        <load-more v-show="load" tip="正在加载"></load-more>

      </div>


    </div>
</template>

<script>
import {
  Icon,
  Group,
  Cell,
  LoadMore
} from 'vux'
import Star from './Star.vue'
import { formatDate } from '../mixin/index'

export default {
    name: 'Home',
    components: {
      Icon,
      LoadMore,
      Group,
      Cell,
      Star
    },
    data() {
      return {
        list: [],
        load: false,
        totalpage: 1,

        id: '',
        name: '',

        form: {
          page: 1,
          type: '',
          grade: '优质商家',
          perpage: 10
        }
      }
    },
    created() {
      this.id = this.$route.params.id;
      this.name = this.$route.query.name;
      this.fetchData();
    },
    watch: {
      '$route': function() {
        this.id = this.$route.params.id;
        this.name = this.$route.query.name;
        this.fetchData();
      }
    },
    methods: {
      fetchData() {
        this.load = true;
        this.$http.post(this.$con.api('starapi/getGreatBusinessList'), {
          month: this.id
        }, {timeout: 20000}).then(result => {

          this.load = false;
          if (result.body.errorcode != '0000') {
            this.$vux.toast.show({
              type: 'cancel',
              text: result.body.errormsg
            })
            return;
          }

          this.totalpage = Math.ceil(result.body.data.total/this.form.perpage);
          this.list = this.list.concat(result.body.data.data);
          this.form.page++;

        }, error => {
          this.load = false;
          console.log(error);
          this.$vux.toast.show({
            type: 'cancel',
            text: '连接服务器发生错误, 请检查网络或重试'
          })
        })
      }
    }
}
</script>

<style lang="less">
@import '../style/mixin.less';

.header {
  position: relative;
  margin-bottom: 46px;

  .txt {
    position: absolute;
    font-size: 18px;
    color: #fff;
    bottom: 26px;
    left: 12px;
    background: rgba(0,0,0,.6);
    padding: 5px 12px;
    border-radius: 2px;
  }
}

.banner {
  height: 170px;
  background-image: url(../assets/banner.jpg);
  -webkit-background-size: cover;
  background-size: cover;
}

.search_box {
  position: absolute;
  bottom: -34px;
  right: 12px;
  left: 12px;
  background: #fff;
  border-radius: 4px;
  height: 44px;
  box-sizing: border-box;

  i {
    position: absolute;
    left: 10px;
    top: 14px;
    font-size: 16px;
  }
  input {
    font-size: 16px;
    height: 44px;
    width: 100%;
    background: none;
    padding: 0 14px 0 40px;
    box-sizing: border-box;

    &::-webkit-input-placeholder {
      color: #b8b8b8;
      font-weight: 400;
    }
  }
}

.content_header {
  background: #fff;
  height: 44px;
  line-height: 44px;
  padding-left: 12px;
  font-size: 16px;

  .tip {
    display: inline-block;
    width: 16px;
    height: 16px;
    line-height: 16px;
    text-align: center;
    color: #fff;
    font-size: 12px;
    background: @theme;
    vertical-align: 1px;
    border-radius: 2px;
    margin-right: 2px;
  }
}

.pre {
  background: #fff;
  padding: 12px;
  box-sizing: border-box;
  transition: all .3s ease;

  &:active {
    background: #f3f3f5;
  }

  .pre_content {
    padding-left: 8px;
    position: relative;
    padding-top: 22px;

    .head {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      font-size: 16px;
      line-height: 16px;
      white-space: nowrap;
      text-overflow: ellipsis;
      overflow: hidden;
      box-sizing: border-box;
      padding-left: 8px;
      padding-right: 86px;
    }
    .right-star {
      position: absolute;
      right: 0;
      top: -1px;
    }
    .desc {
      font-size: 14px;
      line-height: 20px;
      color: #888;
      height: 38px;
      overflow: hidden;
      margin-bottom: 4px;
    }
    .foot {
      position: absolute;
      left: 0;
      bottom: 0;
      width: 100%;
      padding-left: 8px;
      box-sizing: border-box;
      white-space: nowrap;
      text-overflow: ellipsis;
      overflow: hidden;

      font-size: 12px;
      color: #888;
      line-height: 16px;
      i {
        font-size: 13px;
        color: @theme;
      }
    }
  }
}

.img_wrap {
  position: relative;
  width: 90px;
  vertical-align: top;
  img {
    display: block;
    border-radius: 2px;
  }
  .cx {
    position: absolute;
    top: 0;
    left: 5px;
    background: #ff6301;
    color: #fff;
    border-radius: 0 0 2px 2px;
    font-size: 10px;
    padding: 1px 4px;
  }
}

.content_body {
  padding-bottom: 60px;
}
</style>
