<template>
  <div
    class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
  >
    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
      <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
        <div class="w-full lg:w-6/12">
          <form @submit.prevent>
            <div class="relative">
              <button class="absolute -translate-y-1/2 left-4 top-1/2 pointer-events-none">
                <svg
                  class="fill-gray-500 dark:fill-gray-400"
                  width="20"
                  height="20"
                  viewBox="0 0 20 20"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M3.04175 9.37363C3.04175 5.87693 5.87711 3.04199 9.37508 3.04199C12.8731 3.04199 15.7084 5.87693 15.7084 9.37363C15.7084 12.8703 12.8731 15.7053 9.37508 15.7053C5.87711 15.7053 3.04175 12.8703 3.04175 9.37363ZM9.37508 1.54199C5.04902 1.54199 1.54175 5.04817 1.54175 9.37363C1.54175 13.6991 5.04902 17.2053 9.37508 17.2053C11.2674 17.2053 13.003 16.5344 14.357 15.4176L17.177 18.238C17.4699 18.5309 17.9448 18.5309 18.2377 18.238C18.5306 17.9451 18.5306 17.4703 18.2377 17.1774L15.418 14.3573C16.5365 13.0033 17.2084 11.2669 17.2084 9.37363C17.2084 5.04817 13.7011 1.54199 9.37508 1.54199Z"
                    fill=""
                  />
                </svg>
              </button>
              <input
                type="text"
                v-model="globalFilter"
                placeholder="Cari Shift, Waktu, atau Pembuat..."
                class="h-11 w-full rounded-lg border border-gray-200 bg-transparent py-2.5 pl-12 pr-14 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:bg-white/[0.03] dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
              />
              <button
                class="absolute right-2.5 top-1/2 inline-flex -translate-y-1/2 items-center gap-0.5 rounded-lg border border-gray-200 bg-gray-50 px-[7px] py-[4.5px] text-xs -tracking-[0.2px] text-gray-500 dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-400"
              >
                <span> ⌘ </span>
                <span> K </span>
              </button>
            </div>
          </form>
        </div>

        <div>
          <button
            class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-3 text-sm bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600 disabled:bg-brand-300"
          >
            Tambah Shift
          </button>
        </div>
      </div>
    </div>
    <div class="max-w-full overflow-x-auto custom-scrollbar">
      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead>
          <tr class="border-b border-gray-200 dark:border-gray-700">
            <th
              v-for="header in table.getFlatHeaders()"
              :key="header.id"
              :colSpan="header.colSpan"
              :class="[
                'px-5 py-3 text-left sm:px-6 bg-gray-50 dark:bg-gray-800',
                { 'cursor-pointer select-none': header.column.getCanSort() },
              ]"
              @click="header.column.getToggleSortingHandler()?.($event)"
            >
              <p
                class="font-medium text-gray-500 text-theme-xs dark:text-gray-400 flex items-center gap-1"
              >
                <FlexRender
                  v-if="!header.isPlaceholder"
                  :render="header.column.columnDef.header"
                  :props="header.getContext()"
                />

                <span v-if="header.column.getIsSorted()">
                  <span v-if="header.column.getIsSorted() === 'asc'">▲</span>
                  <span v-else>▼</span>
                </span>
              </p>
            </th>
          </tr>
        </thead>

        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
          <tr v-for="row in table.getRowModel().rows" :key="row.id">
            <td
              v-for="cell in row.getVisibleCells()"
              :key="cell.id"
              class="px-5 py-4 sm:px-6 whitespace-nowrap text-theme-sm text-gray-700 dark:text-gray-300"
            >
              <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div
      class="p-4 flex flex-col sm:flex-row justify-between items-center gap-4 border-t border-gray-200 dark:border-gray-700"
    >
      <div class="flex items-center gap-4">
        <p class="text-theme-sm text-gray-500 dark:text-gray-400">
          Menampilkan {{ table.getPaginationRowModel().rows.length }} dari
          {{ table.getFilteredRowModel().rows.length }} data
        </p>

        <select
          :value="table.getState().pagination.pageSize"
          @change="table.setPageSize(Number($event.target.value))"
          class="rounded-lg border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm py-1"
        >
          <option value="5">5 per halaman</option>
          <option value="10">10 per halaman</option>
          <option value="20">20 per halaman</option>
          <option value="50">50 per halaman</option>
        </select>
      </div>

      <div class="flex items-center gap-2">
        <button
          @click="table.setPageIndex(0)"
          :disabled="!table.getCanPreviousPage()"
          class="p-2 border rounded-lg text-gray-600 dark:text-gray-400 dark:border-gray-700 disabled:opacity-50"
        >
          &lt;&lt;
        </button>
        <button
          @click="table.previousPage()"
          :disabled="!table.getCanPreviousPage()"
          class="p-2 border rounded-lg text-gray-600 dark:text-gray-400 dark:border-gray-700 disabled:opacity-50"
        >
          &lt;
        </button>

        <span class="flex items-center gap-1 text-sm text-gray-700 dark:text-gray-300">
          Halaman
          <strong class="font-semibold">
            {{ table.getState().pagination.pageIndex + 1 }} dari
            {{ table.getPageCount() }}
          </strong>
        </span>

        <button
          @click="table.nextPage()"
          :disabled="!table.getCanNextPage()"
          class="p-2 border rounded-lg text-gray-600 dark:text-gray-400 dark:border-gray-700 disabled:opacity-50"
        >
          &gt;
        </button>
        <button
          @click="table.setPageIndex(table.getPageCount() - 1)"
          :disabled="!table.getCanNextPage()"
          class="p-2 border rounded-lg text-gray-600 dark:text-gray-400 dark:border-gray-700 disabled:opacity-50"
        >
          &gt;&gt;
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import {
  FlexRender,
  getCoreRowModel,
  useVueTable,
  createColumnHelper,
  getSortedRowModel,
  getFilteredRowModel,
  getPaginationRowModel,
} from '@tanstack/vue-table'
import { ref, h } from 'vue'

