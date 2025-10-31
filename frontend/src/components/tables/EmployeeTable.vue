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
                placeholder="Cari Nama, Telepon dan lainnya..."
                class="h-11 w-full rounded-lg border border-gray-200 bg-transparent py-2.5 pl-12 pr-14 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:bg-white/[0.03] dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
              />
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

    <div v-if="loading" class="p-6 text-center text-gray-500 dark:text-gray-400">
      Memuat data pengguna...
    </div>
    <div v-else-if="error" class="p-6 text-center text-red-600 dark:text-red-400">
      <p class="font-bold">Gagal memuat data!</p>
      <p class="text-sm">({{ error }})</p>
    </div>
    <div v-else-if="users.length === 0" class="p-6 text-center text-gray-500 dark:text-gray-400">
      Tidak ada data pengguna yang ditemukan.
    </div>

    <div
      v-show="!loading && !error && users.length > 0"
      class="max-w-full overflow-x-auto custom-scrollbar"
    >
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
      v-show="!loading && !error && users.length > 0"
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

import { ref, h, watchEffect, onMounted } from 'vue'
import api from '@/services/api'

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

const users = ref([])
const loading = ref(false)
const error = ref(null)

const columnHelper = createColumnHelper()
const sorting = ref([])
const globalFilter = ref('')
const pagination = ref({
  pageIndex: 0,
  pageSize: 5,
})

const fetchUsers = async () => {
  loading.value = true
  error.value = null
  try {
    const response = await api.get('employee')
    if (Array.isArray(response.data)) {
      users.value = response.data
    } else {
      users.value = response.data.data || []
    }
  } catch (err) {
    console.error('Error fetching users:', err)
    if (err.response) {
      error.value = `Server Error (${err.response.status}): ${err.response.statusText}`
    } else if (err.request) {
      error.value = 'Tidak ada respon dari server'
    } else {
      error.value = 'Gagal memproses request.'
    }
  } finally {
    loading.value = false
  }
}

onMounted(fetchUsers)

const columns = [
  columnHelper.accessor('id', {
    header: 'ID',
    cell: (info) => info.getValue(),
    size: 50,
  }),
  columnHelper.accessor('name', {
    header: 'Nama Pengguna',
    cell: (info) => h('span', { class: 'font-medium' }, info.getValue()),
  }),
  columnHelper.accessor('phone', {
    header: 'Nomor Telepon',
    cell: (info) => info.getValue() || '-',
  }),
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
  columnHelper.accessor('created_at', {
    header: 'Dibuat Pada',
    cell: (info) => formatDateTime(info.getValue()),
  }),
  columnHelper.accessor('updated_at', {
    header: 'Diperbarui Pada',
    cell: (info) => formatDateTime(info.getValue()),
  }),

  columnHelper.display({
    id: 'actions',
    header: 'Aksi',
    cell: ({ row }) =>
      h('div', { class: 'space-x-2 whitespace-nowrap' }, [
        h(
          'button',
          {
            class:
              'inline-flex items-center justify-center rounded-lg transition px-2 py-1 text-xs font-medium bg-yellow-500 text-white shadow-theme-xs hover:bg-yellow-600',
          },
          'Edit',
        ),
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

const table = useVueTable({
  get data() {
    return users.value
  },
  columns,
  getCoreRowModel: getCoreRowModel(),

  getFilteredRowModel: getFilteredRowModel(),
  onGlobalFilterChange: (updater) => {
    globalFilter.value = typeof updater === 'function' ? updater(globalFilter.value) : updater
  },

  getSortedRowModel: getSortedRowModel(),
  onSortingChange: (updater) => {
    sorting.value = typeof updater === 'function' ? updater(sorting.value) : updater
  },

  getPaginationRowModel: getPaginationRowModel(),
  onPaginationChange: (updater) => {
    pagination.value = typeof updater === 'function' ? updater(pagination.value) : updater
  },

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

watchEffect(() => {
  table.setOptions((prev) => ({
    ...prev,
    data: users.value,
  }))
})
</script>
