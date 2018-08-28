<template>
    <div class="search" @click="domPicker">
    
        <div class="header-box border-bottom"  >
            <header class="header" >
                <i class="iconfont icon-search head-left"></i>
                <div class="head-search" >
                    
                <!-- <input @click="headClick" id="countrySI"  v-focus @input="search" v-model.trim="value"  type="text" placeholder="请输入要搜索的目的地国家"  @keyup.enter="toDest"> -->
                <input @click="headClick" id="countrySI"  v-focus  v-model.trim="value"  type="search" placeholder="请输入要搜索的目的地国家"  @keyup.enter="toDest">
                </div>
            </header>
        </div>
        <!-- <div v-show='!isInput'>
            <div class="history-box border-top" v-if="showHiustory">
                <div >
                    <h1>历史搜索</h1>
                    <span @click = "delHistory">删除</span>
                </div>
            
                <ul class="search-list">
                    <li v-for="(item, index) in  msg"  :key='index' >
                        <router-link class="border" :to="{name:'Dest',params:{area: item}}" >{{item}}</router-link>
                    </li>

                </ul>

            </div>
        </div> -->
        <div v-show='!isInput'>
            <div class="history-box" v-if="showHot">
                <div >
                    <h1>热门搜索</h1>
                </div>
            
                <ul class="search-list">
                    <li @click="searchWordHot" v-for="(item, index) in  hotMsg"  :key='index' >
                        <router-link class="border" :to="{name:'Dest',params:{area: item.cn_name,code:item.code}}" >{{item.cn_name}}</router-link>
                    </li>

                </ul>

            </div>
        </div>

        <ul  class="result-list" v-show="hasresult">
            <li @click="searchWord"  v-for='(item, index) in result' :key="index" class="border-bottom">
                
                <router-link  :to="{name:'Dest',params:{area: item.cn_name,code:item.code}}" >
                    <i class="iconfont   icon-location"></i>
                    <!-- <p>{{item.cn_name}}</p> -->
                    <p>{{item.cn_name}}</p>
                    <i class="iconfont   icon-you  " ></i>
                    
                    </router-link>
            </li> 
        </ul>

        <!-- <div v-transfer-dom>
            <confirm v-model="delconfrim"
            :title="('删除历史搜索记录')"
            @on-confirm="onConfirm">
                <p style="text-align:center;">{{ ('确定要删除吗?') }}</p>
            </confirm>
        </div> -->

    </div>
</template>
<script>

import { Confirm, TransferDomDirective as TransferDom } from 'vux'

export default {
    name: 'search',
    directives: {
        TransferDom,
        focus(el) {
            el.focus();
        }
    }, 
    components:{
        // 'index-head' : Head,
        // 'list': List,
        Confirm
    },
    created() {
        _hmt.push(['_trackEvent', '安全提醒_搜索', 'view', '12301服务号']);
        this.$http.jsonp(this.$con.api('/region/country/hot')).then(res=>{
            this.hotMsg = res.body.resp_data;
            if(this.hotMsg.length){
                 showHot: true
            }
        })
    },
    data () {
        return{
            delconfrim: false,
            isInput: false,
            hidePicker: false,
            showHiustory: true,
            value: '',
            msg: [],
            hotMsg: [],
            result: [],
            timeout: null,
            hasresult: false,
            showHot: true
    
        }
    },
    watch:{
        value() {
            var that = this;
            clearTimeout(this.timeout);
            if(this.value == ''){
                this.hasresult = false;
            }else{
                this.timeout = setTimeout(function() {
                    _hmt.push(['_trackEvent', '安全提醒_匹配国家', 'ajax', '12301服务号']);
                    that.$http.jsonp(that.$con.api('/region/country/search?search_word='+that.value)).then(res => {
                        if(res.body.resp_data.length>0){
                            that.result=res.body.resp_data
                            that.hasresult = true;
                        }else {
                            that.result = []
                            that.hasresult = false;
                        }
                    })
                },300)
            }
            
        }
    },
    
    
    
    methods: {
         headClick() {
            var idObj = document.getElementById('countrySI');
            idObj.focus();
        },
        searchWord() {
             _hmt.push(['_trackEvent', '安全提醒_搜索', 'click', '12301服务号']);
        },
        searchWordHot() {
            _hmt.push(['_trackEvent', '安全提醒_搜索', 'click', '12301服务号']);
            _hmt.push(['_trackEvent', '安全提醒_热门', 'click', '12301服务号']);
        },
        
        search() {
            var that = this;
            clearTimeout(this.timeout);
            if(this.value == ''){
                this.hasresult = false;
            }else{
                this.timeout = setTimeout(function() {
                    _hmt.push(['_trackEvent', '安全提醒_匹配国家', 'ajax', '12301服务号']);
                    that.$http.jsonp(that.$con.api('/region/country/search?search_word='+that.value)).then(res => {
                        if(res.body.resp_data.length>0){
                            that.result=res.body.resp_data
                            that.hasresult = true;
                        }else {
                            that.result = []
                            that.hasresult = false;
                        }
                    })
                },300)
            }
        },
        domPicker() {
            this.hidePicker = false;
        },
        picker(val) {
            this.hidePicker = val;
        },
        onConfirm() {
            this.showHiustory = false;
        },
        delHistory() {
            this.delconfrim = true;
            // 这里应该发请求删除历史记录
        },
        toDest() {
            this.$router.push({
                path: 'dest', 
                name: 'Dest',
                params: { 
                    area: this.value,
                    code: ''
                }

            })
        }
       
    }
}
</script>
<style scoped lang="scss">
    .search{
        width: 100%;
        background: #fff;
        min-height: 100%;
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
            line-height: .6rem;
            box-sizing: border-box;
            margin-right: .3rem;
            width: 100%;
            input{
                position: relative;
                display: block;
                width: 100%;
                height: .85rem;
                line-height: .55rem;
                padding: .15rem 0;
                border: 0;
                background: #f4f4f4;
                font-family: "Microsoft Yahei",Arial;
                font-size: .28rem;
            }
        }
    }
    .result-list{
        position: absolute;
        width: 100%;
        background: #fff;
        z-index: 3;
        top: 1.3rem;
        li{
            padding: 0  0  0 .4rem;
            a{
                display: block;
                width: 100%;
                height: 100%;
                padding: .2rem   0;
                color: #444;
            }
        }
        li:active{
            a{  
                background: #eee;
                color: #25a4bb;
            }
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

