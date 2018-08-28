<template>
    <div class="detail">
        <div id="banner">

        </div>
        <div class="detail-box">
            <h3>{{msg.title}}</h3>
            <p class="time  publish" v-if="hasSTime">
                发布时间： {{msg.release_time}}
            </p>
            <p class="time  valid"  v-if= "hasETime">
                有效期至： {{msg.execute_time_end}}
            </p>

            <pre class="detail-info">{{msg.content}}</pre>

            <a v-show="showLink"  @click="hmtOrigin"  :href="msg.original_link">
                来源出处： <span>原始文章链接</span> 
            </a>
        </div>
    	<toast v-model="showToast" type="cancel" >{{toastmsg}}</toast>
    </div>
</template>
<script>


import { Toast} from 'vux'

// require styles


export default {
    name: 'search',
    components:{
        Toast
    },
    deactivated: function() {
        this.$destroy(); //销毁
    },
    created() {
        // this.postData.id= window.location.href.split('&')[0].split('=')[1]
        // this.postData.id = window.sessionStorage.getItem("searchId")
        this.postData.id = window.location.href.split('id=')[1].split('&')[0]
        _hmt.push(['_trackEvent', '安全提醒_详情', 'view', '12301服务号']);
        this.fetchData(this.postData)
    },
    data () {
        return{
            msg: {},
            postData: {
                // id: this.$route.params.id
                id: ''
                },
            showToast: false,
            showLink: false,
            toastmsg: '',
            hasSTime: false,
            hasETime: false 

        }
    },

    methods: {
        fetchData(info) {
             _hmt.push(['_trackEvent', '安全提醒_详情', 'ajax', '12301服务号']);
			let that = this;
			this.$http.post(this.$con.api('/security/detail'),info).then(result => {
				if(result.body.error_code == 0){
                    this.msg = result.body.resp_data;
                    if(this.msg.original_link.length >0){
                        this.showLink = true
                    }else{
                        this.showLink = false
                    }
                    this.hasSTime = this.msg.release_time==''? false: true;
                    this.hasETime = this.msg.execute_time_end==''? false: true;

                    var con = this.msg.content.slice(0, 40)+"..."
                    var l = window.location.href;
                    var s = l.replace('#/','?id='+this.postData.id+'&componentPath=detail'+'#/')
                    let data = {
                        title: this.msg.country_name, // 分享标题
                        link: s, // 分享链接
                        desc: this.msg.title, // 分享描述
                        imgUrl: window.location.origin+'/Public/wap_dynamic/small.jpg', // 分享图标
                    }
                    wx.onMenuShareTimeline(data) //朋友圈
                    wx.onMenuShareAppMessage(data) //朋友
                    wx.onMenuShareQQ(data) //qq
                    wx.onMenuShareWeibo(data)  //腾讯微博
                }else{
                    this.toastmsg = result.body.error_msg;
                    this.showToast = true;
                }
			
				
			
			})
        },
        hmtOrigin(){
             _hmt.push(['_trackEvent', '安全提醒_详情原始连接', 'click', '12301服务号']);
        }
    }
  
}
</script>
<style lang="scss">
    .detail{
        background: #fff;
        position:absolute;
        min-height: 100%;
        width:100%;

    }
     #banner{
            overflow: hidden;
            padding-bottom: 48%;
            height: 0;
            background: url('../../assets/img/se.jpg') no-repeat 0 0;
            background-size: 100% 100%;
        }
    .detail-box{
        padding: 0 .4rem  50px  .4rem;
        h3{
            font-size: .28rem;
            font-weight: bold ;
            line-height: .48rem;
            padding: .2rem 0;
        }
        p{  
            font-size: .28rem;
            line-height: .5rem;
        }
        .time{
            font-size: .24rem;
        }
        .publish{
            color: #666;
        }
        .valid{
            color:#ef6824;
            margin-bottom: .5rem;

        }
        .detail-info{
            margin-top:   .4rem;
            padding-bottom: .4rem;
            white-space: pre-wrap;
        }
    }
   
 
</style>

