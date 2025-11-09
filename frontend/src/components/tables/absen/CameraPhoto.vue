<template>
  <div class="camera-container">
    <video ref="video" autoplay playsinline class="camera-video"></video>

    <button @click="takePhoto" :disabled="!isStreaming" class="capture-button">
      Ambil Foto 📸
    </button>

    <canvas ref="canvas" style="display: none"></canvas>

    <div v-if="photoData" class="photo-preview">
      <h3>Hasil Foto:</h3>
      <img :src="photoData" alt="Captured Photo" class="preview-image" />
      <button @click="clearPhoto" class="clear-button">Hapus Foto</button>
    </div>

    <p v-if="error" class="error-message">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const video = ref(null)
const canvas = ref(null)
const photoData = ref(null)
const isStreaming = ref(false)
const error = ref(null)
let mediaStream = null

// --- 1. Mengaktifkan Kamera ---
const startCamera = async () => {
  error.value = null
  try {
    // Meminta akses ke video (kamera)
    mediaStream = await navigator.mediaDevices.getUserMedia({ video: true })

    if (video.value) {
      video.value.srcObject = mediaStream
      video.value.onloadedmetadata = () => {
        isStreaming.value = true
        video.value.play()
      }
    }
  } catch (err) {
    console.error('Gagal mengakses kamera:', err)
    error.value = 'Gagal mengakses kamera. Pastikan izin diberikan.'
    isStreaming.value = false
  }
}

// --- 2. Mengambil Foto (Snapshot) ---
const takePhoto = () => {
  if (!isStreaming.value || !video.value || !canvas.value) return

  // Atur dimensi canvas sesuai video stream
  const width = video.value.videoWidth
  const height = video.value.videoHeight

  canvas.value.width = width
  canvas.value.height = height

  const context = canvas.value.getContext('2d')

  // Gambar frame video saat ini ke canvas
  context.drawImage(video.value, 0, 0, width, height)

  // Ubah canvas menjadi URL data (Base64)
  photoData.value = canvas.value.toDataURL('image/png')

  // Opsional: Hentikan stream setelah foto diambil
  stopCamera()
}

// --- 3. Menghentikan Kamera ---
const stopCamera = () => {
  if (mediaStream) {
    mediaStream.getTracks().forEach((track) => track.stop())
    isStreaming.value = false
  }
}

// --- 4. Fungsi Bersihkan ---
const clearPhoto = () => {
  photoData.value = null
  // Mulai kamera lagi agar pengguna bisa mengambil foto baru
  startCamera()
}

// Hook Siklus Hidup
onMounted(() => {
  // Hanya mulai jika API tersedia
  if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    startCamera()
  } else {
    error.value = 'Browser Anda tidak mendukung MediaDevices API.'
  }
})

onUnmounted(() => {
  stopCamera()
})
</script>

<style scoped>
/* Styling dasar agar video dan tombol terlihat jelas */
.camera-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 20px;
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
.photo-preview {
  margin-top: 20px;
  border: 1px dashed #ccc;
  padding: 10px;
}
.preview-image {
  max-width: 100%;
  height: auto;
  border-radius: 8px;
}
.capture-button,
.clear-button {
  padding: 10px 20px;
  margin: 5px;
  cursor: pointer;
  border: none;
  border-radius: 5px;
  background-color: #28a745;
  color: white;
}
.error-message {
  color: red;
  margin-top: 10px;
}
</style>
