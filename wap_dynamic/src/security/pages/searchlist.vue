<template>
    <div class="search" >
        <div class="header-box border-bottom">
            <header class="header" >
                <i class="iconfont icon-search head-left"></i>
                <div class="head-search" >
                    
                <input @focus="gotoSearch" v-model="searchArea"  type="text" placeholder="搜索目的地国家">
				
                </div>
				<!-- <span @click.stop="search" class="searchbtn border-left">搜    索</span> -->
            </header>
        </div>

        <div class="list-head">
			<ul class="type-box">
				<li class="border-rightbottom" :class="{active:num==index}" @click="continentSearch(item,index)" v-for="(item,index )  in  continent" >{{item}}</li>
			</ul>
		</div>
        
        <!-- <list  :choose-area="area"  @change="resolveArea"  v-if='isInput'></list> -->

         <ul class="list-box ">
			<li class="border-bottom" v-for="(item,index) in searchMsg" @click="hmtDetail(item.id)">
				<router-link  :to="{name:'Detail',query:{id: item.id}}">
					<!-- <div class="flag-box"   v-bind:style="{ 'background-image': 'url(' + item.country_flag + ')','background-repeat':'no-repeat','background-size':'cover' }">

					</div> -->
					<!-- <span class=" flag-box flag-icon flag-icon-gr"></span> -->
					<span class="flag-border  border  bgimg"  >

							<span class=" flag-box flag-icon" :class="setClass(item.country_code2l)"></span>
					</span>
					<!-- <span class=" flag-box flag-icon" :class="setClass(item.country_code2l)"></span> -->
					<!-- <img :src="item.country_flag" alt="" > -->
					<div>
						<div class="slist-title">
							<h3>{{item.country_name}}</h3>
							<span>{{item.diff_time}}</span>
						</div>

						<p>{{item.content}}</p>

					</div>

				</router-link>
			</li>
			<infinite-loading @infinite="infiniteHandler" ref="infiniteLoading">
				<span slot="no-more">
					没有内容了
				</span>
			</infinite-loading>
           
        </ul>
       

    </div>
</template>
<script>
import { Cell, Group, Picker, GroupTitle, XButton } from "vux";
import InfiniteLoading from 'vue-infinite-loading';

