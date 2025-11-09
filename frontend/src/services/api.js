import axios from 'axios'

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

// Attach token automatically from localStorage (if exists)
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

export default api
