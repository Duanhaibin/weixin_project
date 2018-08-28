<template>
    <div class="searchlist">
        <div class="header-box border-bottom">
            <header class="header" >
                <i class="iconfont icon-search head-left"></i>
                <div class="head-search" >
                <input @focus="handleSearch"  v-model="searcharea"  type="text"   placeholder="输入城市/景区/时讯主题">
                </div>
				
            </header>
        </div>

        <div class="list">
			<div class="list-head">
				<div class="list-title border-top">
					<div>快速找动态</div> 
					<div class="choose-address">

						<x-button  @click.native.stop="setList"  type="primary">{{area}}</x-button>
						<i @click.native.stop="setList"  class="iconfont  icon-xialasanjiao"></i>
					</div>
				</div>
			</div>
			<ul class="type-box">
				<li class="border-rightbottom"  :class="{active:activeIndex===index}"  @click="activeType(index,item.id,item.name)" v-for="(item,index) in typelist" :id="item.id">
					{{item.name}}
				</li>
				
			</ul>
		</div>
        <ul class="list-box ">
            <li v-for="(item , index ) in  searchMsg"   @click="hmtDetail(item.id)" class="border-bottom">
				<router-link :to="{name:'Detail',query:{id: item.id}}">
					<h1>
					{{item.ta_name}}
						<!-- <router-link :to="{name:'Detail',params:{id: item.id}}">紧急任务紧急任务紧急任务紧急任务紧急任务紧急任务紧急任务紧急任务</router-link>  -->

					<span class="border" v-show="!!item.newest" >{{item.newest}}</span>
					</h1>
					<p>{{item.content}}</p>
					<div class="more-box">
						<div class="label border" v-show="!!item.detail_type_text">
							{{item.detail_type_text}}
						</div>
						<div v-show="!item.detail_type_text"></div>
						<p>
							<i class="iconfont  icon-shijian"></i>
						{{item.release_time}}
						</p>

					</div>
				</router-link> 
            </li>
			<infinite-loading @infinite="infiniteHandler" ref="infiniteLoading">
				<span slot="no-more">
					没有内容了
				</span>
			</infinite-loading>
        </ul>

		<div class="pick-mask" @click.stop="pickerClick" v-show='showPicker'>
			<div  class="area-list">
				<p> <span @click.stop="cancelArea" class='left'>取消</span/> 请选择地区  <span class="right" @click.stop="confirmArea">确定</span/></p>
			    <picker :data='year7'  :columns=2 v-model='year8Value' @on-change='change'></picker>
			</div>
		</div>

		<!-- <pre>{{year8Value}}</pre> -->
    </div>
</template>

