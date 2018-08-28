<template>
    <div class="index">
        <!-- <index-banner></index-banner> -->
        <div id="banner">

        </div>

        <div class="link-search">
    
            <div class="index-search">
                <i class="iconfont icon-search head-left"></i>
                <router-link @click="hmtSearch" to="/search" class="head-search">
                    <span>
                        搜索目的地国家
                    </span>
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
                <router-link @click="zuijin"  to="/searchlist" class="border link-btn lastest">

                    <i class="iconfont  icon-iconfontanquan"></i>
                </router-link  >
                <router-link @click="zuijin" class="lastTitle" to="/searchlist">最近安全提醒</router-link>
            </div>
            <div class="btn-box">
                <a   @click="rexian" href="tel: +861012308" class="border link-btn  phone">
                    <i class="iconfont  icon-dianhua"></i>
                </a>
                <a @click="rexian" class="phoneTitle"  href="tel: +861012308">领事保护热线</a>
            </div>
            <!-- <div class="btn-box">
                <a  @click="zixun" href="http://12301.udesk.cn/im_client/?web_plugin_id=43524&group_id=68992&channel=%E5%AF%BC%E6%B8%B8%E4%B9%8B%E5%AE%B6" class="border link-btn  ask ">
                    <i class="iconfont  icon-xinxifill"></i>
                </a>
                <a  @click="zixun" class="askTitle" href="http://12301.udesk.cn/im_client/?web_plugin_id=43524&group_id=68992&channel=%E5%AF%BC%E6%B8%B8%E4%B9%8B%E5%AE%B6">旅游咨询 12301</a>
            </div> -->

        </div>

     
    </div>

</template>

<script>

    // import  Banner from '../components/banner'
   
    export default {
        name: 'Index',
        // components:{

        //     'index-banner': Banner
        // },
        data(){
            return{
                todayCount: 0,
                allCount: 0            }
        },
        deactivated: function() {
            this.$destroy(); //销毁
        },
        created(){
            if(window.location.href.indexOf('componentPath=detail')>0){
                var ids = window.location.href.split('&componentPath')[0].split('id=')[1]
                this.$router.push({
                    name: 'Detail',
                    query:{
                        id: ids
                    }
                });
            }  



            _hmt.push(['_trackEvent', '安全提醒_首页', 'view', '12301服务号']);
            _hmt.push(['_trackEvent', '安全提醒_发布量', 'ajax', '12301服务号']);
            this.$http.jsonp(this.$con.api('/security')).then(res=>{
                this.todayCount = res.body.resp_data.todaySe
                this.allCount = res.body.resp_data.totalSe
            })

            
        },
        mounted() {

            let data = {
                title: '安全提醒', // 分享标题
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
        methods: {
            hmtSearch(){
                 _hmt.push(['_trackEvent', '安全提醒_首页搜索', 'click', '12301服务号']);
            },
            zuijin(){
                _hmt.push(['_trackEvent', '安全提醒_首页列表', 'click', '12301服务号']);
            },
            rexian() {
                 _hmt.push(['_trackEvent', '安全提醒_首页热线保护', 'click', '12301服务号']);
            },
            zixun(){
                _hmt.push(['_trackEvent', '安全提醒_首页12301', 'click', '12301服务号']);
            }
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang='scss'>
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
            background: url('../../assets/img/se.jpg') no-repeat 0 0;
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
                line-height: .66rem;
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
        .phone i{
            font-size: .4rem;
            color: #ef6824;
            
        }
        .phone .icon-xinxifill{
            font-size: .5rem;
        }
        .phone::before{
            border-color: #ef6824;
        }
         .ask i{
            font-size: .4rem;
            color: #227cb1;
            
        }
        .ask .icon-xinxifill{
            font-size: .5rem;
        }
        .ask::before{
            border-color: #227cb1;
        }
        .ask i{
                color: #227cb1;
        }
        p{
            margin-top: .3rem;
        }
    }
    .lastTitle{
                color: #06a951;
            }
            .phoneTitle{
                color: #ef6824;
            }
            .askTitle{
                color: #3a9cd4;
            }
</style>


