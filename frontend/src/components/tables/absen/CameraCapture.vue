<template>
  <div class="attendance-container">
    <h2 class="status-date">🗓️ Tanggal: {{ todayDate }}</h2>
    <p v-if="loading" class="loading-text">⏳ Memproses...</p>
    <p v-if="error" class="error-text">❌ Error: {{ error }}</p>

    <div v-if="!isAttendanceComplete">
      <video ref="video" autoplay playsinline class="camera-video"></video>
      <canvas ref="canvas" style="display: none"></canvas>

      <div class="button-group">
        <h3 v-if="isAttendanceComplete" class="complete-text">✅ Anda sudah absen hari ini.</h3>

        <template v-else>
          <button
            v-if="!isCheckInDone"
            @click="handleCheckIn"
            :disabled="loading || !isStreaming"
            class="btn check-in-btn"
          >
            {{ loading ? 'Mengambil Foto & Mengirim...' : '✅ Absen Masuk' }}
          </button>

          <button
            v-else
            @click="handleCheckOut"
            :disabled="loading || !isStreaming"
            class="btn check-out-btn"
          >
            {{ loading ? 'Mengambil Foto & Mengirim...' : '✅ Absen Pulang' }}
          </button>
        </template>
      </div>
    </div>
    <h3 v-else class="complete-text">✅ Anda sudah absen hari ini.</h3>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import api from '@/services/api' // Pastikan ini adalah instance Axios Anda

// --- 1. STATE KAMERA ---
const video = ref(null as HTMLVideoElement | null)
const canvas = ref(null as HTMLCanvasElement | null)
const isStreaming = ref(false)
let mediaStream: MediaStream | null = null

// --- 2. STATE ABSENSI ---
const loading = ref(false)
const error = ref<string | null>(null)
const attendanceId = ref<number | null>(null)
const isCheckInDone = ref(false)
const isCheckOutDone = ref(false)
const isAttendanceComplete = computed(() => isCheckOutDone.value)

