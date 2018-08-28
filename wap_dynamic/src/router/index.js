import Vue from 'vue'
import Router from 'vue-router'
// import Header from '@/components/header'
import Search from '@/pages/search'
import Searchlist from '@/pages/searchlist'
// import Searchs from '@/pages/searchs'
import Detail from '@/pages/detail'
import Report from '@/pages/report'
import Index from '@/pages/index'
import Letter from '@/pages/letter'
import Success  from '@/pages/success'



Vue.use(Router)

export default new Router({
  routes: [
    {
		path: '/',
		name: 'Index',
		component: Index,
		meta: {
			title:'景区时讯'
		}
    },
    {
		path: '/search',
		name: 'Search',
		component: Search
		},
		{
			path: '/searchlist',
			name: 'Searchlist',
			component: Searchlist
		},
    {
		path: '/detail',
		name: 'Detail',
		component: Detail
	},
	{
		path: '/report',
		name: 'Report',
		component: Report
	},
	{
		path: '/letter',
		name: 'Letter',
		component: Letter,
		meta: {
			title:'现场报道'
		}
	},
	{
		path: '/success',
		name: 'Success',
		component: Success
	},

  ]
})