export default {
    name: 'search',
    components: {
		// "index-head": Head,
		Picker,
		GroupTitle,
		XButton,
		Cell,
		Group,
		InfiniteLoading
	},
    data () {
        return{
            isInput: true,
			hidePicker: false,
			num: 99,
            continent:['全部','亚洲','欧洲','北美洲','南美洲','非洲','大洋洲','南极洲'], 
            msg: [],
            searchArea:"",
            searchMsg:[],
            postData:{
				page: 1,
                page_size: 10 ,
                continent: ''
			}
        }
	},
	created() {
		_hmt.push(['_trackEvent', '安全提醒_搜索列表', 'view', '12301服务号']);
	},
  
    methods: {
		hmtDetail(id){
			window.sessionStorage["searchId"] = id;
			_hmt.push(['_trackEvent', '安全提醒_搜索列表详情', 'click', '12301服务号']);
		},
		setClass(key) {
			return 'flag-icon-'+key
		},
		gotoSearch() {
			this.$router.push({
				name: 'Search',
			});
		},
		search(){
			
			 this.$router.push({
                path: 'search', 
                name: 'Search',
                params: { 
                    area: this.searchArea
                }

            })
		},
		continentSearch(area,index) {
			_hmt.push(['_trackEvent', '安全提醒_搜索列表类型', 'click', '12301服务号']);
			this.num = index;
			if(this.postData.continent != area) {
				if(area != '全部'){
					this.postData ={
						page: 1,
						page_size: 10 ,
						continent: area
					}
				}else{
					this.postData ={
						page: 1,
						page_size: 10 ,
						continent: ''
					}

				}
				
				this.searchMsg = [];
				this.$nextTick(() => {
					this.$refs.infiniteLoading.$emit('$InfiniteLoading:reset');
				});
				
			}
		},
		fetchData(info) {
			_hmt.push(['_trackEvent', '安全提醒_搜索列表', 'ajax', '12301服务号']);
			this.$http.post(this.$con.api('/security/search/valid'),info).then(res =>{
				this.searchMsg = this.searchMsg.concat(res.body.resp_data.list)
				this.postData.page++
			})
		},
        infiniteHandler($state) {
			_hmt.push(['_trackEvent', '安全提醒_搜索列表', 'ajax', '12301服务号']);
			this.$http.post(this.$con.api('/security/search/valid'),this.postData,{
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
            margin: .14rem   .4rem  .14rem  0;
            padding: .1rem  .3rem;
        }
        
    }
   
    // .border::before{
    //     border-radius: .5rem;
    //     border-color:#fe6a1c;
    // }
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
            // width: calc(100% - 2rem);
            input{
                position: relative;
                display: block;
                width: 100%;
                height:.55rem;
                line-height: .55rem;
                padding: .15rem 0;
                border: 0;
                background: #f4f4f4;
                font-family: "Microsoft Yahei",Arial;
                font-size: .28rem;
            }
        }
		.searchbtn{
			font-size: .28rem;
			height:100%;
			width:2rem;
			text-align: center;
			line-height: .7rem;
			border-radius: 0 .08rem  .08rem 0 ;

		}
		.searchbtn:active{
			background: #eee;
		}
		
    }
	.type-box{
		margin: .2rem .4rem;
		display: flex;
		justify-content: flex-start;
		flex-wrap: wrap;
		li{
			width: 1.5rem;
			height: .8rem;
			// background: #f8f8f8;
			background: linear-gradient(to bottom right, #eee 0%, #fff 25%, #fff 50%,#fff 75%, #eee 100%);
			margin: .2rem .2rem  0 0;
			text-align: center;
			line-height: .8rem;
		}
		.active{
			background: linear-gradient(to bottom right, #54d6ec 0%, #2cbbd4 25%, #25a4bb 50%,#2cbbd4 75%, #54d6ec 100%);
			color: #fff;
		}
		li:nth-child(4){
			margin-right: 0;
		}
		li:nth-child(8){
			margin-right: 0;
		}
		>li::before{
			box-shadow: 2px 0 4px  #0e6473;
		}
		>li::after{
			box-shadow: 0 2px 4px  #0e6473;
		}
	}
    .list-box {
		li:active{
			background: #eee;
		}
		li {
			padding: 0.4rem;
			position: relative;
			.flag-border{
				display: inline-block;
				padding: 0;
				width: 1.6rem;
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
			.bgimg{
				background:#ececec url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAOBAMAAACfqVJUAAAAGFBMVEUAAADsaSX98ev4v6LxkF6/CnTQTHbceaa8AET+AAAAAXRSTlMAQObYZgAAAM9JREFUGNMtjjFywjAURBfZJK0XSC9cUDsm6XGRHIAZmwsY11Bwf54+7IzXmv9Wq6/Qzw5rUZZSr6KklRs+bySjU/ltpRrbu0n24BfpNOBkibY+1e6SS9sen+fFOyWoyLW4VK7rY7npepfcQDPDyuKEHRZp/Z/Fi7WFKJCNPyYi3KNpBQE3lIGCaLrRFKRiFHL+HaXPa0cTSQa0hqw15DBGfjCgYwr8tupxquZC4m0j+HaIPRb/XUoTg0LenmOPMZcmHXsdUV/W2Aj56yxGegLNzBxneV/X7gAAAABJRU5ErkJggg==) no-repeat  50%;
			}
			a{
				display: flex;
				img{
					width: 1.3rem;
					height: .9rem;
					margin-right: .4rem;
				}
				.flag-box{
					width: 1.2rem;
					height: .9rem;
					margin-right: .3rem;
				}
				>div{
				
					.slist-title{
						padding: .05rem  0;
						margin-left:.4rem;
						display: flex;
						justify-content: space-between;
						h3{
							font-weight: bold;
							font-size: .32rem;
							color: #333;
						}
						span{
							font-size: .24rem;
							color: #ef6824;
						}
					}
					>p{
						color: #666;
						width: 5rem;
						font-size: .28rem;
						overflow: hidden;
						padding-top: .3rem;
						text-indent: .4rem;
						text-overflow:ellipsis;
						white-space: nowrap;
					}
				}	
				
				
			}
			
		}
	}
</style>

