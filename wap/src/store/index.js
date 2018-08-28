/**
 * Created by Toma on 2017/7/17.
 */
import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

const state = {
    direction: 'forward',
    showNav: true
}

const mutations = {
  updateNavState (state, payload) {
      state.showNav = payload
  },
  updateDirection (state, payload) {
    state.direction = payload
  }
}

const actions = {

}

export default new Vuex.Store({
    state,
    mutations,
    actions
})
