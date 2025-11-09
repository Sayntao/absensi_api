<template>
  <div class="relative w-full max-w-md rounded-3xl bg-white p-6 shadow-xl dark:bg-gray-900 md:p-8">
    <button
      @click="$emit('close')"
      class="absolute right-3 top-3 flex h-9 w-9 items-center justify-center rounded-full bg-gray-100 text-gray-400 hover:bg-gray-200 hover:text-gray-600 dark:bg-gray-700 dark:bg-white/[0.05] dark:text-gray-400 dark:hover:bg-white/[0.07] dark:hover:text-gray-300"
      :disabled="loading"
    >
      <svg
        class="h-5 w-5 fill-current"
        viewBox="0 0 24 24"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          fill-rule="evenodd"
          clip-rule="evenodd"
          d="M6.04289 16.5418C5.65237 16.9323 5.65237 17.5655 6.04289 17.956C6.43342 18.3465 7.06658 18.3465 7.45711 17.956L11.9987 13.4144L16.5408 17.9565C16.9313 18.347 17.5645 18.347 17.955 17.9565C18.3455 17.566 18.3455 16.9328 17.955 16.5423L13.4129 12.0002L17.955 7.45808C18.3455 7.06756 18.3455 6.43439 17.955 6.04387C17.5645 5.65335 16.9313 5.65335 16.5408 6.04387L11.9987 10.586L7.45711 6.04439C7.06658 5.65386 6.43342 5.65386 6.04289 6.04439C5.65237 6.43491 5.65237 7.06808 6.04289 7.4586L10.5845 12.0002L6.04289 16.5418Z"
          fill="currentColor"
        />
      </svg>
    </button>

    <div class="mb-4 flex flex-col items-center">
      <div
        class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-900/20 dark:text-red-400"
      >
        <svg
          class="h-6 w-6 stroke-current"
          fill="none"
          viewBox="0 0 24 24"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
          />
        </svg>
      </div>
      <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">Hapus Karyawan</h3>
      <p class="mt-2 text-center text-sm text-gray-500 dark:text-gray-400">
        Anda yakin ingin menghapus karyawan <b>{{ props.employeeData.username }}</b
        >? Tindakan ini tidak dapat dibatalkan.
      </p>
    </div>

    <div
      v-if="error"
      class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg dark:bg-red-900/20 dark:border-red-600 dark:text-red-400"
    >
      <p class="text-sm font-bold">Gagal menghapus data!</p>
      <p class="text-sm">{{ error }}</p>
    </div>

    <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
      <button
        @click="$emit('close')"
        type="button"
        :disabled="loading"
        class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto disabled:opacity-50 disabled:cursor-not-allowed"
      >
        Batal
      </button>

      <button
        @click="deleteEmployee"
        type="button"
        :disabled="loading"
        class="flex w-full justify-center rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-700 sm:w-auto disabled:bg-red-400 disabled:cursor-not-allowed"
      >
        <span v-if="loading">Menghapus...</span>
        <span v-else>Ya, Hapus Karyawan</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import api from '@/services/api'
import { ref } from 'vue'

const props = defineProps({
  // Menerima data karyawan yang akan dihapus
  employeeData: {
    type: Object,
    required: true,
  },
})

const loading = ref(false)
const error = ref(null)
const emit = defineEmits(['close', 'employeeDeleted'])

// --- DELETE FUNCTION ---
const deleteEmployee = async () => {
  loading.value = true
  error.value = null

  const employeeId = props.employeeData.id

  try {
    // Menggunakan API DELETE
    await api.delete(`employee/${employeeId}`)

    // Sukses: Beri tahu komponen induk bahwa data sudah dihapus
    emit('employeeDeleted')
    emit('close')
  } catch (err) {
    console.error(`Error saat menghapus Employee ID ${employeeId}:`, err)
    if (err.response) {
      error.value = `Gagal menghapus. Server Error (${err.response.status}): ${err.response.data.message || 'Silakan coba lagi.'}`
    } else {
      error.value = 'Gagal memproses request. Periksa koneksi jaringan.'
    }
  } finally {
    loading.value = false
  }
}
</script>
