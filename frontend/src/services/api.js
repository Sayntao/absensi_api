// services/api.js

import axios from 'axios'
import { useAuthStore } from '@/stores/auth'

const api = axios.create({
  baseURL: 'http://localhost/project_absensi/backend/',
  headers: {
    'Content-Type': 'application/x-www-form-urlencoded',
  },
  transformRequest: [
    (data) => {
      const formData = new URLSearchParams()
      for (const key in data) {
        formData.append(key, data[key])
      }
      return formData.toString()
    },
  ],
})

// Interceptor Request: Tambahkan Token
api.interceptors.request.use((config) => {
  try {
    const token = localStorage.getItem('authToken')
    if (token) {
      config.headers = config.headers || {}
      config.headers.Authorization = `Bearer ${token}`
    }
  } catch (e) {
    // ignore
  }
  return config
})

// Interceptor Response: Tangani Token Expired (401 Unauthorized)
api.interceptors.response.use(
  (response) => {
    return response
  },
  (error) => {
    // Cek apakah ada respons dan statusnya 401
    if (error.response && error.response.status === 401) {
      const authStore = useAuthStore()

      console.error('Token Expired atau Unauthorized. Redirecting to Signin.')

      // Panggil fungsi baru yang spesifik untuk redirect
      authStore.redirectToSignin('Token Anda sudah habis masa berlakunya. Silakan masuk kembali.')

      // Kembalikan Promise.reject agar request gagal ini tidak diproses lebih lanjut
      return Promise.reject(error)
    }

    return Promise.reject(error)
  },
)

export default api
