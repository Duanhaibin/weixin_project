<template>
    <div class="page page_search" :style="{
      'min-height': mh + 'px'
      }">

      <div class="search_control">
        <input type="search" placeholder="搜索商户名称" @focus="focus" @blur="blur"
          v-model.trim="name" @keyup.enter="search">
        <button class="search_btn" type="button" @click="search"><i class="iconfont icon-search"></i></button>
      </div>

      <div class="category_nav vux-1px-b">
        <s-tab :category="category.map((t) => t.n)" v-model="cateIndex"></s-tab>
      </div>

      <div class="detail_content">
        <div class="detail_item" v-for="(cateItem, $index) in category" v-show="cateIndex === $index">

          <div class="pre vux-1px-b" @click="$con.go('#/s/' + item.id)" v-for="item in cateItem.list">
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

          <div v-show="!cateItem.list.length && !cateItem.load" class="no-data">暂无商家</div>
          <div class="showmore" v-show="cateItem.list.length && !cateItem.load && cateItem.totalpage >= cateItem.page"
            @click="fetchData()">加载更多</div>
          <load-more v-show="cateItem.load" tip="正在加载"></load-more>

        </div>
      </div>


    </div>
</template>

<script>
import NProgress from 'NProgress'
import sTab from './Tab.vue'
import Star from './Star.vue'
import {
  LoadMore
} from 'vux'
var HEIGHT = document.body.clientHeight || document.documentElement.clientHeight;

export default {
    name: 'Search',
    components: {
      sTab,
      Star,
      LoadMore
    },
    data() {
      return {
        mh: HEIGHT,
        name: '',
        cateIndex: 0,
        perpage: 15,
        category: [
          {n: '全部', page: 1, totalpage: 1, load: false, type: '', list: []},
          {n: '餐饮', page: 1, totalpage: 1, load: false, type: 5, list: []},
          {n: '购物', page: 1, totalpage: 1, load: false, type: 4, list: []},
          {n: '旅行社', page: 1, totalpage: 1, load: false, type: 1, list: []},
          {n: '住宿', page: 1, totalpage: 1, load: false, type: 2, list: []},
          {n: '交通', page: 1, totalpage: 1, load: false, type: 6, list: []},
          {n: '景区', page: 1, totalpage: 1, load: false, type: 3, list: []}
        ]
      }
    },
    created() {
      this.fetchData();
    },
    watch: {
      cateIndex(v) {
        !this.category[v].list.length && this.fetchData();
      }
    },
    methods: {
      fetchData() {
        var cate = this.category[this.cateIndex];

        this.$http.post(this.$con.api('starapi/getBusinessList'), {
          page: cate.page,
          type: cate.type,
          perpage: this.perpage,
          name: this.name
        }, {timeout: 20000}).then(result => {

          if (result.body.errorcode != '0000') {
            this.$vux.toast.show({
              type: 'cancel',
              text: result.body.errormsg
            })
            return;
          }

          cate.totalpage = Math.ceil(result.body.data.total/this.perpage);
          cate.list = cate.list.concat(result.body.data.data);
          cate.page++;

        }, error => {
          console.log(error);
          this.$vux.toast.show({
            type: 'cancel',
            text: '连接服务器发生错误, 请检查网络或重试'
          })
        })
      },

      /**
       * 搜索
       * @return {[type]} [description]
       */
      search() {

        this.cateIndex = 0;
        for (var i in this.category) {
          this.category[i].page = 1;
          this.category[i].list = [];
        }

        this.fetchData();
      },

      focus() {
        this.$store.commit('updateNavState', false)
      },

      blur() {
        this.$store.commit('updateNavState', true)
      }
    }
}
</script>

<style lang="less">
@import '../style/mixin.less';

.page_search {
  background: #fff;
}

.search_control {
  position: relative;
  padding: 12px;
  input {
    background: #f4f4f4;
    width: 100%;
    box-sizing: border-box;
    height: 38px;
    border-radius: 3px;
    padding: 0 48px 0 12px;
    font-size: 15px;
  }
  .search_btn {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 38px;
    height: 38px;
    background: none;
    border: 0;
    color: #888;
    border-radius: 0 3px 3px 0;
    transition: all .2s ease;
    &:active {
      background: #ccc;
    }
  }
}

.detail_content {
  padding-bottom: 60px;
}
</style>
