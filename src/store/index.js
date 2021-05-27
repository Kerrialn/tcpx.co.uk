import Vue from 'vue'
import Vuex from 'vuex'
import services from './services'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    loading: false
  },
  getters:{
    getLoading(state){
      return state.loading
    }
  },
  mutations: {
    setLoading(state,newLoadingState){
      state.loading = newLoadingState
    }
  },
  actions: {
  },
  modules: {
    services
  }
})
