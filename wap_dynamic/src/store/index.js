import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
	// 定义状态
	// state : 存储公共数据
	// getters ： 获取公共数据
	// mutations : 里面放的是同步的操作
	// actions : 里面放的是异步操作
	// context : store 的副本
	// dispatch 执行action
	state: {
		prov:'',
		city: '请选择地址'
	},
	getters: {
		getCity: (state) => {
			return state.city
		},
		getProv: (state) => {
			return state.prov
		}
	},
	mutations: {
		changeCity: (state, name) => {
			state.city = name
		},
		changeProv: (state, name) => {
			state.prov = name
		}
	},
	actions: {
		setCity: (context,city) => {
			context.commit('changeCity', city)		
		},
		setProv: (context,prov) => {
			context.commit('changeProv', prov)		
		}
	}
})

export default store
