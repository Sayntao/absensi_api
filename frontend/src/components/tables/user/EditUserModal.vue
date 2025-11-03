<template>
  <div
    class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11"
  >
    <button
      @click="$emit('close')"
      class="transition-color absolute right-5 top-5 z-999 flex h-11 w-11 items-center justify-center rounded-full bg-gray-100 text-gray-400 hover:bg-gray-200 hover:text-gray-600 dark:bg-gray-700 dark:bg-white/[0.05] dark:text-gray-400 dark:hover:bg-white/[0.07] dark:hover:text-gray-300"
    >
      <svg
        class="fill-current"
        width="24"
        height="24"
        viewBox="0 0 24 24"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          fill-rule="evenodd"
          clip-rule="evenodd"
          d="M6.04289 16.5418C5.65237 16.9323 5.65237 17.5655 6.04289 17.956C6.43342 18.3465 7.06658 18.3465 7.45711 17.956L11.9987 13.4144L16.5408 17.9565C16.9313 18.347 17.5645 18.347 17.955 17.9565C18.3455 17.566 18.3455 16.9328 17.955 16.5423L13.4129 12.0002L17.955 7.45808C18.3455 7.06756 18.3455 6.43439 17.955 6.04387C17.5645 5.65335 16.9313 5.65335 16.5408 6.04387L11.9987 10.586L7.45711 6.04439C7.06658 5.65386 6.43342 5.65386 6.04289 6.04439C5.65237 6.43491 5.65237 7.06808 6.04289 7.4586L10.5845 12.0002L6.04289 16.5418Z"
          fill=""
        />
      </svg>
    </button>

    <div class="px-2 pr-14">
      <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">Edit Pengguna</h4>

      <p class="mb-6 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">
        Perbarui detail akun pengguna.
      </p>
    </div>

    <VeeForm
      :validation-schema="schema"
      @submit="onSubmit"
      :initial-values="initialValues"
      class="flex flex-col"
    >
      <div class="px-2 overflow-y-auto custom-scrollbar">
        <div class="col-span-2">
          <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Nama Lengkap
          </label>
          <VeeField name="name" validate-on-input v-slot="{ field, meta, errorMessage }">
            <input
              v-bind="field"
              type="text"
              placeholder="Masukkan Nama Lengkap"
              :class="{
                'border-error-300 focus:border-error-300 focus:ring-3 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-10':
                  !meta.valid && meta.dirty,
                'dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800': true,
              }"
            />
            <p v-if="!meta.valid && meta.dirty" class="mt-1.5 text-theme-xs text-error-500">
              {{ errorMessage }}
            </p>
          </VeeField>
        </div>

        <div class="col-span-2 mt-5">
          <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Nomor Telepon
          </label>
          <VeeField name="phone" validate-on-input v-slot="{ field, meta, errorMessage }">
            <input
              v-bind="field"
              type="text"
              placeholder="08xxxxxxxxxx"
              :class="{
                'border-error-300 focus:border-error-300 focus:ring-3 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-10':
                  !meta.valid && meta.dirty,
                'dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800': true,
              }"
            />
            <p v-if="!meta.valid && meta.dirty" class="mt-1.5 text-theme-xs text-error-500">
              {{ errorMessage }}
            </p>
          </VeeField>
        </div>

        <div class="col-span-2 mt-5">
          <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Password (Kosongkan jika tidak ingin diubah)
          </label>
          <VeeField name="password" validate-on-input v-slot="{ field, meta, errorMessage }">
            <input
              v-bind="field"
              type="password"
              placeholder="Masukkan password baru (opsional)"
              :class="{
                'border-error-300 focus:border-error-300 focus:ring-3 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-10':
                  !meta.valid && meta.dirty,
                'dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800': true,
              }"
            />
            <p v-if="!meta.valid && meta.dirty" class="mt-1.5 text-theme-xs text-error-500">
              {{ errorMessage }}
            </p>
          </VeeField>
        </div>

        <div class="col-span-2 mt-5">
          <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Role (Hak Akses)
          </label>
          <VeeField name="role_id" validate-on-input v-slot="{ field, meta, errorMessage }">
            <div class="relative z-20 bg-transparent">
              <select
                v-bind="field"
                :disabled="loading"
                :class="{
                  'border-error-300 focus:border-error-300 focus:ring-3 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-11':
                    !meta.valid && meta.dirty,
                  'dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800': true,
                }"
              >
                <option :value="null" disabled>--- Pilih Role ---</option>
                <option value="1">Administrator</option>
                <option value="2">HR</option>
                <option value="3">Karyawan</option>
              </select>
              <span
                class="absolute z-30 text-gray-500 -translate-y-1/2 pointer-events-none right-4 top-1/2 dark:text-gray-400"
              >
                <svg
                  class="stroke-current"
                  width="20"
                  height="20"
                  viewBox="0 0 20 20"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396"
                    stroke=""
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                </svg>
              </span>
            </div>
            <p v-if="!meta.valid && meta.dirty" class="mt-1.5 text-theme-xs text-error-500">
              {{ errorMessage }}
            </p>
          </VeeField>
        </div>

        <div class="col-span-2 mt-5">
          <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Shift Kerja
          </label>
          <VeeField name="shift_id" validate-on-input v-slot="{ field, meta, errorMessage }">
            <div class="relative z-20 bg-transparent">
              <select
                v-bind="field"
                :disabled="props.loadingShifts || loading"
                :class="{
                  'border-error-300 focus:border-error-300 focus:ring-3 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-11':
                    !meta.valid && meta.dirty,
                  'dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800': true,
                }"
              >
                <option :value="null" disabled>
                  {{ props.loadingShifts ? 'Memuat Shift...' : '--- Pilih Shift ---' }}
                </option>
                <option v-for="shift in props.shiftOptions" :key="shift.id" :value="shift.id">
                  {{ shift.shift_name }} ({{ shift.check_in_time.substring(0, 5) }} -
                  {{ shift.check_out_time.substring(0, 5) }})
                </option>
              </select>
              <span
                class="absolute z-30 text-gray-500 -translate-y-1/2 pointer-events-none right-4 top-1/2 dark:text-gray-400"
              >
                <svg
                  class="stroke-current"
                  width="20"
                  height="20"
                  viewBox="0 0 20 20"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396"
                    stroke=""
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                </svg>
              </span>
            </div>
            <p v-if="!meta.valid && meta.dirty" class="mt-1.5 text-theme-xs text-error-500">
              {{ errorMessage }}
            </p>
          </VeeField>
        </div>

        <div
          v-if="error"
          class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg dark:bg-red-900/20 dark:border-red-600 dark:text-red-400"
        >
          <p class="font-bold">Gagal menyimpan data!</p>
          <p class="text-sm">{{ error }}</p>
        </div>
      </div>

      <div class="flex items-center gap-3 mt-6 lg:justify-end">
        <button
          @click="$emit('close')"
          type="button"
          :disabled="loading"
          class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto disabled:opacity-50 disabled:cursor-not-allowed"
        >
          Batal
        </button>

        <button
          type="submit"
          :disabled="loading"
          class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto disabled:bg-brand-300 disabled:cursor-not-allowed"
        >
          {{ loading ? 'Memperbarui...' : 'Simpan Perubahan' }}
        </button>
      </div>
    </VeeForm>
  </div>
