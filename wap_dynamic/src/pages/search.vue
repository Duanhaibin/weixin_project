<template>
    <div class="search" @click="domPicker">
    
        <div class="header-box border-bottom">
            <header class="header" >
                <i class="iconfont icon-search head-left"></i>
                <div class="head-search" >
                <!-- <input  @click.stop.prevent="headClick"  v-focus id="scenicSI"  @input="handleScenic" v-model.trim="value"  type="text" placeholder="输入城市/景区/时讯主题"  @keyup.13="search"> -->

                 <input  @click.stop.prevent="headClick"  v-focus id="scenicSI"   v-model.trim="value"  type="search" placeholder="输入城市/景区/时讯主题"  @keyup.enter="search">
                </div>
                <span @click.stop="search"   class="searchbtn border-left">搜    索</span>
            </header>
        </div>
        <div v-show='!hasrelative'>
            <div class="history-box border-top" v-if="showHistory">
                <div >
                    <h1>历史搜索</h1>
                    <span @click = "delHistory">删除</span>
                </div>
            
                <ul class="search-list">
                    <li @click="searchWordHis" v-for="(item, index) in  historymsg"  :key='index' >
                        <router-link class="border" :to="{name:'Searchlist',params:{area: item}}" >{{item}}</router-link>
                    </li>

                </ul>

            </div>

            <div v-if = "hashot"  v-show="!hasrelative">
                <h1>热门搜索</h1>
                <ul class="search-list">
                    <li @click="searchWordHot"  v-for="(item, index) in  hotmsg"  :key='index'>
                        <router-link class="border" :to="{name:'Searchlist',params:{area: item}}" >{{item}}</router-link>
                    </li>

                </ul>

            </div>
        </div>

        <div v-transfer-dom>
            <confirm v-model="delconfrim"
            :title="('删除历史搜索记录')"
            @on-confirm="onConfirm">
                <p style="text-align:center;">{{ ('确定要删除吗?') }}</p>
            </confirm>
        </div>

        <ul  class="result-list" v-show="scenicRes">
            <li   v-for='(item, index) in list' :key="index" class="border-bottom"  @click="chooseList(item)">
                
                <!-- <router-link  :to="{name:'Searchlist',params:{area: item.cn_name,code:item.code}}" > -->
                    <i class="iconfont   icon-location"></i>
                    <!-- <p>{{item.cn_name}}</p> -->
                    <p><span style="color: #ccc;">{{item.province}}</span>
                        <span v-show="item.province">/</span>
                        {{item.scenicName}}</p>
                    <i class="iconfont   icon-you  " ></i>
                    
                    <!-- </router-link> -->
            </li> 
        </ul>

    </div>
</template>
<script>

import { Confirm, TransferDomDirective as TransferDom } from 'vux'

