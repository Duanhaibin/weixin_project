<template>
	<div class="letter">
		<div class="prompt">
			感谢您的参与，我们会马上核实您提供的景区情况并联系解决，同时通过12301国家智慧旅游公共服务平台提醒更多游客关注。请用语音输入的方式录入您要提供的信息，录音时间不超过60秒，最后请提交现场图片。
		</div>
		<ul>
			<li v-for="(item, index) in msg" :key="index"   >
				<div :class="setClass(index)">
					<div class="header"  :class="setBGClass(index)"   :style="{ 
						'background': typmsg[index] ? 'url('+ userhead +') no-repeat 0 0':''  ,
						'background-size': '100% 100%'
						}">

					</div>
					<span>
						{{item}}
					 	<p class="converbox">
							<span>
								<i class="iconfont  icon-icon-"></i>
								转化成功
							</span>	 

							<i v-show="editArr[index]" @click = "edit(index)" class="iconfont  icon-bianji-01"></i>

						</p>
						
					</span>
				</div>

				<p  class="voice-menu "  v-show="editArr[index]">
					您是否表达清楚？
					<span class="right handle-btn border" @click="submit(index,item)">
						确定
					</span>
					<!-- <span class="right handle-btn" @click = "edit(index)">
						编辑
					</span> -->
					<span class="right handle-btn border" @click = "again(index)">
						重录
					</span>
					
				</p> 
			</li>
			<li v-show="theLast">
				<upload   @change = "change" @isloadings="isloadingsState"></upload>
			</li>
		
		</ul>


	
		
		<div class="btn-box" v-show="canSay">
			
			<div v-show="!theLast"  class="vbtn"  @touchstart.stop.prevent= "start" @touchend.stop.prevent="end" @touchmove.prevent="move">
				<i class="iconfont  icon-yuyin"></i>
			</div>

			<div v-show="theLast" class="suball" @click="submitAll()">
				确认提交
			</div>
		</div>


		<div v-show="isedit" class="mask">
			<div class="edit-box">
				<h3 class="border-bottom">编辑内容</h3>
				<textarea v-model="editmsg" name="" id="" cols="30" rows="10"></textarea>
				<div class="edit-btnbox  border-top">
					<span class="border-right" @click="cancel">取消</span>
					<span @click="update">确定</span>
				</div>
			</div>
			
		</div>


		<!-- <div  class="ing" v-show="isrecording">
			<i class="iconfont  icon-techreport-"></i>
			<p>录音中...</p>	

		</div> -->
		<div class="cordingBox" id="container" v-show="isrecording" type="text" ref="a" @touchend.stop="endmove">
			<!-- <i class="iconfont  icon-techreport-"></i> -->
			<span v-show="!ismove"></span>
            <span v-show="!ismove"></span>
            <span v-show="!ismove"></span>
            <span v-show="!ismove"></span>
            <span v-show="!ismove"></span>
			<div class="iconBox">
				<i class="iconfont icon-return" v-show="ismove"></i>
			</div>
			
			<p>{{movemsg}}</p>	
			</div>
		<toast v-model="prob" type="text">{{probInfo}}</toast>

		
	</div>



</template>