<script>
import { Cell, Group, Picker, GroupTitle, XButton } from "vux";
import InfiniteLoading from 'vue-infinite-loading';
export default {
    name: 'searchlist',
    components: {
		// "index-head": Head,
		Picker,
		GroupTitle,
		XButton,
		Cell,
		Group,
		InfiniteLoading,
	},

	data() {
		return {
			area: this.$store.getters.getCity,
			provance: '',
			areaArr: [],
			searchMsg: [],
			searcharea: '',
			postData: {
				page: 1,
                page_size: 10 ,
                province_name: this.$store.getters.getProv ,
				city_name : this.$store.getters.getCity == '请选择地址'?'':this.$store.getters.getCity,
				detail_type: '',
				word: this.searcharea
			},
			year7: [],
			year7Value: [],
			year8Value: [],
			showPicker: false,
			typelist:[],
			activeIndex: 9 ,
			typeId: '',
			typeTndex: 99,
			nowProv: '',
			nowCity: '',
			typeName: ''
		}
	},
	created() {
		 _hmt.push(['_trackEvent', '景区时讯_搜索列表', 'view', '12301服务号']);
		this.fetchProvData()
		this.fetchlist()
		this.searcharea = this.$route.params.area || '';
		this.postData = {
			page: 1,
			page_size: 10 ,
			province_name: this.$store.getters.getProv ,
			city_name : this.$store.getters.getCity == '请选择地址'?'':this.$store.getters.getCity,
			detail_type: '',
			word: this.searcharea
		};
		this.area = this.$store.getters.getCity;
		if(this.area == "请选择地址") {
			var map = new AMap.Map('nuMap');
			map.plugin('AMap.Geolocation', () => {
				var geolocation = new AMap.Geolocation({timeout: 20000});
				geolocation.getCurrentPosition();
				AMap.event.addListener(geolocation, 'complete', data => {
				this._province = this._city = '';
				this._province = data.addressComponent.province;
				this._city = data.addressComponent.city;
				this.area = data.addressComponent.city;
				// this.address = this._city || this._province;
				this.area = this._city || this._province;
				this.provance = this._province ;
				if(this.searcharea == ''){
					this.postData=  {
						page: 1,
						page_size: 10 ,
						province_name: this._province ,
						city_name : this.area,
						detail_type: this.typeId,
						word: this.searcharea
					}
					this.searchMsg = [];
					this.fetchData(this.postData)

				}

				
				});
				AMap.event.addListener(geolocation, 'error', err => console.log(err));
			});
		}
		
	},
	methods: {
		hmtDetail(id){
			window.sessionStorage["searchId"] = id;
			_hmt.push(['_trackEvent', '景区时讯_搜索列表详情', 'click', '12301服务号']);
			// // this.$store.commit('changeID',id);
			// this.$store.dispatch('setID',id)

		},
		handleSearch(){
			_hmt.push(['_trackEvent', '景区时讯_搜索列表搜索', 'click', '12301服务号']);
			this.$router.push({
				name: 'Search',
				params:{
					value: this.searcharea
				}
			});
		},
		change (value) {
			var that = this;
			this.areaArr = value ;

			if(this.provance !== this.areaArr[0] ) {
				this.provance= this.areaArr[0];
				setTimeout(function() {
					that.fetchCityData(that.provance)
				}, 100)
			}
		},
		activeType(index,id ,name) {
			_hmt.push(['_trackEvent', '景区时讯_搜索列表分类', 'click', '12301服务号']);
			_hmt.push(['_trackEvent', '搜索列表分类_'+name, 'click', '12301服务号']);
			this.typeName = name ;
			if(this.activeIndex != index){
				this.activeIndex = index
			}else {
				this.activeIndex = 9
				id= ''
			}
			this.searchMsg = [];
			this.postData=  {
				page: 1,
				page_size: 10 ,
			    province_name: this.$store.getters.getProv ,
				city_name : this.$store.getters.getCity == '请选择地址'?'':this.$store.getters.getCity,
				detail_type: id,
				word: this.searcharea
			}
			this.typeId = id;
			this.postData.province_name = this.$store.getters.getProv||'';
			this.postData.city_name =  (this.area == '请选择地址')?'':this.area;

			// this.fetchData(this.postData)
			this.$nextTick(() => {
				this.$refs.infiniteLoading.$emit('$InfiniteLoading:reset');
			});
			
		},
		fetchlist() {
			_hmt.push(['_trackEvent', '景区时讯_搜索列表分类', 'ajax', '12301服务号']);
			this.$http.jsonp(this.$con.api('/openapi/dyQuickTypes')).then(result => {

				this.typelist = result.body.resp_data ;

			
			})

		},
		fetchProvData() {
			 _hmt.push(['_trackEvent', '景区时讯_省级列表', 'ajax', '12301服务号']);
			this.$http.jsonp(this.$con.api('/region/prov_list')).then(result => {
				for(var i of result.body){
					this.year7.push({
						name: i,
						value: i ,
						parent: 0
					})
				}
			
			}, err => {
				console.log(err)
			})
		},
		fetchCityData(prov) {
			 _hmt.push(['_trackEvent', '景区时讯_市级列表', 'ajax', '12301服务号']);
			this.$http.jsonp(this.$con.api('/region/city_list?name='+prov)).then(result => {
				this.year7 = this.year7.slice(0,36);
				for(var i of result.body){
					if(i.indexOf('全国') > -1){
						this.year7.push({
							name: i,
							value: i ,
							parent: prov
						});
					}
					else{
						this.year7.push({
							name:  '全国',
							value: '全国' ,
							parent: '全国'
						});
						this.year7.push({
							name: i,
							value: i ,
							parent: prov
						});
					}
				};
			}, err => {
				console.log(err)
			})

		},
		fetchData(info) {
			_hmt.push(['_trackEvent', '景区时讯_搜索列表', 'ajax', '12301服务号']);
            this.$http.post(this.$con.api('/dynamic/search'), info).then(result => {
				this.searchMsg =this.searchMsg.concat(result.body.resp_data.list)
				this.postData.page++
            }).catch(err => {
				console.log(err);
			})
		},
   
		setList () {
			this.showPicker = true;
		},
		confirmArea(a) {
			_hmt.push(['_trackEvent', '景区时讯列表_地域选择', 'click', '12301服务号']);
			let  nowCity = this.areaArr[1]||this.areaArr[0];
			let  nowProv = this.areaArr[0]||'';
			this.$store.dispatch('setCity',nowCity)
			this.$store.dispatch('setProv',nowProv)
			
			this.area =  this.$store.getters.getCity;
			this.showPicker = false ;
			this.searchMsg = [] ;
			this.postData = {
				page: 1,
                page_size: 10 ,
                province_name: this.$store.getters.getProv,
				city_name : this.$store.getters.getCity,
				detail_type: this.typeId,
				word: this.searcharea
			}
			// this.fetchData(this.postData)
			this.$nextTick(() => {
				this.$refs.infiniteLoading.$emit('$InfiniteLoading:reset');
			});
		
		},
		cancelArea() {
			this.showPicker = false ;
		},
		pickerClick() {
			this.showPicker = false ;
		},
		infiniteHandler($state) {
				_hmt.push(['_trackEvent', '景区时讯_搜索列表', 'ajax', '12301服务号']);
				_hmt.push(['_trackEvent', '搜索列表分类_'+this.typeName, 'ajax', '12301服务号']);
				this.$http.post(this.$con.api('/dynamic/search'),this.postData,{
					params:this.searchMsg.length/10 +1 
				}).then(res => {
					if (res.body.resp_data.list.length){
						this.searchMsg= this.searchMsg.concat(res.body.resp_data.list)
						setTimeout(function() {
							$state.loaded();
						},500)
						
						this.postData.page ++;
						if (this.searchMsg.length / 20 === 10) {
							$state.complete();
						}
					}else{
						$state.complete();
					}
				})
			
			
		}
		
	}

    
}
</script>



