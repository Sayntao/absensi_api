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
      <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
        Edit Shift ID: {{ props.shiftData.id }}
      </h4>

      <p class="mb-6 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">
        Perbarui detail untuk shift {{ props.shiftData.shift_name }}.
      </p>
    </div>

    <VeeForm
      :validation-schema="schema"
      @submit="onSubmit"
      class="flex flex-col"
      :initial-values="initialValues"
    >
      <div class="px-2 overflow-y-auto custom-scrollbar">
        <div class="col-span-2">
          <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Nama Shift
          </label>

          <VeeField name="shift_name" validate-on-input v-slot="{ field, meta, errorMessage }">
            <div class="relative">
              <input
                v-bind="field"
                type="text"
                placeholder="Shift Malam"
                :class="{
                  // Kelas ERROR
                  'border-error-300 focus:border-error-300 focus:ring-3 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-10':
                    !meta.valid && meta.dirty, // Kelas DEFAULT/VALID
                  'dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800': true,
                }"
              />

              <span
                v-if="!meta.valid && meta.dirty"
                class="absolute right-3.5 top-1/2 -translate-y-1/2"
              >
                <svg
                  width="16"
                  height="16"
                  viewBox="0 0 16 16"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M2.58325 7.99967C2.58325 5.00813 5.00838 2.58301 7.99992 2.58301C10.9915 2.58301 13.4166 5.00813 13.4166 7.99967C13.4166 10.9912 10.9915 13.4163 7.99992 13.4163C5.00838 13.4163 2.58325 10.9912 2.58325 7.99967ZM7.99992 1.08301C4.17995 1.08301 1.08325 4.17971 1.08325 7.99967C1.08325 11.8196 4.17995 14.9163 7.99992 14.9163C11.8199 14.9163 14.9166 11.8196 14.9166 7.99967C14.9166 4.17971 11.8199 1.08301 7.99992 1.08301ZM7.09932 5.01639C7.09932 5.51345 7.50227 5.91639 7.99932 5.91639H7.99999C8.49705 5.91639 8.89999 5.51345 8.89999 5.01639C8.89999 4.51933 8.49705 4.11639 7.99999 4.11639H7.99932C7.50227 4.11639 7.09932 4.51933 7.09932 5.01639ZM7.99998 11.8306C7.58576 11.8306 7.24998 11.4948 7.24998 11.0806V7.29627C7.24998 6.88206 7.58576 6.54627 7.99998 6.54627C8.41419 6.54627 8.74998 6.88206 8.74998 7.29627V11.0806C8.74998 11.4948 8.41419 11.8306 7.99998 11.8306Z"
                    fill="#F04438"
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
            Check-in Time
          </label>

          <VeeField name="check_in_time" validate-on-input v-slot="{ field, meta, errorMessage }">
            <div class="relative">
              <input
                v-bind="field"
                type="text"
                placeholder="check in time"
                :class="{
                  // Kelas ERROR
                  'border-error-300 focus:border-error-300 focus:ring-3 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-10':
                    !meta.valid && meta.dirty, // Kelas DEFAULT/VALID
                  'dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800': true,
                }"
              />

              <span
                v-if="!meta.valid && meta.dirty"
                class="absolute right-3.5 top-1/2 -translate-y-1/2"
              >
                <svg
                  width="16"
                  height="16"
                  viewBox="0 0 16 16"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M2.58325 7.99967C2.58325 5.00813 5.00838 2.58301 7.99992 2.58301C10.9915 2.58301 13.4166 5.00813 13.4166 7.99967C13.4166 10.9912 10.9915 13.4163 7.99992 13.4163C5.00838 13.4163 2.58325 10.9912 2.58325 7.99967ZM7.99992 1.08301C4.17995 1.08301 1.08325 4.17971 1.08325 7.99967C1.08325 11.8196 4.17995 14.9163 7.99992 14.9163C11.8199 14.9163 14.9166 11.8196 14.9166 7.99967C14.9166 4.17971 11.8199 1.08301 7.99992 1.08301ZM7.09932 5.01639C7.09932 5.51345 7.50227 5.91639 7.99932 5.91639H7.99999C8.49705 5.91639 8.89999 5.51345 8.89999 5.01639C8.89999 4.51933 8.49705 4.11639 7.99999 4.11639H7.99932C7.50227 4.11639 7.09932 4.51933 7.09932 5.01639ZM7.99998 11.8306C7.58576 11.8306 7.24998 11.4948 7.24998 11.0806V7.29627C7.24998 6.88206 7.58576 6.54627 7.99998 6.54627C8.41419 6.54627 8.74998 6.88206 8.74998 7.29627V11.0806C8.74998 11.4948 8.41419 11.8306 7.99998 11.8306Z"
                    fill="#F04438"
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
            Check-out Time
          </label>

          <VeeField name="check_out_time" validate-on-input v-slot="{ field, meta, errorMessage }">
            <div class="relative">
              <input
                v-bind="field"
                type="text"
                placeholder="check out time"
                :class="{
                  // Kelas ERROR
                  'border-error-300 focus:border-error-300 focus:ring-3 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-10':
                    !meta.valid && meta.dirty, // Kelas DEFAULT/VALID
                  'dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800': true,
                }"
              />

              <span
                v-if="!meta.valid && meta.dirty"
                class="absolute right-3.5 top-1/2 -translate-y-1/2"
              >
                <svg
                  width="16"
                  height="16"
                  viewBox="0 0 16 16"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M2.58325 7.99967C2.58325 5.00813 5.00838 2.58301 7.99992 2.58301C10.9915 2.58301 13.4166 5.00813 13.4166 7.99967C13.4166 10.9912 10.9915 13.4163 7.99992 13.4163C5.00838 13.4163 2.58325 10.9912 2.58325 7.99967ZM7.99992 1.08301C4.17995 1.08301 1.08325 4.17971 1.08325 7.99967C1.08325 11.8196 4.17995 14.9163 7.99992 14.9163C11.8199 14.9163 14.9166 11.8196 14.9166 7.99967C14.9166 4.17971 11.8199 1.08301 7.99992 1.08301ZM7.09932 5.01639C7.09932 5.51345 7.50227 5.91639 7.99932 5.91639H7.99999C8.49705 5.91639 8.89999 5.51345 8.89999 5.01639C8.89999 4.51933 8.49705 4.11639 7.99999 4.11639H7.99932C7.50227 4.11639 7.09932 4.51933 7.09932 5.01639ZM7.99998 11.8306C7.58576 11.8306 7.24998 11.4948 7.24998 11.0806V7.29627C7.24998 6.88206 7.58576 6.54627 7.99998 6.54627C8.41419 6.54627 8.74998 6.88206 8.74998 7.29627V11.0806C8.74998 11.4948 8.41419 11.8306 7.99998 11.8306Z"
                    fill="#F04438"
                  />
                </svg>
              </span>
            </div>

            <p v-if="!meta.valid && meta.dirty" class="mt-1.5 text-theme-xs text-error-500">
              {{ errorMessage }}
            </p>
          </VeeField>
        </div>
      </div>

      <div class="flex items-center gap-3 mt-6 lg:justify-end">
        <button
          @click="$emit('close')"
          type="button"
          class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto"
        >
          Close
        </button>

        <button
          type="submit"
          class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto"
        >
          Update Shift
        </button>
      </div>
    </VeeForm>
  </div>
