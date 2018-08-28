<template>

    <div class="report-box">
        <!-- <report-banner :url-lists = "bannerUrl"></report-banner> -->

        <!-- <group>
            <i class="iconfont  icon-dizhi"></i>
            <x-input :required="true"   @click.native="show"  v-model="form.inspect_address"  title="景区地址" name="username" readonly  placeholder="请选择景区地址"></x-input>
        </group> -->

        <group>
            <i class="iconfont  icon-jingdian"></i>
            <x-input ref="scenic" :required="true"  title="景区名称"  v-model.trim="scenicName"  name="username" placeholder="请输入并选择景区名称" @input="handleScenic"  @on-focus="setAjax"></x-input>
        </group>
        <div class="listmask" @click="clooseMask" v-show="scenicRes">
            <ul class="sceniclist"  >
                <li @click.stop="checkList(item)" v-for="(item,index) in  list" class="border-bottom">
                    {{item.province}}
                    <span v-show="item.province">/</span>   
                    {{item.city}}
                    <span v-show="item.city">/</span>   
                    {{item.scenicName}}
                </li>
            </ul>
        </div>

        <group >
            <i class="iconfont  icon-icon-test up-icon"></i>
            <!-- <x-input :max="20" :placeholder="('标题')"></x-input> -->
            <x-textarea v-model.trim="content" :rows="8" :max="400" name="description" :placeholder="('内容')"></x-textarea>
        </group>

        <group>
            <i class="iconfont  icon-yonghu  icon-user"></i>

            <x-input ref="author" :required="true"   v-model.trim="author"  title="姓名" name="username" placeholder="请输入姓名" is-type="china-name"></x-input>
          
        </group>
       

        <group>
            <i class="iconfont  icon-shouhuorenshoujihao   up-icon  phone-icon"></i>
            <x-input ref="mobile" :required="true"  v-model.trim="phone" title="手机号码" name="mobile" placeholder="请输入手机号码" keyboard="number" is-type="china-mobile"></x-input>
        </group>

        <div class="upload-container">
            <upload @change = "change" @isloadings="isloadingsState"></upload>
            <p class="annotation">
                注： 上传图片、视频有助于景区动态发布审核，谢谢配合
            </p>
            
        </div>






        <div class="btn-box">
            <x-button @click.native= "handleSubmit">提交</x-button>
        </div>
        
       

       <toast width="12em" v-model="incomplete" type="text">{{toastmsg}}</toast>
       <toast width="12em" v-model="returnError" type="cancel">{{toastmsg}}</toast>
         
        <!-- <transition style="width:100%; height: 100%" name="up">
            <v-map v-show="showMap" :start="showMap"  @changemap='changeshow'  @address="getAddress" ></v-map>
        </transition>
     -->
    </div>
</template>