</template>

<script setup>
import { Form as VeeForm, Field as VeeField } from 'vee-validate'
import * as yup from 'yup'
import api from '@/services/api'
import { ref, computed } from 'vue'

const props = defineProps({
  // Data pengguna yang akan diedit (wajib diisi saat modal dibuka)
  userData: {
    type: Object,
    required: true,
  },
  shiftOptions: {
    type: Array,
    default: () => [],
  },
  loadingShifts: {
    type: Boolean,
    default: false,
  },
})

const loading = ref(false)
const error = ref(null)
const emit = defineEmits(['close', 'userUpdated'])

// --- COMPUTED INITIAL VALUES ---
// MEMPERBAIKI BUG: Menambahkan pengecekan null/undefined
const initialValues = computed(() => {
  // Gunakan data kosong sebagai fallback jika props.userData undefined
  const data = props.userData || {}

  return {
    // Akses properti dengan fallback string kosong
    name: data.name || '',
    phone: data.phone || '',
    password: '', // Password selalu dikosongkan saat modal update dibuka
    // Pastikan ID dikonversi ke String dan menggunakan fallback 0 jika data.id undefined
    role_id: String(data.role_id || 0),
    shift_id: String(data.shift_id || 0),
  }
})

// --- VALIDATION SCHEMA ---
const schema = yup.object({
  name: yup.string().required('Nama Lengkap wajib diisi.'),
  phone: yup.string().required('Nomor Telpon wajib diisi.'),
  // Password opsional: min 6 karakter hanya jika diisi (bukan null/empty)
  password: yup.string().min(6, 'Password minimal 6 karakter.').nullable(),
  role_id: yup.number().required('Role wajib dipilih.').typeError('Role wajib dipilih.'),
  shift_id: yup.number().required('Shift wajib dipilih.').typeError('Shift wajib dipilih.'),
})

// --- SUBMIT FUNCTION ---
const updateUser = async (formData) => {
  loading.value = true
  error.value = null

  // Payload: Hapus password jika kosong (tidak diubah)
  const payload = {
    ...formData,
    role_id: Number(formData.role_id),
    shift_id: Number(formData.shift_id),
  }

  // Hapus password dari payload jika pengguna tidak mengisinya
  if (!payload.password) {
    delete payload.password
  }

  // ID pengguna yang akan diperbarui (sudah pasti ada karena userData required)
  const userId = props.userData.id

  try {
    // Gunakan api.patch untuk operasi update
    await api.put(`user/${userId}`, payload)

    // Sukses
    emit('userUpdated')
    emit('close')
  } catch (err) {
    console.error('Error saat memperbarui User:', err)
    if (err.response) {
      // Penanganan error umum
      error.value = `Gagal menyimpan data. Server Error (${err.response.status}): ${err.response.data.message || 'Periksa kembali input Anda.'}`

      // Penanganan error spesifik dari validasi API (misalnya duplikasi telepon)
      if (err.response.data && err.response.data.errors && err.response.data.errors.phone) {
        error.value = `Nomor telepon sudah digunakan: ${err.response.data.errors.phone[0]}`
      }
    } else {
      error.value = 'Gagal memproses request.'
    }
  } finally {
    loading.value = false
  }
}

const onSubmit = (values) => {
  updateUser(values)
}
</script>
