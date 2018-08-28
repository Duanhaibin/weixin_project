<template>
<div class="face page_detail">
  <div class="detail_head vux-1px-b">
    <div class="table-show">
      <div class="cell img_wrap" @click="photolist">
        <div class="r_img" :style="'background-image:url('+ info.source_url +')'"></div>
        <span class="cx">{{info.grade}}</span>
      </div>
      <div class="cell detail_mininfo">
        <div class="head">{{info.name}}</div>
        <star class="right-star" :value="info.overall_score"></star>
        <table class="desc">
          <tbody>
            <tr>
              <td>店铺情况评分</td>
              <td>与同行业相比</td>
            </tr>
            <tr>
              <td>{{score_1_name}}: <span class="theme">{{info.score_1}}</span></td>
              <td v-if="info.score_1_compare">
                {{info.score_1_compare.compare_name}}
                <span class="theme">{{info.score_1_compare.compare_percent}} </span>
              </td>
            </tr>
            <tr>
              <td>{{score_2_name}}: <span class="theme">{{info.score_2}}</span></td>
              <td v-if="info.score_2_compare">
                {{info.score_2_compare.compare_name}}
                <span class="theme">{{info.score_2_compare.compare_percent}} </span>
              </td>
            </tr>
            <tr>
              <td>{{score_3_name}}: <span class="theme">{{info.score_3}}</span></td>
              <td v-if="info.score_3_compare">
                {{info.score_3_compare.compare_name}}
                <span class="theme">{{info.score_3_compare.compare_percent}} </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="content_header vux-1px-b">
    商户介绍
    <a class="min-r-btn" :href="
      tousuurl + 'view/complaintmobile?type=changbaishan&unitname=' + info.name + '#/pact'">
      <i class="iconfont icon-comment"></i> 快捷投诉
    </a>
  </div>
  <div class="merchants">
    <ul class="mer_list">
      <li><i class="iconfont icon-timeclock m_icon"></i> {{info.business_hours || '暂无数据'}} </li>
      <li><i class="iconfont icon-locationfill m_icon"></i> {{info.address || '暂无数据'}}</li>
      <li><i class="iconfont icon-fenlei m_icon"></i> {{info.introduce || '暂无数据'}}</li>
    </ul>
  </div>

  <div class="content_header vux-1px-b">
    用户评价
  </div>
  <div class="common_box">
    <div class="common_head">
      <div class="btn_list">
        <div class="item" v-for="(item, $index) in cmTypeList"
          :class="{'active': $index === cmTypeIndex}"
          @click="checkCmtype($index)">{{item.n}}</div>
      </div>
    </div>

    <div class="common_list">
      <div class="common_type" v-for="(item, $index) in cmTypeList" v-show="$index === cmTypeIndex">

        <div class="cm vux-1px-b" v-for="t in item.list">
          <div class="avatar" :style="'background-image: url(' + t.headimgurl + ')'"></div>
          <div class="info">
            <div class="head">
              <span class="pull-right time">{{ t.time }}</span>
              {{t.nickname || '匿名用户'}}
            </div>
            <star :value="t.overall_score"></star>
            <div class="desc">{{ t.comment }}</div>
            <upload-files class="fileslist" :files="t.attachment.map(t => {
              return {
                src: t.source_url,
                isImg: true
              }
            })" disabled="1"></upload-files>
            <div class="reply vux-1px-t" v-if="t.reply.length">
              <span class="theme">商家回复: </span>
              {{ t.reply[t.reply.length - 1].reply }}
            </div>
          </div>
        </div>

        <div v-if="!item.list.length && !item.load" class="no-data">暂无评论</div>
        <div class="showmore" v-if="item.list.length && !item.load && item.totalpage >= item.page"
          @click="fetchCommonList($index)">加载更多</div>
        <load-more v-show="item.load" tip="正在加载"></load-more>

      </div>
    </div>

  </div>

  <div class="pop_btn" @click="assess" v-show="showBtn">
    <div class="pop_btn_content"></div>
  </div>

</div>
</template>

<script>
import Star from './Star.vue'
import UploadFiles from './UploadFiles.vue'
import NProgress from 'NProgress'
import {
  LoadMore
} from 'vux'