<script>
// import  Banner from '../components/banner'
// import vMap from '../components/LbsMap.vue'
import { XInput, XTextarea , Group, XButton, Cell,Toast } from 'vux'
import upload from "../components/UploadFiles"
export default {
    components: {
        // reportBanner: Banner,
        // vMap,
        XInput,
        XTextarea,
        XButton,
        Group,
        Cell,
        Toast,
        upload
    },
    data () {
        return {
            showMap: false,
            bannerUrl:  [
                // "https://ww1.sinaimg.cn/large/663d3650gy1fq66vvsr72j20p00gogo2.jpg",
                "https://ww1.sinaimg.cn/large/663d3650gy1fq66vw1k2wj20p00goq7n.jpg",
                "https://ww1.sinaimg.cn/large/663d3650gy1fq66vw50iwj20ff0aaaci.jpg"
            ],
            scenicName: '',
            content:'',
            author: '',
            phone:'',
            attachment: [],
            scenicRes: false,
            scenicajax: true,
            timeout : null,
            incomplete: false,
            loading: false,
            toastmsg: '',
            returnError: false ,
            msginfo:{
                nophone: '手机号码必填',
                errphone: '手机号码填写有误',
                noauthor: '姓名必填',
                errauthor: '姓名填写有误',
                noscenic: '景区名称必填',
                errscenic: '景区名称填写有误',
                nocontent: '报道内容必填',
                errcontent: '报道内容填写有误',
            },
            list: [],
            showinfo:'',
            hideinfo: false,
            ta_id:"",
            ta_name:"",
            alias_name:"",
            ta_level:'',
            province_name:"",
            city_name:"",
            county_name:"",
        
        }
    },
    methods: {
        // changeshow(val){
        //      this.showMap = val;
        // },
        // getAddress(ads) {
        //     this.form.inspect_address = ads.address
        //     this.form.inspect_province = ads.province
        //     this.form.inspect_city = ads.city
        //     this.form.inspect_name = ads.name
        //     this.showMap = false;
        // },
        // show() {
        //     this.showMap = true;
        // },
        
        isloadingsState(val) {
            this.loading  = val;
            console.log(val)
        },
        checkList(item) {
            this.scenicRes = false;
            this.scenicajax = false;
            if(!item.province&&!item.city){
                this.scenicName = item.scenicName
            }else if(!item.province) {
                this.scenicName = item.city+'/'+item.scenicName
            }else if(!item.city) {
                this.scenicName = item.province+'/'+item.scenicName
            }else{
                this.scenicName = item.province+'/'+item.city+'/'+item.scenicName
            }
            this.ta_id=item.scenicId;
            this.ta_name=item.scenicName;
            this.alias_name=item.alias;
            this.ta_level= item.level;
            this.province_name=item.province;
            this.city_name=item.city;
            this.county_name=item.district;
            this.hideinfo = true;
        },
        clooseMask(){
            this.scenicRes= false
        },
        setAjax() {
            this.scenicajax = true;
        },
        handleScenic() {
            var that = this;
            const info = {
                name: this.scenicName
            }
            clearTimeout(this.timeout);
            if(this.scenicajax) {
                this.hideinfo = false;
                this.timeout = setTimeout(function() {
               	that.$http.post(that.$con.api('/dynamic/searchScenic'),info).then(res => {
                    if(res.body.result.total > 0) {
                        that.list = res.body.result.data;
                        console.log(that.list)
                        that.scenicRes = true;
                    }else{
                        that.list = [{'scenicName': '没有相关搜索结果'}];
                        that.scenicRes = true;
                    }
                 
                })
            },300)
            }
          
        },
        handleSubmit() {
            if(this.loading){
                this.toastmsg = "上传尚未完成"
                this.incomplete = true;
                return
            }
            if(!this.hideinfo || !this.ta_id){
                this.toastmsg = "景区名称必须选择"
                this.incomplete = true;
                return
            }
            if(this.scenicName== ''){
                this.toastmsg = this.msginfo.noscenic;
                this.incomplete = true;
                return
            }
            if(this.content== ''){
                this.toastmsg = this.msginfo.nocontent;
                this.incomplete = true;
                return
            }
            if(this.author == ''){
                this.toastmsg = this.msginfo.noauthor;
                this.incomplete = true;
                return
            }
            if(!this.$refs.author.valid) {
                this.toastmsg = this.msginfo.errauthor;
                this.incomplete = true;
                return
            }
            if(this.phone == ''){
                this.toastmsg = this.msginfo.nophone;
                this.incomplete = true;
                return
            }
            if(!this.$refs.mobile.valid) {
                this.toastmsg =  this.msginfo.errphone;
                this.incomplete = true;
                return
            }
            
            const data = {
                content: this.content,
                tourist_tel: this.phone,
                ta_name: this.scenicName,
                author: this.author,
                attachment: this.attachment
            }
            this.$http.post('/dynamic/add',data).then(res => {
                if(res.body.error_code == 0) {
                    this.$router.push({name: 'Success'})
                }else{  
                    this.toastmsg =  res.body.error_msg
                    this.returnError = true;
                }
            })

        },
        change(imgArr,b) {
            this.attachment = imgArr;
        }

    }
    
    
}
</script>
<style  lang="scss">
.report-box{
    background: #fff;
    position: absolute;
    width: 100%;
    .listmask{
        position: absolute;
        top: 0;
        bottom: 0;
        left:0;
        right: 0;
        background:rgba(255,255,255,0);
        z-index: 9;
    }
    .sceniclist{
        position: absolute;
        top: 4.8rem;
        z-index: 9999;
        background: #f0f0f0;
        width: 100%;
        li{
            padding-left: .8rem;
            line-height: .6rem;
        }
    }
    .weui-cells{
        font-size: .28rem;
        padding-left: .5rem;
        position: relative;
        margin-top : 0;
        .iconfont{
            position: absolute; 
            left: .2rem;
            color: #333;
            font-size: .35rem;
        }
        .up-icon{
            // top: .06rem;
            font-size: .4rem;
        }
       
        .phone-icon{
            // top: .06rem;
            left: .14rem;
            font-size: .44rem;
        }
    }
    .weui-cells:before{
        display: none;
    }
    .vux-x-textarea::before{
        display: none;
    }
    .vux-x-input::before{
        display: none;
    }
    @media screen and (max-width: 600px) { /*当屏幕尺寸小于600px时，应用下面的CSS样式*/  
        .iconfont{
            top:.16rem;      
        }
        .icon-user{
            top:.14rem;
        }
        .phone-icon{
            top: .04rem;
        }
         .icon-icon-test{
            top: .06rem
        }
    }  

    @media screen and (min-width: 600px) { /*当屏幕尺寸小于600px时，应用下面的CSS样式*/  
        .iconfont{
            top: .08rem;      
        }
        .icon-user{
            top: .06rem;
        }
        .phone-icon{
            top: -0.02rem;
        }
        .icon-icon-test{
            top: 0;
        }
    }  
    @media screen and (min-width: 900px) { /*当屏幕尺寸小于600px时，应用下面的CSS样式*/  
        .iconfont{
            top: .03rem;      
        }
        .icon-user{
            top: .04rem;
        }
        .phone-icon{
            top: -0.08rem;
        }
        .icon-icon-test{
            top: -0.04rem;
        }
    }  



    .btn-box{
        padding: .4rem;
        .weui-btn{
            background: #ef6824;
            color: #fff;
            height: .8rem;
            font-size: .32rem;
            
        }
        .weui-btn:active{
            background: #ec4e00;
            color: #fff;
        }
    }
    .upload-container{
        margin:  .4rem .2rem; 
        .upload-box{
            height: 1rem;
            width: 1rem;
            text-align: center;
            line-height: 1rem;
            i{
                font-size: .6rem;
                color: #06a951;
            }

        }

    }
    .annotation{
        font-size:  .24rem;
        color : red;
        margin-top: .2rem;
    }
    
  
    
}
.report-img{
    height: 2rem;
    width: 100%;
}

</style>