export default {
    name: 'search',
    directives: {
        TransferDom,
        focus: function(el) {
            el.focus()

        }
  
    }, 
    components:{
        Confirm
    },
    data () {
        return{
            delconfrim: false,
            isInput: false,
            hidePicker: false,
            showHistory: false,
            hashot: false,
            value: this.$route.params.value||'',
            historymsg: [],
            hotmsg: [],
            result: [],
            list: [],
            hasrelative: false,
            scenicRes: true,
            scenicId: '',
            sureValue: '',// 用户选择以后有没有操作input
            canWatch: true
        }
    },
    created(){
         _hmt.push(['_trackEvent', '景区时讯_搜索', 'view', '12301服务号']);
        this.fetchHotData()
        this.historymsg =  window.localStorage["searchHistory"]?window.localStorage["searchHistory"].split(','):[];
        if( this.historymsg.length){
            this.showHistory = true;
        }
    },
    watch:{
        value() {
            for(let i in this.list) {
                if(this.list[i].scenicName == this.value){
                    this.canWatch = false;
                    return
                }else{
                    this.canWatch = true
                }
            }
            if(this.canWatch){
                var that = this;
                let info = {
                    name: this.value
                }
                clearTimeout(this.timeout);
                if(this.value) {
                    // this.hideinfo = false;
                    this.timeout = setTimeout(function() {
                        that.$http.post(that.$con.api('/dynamic/searchScenic'),info).then(res => {
                            if(res.body.result&&res.body.result.total > 0) {
                                that.list = res.body.result.data;
                                that.scenicRes = true;
                                that.hasrelative = true;
                            }else{
                                that.scenicRes = false;
                                that.hasrelative = false;
                            }
                        
                        })
                    },300)
                }else{
                    this.scenicRes = false;
                    this.hasrelative = false;
                }

            }
            
        }
    },
   
    methods: {
        headClick() {
            var idObj = document.getElementById('scenicSI');
            idObj.focus();
        },
        handleScenic() {
            var that = this;
            let info = {
                name: this.value
            }
            clearTimeout(this.timeout);
            if(this.value) {
                // this.hideinfo = false;
                this.timeout = setTimeout(function() {
                    that.$http.post(that.$con.api('/dynamic/searchScenic'),info).then(res => {
                        if(res.body.result&&res.body.result.total > 0) {
                            that.list = res.body.result.data;
                            that.scenicRes = true;
                            that.hasrelative = true;
                        }else{
                            that.scenicRes = false;
                            that.hasrelative = false;
                        }
                    
                    })
                },300)
            }else{
                this.scenicRes = false;
                this.hasrelative = false;
            }
          
        },
        searchWordHis(){
            _hmt.push(['_trackEvent', '景区时讯_历史', 'click', '12301服务号']);
            _hmt.push(['_trackEvent', '景区时讯_搜索', 'click', '12301服务号']);
        },
         searchWordHot(){
            _hmt.push(['_trackEvent', '景区时讯_热门', 'click', '12301服务号']);
            _hmt.push(['_trackEvent', '景区时讯_搜索', 'click', '12301服务号']);
        },
        search(){
            _hmt.push(['_trackEvent', '景区时讯_搜索', 'click', '12301服务号']);

            let  history =  window.localStorage["searchHistory"]?window.localStorage["searchHistory"].split(','):[];
            if(this.value){
                history.unshift(this.value)
            }

            let  newhis =  Array.from(new Set(history));


            if(newhis.length>10) {
                newhis=history.slice(0,9)
            }
         
            let  newHistory =  newhis.join(',')
            window.localStorage["searchHistory"] = newHistory;

            if(!this.value ||  !this.sureValue || this.value != this.sureValue) {
                this.scenicId =''
            }
			this.$router.push({
                path: 'searchlist', 
                name: 'Searchlist',
                params: { 
                    area: this.value,
                }
            })
        },
        chooseList(item){
            this.canWatch = false;
            this.value = item.scenicName;
            this.scenicRes = false;
            this.hasrelative = false;
            this.scenicId = item.scenicId;
            this.sureValue = item.scenicName;

        },
        domPicker() {
            this.hidePicker = false;
        },
        picker(val) {
            this.hidePicker = val;
        },
        onConfirm() {
            this.showHistory = false;
             localStorage.removeItem("searchHistory");
        },
        delHistory() {
            this.delconfrim = true;
            // 这里应该发请求删除历史记录
        },
        toSearchlit() {
            this.$router.push({
                path: 'searchlist', 
                name: 'Searchlist',
                params: { 
                    area: this.value
                }

            })
        },
        fetchHotData() {
            _hmt.push(['_trackEvent', '景区时讯_热门景区', 'ajax', '12301服务号']);
            this.$http.jsonp(this.$con.api('/dynamic/hotWords')).then(result => {
                this.hotmsg = result.body.resp_data;
                this.hashot = result.body.resp_data.length===0? false : true;
			},err=>{
                console.log(err)
            })
        }
       
    }
}
</script>
<style scoped lang="scss">
    .search{
       
        background: #fff;
        min-height: 100%;
        width:100%;
        position: absolute;
    }
    h1{
        margin: .32rem  0  .3rem  5%;
        font-weight: bold;
    }
    .search-list{
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        padding: 0  0  0  .4rem ;
        li{ 
            // border: 1px solid #fe6617;
            word-wrap: none;
            margin: .3rem   .4rem  .3rem  0;
            a{
                padding:  .1rem  .3rem;
                margin-bottom: .4rem;
                color: #777;
            }
        }
        
    }
   
    .border::before{
        border-radius: .5rem;
        border-color:#fe6a1c;
    }
    .history-box{
        div{
            display: flex;
            justify-content:space-between  ;
            span{
                margin: .32rem  .3rem  0  0 ;
                color: #3a9cd4;
            }
        }
    }
      .header-box{
        padding: .28rem 0  .02rem  0;
        .header{
            display: flex;
            box-sizing: border-box;
            font-size: .24rem;
            height:.85rem;
            border-radius: .08rem;
            background:#f4f4f4;
            margin:0 .4rem .28rem ;
        }
        .head-left{
            width: .48rem;
            height:.48rem;
            line-height: .85rem;
            margin-left: .18rem;
            color: #a8a8a8;
            text-align: center;
            font-size: .32rem;
            
        }
        .head-search{
            padding-left: .3rem;
            line-height: .85rem;
            box-sizing: border-box;
            margin-right: .3rem;
            width: 100%;
            input{
                position: relative;
                display: block;
                width: 100%;
                height: .85rem;
                line-height: .4rem;
                padding: .15rem 0;
                border: 0;
                background: #f4f4f4;
                font-family: "Microsoft Yahei",Arial;
                font-size: .28rem;
            }
        }
        .searchbtn{
			font-size: .28rem;
			height:100%;
			width:2rem;
			text-align: center;
			line-height: .85rem;
			border-radius: 0 .08rem  .08rem 0 ;
		}
        .searchbtn:active{
            color:#25a4bb
        }
    }
    .result-list{
        position: absolute;
        width: 100%;
        background: #fff;
        z-index: 3;
        top: 1.2rem;
        li{
            padding:    .2rem   0  .2rem  .4rem;
            // a{
                display: block;
                // width: 100%;
                // height: 100%;
                color: #444;
            // }
        }
        li:active{
            background: #eee;
            color: #25a4bb;
        }
        p{
            display: inline-block;
            width: 5.5rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .icon-you {
            float: right;
            margin-right: .4rem;
        }
    }
</style>

