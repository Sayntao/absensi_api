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
                placeholder="Cari Nama, Telepon, atau Role..."
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
            Tambah Pengguna
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
 * @typedef {Object} User
 * @property {number} id - The unique ID of the user.
 * @property {number} role_id - The ID referencing the user's role.
 * @property {string} name - The full name of the user.
 * @property {string | null} phone - The user's phone number.
 * @property {number} is_active - User's active status (1 for active, 0 for inactive).
 * @property {string | null} created_at - The user creation timestamp.
 * @property {string | null} updated_at - The last user update timestamp.
 * @property {number | null} created_by - ID of the user who created this record.
 * @property {number | null} updated_by - ID of the user who last updated this record.
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

// Helper for Role Name mapping (Assuming role_id 1=Admin, 2=HR, 3=Karyawan)
const getRoleName = (roleId) => {
  switch (roleId) {
    case 1:
      return 'Admin'
    case 2:
      return 'HRD'
    case 3:
      return 'Karyawan'
    default:
      return 'N/A'
  }
}

// --- STATE MANAGEMENT ---

/** @type {User[]} */
const defaultUserData = [
  {
    id: 1,
    role_id: 1,
    name: 'Budi Santoso (Admin)',
    phone: '081234567890',
    is_active: 1,
    created_at: '2025-01-10T08:00:00Z',
    updated_at: '2025-10-25T10:00:00Z',
    created_by: null,
    updated_by: 1,
  },
  {
    id: 2,
    role_id: 2,
    name: 'Siti Rahmawati (HRD)',
    phone: '085000111222',
    is_active: 1,
    created_at: '2025-02-15T09:30:00Z',
    updated_at: '2025-02-15T09:30:00Z',
    created_by: 1,
    updated_by: 1,
  },
  {
    id: 3,
    role_id: 3,
    name: 'Joko Susanto (Karyawan)',
    phone: null, // Null value for phone field
    is_active: 0, // Inactive user
    created_at: '2025-05-20T11:00:00Z',
    updated_at: '2025-10-28T14:45:00Z',
    created_by: 2,
    updated_by: 2,
  },
  // Additional dummy data for pagination
  {
    id: 4,
    role_id: 3,
    name: 'Rina Dewi',
    phone: '089988776655',
    is_active: 1,
    created_at: '2025-11-01T08:00:00Z',
    updated_at: '2025-11-01T08:00:00Z',
    created_by: 2,
    updated_by: 2,
  },
  {
    id: 5,
    role_id: 3,
    name: 'Adi Saputra',
    phone: '081122334455',
    is_active: 1,
    created_at: '2025-11-02T09:00:00Z',
    updated_at: '2025-11-02T09:00:00Z',
    created_by: 2,
    updated_by: 2,
  },
]

/** @type {import('vue').Ref<User[]>} */
const users = ref(defaultUserData)

const columnHelper = createColumnHelper()
const sorting = ref([])
const globalFilter = ref('')

// Pagination State
const pagination = ref({
  pageIndex: 0,
  pageSize: 5,
})

// --- COLUMN DEFINITION ---

/** @type {import('@tanstack/vue-table').ColumnDef<User>[]} */
const columns = [
  // Column ID
  columnHelper.accessor('id', {
    header: 'ID',
    cell: (info) => info.getValue(),
    size: 50,
  }),
  // Name Column (name)
  columnHelper.accessor('name', {
    header: 'Nama Pengguna',
    cell: (info) => h('span', { class: 'font-medium' }, info.getValue()),
  }),
  // Phone Column (phone)
  columnHelper.accessor('phone', {
    header: 'Nomor Telepon',
    cell: (info) => info.getValue() || '-', // Handle NULL
  }),
  // Role Column (role_id)
  columnHelper.accessor('role_id', {
    id: 'Role',
    header: 'Role',
    cell: (info) => getRoleName(info.getValue()),
    enableSorting: true,
  }),
  // Status Column (is_active)
  columnHelper.accessor('is_active', {
    id: 'Status',
    header: 'Status',
    cell: (info) => {
      const isActive = info.getValue() === 1
      const text = isActive ? 'Aktif' : 'Tidak Aktif'
      const color = isActive ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'

      return h(
        'span',
        {
          class: `inline-flex items-center rounded-full px-3 py-0.5 text-xs font-medium ${color}`,
        },
        text,
      )
    },
    enableSorting: true,
    filterFn: 'equals',
  }),
  // Created At Column (created_at)
  columnHelper.accessor('created_at', {
    header: 'Dibuat Pada',
    cell: (info) => formatDateTime(info.getValue()),
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
  data: users.value,
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