<script>
	import upload from "../components/UploadFiles";
	import { Toast} from 'vux'
	export default {
		components: {
			upload,
			Toast
		},
		data() {
			return {
				msg:['亲爱的游客， 请说出您目前所在的景区名。'],
				typmsg:[0],
				editArr:[0],
				isedit: false,
				editmsg: '',
				index: '', //确定编辑信息的位置
				userhead: HEADIMG,
				canSay: true,
				theLast: false,
				isrecording: false,
				prob:false,
				probInfo: '',
				timer: null,
				time: 0,
				movemsg: '手指上滑,取消发送',
				ismove: false,
				movecancel: false,
				province_name :'',
				city_name :'',
				county_name : ''
			}
		},
		created() {
			_hmt.push(['_trackEvent', '景区时讯_现场报道', 'view', '12301服务号']);
			var map = new AMap.Map('nuMap');
			map.plugin('AMap.Geolocation', () => {
				var geolocation = new AMap.Geolocation({timeout: 20000});
				geolocation.getCurrentPosition();
				AMap.event.addListener(geolocation, 'complete', data => {
					this.province_name  = data.addressComponent.province||'';//省
					this.city_name = data.addressComponent.city||'';//市
					this.county_name = data.addressComponent.district||'';//区
			
				});
				AMap.event.addListener(geolocation, 'error', err => console.log(err));
			});
		},

		methods:{
			move(e){
				let that = this; 
				let touch = e.targetTouches[0];
				let left = this.$refs.a.getBoundingClientRect().left;
				let right =  this.$refs.a.getBoundingClientRect().right;
				let top =  this.$refs.a.getBoundingClientRect().top;
				let bottom =  this.$refs.a.getBoundingClientRect().bottom;
				if(touch.clientY<=(bottom+50)) {
					that.ismove = true;
					that.movemsg = '手指松开,确定取消';
					that.movecancel = true;

				}else{
					that.ismove = false;
					that.movemsg = '手指上滑,取消发送';
					that.movecancel = false;

				}
			
			},
			endmove(){

			},
			setClass(index) {
				return this.typmsg[index]? 'right': 'left';	
			},
			setBGClass(index){
				if(index%2 ==0){
					return  'selfHead'
				}
			},
			start() {
				_hmt.push(['_trackEvent', '景区时讯_现场报道开始录音', 'touchstart', '12301服务号']);
				let that = this
				this.movemsg='手指上滑,取消录音';
				this.ismove= false;
				this.time = 0 ;
				this.timer  = null ;
				this.isrecording = true;
				wx.stopRecord()
				let  shijian = function () {
					if(that.time >3 ){
						that.timer = null ;
						wx.startRecord();
					}else {
						that.time ++ ;
						that.timer = setTimeout(shijian, 100)
					}
					
				}
				shijian()
				
			},
			end() {
				_hmt.push(['_trackEvent', '景区时讯_现场报道结束录音', 'touchend', '12301服务号']);
				let that = this
				this.isrecording = false;
				this.timer = null;
				if(this.time <=3){
					this.prob = true;
					this.probInfo ='说话时间太短' ;
					wx.stopRecord()
				}else{
					if(this.movecancel) {
						wx.stopRecord()

					}else{
						wx.stopRecord({
							success: function (res) {
								let localId = res.localId;
								wx.translateVoice({
									localId: localId, // 需要识别的音频的本地Id，由录音相关接口获得
									isShowProgressTips: 1, // 默认为1，显示进度提示
									success: function (res) {

										if(res.translateResult) {
											that.msg.push(res.translateResult);
											that.canSay = false ;
											that.typmsg.push(1);
											that.editArr.push(1)
										}else{
											that.probInfo = '无法识别,请重新录入'
											that.prob = true;
										}
										

									}
								});
							}
						});
					}

				}
			
				
			},
			edit(index){
				this.editmsg = this.msg[index];
				this.index = index;
				this.isedit = true;
			},
			cancel() {
				this.isedit = false;
			},
			again(index) {
				this.msg.splice(index,1);
				this.typmsg.splice(index,1);
				this.editArr.splice(index,1);
				this.canSay = true;
			},
			update() {
				this.msg.splice(this.index, 1 , this.editmsg)
				this.isedit = false;
				
			},
			submit(index,item) {

				if(item == ''||item == undefined){
					this.prob = true;
					this.probInfo ='没有录入任何内容，点击重录再次录入。' ;
				}else{
					if(index == 1){
						this.msg.push('请简单并准确地描述您需要报道的内容。')
						this.typmsg.push(0)
						this.canSay = true;
						this.editArr[index]=0;
						this.editArr.push(0)
					}
					if(index == 3){
						this.msg.push('请上传相关凭证。')
						this.editArr[index]=0;
						this.editArr.push(0)
						this.typmsg.push(0)
						this.canSay = true;
						this.theLast = true;
				
					}
				}
				
				

				// this.$router.push({name: 'Success'});
			},
			submitAll() {
				_hmt.push(['_trackEvent', '景区时讯_现场报道提交', 'click', '12301服务号']);
				let info  =  {
					oid: OPENID,
					aid: ACCOUNTID,
					ta_name:this.msg[1],
					content:this.msg[3],
					province_name :this.province_name,
					city_name :this.city_name,
					county_name : this.county_name,
					attachment: this.attachment.length>0?JSON.stringify(this.attachment):0
				}


				this.$http.post(this.$con.api('/dynamic/add'), info).then(result => {
					_hmt.push(['_trackEvent', '景区时讯_现场报道提交', 'ajax', '12301服务号']);
					if(result.body.error_code == 0){
						this.$router.push({name: 'Success'});
					}else{
						this.probInfo=result.body.error_msg;
						this.prob = true;

					}

				}).catch(err => {
					alert(22222)

				})

				// this.$router.push({name: 'Success'});
			},
			
			change(imgArr,b) {
				this.attachment = imgArr;
			},
			isloadingsState(val) {
				this.loading  = val;
			},

		},
		watch: {
			msg () {
				this.$nextTick(function(){ 
					document.scrollingElement.scrollTop = document.scrollingElement.scrollHeight;
				}) 

			}
		}

	};
