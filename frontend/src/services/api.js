import axios from 'axios'

const api = axios.create({
  baseURL: 'http://localhost/project_absensi/backend/',
  headers: {
    'Content-Type': 'application/json',
  },
})

export default api
