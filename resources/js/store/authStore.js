import { createStore } from 'vuex'
import api from '../axios'

const store = createStore({
  state: {
    user: JSON.parse(localStorage.getItem('user')) || null,
    token: localStorage.getItem('token') || null,
  },

  getters: {
    isAuthenticated: state => !!state.token,
    user: state => state.user,
    role: state => state.user?.role || null,
    isAdmin: state => state.user?.role === 'admin',
    isUser: state => state.user?.role === 'user',
    token: state => state.token
  },

  mutations: {
    SET_USER(state, user) {
      state.user = user
      user 
        ? localStorage.setItem('user', JSON.stringify(user))
        : localStorage.removeItem('user')
    },
    SET_TOKEN(state, token) {
      state.token = token
      if (token) {
        localStorage.setItem('token', token)
      } else {
        localStorage.removeItem('token')
      }
    },
  },

  actions: {
    async login({ commit }, credentials) {
      try {
        const res = await api.post('/api/login', credentials)
        const { token, user } = res.data.data
        
        commit('SET_TOKEN', token)
        commit('SET_USER', user)

        return { success: true }
      } catch (err) {
        return {
          success: false,
          message: err.response?.data?.message || 'Login failed'
        }
      }
    },

    async logout({ commit }) {
      try {
        await api.post('/api/logout')
      } catch (err) {
        console.error('Logout error:', err)
      } finally {
        commit('SET_TOKEN', null)
        commit('SET_USER', null)
      }
    }
  }
})

export { store }