</script>

<style  lang="scss" >

	.letter{
		position: absolute;
		background: #eee;
		font-size: 14px;
		width: 100%;
		ul{
			margin-bottom : 200px;
			overflow: hidden;
		}
		ul li{
			margin: 30px 10px;
			font-size: 14px;
			display: flex;
			flex-direction: column ;
			.voice-menu{
				height: 38px;
				line-height: 38px;
				background: #fff;
				margin: 15px 55px 0 60px;
				font-size: 12px;
				font-weight: 200;
				padding: 0 10px;
				>span{
					display: inline-block;
					width: 40px;
					height: 24px;	
					text-align: center;
					margin: 7px 5px ;
					line-height: 26px;
					font-weight: normal;
				}

				>span:nth-child(1){
					background: #3a9cd4;
					color: #fff;
				}
				>span:nth-child(2){
					color: #333;
				}
			}
			>div{
			display: flex;
			width: 100%;
			
			.header{
				width: 40px;
				height: 40px;
				min-width: 40px;
				background: yellow;
			}
			.selfHead{
				background: url('../assets/img/h.png') no-repeat 0 0;
				background-size: 100% 100%;
			}
			.hide{
				display: none;
			}
			>span{
				font-size: 14px;
				line-height: 20px;
				padding: 10px;
				background: #fff;
				margin: 0  15px;
				position: relative;
				border-radius: 5px;
			}
			}
		}

		ul .left{
			flex-direction: row;
			>span{
				margin-right: 60px;
				p{
					display: none;
				}
			
			}
			>span::before{
				content: '';
				display: block;
				width:0;
				height: 0;
				border-style: solid;
				border-width: 7px 7px 7px 7px;
				border-color: transparent #fff transparent transparent;
				position: absolute;
				top: 13px;
				left: -14px;
			}
		}
		ul .right{
			flex-direction: row-reverse;
			.converbox{
				display: flex;
				justify-content:space-between;
				margin-top:5px;
				.icon-bianji-01{
					color:#ef6824;
					font-size: 16px;

				}

			}
			>span{
				margin-left: 60px;
				p{
					font-size: 12px;
					color: #bbb;
					width: 100%;
					i{
						font-size: 12px;
						margin: 0  5px;
					}
					.handle-btn{
						color: #ef6824;
						margin-right: 20px;
					}

				}
			}
			>span::before{
				content: '';
				display: block;
				width:0;
				height: 0;
				border-style: solid;
				border-width: 7px 7px 7px 7px;
				border-color: transparent  transparent transparent #fff;
				position: absolute;
				top: 13px;
				right: -14px;
			}
			
		}
		.prompt{
			font-size: 12px;
			font-weight: 200;
			text-indent: 28px;
			line-height: 1.5;
			padding:  8px  15px;
			background: #fff;
		}
		.btn-box{
			position: fixed;
			height: 100px;
			bottom: 0 ;
			width: 100%;
			display: flex;
			align-items:top;
        	justify-content:center;
			// user-select: none;
			.vbtn{
				width: 70px;
				height: 70px;
				background: #fff;
				text-align: center;
				border-radius: 70px;
				i{
					line-height: 70px;
					font-size: 30px;
					color: #ef6824;
				}

			}
			.vbtn:active{
				background: #ddd;
			}
		}

		.mask{
			position: fixed;
			top: 0;
			bottom: 0;
			left: 0;
			right: 0;
			z-index: 9;
			background: rgba(0,0,0, .4);
			display: flex;
			align-items:center;
        	justify-content:center;
			.edit-box{
				background: #fff;
				width: 90%;
				h3{
					line-height: 36px;
					text-align: center;
					font-size: 18px;
				}
				textarea{
					width: 90%;
					margin-left: 5%;
					border-color:transparent;
					border:none;
					outline: none;
					
				}
				.edit-btnbox{
					display: flex;
					justify-content:space-around;
					line-height: 36px;
					span{
						flex:1;
						text-align: center;	
					}
					span:active{
						background: #eee;
						color: #ef6824;
					}
				}
			}
		}
	}
	.suball{
		background: #ff6301;
		height: 38px;
		line-height: 38px;
		width: 200px;
		text-align: center;
		color: #fff;
		font-size: 16px;
		position:relative;
		z-index: 9999999999;

	}
	.files-upload .file-btn{
		border-color: #ff6301;
	}

	.files-upload .file-btn::before{
		background: #ff6301;
	}
	.files-upload .file-btn::after{
		background: #ff6301;
	}
	.cordingBox{
		position: fixed;
		top: 180px;
		width: 120px;
		height: 120px;
		background: rgba(0,0,0, .4);
		left: 50%;
		margin-left: -60px;
		color: #fff;
		border-radius: 10px;
		text-align: center;
		p{
			line-height: 28px;
			position: absolute;
			bottom: 15px; 
			font-size:12px;
			text-align: center;
			width: 100%;
			
		}
		i{
			font-size: 50px;
			position:relative;
			right:20px;
			
		}
		.iconBox{
			transform:rotate(-90deg);
			-ms-transform:rotate(-90deg); 	
			-moz-transform:rotate(-90deg); 	
			-webkit-transform:rotate(-90deg); 
			-o-transform:rotate(-90deg); 	
		}	

	}


	#container span{
		width:5px;
		height: 5px;
		bottom:60px;
		position:absolute;
		background:#fff;
		-webkit-animation: bodong 0.5s infinite  ease;
		-moz-animation: bodong 0.5s infinite  ease;
		-ms-animation: bodong 0.5s infinite  ease;
		animation: bodong 0.5s infinite  ease;
	}

	#container span:first-child{
		left:30px;
		-webkit-animation-delay:.3s;
		-moz-animation-delay: .3s;
		-ms-animation-delay: .3s;
		-o-animation-delay: .3s;
		animation-delay: .3s;
	}

	#container span:nth-child(2){
		left:44px;
		-webkit-animation-delay:.4s;
		-moz-animation-delay: .4s;
		-ms-animation-delay: .4s;
		-o-animation-delay: .4s;
		animation-delay: .4s;

	}
	#container span:nth-child(3){
		left:58px;
		-webkit-animation-delay:.6s;
		-moz-animation-delay: .6s;
		-ms-animation-delay: .6s;
		-o-animation-delay: .6s;
		animation-delay: .6s;
	}
		#container span:nth-child(4){
		left:72px;
		-webkit-animation-delay:.8s;
		-moz-animation-delay: .8s;
		-ms-animation-delay: .8s;
		-o-animation-delay: .8s;
		animation-delay: .8s;
	}
	#container span:nth-child(5){
		left:86px;
		-webkit-animation-delay:1s;
		-moz-animation-delay: 1s;
		-ms-animation-delay: 1s;
		-o-animation-delay: 1s;
		animation-delay: 1s;
	}

	@-webkit-keyframes bodong{
		0%{height:5px; }
		30%{height:15px; }
		60%{height:25px; }
		80%{height:15px; }
		100%{height:5px; }
	}
	@-moz-keyframes bodong{
		0%{height:5px; }
		30%{height:15px; }
		60%{height:25px; }
		80%{height:15px; }
		100%{height:5px; }
	}
	@-ms-keyframes bodong{
		0%{height:5px; }
		30%{height:15px; }
		60%{height:25px; }
		80%{height:15px; }
		100%{height:5px; }
	}
	@keyframes bodong{
		0%{height:5px; }
		30%{height:15px; }
		60%{height:25px; }
		80%{height:15px; }
		100%{height:5px; }
	}
	

</style>

