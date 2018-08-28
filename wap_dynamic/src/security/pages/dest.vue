<template>
    <div class="destlist">
        <ul class="list-box ">
			<li v-for="(item,index) in searchMsg" class="border-bottom" @click="hmtDetail(item.id)">
				<router-link  :to="{name:'Detail',query:{id: item.id}}">
					<span class="border  flag-border" >
						<span class=" flag-box flag-icon" :class="setClass(item.country_code2l)"></span>
					</span>
					
					<div>
						<div class="slist-title">
							<h3>{{item.country_name}} &nbsp;&nbsp;&nbsp;&nbsp;
								<span class="border" :class="setClolr(item.type)">{{item.type ==2?'紧急':'常规' }}</span>    
								
							</h3>
							<span>{{item.diff_time}}</span>
						</div>

						<p>{{item.content}}</p>

					</div>

				</router-link>
			</li>
			<infinite-loading @infinite="infiniteHandler" >
				<span slot="no-more">
					没有内容了
				</span>
			</infinite-loading>
           
        </ul>

    </div>
</template>

<script>

import InfiniteLoading from 'vue-infinite-loading';



export default {
	name: "search",
	components: {
		InfiniteLoading
	},
	created() {
		_hmt.push(['_trackEvent', '安全提醒_国家列表', 'view', '12301服务号']);

    },

	data() {
		return {
			searchMsg: [],
			postData:{
				page: 1,
                page_size: 10 ,
				search_word: this.$route.params.area,
				coutry_code: this.$route.params.code
			}
	
		};
	},
	
	methods: {

		setClass(key) {
			return 'flag-icon-'+key
		},
		setClolr(type){
			if(type ==1 ){
				return 'normal'
			}
		},
		hmtDetail(id){
			window.sessionStorage["searchId"] = id;
			_hmt.push(['_trackEvent', '安全提醒_国家列表', 'click', '12301服务号']);
		},
		fetchData(info) {
			_hmt.push(['_trackEvent', '安全提醒_国家列表', 'ajax', '12301服务号']);
			this.$http.post(this.$con.api('/security/search'),info).then(res =>{
				this.searchMsg = this.searchMsg.concat(res.body.resp_data.list)
				this.postData.page++

			})
		},
		infiniteHandler($state) {
			_hmt.push(['_trackEvent', '安全提醒_国家列表', 'ajax', '12301服务号']);
			this.$http.post(this.$con.api('/security/search'),this.postData,{
				params:this.searchMsg.length/10 +1 
			}).then(res => {
				if (res.body.resp_data.list.length){
					this.searchMsg= this.searchMsg.concat(res.body.resp_data.list)
					setTimeout(function() {
						$state.loaded();
					},500)
					
					this.postData.page ++;
					if (this.searchMsg.length / 10 === 10) {
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
	.destlist {
        background: #fff;
        min-height: 100%;
		position: absolute;
		width:100%;
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
		justify-content: flex-start;
		flex-wrap: wrap;
		li{
			width: 1.5rem;
			height: .8rem;
			background: #f8f8f8;
			margin: .2rem .2rem  0 0;
			text-align: center;
			line-height: .8rem;
		}
		li:nth-child(4){
			margin-right: 0;
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
		li:active{
			background: #eee;
		}
		li {
			padding: .4rem  ;
			position: relative;
			.flag-border{
				display: inline-block;
				padding: 0;
				width: 1.68rem;
				height:1.2rem;
				.flag-box{
					width: 100%;
					height: 100%;
					margin: 0;
				}
			}
			.flag-border::before{
				box-shadow: 10px 15px 20px #555;
			}

			a{
				display: flex;
				>div{
					width: 80%;
					overflow: hidden;
					.slist-title{
						padding: .05rem  0   .05rem   .4rem;
						display: flex;
						justify-content: space-between;
						h3{
							font-weight: bold;
							font-size: .32rem;
                            color: #333;
                            >span{
                                padding: 0.03rem  .1rem;
                                position:relative;
                                top: -0.03rem;
                                color: #ce1326;
                                font-weight: normal;
							}
						
						}
						span{
							font-size: .24rem;
							color: #ef6824;
                        }
                        span::before{
                            border-color: #ce1326;
                            border-radius: 1rem
						}
						.normal{
							color: #25a4bb;
						}
						.normal::before{
                            border-color: #25a4bb;
                            border-radius: 1rem
						}
					}
					>p{
						color: #666;
						width: 5rem;
						font-size: .28rem;
						overflow: hidden;
						padding-top: .15rem;
						margin:.15rem 0 0 .4rem;
						text-overflow:ellipsis;
						white-space: nowrap;
						word-break:break-all;
					}
				}	
				
				
			}
			
		}
	}
</style>

