<template>
    <div class="detail">

        <div id="banner">

        </div>

        <div class="detail-box">
            <h3>{{msg.title}}</h3>
            <p>景区名称： {{msg.ta_name}}</p>
            <p>景区等级： {{msg.ta_level}}</p>
            <p>发布时间： {{msg.release_time}}</p>
            <p v-if="hasEstime" class="handletime">执行时间： {{msg.execute_time_start}}</p>
            <p v-if="hasEetime" class="handletime">结束时间： {{msg.execute_time_end}}</p>
            <pre class="detail-info">{{msg.content}}</pre>

            <div v-show="showLink" style="padding-bottom: 50px">
                 <a @click="hmtOrigin" class="bottonlink" :href="msg.url">来源出处： 原始文章链接</a>
            </div>
        </div>
       
    
    </div>
</template>
<script>




// require styles


export default {
    name: 'search',
    data () {
        return{
            msg: {},
            postData: {
                // id: this.$store.getters.getID
                id:''
            },
            showLink:false,
            hasEstime: false,
            hasEetime: false
            
            

        }
    },
    deactivated: function() {
        this.$destroy(); //销毁
    },
    created() {
       
        this.postData.id = window.location.href.split('?id=')[1].split('&')[0]
        _hmt.push(['_trackEvent', '景区时讯_详情', 'view', '12301服务号']);
        this.fetchData(this.postData)
    },
  
    mounted() {
       
        // console.log(this.msg.title)
        // let data = {
        //     title: this.msg.title, // 分享标题
        //     link: window.location.href, // 分享链接
        //     imgUrl: '../assets/img/dy.jpg', // 分享图标
        // }
        // wx.onMenuShareTimeline(data) //朋友圈
        // wx.onMenuShareAppMessage(data) //朋友
        // wx.onMenuShareQQ(data) //qq
        // wx.onMenuShareWeibo(data)  //腾讯微博
    },
    methods:{
        fetchData(info) {
            _hmt.push(['_trackEvent', '景区时讯_详情数据', 'ajax', '12301服务号']);
			let that = this;
			this.$http.post(this.$con.api('/dynamic/detail'),info).then(result => {
				if(result.body.error_code == 0){
                    this.msg = result.body.resp_data;
                    this.msg.execute_time_start = result.body.resp_data.execute_time_start ;
                    this.msg.execute_time_end = result.body.resp_data.execute_time_end ;

                    this.hasEstime = this.msg.execute_time_start==""?false: true;
                    this.hasEetime = this.msg.execute_time_end==""?false:true;

                    if(this.msg.url.length > 0){
                        this.showLink = true
                    }else{
                        this.showLink = false
                    }
                    var con = this.msg.content.slice(0, 20)+"..."
                    var l = window.location.href;
                    var s = l.replace('#/','?id='+this.postData.id+'&componentPath=detail'+'#/')
                    
                    let data = {
                        title: this.msg.ta_name, // 分享标题
                        link: s, // 分享链接
                        desc: this.msg.title, // 分享描述
                        imgUrl: window.location.origin+'/Public/wap_dynamic/small.jpg', // 分享图标
                    }
                    // alert('图片路径写的是测试的，注意')
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
            _hmt.push(['_trackEvent', '景区时讯_详情原始连接', 'click', '12301服务号']);
        }
        
    }


}
</script>
<style lang="scss">
    .detail{
        width: 100%;
        background: #fff;
        position:absolute;
        min-height: 100%;

    }
     #banner{
            overflow: hidden;
            padding-bottom: 48%;
            height: 0;
            background: url('../assets/img/dy.jpg') no-repeat 0 0;
            background-size: 100% 100%;
        }
    .detail-box{
        padding: 0 .4rem;
        h3{
            font-size: .28rem;
            font-weight: bold ;
            line-height: .74rem;
        }
        p{  
            font-size: .28rem;
            line-height: .5rem;
        }
        .detail-info{
            margin-top:   .4rem;
            padding-bottom: .4rem;
            white-space: pre-wrap;
        }
        .handletime{
            color: #ef6824;
        }
        .bottonlink{
            margin-bottom: 20px;
        }
    }
   
 
</style>