<style  lang='scss'>
    .searchlist{
        width: 100%;
        background: #fff;
        min-height: 100%;
        position: absolute;
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
                height: .55rem;
                line-height: .55rem;
                padding: .15rem 0;
                border: 0;
                background: #f4f4f4;
                font-family: "Microsoft Yahei",Arial;
                font-size: .28rem;
            }
        }
    }
    .list {
		button{
			font-size: .28rem;
			line-height:.28rem;
			background: none;
			color: #333;
		}
		.weui-btn::after{
			display: none
		}

	}
	.list-title {
		padding: .3rem 0.4rem;

		display: flex;
		justify-content: space-between;
		.choose-address {
			display: flex;
			line-height: 1.1;
		}
	}
	.type-box{
		margin: .2rem .4rem ;
		display: flex;
		justify-content: space-between;
		flex-wrap: wrap;
		li{
			width: 1.6rem;
			height: .8rem;
			// background: #f8f8f8;
			margin: .2rem  0;
			text-align: center;
			line-height: .8rem;
			background: linear-gradient(to bottom right, #eee 0%, #fff 25%, #fff 50%,#fff 75%, #eee 100%);
		}
		>li::before{
			box-shadow: 2px 0 4px  #0e6473;
		}
		>li::after{
			box-shadow: 0 2px 4px  #0e6473;
		}
		.active{
			background: linear-gradient(to bottom right, #54d6ec 0%, #2cbbd4 25%, #25a4bb 50%,#2cbbd4 75%, #54d6ec 100%);
			// background: #25a4bb;
			color: #fff;
		}
	}

	.pick-mask{
		position: fixed;
		top:0;
		bottom:0;
		left:0;
		right:0;
		background: rgba(0,0,0,.5)
	}
	.area-list{
		height: 5.3rem;
		width: 100%;
		background: #fff;
		z-index: 3;
		position: fixed;
		bottom: 0;
		.vux-picker {
			width: 100%;
			height: 4.5rem;
			position: fixed;
			bottom: 0;
			color: #fff;
			background: rgba(0,0,0,.4);
			background: #fff;
			
		}
		p{
			line-height: .8rem;
			text-align: center;
			font-size: .36rem;
			background: #f4f4f4;
			span{
				color: #3a9cd4;
			}
			.left{
				margin-left: .4rem;
			}
			.right{
				margin-right: .4rem;
			}
		}
	}
	
	.list-box {
		li {
			padding: 0.4rem 0.4rem 0;
			h1 {
				font-size: 0.28rem;
				font-weight: bold;
				padding: 0.1rem 0;
				margin-bottom: .1rem;
				span{
					float:right;
					padding:2px  5px;
					color: #ef6c2a;
					font-weight: normal;
					font-size: .24rem;
				}
				span::before{
					border-color: #ef6c2a;
					border-radius: 15px;
				
				}
			}
			p {
				font-size: 0.26rem;
				color: #333;
				line-height: 0.44rem;
				overflow : hidden;
				white-space: nowrap;
				display: block;
				text-overflow: ellipsis;
			}
			.more-box {
				display: flex;
				font-size: 0.2rem;
				justify-content: space-between;
				.label {
						padding: 0.15rem 0.3rem;
						margin: 0.2rem 0;
						// background: #e2f0f9;
						color: #3a9cd4;
				}
				.label::before{
					border-color: #3a9cd4;
					border-radius: 5rem;
				}
				p {
					color: #bbb;
					margin-top: 0.25rem;
					font-size: .24rem;
					i{
						font-size: .24rem;
					}
				}
			}
		}
		li:active{
			background: #eee;
		}
	}
	
    
    

  
</style>
