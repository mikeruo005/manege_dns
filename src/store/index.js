import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    list:'',
    cont:'',
    listData:''
  },
  mutations: {
    updateList(state, payload) {
      state.list = payload;
    },
    updateCont(state, payload) {
      state.cont = payload;
    },
    updatelistData(state, payload) {
      state.listData = payload;
    }
  },
  actions: {
  },
  modules: {
  }
})