/**
 * @typedef {Object} Shift
 * @property {number} id - The unique ID of the shift.
 * @property {string} shift_name - The display name of the shift.
 * @property {string} check_in_time - The check-in time (e.g., '08:00:00').
 * @property {string} check_out_time - The check-out time (e.g., '17:00:00').
 * @property {string} created_at - The creation timestamp.
 * @property {string} created_by - The user who created the shift.
 * @property {string} updated_at - The last update timestamp.
 * @property {string} updated_by - The user who last updated the shift.
 */

/**
 * Formats an ISO date string into a localized date and time string (id-ID locale).
 * @param {string | undefined} dateString - The ISO date string to format.
 * @returns {string} The formatted date/time string or '-' if input is empty.
 */
const formatDateTime = (dateString) => {
  if (!dateString) return '-'
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('id-ID', {
    year: 'numeric',
    month: 'short',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
  }).format(date)
}

/**
 * Calculates the duration between two time strings.
 * @param {string} checkIn - The check-in time string (e.g., '08:00:00').
 * @param {string} checkOut - The check-out time string (e.g., '17:00:00').
 * @returns {string} The duration string (e.g., '9 Jam 0 Menit').
 */
const calculateDuration = (checkIn, checkOut) => {
  // Simple logic: assumes checkout is later than check-in on the same day.
  const diffMs =
    new Date(`2000/01/01 ${checkOut}`).getTime() - new Date(`2000/01/01 ${checkIn}`).getTime()
  if (diffMs < 0) return 'Error'
  const hours = Math.floor(diffMs / (1000 * 60 * 60))
  const minutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60))
  return `${hours} Jam ${minutes} Menit`
}

// --- STATE MANAGEMENT ---

/** @type {Shift[]} */
const defaultShiftData = [
  {
    id: 1,
    shift_name: 'Shift Pagi (Reguler)',
    check_in_time: '08:00:00',
    check_out_time: '17:00:00',
    created_at: '2025-10-25T10:00:00Z',
    created_by: 'Admin Utama',
    updated_at: '2025-10-25T10:00:00Z',
    updated_by: 'Admin Utama',
  },
  {
    id: 2,
    shift_name: 'Shift Sore (Produksi)',
    check_in_time: '14:00:00',
    check_out_time: '23:00:00',
    created_at: '2025-10-28T12:30:00Z',
    created_by: 'HR Manager',
    updated_at: '2025-10-28T12:30:00Z',
    updated_by: 'HR Manager',
  },
  {
    id: 3,
    shift_name: 'Shift Libur (Admin)',
    check_in_time: '09:00:00',
    check_out_time: '15:00:00',
    created_at: '2025-10-20T08:00:00Z',
    created_by: 'Admin Utama',
    updated_at: '2025-10-27T15:45:00Z',
    updated_by: 'HR Staff Budi',
  },
  // Additional dummy data for pagination
  {
    id: 4,
    shift_name: 'Shift Malam (Keamanan)',
    check_in_time: '22:00:00',
    check_out_time: '07:00:00',
    created_at: '2025-11-01T08:00:00Z',
    created_by: 'Admin Utama',
    updated_at: '2025-11-01T08:00:00Z',
    updated_by: 'Admin Utama',
  },
  {
    id: 5,
    shift_name: 'Shift Khusus (IT)',
    check_in_time: '10:00:00',
    check_out_time: '19:00:00',
    created_at: '2025-11-02T09:00:00Z',
    created_by: 'IT Staff',
    updated_at: '2025-11-02T09:00:00Z',
    updated_by: 'IT Staff',
  },
  {
    id: 6,
    shift_name: 'Shift Siang (Penjualan)',
    check_in_time: '11:00:00',
    check_out_time: '20:00:00',
    created_at: '2025-11-03T11:00:00Z',
    created_by: 'Sales Manager',
    updated_at: '2025-11-03T11:00:00Z',
    updated_by: 'Sales Manager',
  },
]

