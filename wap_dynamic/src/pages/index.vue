<template>
    <div class="index">

        <div id="banner">

        </div>

        <div class="link-search">
            <div class="index-search">
                <i class="iconfont icon-search head-left"></i>
                <router-link to="/search" class="head-search"  @click="hmtSearch">
                    <span>输入城市/景区/时讯主题</span>
                </router-link>
            </div>
        </div>
      
       
        <div class="count-box">
            <p class="border-right">
                今日发布： <span>{{todayCount}}</span>
            </p>
            <p>
                累计发布： <span>{{allCount}}</span>
            </p>
        </div>

        <div class="handle-box">
            <div class="btn-box">
                <router-link @click="zixun('0')"  to="/searchlist"   class="border link-btn lastest">

                    <i class="iconfont  icon-iconfontdongtai"></i>
                </router-link >
                <router-link  @click="zixun('0')"  class="lastTitle"  to="/searchlist" >最新景区时讯</router-link >
            </div>
            <div class="btn-box">
                <div  @click="handleReport"  class="border link-btn report">
                    <i class="iconfont  icon-weibiaoti111"></i>
                </div>
                <p  @click="handleReport"  class="reportTitle"  >我要现场报道</p>
            </div>
            <!-- <div class="btn-box">
                <a  @click="zixun('1')"  href="http://12301.udesk.cn/im_client/?web_plugin_id=43524&group_id=68992&channel=%E5%AF%BC%E6%B8%B8%E4%B9%8B%E5%AE%B6" class="border link-btn  ask">
                    <i class="iconfont  icon-xinxifill"></i>
                </a>
                <a @click="zixun('1')"  class="askTitle"  href="http://12301.udesk.cn/im_client/?web_plugin_id=43524&group_id=68992&channel=%E5%AF%BC%E6%B8%B8%E4%B9%8B%E5%AE%B6">景区在线咨询</a> -->
            <!-- </div> -->
            <div class="btn-box">
                <a  @click="zixun('1')"   class="border link-btn  ask">
                    <i class="iconfont  icon-xinxifill"></i>
                </a>
                <a @click="zixun('1')"  class="askTitle"  >景区在线咨询</a>
            </div>







            <!-- <div class="btn-box">
                <a  href="tel: 12301" class="border link-btn  ask">
                    <i class="iconfont  icon-dianhua"></i>
                </a>
                <a  class="askTitle"  href="tel: 12301">咨询12301</a>
            </div> -->
            <!-- <div class="btn-box">
                <router-link  to="/letter"  class="border link-btn report">
                    <i class="iconfont  icon-weibiaoti111"></i>
                </router-link>
                <router-link  to="/letter">我做现场报道</router-link>
            </div> -->
          

        </div>

         <div v-transfer-dom>
            <confirm v-model="ask"
            :title="('提示')"
            @on-confirm="onConfirm">
                <p style="text-align:center;">{{ ('确认要咨询12301客服吗?') }}</p>
            </confirm>
        </div>

     
    </div>

</template>

