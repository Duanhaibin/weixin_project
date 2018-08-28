<template>
    <div class="list">
		<div class="list-head">
			<!-- <index-head></index-head>  -->
			<div class="list-title border-top">
				<div>快速找动态</div> 
				<div class="choose-address">
					<!-- <x-button @click.native.stop="setList" type="primary">{{$store.getters.getcity}}</x-button> -->
					<x-button @click.native.stop="setList" type="primary">{{area}}</x-button>
					<i class="iconfont  icon-xialasanjiao"></i>
				</div>
			</div>
			<ul class="type-box">
				<li v-for= "(item,index) in typeList" :type-key="index">{{item}}</li>
				
			</ul>
		</div>
		<div class="pick-mask" v-show='showPicker'>

			<div @click.stop="pickerClick" class="area-list" >
				<p> <span @click.stop="cancelArea" class='left'>取消</span/> 请选择地区  <span class="right" @click.stop="confirmArea">确定</span/></p>
				<picker  :data='year7' :columns=2 v-model='year8Value' @on-change='change'></picker>
			</div>
		</div>
        <ul class="list-box " >
            <li v-for="(item , index ) in  searchMsg" class="border-bottom">
                <!-- <h1>
                   <router-link :to="{ name: 'Detail', params: { id: item.id }}">{{item.ta_name}}</router-link> 
                </h1> -->
				<h1>
                   <router-link :to="{name:'Detail',params:{id: item.id}}">{{item.ta_name}}</router-link> 
                </h1>
                <p>{{item.content}}</p>
                <div class="more-box">
                    <div>
                        线路调整
                    </div>
                    <p>
                       {{item.release_time}}
                    </p>

                </div>
            </li>
			<infinite-loading @infinite="infiniteHandler">
				<span slot="no-more">
					没有内容了
				</span>
			</infinite-loading>
             
        </ul>

		
        

		<!-- <pre>{{year8Value}}</pre> -->
    </div>
</template>
<script>
// import Head from "../components/head";
import { Cell, Group, Picker, GroupTitle, XButton } from "vux";
import InfiniteLoading from 'vue-infinite-loading';

export default {
	name: "search",
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
			area: '选择城市',
			areaArr: [] ,
			provance: '',
			typeList: ['景区','酒店','旅行社','商场','导游','出租','餐饮','娱乐'],
			postData:{
				page: 1,
                page_size: 10 ,
                province_name: '云南' ,
                city_name : '昆明'
			},
			pickShow: false,
			searchMsg: [],
			year7: [],
			year7Value: [],
			year8Value: ["USA", "usa002", "0006"]
		};
	},
	props:['showPicker'],
	
	created() {
		this.fetchProvData()
		this.fetchCityData()
		// this.fetchData(this.postData)
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
			});
			AMap.event.addListener(geolocation, 'error', err => console.log(err));
		});
	},
	methods: {
		setList() {
			this.$store.commit('changeCity','上海');
			this.$store.dispatch('layerchange')
			this.$emit('changePicker', true)
			this.year7.push(	{
					name: "美国002",
					value: "usa002",
					parent: "USA"
				});
		},
		change(arr) {
			let that = this; 
			this.areaArr = arr;
			if(this.provance !== this.areaArr[0] ) {
				this.provance= this.areaArr[0];
				setTimeout(function() {
					that.fetchCityData(that.provance)
				}, 100)
			}
		},
		confirmArea() {
			this.$emit('changePicker', false)
			this.area =  this.areaArr[1];
			this.searchMsg =[];
			this.postData = {
				page: 1,
                page_size: 10 ,
                province_name: this.areaArr[0] ,
                city_name : this.areaArr[1]
			}
			// this.infiniteHandler();
			this.fetchData(this.postData)
		},
		cancelArea() {
			this.$emit('changePicker', false)			
		},
		pickerClick() {
			this.$emit('changePicker', true)
		},
		fetchProvData() {
			let that = this;
			this.$http.get('/region/prov_list').then(result => {

				for(var i of result.body){
					that.year7.push({
						name: i,
						value: i ,
						parent: 0
					})
				}
			
			})
		},
		fetchCityData(prov) {
			let that = this;
			this.$http.get('/region/city_list?name='+prov).then(result => {
				for(var i of result.body){
					that.year7.push({
						name: i,
						value: i ,
						parent: prov
					})
				}
			
			})

		},
		fetchData(info) {
			let that = this
            this.$http.post('/dynamic/search', info).then(result => {
				this.searchMsg =this.searchMsg.concat(result.body.resp_data.list)
				this.postData.page++
            }).catch(err => {
				console.log(err)
			})
		},
		infiniteHandler($state) {
				this.$http.post('/dynamic/search',this.postData,{
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
};
</script>
<style scoped lang="scss">
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
		}
	}
	.type-box{
		margin: 0  .4rem;
		display: flex;
		justify-content: space-between;
		flex-wrap: wrap;
		li{
			width: 1.6rem;
			height: .8rem;
			background: #f8f8f8;
			margin: .2rem  0;
			text-align: center;
			line-height: .8rem;
		}
	}

	.pick-mask{
		position: fixed;
		top:0;
		bottom:0;
		left:0;
		right:0;
		z-index: 99;
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
			font-size: 18px;
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
				font-size: 0.26rem;
				font-weight: bold;
				padding: 0.1rem 0;
			}
			p {
				font-size: 0.2rem;
				line-height: 0.44rem;
			}
			.more-box {
				display: flex;
				font-size: 0.2rem;
				justify-content: space-between;
				div {
						padding: 0.15rem 0.3rem;
						margin: 0.2rem 0;
						background: #e2f0f9;
						color: #3a9cd4;
				}
				p {
					margin-top: 0.25rem;
				}
			}
		}
	}
</style>