/** @type {import('vue').Ref<Shift[]>} */
const shifts = ref(defaultShiftData)

const columnHelper = createColumnHelper()
const sorting = ref([])
const globalFilter = ref('')

// Pagination State
const pagination = ref({
  pageIndex: 0,
  pageSize: 5,
})

// --- COLUMN DEFINITION ---

/** @type {import('@tanstack/vue-table').ColumnDef<Shift>[]} */
const columns = [
  // Column ID
  columnHelper.accessor('id', {
    header: 'ID',
    cell: (info) => info.getValue(),
    size: 50,
  }),
  // Shift Name Column (shift_name)
  columnHelper.accessor('shift_name', {
    header: 'Nama Shift',
    cell: (info) => h('span', { class: 'font-medium' }, info.getValue()),
  }),
  // Work Time Column (Composite)
  columnHelper.accessor('check_in_time', {
    id: 'WaktuKerja',
    header: 'Waktu Kerja',
    cell: ({ row }) => {
      const checkIn = row.original.check_in_time.substring(0, 5)
      const checkOut = row.original.check_out_time.substring(0, 5)
      const duration = calculateDuration(row.original.check_in_time, row.original.check_out_time)
      return h('div', { class: 'flex flex-col' }, [
        h('span', `${checkIn} - ${checkOut}`),
        h('span', { class: 'text-xs text-gray-400 dark:text-gray-500' }, `(${duration})`),
      ])
    },
  }),
  // Created By Column (created_by)
  columnHelper.accessor('created_by', {
    header: 'Dibuat Oleh',
    cell: (info) => info.getValue(),
  }),
  // Created At Column (created_at)
  columnHelper.accessor('created_at', {
    header: 'Dibuat Pada',
    cell: (info) => formatDateTime(info.getValue()),
  }),
  // Updated By Column (updated_by)
  columnHelper.accessor('updated_by', {
    header: 'Diperbarui Oleh',
    cell: (info) => info.getValue(),
  }),
  // Updated At Column (updated_at)
  columnHelper.accessor('updated_at', {
    header: 'Diperbarui Pada',
    cell: (info) => formatDateTime(info.getValue()),
  }),

  // Action Column (Display Column with Full Buttons)
  columnHelper.display({
    id: 'actions',
    header: 'Aksi',
    cell: () =>
      h('div', { class: 'space-x-2 whitespace-nowrap' }, [
        // Edit Button (Warning/Yellow Style)
        h(
          'button',
          {
            class:
              'inline-flex items-center justify-center rounded-lg transition px-2 py-1 text-xs font-medium bg-yellow-500 text-white shadow-theme-xs hover:bg-yellow-600',
          },
          'Edit',
        ),
        // Delete Button (Danger/Red Style)
        h(
          'button',
          {
            class:
              'inline-flex items-center justify-center rounded-lg transition px-2 py-1 text-xs font-medium bg-red-500 text-white shadow-theme-xs hover:bg-red-600',
          },
          'Hapus',
        ),
      ]),
    enableSorting: false,
  }),
]

// --- TANSTACK TABLE INITIALIZATION ---

const table = useVueTable({
  data: shifts.value,
  columns,
  getCoreRowModel: getCoreRowModel(),

  // Filtering Logic
  getFilteredRowModel: getFilteredRowModel(),
  onGlobalFilterChange: (updater) => {
    globalFilter.value = typeof updater === 'function' ? updater(globalFilter.value) : updater
  },

  // Sorting Logic
  getSortedRowModel: getSortedRowModel(),
  onSortingChange: (updater) => {
    sorting.value = typeof updater === 'function' ? updater(sorting.value) : updater
  },

  // Pagination Logic
  getPaginationRowModel: getPaginationRowModel(),
  onPaginationChange: (updater) => {
    pagination.value = typeof updater === 'function' ? updater(pagination.value) : updater
  },

  // State binding
  state: {
    get sorting() {
      return sorting.value
    },
    get globalFilter() {
      return globalFilter.value
    },
    get pagination() {
      return pagination.value
    },
  },
})
</script>
