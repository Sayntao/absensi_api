import { defineStore } from 'pinia'
import { ref } from 'vue'
import router from '@/router' // 🚨 Wajib: Import router Anda di sini

export const useAuthStore = defineStore('auth', () => {
  const token = ref<string | null>(localStorage.getItem('authToken'))
  const user = ref<any>(null)

  function setToken(t: string | null) {
    token.value = t
    if (t) localStorage.setItem('authToken', t)
    else localStorage.removeItem('authToken')
  }

  // --- FUNGSI LOGOUT ASLI (TIDAK DIUBAH) ---
  function logout() {
    setToken(null)
    user.value = null
  }

  // --- FUNGSI BARU KHUSUS UNTUK INTERCEPTOR 401 ---
  function redirectToSignin(message: string = 'Sesi Anda telah berakhir. Silakan masuk kembali.') {
    // 1. Panggil logout untuk membersihkan state
    logout()

    // 2. Tampilkan pesan (opsional)
    // alert(message) // Anda bisa menggantinya dengan notifikasi yang lebih elegan

    // 3. Redirect paksa ke signin
    router.push('/signin')
  }

  const isAuthenticated = () => !!token.value

  // Eksport kedua fungsi: logout untuk tombol, redirectToSignin untuk Axios.
  return { token, user, setToken, logout, redirectToSignin, isAuthenticated }
})
