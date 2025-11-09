import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useAuthStore = defineStore('auth', () => {
  const token = ref<string | null>(localStorage.getItem('authToken'))
  const user = ref<any>(null)

  function setToken(t: string | null) {
    token.value = t
    if (t) localStorage.setItem('authToken', t)
    else localStorage.removeItem('authToken')
  }

  function logout() {
    setToken(null)
    user.value = null
  }

  const isAuthenticated = () => !!token.value

  return { token, user, setToken, logout, isAuthenticated }
})