</template>

<script setup>
import { Form as VeeForm, Field as VeeField } from 'vee-validate'
import * as yup from 'yup'
import api from '@/services/api'
import { ref, watch } from 'vue'

// 💡 1. TERIMA DATA LAMA MELALUI PROPS
const props = defineProps({
  shiftData: {
    type: Object,
    required: true,
  },
})

const loading = ref(false)
const error = ref(null)

// 💡 2. EMIT EVENT BERUBAH MENJADI 'shiftUpdated'
const emit = defineEmits(['close', 'shiftUpdated'])

// 💡 3. SIAPKAN INITIAL VALUES DARI PROPS
const initialValues = {
  shift_name: props.shiftData.shift_name,
  check_in_time: props.shiftData.check_in_time,
  check_out_time: props.shiftData.check_out_time,
}

// Skema validasi (tetap sama)
const schema = yup.object({
  shift_name: yup.string().required('Nama Shift wajib diisi. Tidak boleh kosong.'),
  check_in_time: yup
    .string()
    .required('Waktu Check-in wajib diisi.')
    .matches(
      /^([01]\d|2[0-3]):([0-5]\d):([0-5]\d)$/,
      'Waktu Check-in harus dalam format 24 jam HH:MM:SS yang valid.',
    ),
  check_out_time: yup
    .string()
    .required('Waktu Check-out wajib diisi.')
    .matches(
      /^([01]\d|2[0-3]):([0-5]\d):([0-5]\d)$/,
      'Waktu Check-out harus dalam format 24 jam HH:MM:SS yang valid.',
    ),
})

const updateShift = async (formData) => {
  loading.value = true
  error.value = null

  const shiftId = props.shiftData.id

  try {
    const response = await api.put(`shift/${shiftId}`, formData)

    console.log(`API Response Sukses (Update ID: ${shiftId}):`, response.data)

    emit('shiftUpdated')

    emit('close')
  } catch (err) {
    console.error('Error saat meng-update Shift:', err)
    if (err.response) {
      error.value = `Gagal menyimpan data. Server Error (${err.response.status}): ${err.response.data.message || err.response.statusText}`
    } else if (err.request) {
      error.value = 'Tidak ada respon dari server.'
    } else {
      error.value = 'Gagal memproses request.'
    }
  } finally {
    loading.value = false
  }
}

const onSubmit = (values) => {
  updateShift(values)
}
</script>