// --- 3. HELPER ---
const todayDate = computed(() => {
  return new Date().toLocaleDateString('id-ID', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
})

// --- FUNGSI KAMERA DASAR ---

const startCamera = async () => {
  error.value = null
  try {
    mediaStream = await navigator.mediaDevices.getUserMedia({ video: true })
    if (video.value) {
      video.value.srcObject = mediaStream
      video.value.onloadedmetadata = () => {
        isStreaming.value = true
        video.value?.play()
      }
    }
  } catch (err) {
    console.error('Gagal mengakses kamera:', err)
    error.value = 'Gagal mengakses kamera. Pastikan izin diberikan.'
    isStreaming.value = false
  }
}

const stopCamera = () => {
  if (mediaStream) {
    mediaStream.getTracks().forEach((track) => track.stop())
    isStreaming.value = false
  }
}

/**
 * Fungsi internal sederhana untuk mengambil foto dan mengembalikan Base64
 */
const takePhotoSnapshot = (): string | null => {
  if (!isStreaming.value || !video.value || !canvas.value) return null

  const width = video.value.videoWidth
  const height = video.value.videoHeight

  canvas.value.width = width
  canvas.value.height = height

  const context = canvas.value.getContext('2d')

  if (context) {
    context.drawImage(video.value, 0, 0, width, height)
    // Langsung kembalikan Base64
    return canvas.value.toDataURL('image/png')
  }
  return null
}

// --- FUNGSI ABSENSI (API Call) ---

/**
 * Absen Masuk: Ambil Foto -> POST ke CI3
 */
async function handleCheckIn() {
  loading.value = true
  error.value = null

  const photoData = takePhotoSnapshot() // AMBIL FOTO OTOMATIS

  if (!photoData) {
    error.value = 'Gagal mengambil foto. Pastikan kamera aktif.'
    loading.value = false
    return
  }

  const payload = {
    check_in_image: photoData, // Kirim Base64 data
  }

  try {
    const response = await api.post('attendance', payload) // CI3 index_post

    if (response.data.status) {
      attendanceId.value = response.data.id
      isCheckInDone.value = true // UBAH TOMBOL ke Absen Pulang

      // Simpan status harian (ID absen, status, tanggal)
      localStorage.setItem(
        'dailyAttendanceStatus',
        JSON.stringify({
          id: response.data.id,
          in: true,
          out: false,
          date: new Date().toLocaleDateString('en-CA'),
        }),
      )
      alert(response.data.message || 'Absen Masuk Berhasil!')
    } else {
      error.value = response.data.message || 'Absen Masuk Gagal.'
    }
  } catch (e: any) {
    error.value = e.response?.data?.message || 'Terjadi kesalahan saat Absen Masuk.'
  } finally {
    loading.value = false
  }
}

/**
 * Absen Pulang: Ambil Foto -> PUT ke CI3
 */
async function handleCheckOut() {
  if (!attendanceId.value) {
    error.value = 'Data absensi hari ini tidak ditemukan. Silakan refresh.'
    return
  }

  loading.value = true
  error.value = null

  const photoData = takePhotoSnapshot() // AMBIL FOTO OTOMATIS

  if (!photoData) {
    error.value = 'Gagal mengambil foto pulang. Pastikan kamera aktif.'
    loading.value = false
    return
  }

  const payload = {
    check_out_image: photoData, // Kirim Base64 data
  }

  try {
    const response = await api.put(`attendance/${attendanceId.value}`, payload) // CI3 index_put

    if (response.data.status) {
      isCheckOutDone.value = true // UBAH TOMBOL ke Selesai
      stopCamera() // Hentikan kamera karena absensi selesai

      // Perbarui status di localStorage
      const status = JSON.parse(localStorage.getItem('dailyAttendanceStatus') || '{}')
      status.out = true
      localStorage.setItem('dailyAttendanceStatus', JSON.stringify(status))

      alert(response.data.message || 'Absen Pulang Berhasil!')
    } else {
      error.value = response.data.message || 'Absen Pulang Gagal.'
    }
  } catch (e: any) {
    error.value = e.response?.data?.message || 'Terjadi kesalahan saat Absen Pulang.'
  } finally {
    loading.value = false
  }
}

// --- LOGIKA RESET HARIAN (Lifecycle Hook) ---
onMounted(() => {
  const storedStatus = localStorage.getItem('dailyAttendanceStatus')
  const today = new Date().toLocaleDateString('en-CA')

  if (storedStatus) {
    const status = JSON.parse(storedStatus)

    // Reset jika tanggal berbeda
    if (status.date !== today) {
      localStorage.removeItem('dailyAttendanceStatus')
    } else {
      // Muat status harian
      attendanceId.value = status.id
      isCheckInDone.value = status.in
      isCheckOutDone.value = status.out
    }
  }

  // Hanya mulai kamera jika absensi belum selesai
  if (!isAttendanceComplete.value) {
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
      startCamera()
    } else {
      error.value = 'Browser Anda tidak mendukung MediaDevices API.'
    }
  }
})

onUnmounted(() => {
  stopCamera()
})
</script>

<style scoped>
/* (Style tidak diubah) */
.attendance-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 20px;
  max-width: 500px;
  margin: 50px auto;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
.camera-video {
  width: 100%;
  max-width: 500px;
  height: auto;
  border: 3px solid #007bff;
  border-radius: 8px;
  margin-bottom: 15px;
  background-color: #000;
}
.button-group {
  width: 100%;
  margin-top: 10px;
}
.btn {
  padding: 12px 25px;
  font-size: 16px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition:
    background-color 0.3s,
    opacity 0.3s;
  width: 100%;
  font-weight: bold;
  margin-bottom: 8px;
}
.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
.check-in-btn {
  background-color: #28a745; /* Hijau */
  color: white;
}
.check-out-btn {
  background-color: #dc3545; /* Merah */
  color: white;
}
.complete-text {
  color: #28a745;
  font-weight: bold;
  padding: 15px;
  background-color: #e9f7ee;
  border-radius: 4px;
}
.error-text {
  color: #dc3545;
  margin-top: 10px;
}
</style>
