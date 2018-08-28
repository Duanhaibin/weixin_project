<template>
	<header class="banner"> 

		<swiper loop auto  height='4rem'  :show-dots=false :list="urlList" :index="banner_index" @on-index-change="banner_onIndexChange"></swiper>
		<!-- <p>current index: {{banner_index}}</p> -->
	</header>

	
</template>
<style>
@import "../../assets/css/swiper.css";
</style>

<script>
	import { Swiper, GroupTitle, SwiperItem, XButton, Divider } from "vux";
	export default {
		components: {
			Swiper,
			SwiperItem,
			GroupTitle,
			XButton,
			Divider
		},
		computed:  {
			urlList: function() {
				return this.list.map((item, index) => ({
					url: "http://m.baidu.com",
					img: item
				}));
			}
		},
		data() {
			return {
				list: [
					"https://ww1.sinaimg.cn/large/663d3650gy1fq66vvsr72j20p00gogo2.jpg",
					"https://ww1.sinaimg.cn/large/663d3650gy1fq66vw1k2wj20p00goq7n.jpg",
					"https://ww1.sinaimg.cn/large/663d3650gy1fq66vw50iwj20ff0aaaci.jpg"
				] ,
				banner_index: 0
			};
		},
		methods: {
			banner_onIndexChange(index) {
			this.banner_index = index;
			}
		},
	};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped  lang='scss'>
	.img-box {
		// height: 3.75rem;
		overflow: hidden;
		padding-bottom: 48%;
		height: 0;
		img {
			width: 100%;
		}
	}
</style>