<script>
import { Confirm, TransferDomDirective as TransferDom } from 'vux'

   
    export default {
        name: 'Index',
        directives: {
            TransferDom
        },
        components:{
        
            Confirm
        },
        deactivated: function() {
            this.$destroy(); //销毁
        },
        data() {
            return{
                todayCount:0,
                allCount:0,
                openid: OPENID,
                accountid: ACCOUNTID,
                ask:false
            }
        },
        mounted() {
            let data = {
                    title: '景区时讯', // 分享标题
                    link: window.location.href, // 分享链接
                    imgUrl: window.location.origin+'/Public/wap_dynamic/small.jpg', // 分享图标
                }
          wx.ready(function() {
                 
                // alert('图片路径写的是测试的，注意')
                wx.onMenuShareTimeline(data) //朋友圈
                wx.onMenuShareAppMessage(data) //朋友
                wx.onMenuShareQQ(data) //qq
                wx.onMenuShareWeibo(data)  //腾讯微博
          })
        
        },
        created() {
            if(window.location.href.indexOf('componentPath=detail')>0){
                var ids = window.location.href.split('&componentPath')[0].split('id=')[1]
                this.$router.push({
                    name: 'Detail',
                    query:{
                        id: ids
                    }
                });
            }  
            _hmt.push(['_trackEvent', '景区时讯_首页', 'view', '12301服务号']);
            this.fetchCount()    
           
        },
        
        methods: {
            hmtSearch(){
                 _hmt.push(['_trackEvent', '景区时讯_首页搜索', 'click', '12301服务号']);
            },
            zixun(x){
                this.ask = true
               
                if(x == 0){
                    _hmt.push(['_trackEvent', '景区时讯_首页景区动态', 'click', '12301服务号']);
                    return
                }
                if(x==1){
                    _hmt.push(['_trackEvent', '景区时讯_首页12301', 'click', '12301服务号']);
                }
                
            },
            onConfirm(){
                 window.location.href="http://12301.udesk.cn/im_client/?web_plugin_id=43524&group_id=68992&channel=%E5%AF%BC%E6%B8%B8%E4%B9%8B%E5%AE%B6"
            },
            handleReport(){
                _hmt.push(['_trackEvent', '景区时讯_首页报道', 'click', '12301服务号']);
                this.$http.jsonp(this.$con.api('/openapi/checkBind?oid='+OPENID+"&aid="+ACCOUNTID)).then(result => {
                    if(result && result.body == "false"){
                        var complain_url = "http://tousug.12301e.com";
                        var page_url = '/index.php?s=/w21/ScenicspotDynamics/ScenicspotDynamicsWap/index/id/21.html'; 
                        if(SITE_URL.indexOf('wx.12301.cn') >-1){
                            page_url = '/index.php?s=/w24/ScenicspotDynamics/ScenicspotDynamicsWap/index/id/24.html';
                            complain_url = "http://mucomplain.12301.cn";
                        }
                        var from = SITE_URL + page_url + '#/letter';
                        var url = complain_url + '/view/complaintweixin?type=weixin&'+'openid='+this.openid + '&accountId='+this.accountid + "&from="+encodeURIComponent(from)+"#/pact";
                        // redirect
                        // console.log(url)
                        window.location.href = url
                    }
                    else {
                        this.$router.push({
                            name: 'Letter',
                            path: "/letter"
                        })
                    }
                }).catch(err => {
                    console.error("===="+err)
                })
             
            },
            fetchCount () {
                _hmt.push(['_trackEvent', '景区时讯_发布量', 'ajax', '12301服务号']);
                this.$http.jsonp(this.$con.api('/dynamic')).then(res=>{
                    this.todayCount = res.body.resp_data.todayDy
                    this.allCount = res.body.resp_data.totalDy
                })

            }
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style  lang='scss'>
    .index{
        height: 100%;
        width: 100%;
        position:fixed;
        background: #eee;
        div{
            background: #fff;
        }
        #banner{
            overflow: hidden;
            padding-bottom: 48%;
            height: 0;
            background: url('../assets/img/dy.jpg') no-repeat 0 0;
            background-size: 100% 100%;
        }
        .link-search{
            height: .8rem;
            padding-top: .4rem;
        }
        .index-search{
            height: 40px;
            display: flex;
            box-sizing: border-box;
            margin : 0 .4rem ;
            background: green;
            height:.64rem;
            border-radius: .08rem;
            background:#f4f4f4;
            color: #a8a8a8;
            .head-left{
                width: .48rem;
                height:.48rem;
                line-height: .64rem;
                margin-left: .18rem;
                text-align: center;
                font-size: .34rem;
            }
            .head-search{
                padding-left: .3rem;
                line-height: .66rem;
                box-sizing: border-box;
                margin-right: .3rem;
                background:#f4f4f4;
                width: 100%;
                color: #a8a8a8;

            }
        }
        .weui-mask{
            background: rgba(0,0,0,.4)
        }
        .weui-dialog{
            border-radius: 10px;
            width: 70%;
            .weui-dialog__btn{
                color: #3a9cd4;
            }
        }
        
    }
  



  
  
    .count-box{
        height: 1.4rem;
        display: flex;
        text-align: center;
        p{
            width: 50%;
            box-sizing: border-box;
            margin: .2rem 0 ;
            line-height: 1rem;
            span{
                color: #ff6301;
            }
        }
    }
    .handle-box{
        background: #ffffff;
        height: 2.8rem;
        margin-top:.3rem;
        display: flex;
        .btn-box{
            flex: 1;
            text-align: center;
            .link-btn{
                display: block;
                height: 1.08rem;
                width: 1.08rem;
                margin: .5rem auto .3rem;
            }
            .lastTitle{
                color: #06a951;
            }
            .askTitle{
                color: #ef6824;
            }
            .reportTitle{
                color: #3a9cd4;
            }
        
            
            i{
                font-size: .5rem;
                line-height: 1.08rem;
                text-align: center;
            }
        }
        .border::before{
            border-radius: 5rem;
        }
        .lastest i{
            color: #06a951;
        }
        .lastest::before{
            border-color: #06a951;
        }
        .ask i{
            font-size: .5rem;
            color: #ef6824;
        }
      
        .ask::before{
            border-color: #ef6824;
        }
        .report i{
                color: #227cb1;
        }
      
        .report::before{
            border-color: #3a9cd4;
        }
        p{
            margin-top: .3rem;
        }
    }
</style>