export default {
  name: 'Detail',
  components: {
    Star,
    LoadMore,
    UploadFiles
  },
  data() {
    return {
      info: {},
      tousuurl: MCOMPLAINURL,
      showBtn: true,
      perpage: 15,
      score_1_name: '产品性价',
      score_2_name: '服务水平',
      score_3_name: '配套设施',
      cmTypeList: [
        {n: '全部', page: 1, totalpage: 1, load: false, type: '', list: []},
        {n: '好评', page: 1, totalpage: 1, load: false, type: '好', list: []},
        {n: '中评', page: 1, totalpage: 1, load: false, type: '中', list: []},
        {n: '差评', page: 1, totalpage: 1, load: false, type: '差', list: []}
      ],
      cmTypeIndex: 0
    }
  },
  created() {
    this.fetchData();
    OPENID === '请在微信中打开' && (this.showBtn = false);
  },
  watch: {
    '$route': 'fetchData'
  },
  methods: {
    fetchData() {
      document.documentElement.scrollTop = 0;
      document.body.scrollTop = 0;

      var id = this.$route.params.id;
      if (!id) return;

      this.fetchCommonList(0);
      NProgress.start();
      this.$http.post(this.$con.api('starapi/getBusinessDetail'), { id, from: 'h5' }, {timeout: 20000}).then(result => {

        NProgress.done();
        if (result.body.errorcode != '0000') {
          this.$vux.toast.show({
            type: 'cancel',
            text: result.body.errormsg
          })

          result.body.errorcode == '0002' && this.$router.push('/');
          return;
        }

        this.info = result.body.data.data;
        this.getScoreName(this.info.type);

      }, error => {
        console.log(error);
        this.$vux.toast.show({
          type: 'cancel',
          text: '连接服务器发生错误, 请检查网络或重试'
        })
      })

    },

    /**
     * 获取评论列表
     * @return {[type]} [description]
     */
    fetchCommonList(index) {
      var id = this.$route.params.id;
      if (!id || this.cmTypeList[index].load) return;

      this.cmTypeList[index].load = true;
      this.$http.post(this.$con.api('starapi/getComment'), {
        id,
        score_type: this.cmTypeList[index].type,
        page: this.cmTypeList[index].page,
        perpage: this.perpage
       }, {timeout: 20000}).then(result => {

        this.cmTypeList[index].load = false;
        if (result.body.errorcode != '0000') {
          this.$vux.toast.show({
            type: 'cancel',
            text: result.body.errormsg
          })
          return;
        }

        this.cmTypeList[index].totalpage = Math.ceil(result.body.data.total/this.perpage);
        this.cmTypeList[index].list = this.cmTypeList[index].list.concat(result.body.data.data);
        this.cmTypeList[index].page++;

      }, error => {
        this.cmTypeList[index].load = false;
        console.log(error);
        this.$vux.toast.show({
          type: 'cancel',
          text: '连接服务器发生错误, 请检查网络或重试'
        })
      })

    },

    /**
     * 切换评价类型
     * @param  {[type]} index [description]
     * @return {[type]}       [description]
     */
    checkCmtype(index) {
      this.cmTypeIndex = index;
      !this.cmTypeList[index].list.length && this.fetchCommonList(index);
    },

    /**
     * 去评价
     * @return {[type]} [description]
     */
    assess() {
      this.$router.push(this.$route.path + '/assess?type=' + this.info.type)
    },

    /**
     * 查看相册
     * @return {[type]} [description]
     */
    photolist() {
      this.$router.push(this.$route.path + '/photo')
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
    }

  }
}
</script>

<style lang="less">
@import '../style/mixin.less';

.detail_head {
  background: #fff;
  padding: 10px 12px 12px 12px;
  margin-bottom: 12px;

  .img_wrap {
    width: 106px;
  }
}

.detail_mininfo {
  padding-left: 12px;
  position: relative;

  .head {
    font-size: 16px;
    line-height: 18px;
    margin-bottom: 2px;
  }

  .desc {
    width: 100%;
    font-size: 14px;
    line-height: 18px;
    margin-bottom: -3px;
    tr > td {
      color: #888;
      width: 50%;
    }
  }

}

.r_img {
  position: absolute;
  width: 100%;
  height: 100%;
  border-radius: 2px;
  background-color: #f5f5f5;
  background-position: center;
  -webkit-background-size: auto 100%;
  background-size: auto 100%;
}

.merchants {
  padding: 12px;
  background: #fff;
  margin-bottom: 12px;

  .mer_list {
    padding-left: 22px;
    color: #888;

    li {
      position: relative;
      list-style: none;
      margin-bottom: 4px;
      font-size: 14px;

      &:last-child {
        margin: 0;
      }

      .m_icon {
        font-size: 15px;
        position: absolute;
        left: -22px;
        top: 0;
        color: @theme;
      }
    }
  }
}

.common_box {
  background: #fff;
  .common_head {
    box-sizing: border-box;
    padding: 12px 22px;
  }
}

.btn_list {
  display: table;
  box-sizing: border-box;
  width: 100%;
  border: 1px solid @theme;
  border-radius: 3px;

  .item {
    display: table-cell;
    text-align: center;
    line-height: 31px;
    color: @theme;
    border-right: 1px solid @theme;
    font-size: 13px;
    transition: all .3s ease;

    &:last-child {
      border: none;
    }
    &:active {
      background: #eee;
    }

    &.active {
      background: @theme;
      color: #fff;
    }
  }
}

.pop_btn {
  position: fixed;
  right: 10px;
  bottom: 56px;
  transition: all .3s ease;

  &:active {
    transform: translate(0, 60px);
  }
}
.pop_btn_content {
  width: 90px;
  height: 60px;
  background: url(../assets/pop.png) no-repeat center;
  background-size: 100% auto;
}

.common_list {
  padding-left: 12px;
  margin-bottom: 20px;
  padding-bottom: 12px;
}

.cm {
  position: relative;
  padding: 12px 12px 12px 50px;
  min-height: 80px;
  .avatar {
    position: absolute;
    top: 16px;
    left: 0;
    background: #eee;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    -webkit-background-size: cover;
    background-size: cover;
  }
  .info {
    .head {
      color: #888;
      font-size: 14px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .time {
      position: relative;
      top: 3px;
      padding-left: 10px;
      font-size: 12px;
    }
    .star {
      margin-bottom: 3px;
    }
    .desc {
      font-size: 14px;
    }
  }
  .reply {
    font-size: 14px;
    padding: 8px 0 0;
    margin-top: 8px;
  }
}

.fileslist {
  padding: 10px 0 0 0;
}
.showmore {
  padding: 20px 12px 5px;
  text-align: center;
}

.no-data {
  text-align: center;
  padding: 30px 0 48px;
  color: #888;
}

.min-r-btn {
  display: inline-block;
  float: right;
  background: #ff6301;
  color: #fff;
  padding: 6px 10px;
  line-height: 1.4;
  border-radius: 3px;
  margin: 6px 12px 0 0;
  font-size: 14px;
  transition: all .2s ease;
  &:active {
    background: #e25f0d;
  }
  i {
    font-size: 14px;
  }
}
</style>
