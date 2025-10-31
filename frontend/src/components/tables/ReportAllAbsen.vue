<template>
  <div
    class="w-full lg:w-12/12 mx-auto overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
  >
    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
      <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
        <div class="w-full lg:w-1/2">
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
                placeholder="Cari Nama Karyawan, Status, atau Tanggal..."
                class="h-11 w-full rounded-lg border border-gray-200 bg-transparent py-2.5 pl-12 pr-14 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:bg-white/[0.03] dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
              />
            </div>
          </form>
        </div>

        <div class="flex items-center gap-2 flex-shrink-0">
          <p class="text-sm text-gray-600 dark:text-gray-400 font-medium whitespace-nowrap">
            Filter Tanggal:
          </p>
          <div>
            <div class="relative">
              <flat-pickr
                v-model="dateFilter.startDate"
                :config="flatpickrConfig"
                class="dark:bg-dark-900 h-11 w-40 cursor-pointer appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pl-4 pr-5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                placeholder="Tanggal Awal"
              />
            </div>
          </div>
          <span class="text-sm text-gray-600 dark:text-gray-400">-</span>
          <div>
            <div class="relative">
              <flat-pickr
                v-model="dateFilter.endDate"
                :config="flatpickrConfig"
                class="dark:bg-dark-900 h-11 w-40 cursor-pointer appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pl-4 pr-5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                placeholder="Tanggal Akhir"
              />
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="max-w-full overflow-x-auto custom-scrollbar">
      <table class="divide-y divide-gray-200 dark:divide-gray-700 table-fixed">
        <thead>
          <tr class="border-b border-gray-200 dark:border-gray-700">
            <th
              v-for="header in table.getFlatHeaders()"
              :key="header.id"
              :colSpan="header.colSpan"
              :class="[
                'px-5 py-3 text-left sm:px-6 bg-gray-50 dark:bg-gray-800',
                { 'cursor-pointer select-none': header.column.getCanSort() },
                header.column.columnDef.size ? `w-[${header.column.columnDef.size}px]` : '',
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
              :class="[
                'px-5 py-4 sm:px-6 text-theme-sm text-gray-700 dark:text-gray-300',
                cell.column.columnDef.size ? `w-[${cell.column.columnDef.size}px]` : '',
                cell.column.id === 'CheckInPhoto' || cell.column.id === 'CheckOutPhoto'
                  ? 'overflow-hidden text-ellipsis whitespace-normal break-all'
                  : 'whitespace-nowrap',
              ]"
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
      <div class="flex items-center gap-4 flex-shrink-0">
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

      <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap justify-center">
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

        <span
          class="flex items-center gap-1 text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap"
        >
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
import { ref, h, computed } from 'vue'
import flatPickr from 'vue-flatpickr-component'
import 'flatpickr/dist/flatpickr.css'
const formatDate = (dateString) => {
  if (!dateString) return '-'
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('id-ID', {
    year: 'numeric',
    month: 'long',
    day: '2-digit',
  }).format(date)
}

const renderStatusBadge = (statusText) => {
  let colorClass
  switch (statusText) {
    case 'Present':
    case 'On Time':
      colorClass = 'bg-green-100 text-green-700'
      break
    case 'Late':
    case 'Early Leave':
      colorClass = 'bg-yellow-100 text-yellow-700'
      break
    case 'Overtime':
      colorClass = 'bg-blue-100 text-blue-700'
      break
    case 'Sick':
    case 'Leave':
      colorClass = 'bg-gray-100 text-gray-700'
      break
    case 'Absent':
      colorClass = 'bg-red-100 text-red-700'
      break
    default:
      colorClass = 'bg-gray-200 text-gray-800'
  }

  return h(
    'span',
    {
      class: `inline-flex items-center rounded-full px-3 py-0.5 text-xs font-medium ${colorClass}`,
    },
    statusText,
  )
}

const renderRawLink = (photoPath) => {
  if (photoPath) {
    return h(
      'span',
      {
        class: 'text-xs text-gray-700 dark:text-gray-300 break-all',
      },
      photoPath,
    )
  }
  return h('span', { class: 'text-gray-400 text-xs' }, '-')
}

const defaultAttendanceData = [
  {
    id: 1,
    user_id: 101,
    shift_id: 1,
    date: '2025-10-28',
    check_in_time: '08:05:00',
    check_out_time: '16:50:00',
    check_in_status: 'On Time',
    check_out_status: 'On Time',
    attedance_status: 'Present',
    check_in_image: '/path/to/img1_very_long_url_to_test_break_all_functionality.jpg',
    check_out_image: '/path/to/img2_very_long_url_to_test_break_all_functionality.jpg',
    user_name: 'Joko Susanto',
    shift_name: 'Shift Pagi (Reguler)',
  },
  {
    id: 2,
    user_id: 102,
    shift_id: 2,
    date: '2025-10-28',
    check_in_time: '14:30:00',
    check_out_time: '23:00:00',
    check_in_status: 'Late',
    check_out_status: 'On Time',
    attedance_status: 'Present',
    check_in_image: '/path/to/img3.jpg',
    check_out_image: '/path/to/img4.jpg',
    user_name: 'Rina Dewi',
    shift_name: 'Shift Sore (Produksi)',
  },
  {
    id: 3,
    user_id: 103,
    shift_id: 1,
    date: '2025-10-29',
    check_in_time: null,
    check_out_time: null,
    check_in_status: null,
    check_out_status: null,
    attedance_status: 'Sick',
    check_in_image: null,
    check_out_image: null,
    user_name: 'Adi Saputra',
    shift_name: 'Shift Pagi (Reguler)',
  },
  {
    id: 4,
    user_id: 101,
    shift_id: 1,
    date: '2025-10-29',
    check_in_time: '07:55:00',
    check_out_time: '18:30:00',
    check_in_status: 'On Time',
    check_out_status: 'Overtime',
    attedance_status: 'Present',
    check_in_image: '/path/to/img5.jpg',
    check_out_image: '/path/to/img6.jpg',
    user_name: 'Joko Susanto',
    shift_name: 'Shift Pagi (Reguler)',
  },
  {
    id: 5,
    user_id: 104,
    shift_id: 3,
    date: '2025-10-29',
    check_in_time: '09:10:00',
    check_out_time: '13:00:00',
    check_in_status: 'Late',
    check_out_status: 'Early Leave',
    attedance_status: 'Present',
    check_in_image: '/path/to/img7.jpg',
    check_out_image: '/path/to/img8.jpg',
    user_name: 'Budi Santoso',
    shift_name: 'Shift Libur (Admin)',
  },
  {
    id: 6,
    user_id: 105,
    shift_id: 1,
    date: '2025-10-30',
    check_in_time: '08:00:00',
    check_out_time: '17:00:00',
    check_in_status: 'On Time',
    check_out_status: 'On Time',
    attedance_status: 'Present',
    check_in_image: '/path/to/img9.jpg',
    check_out_image: '/path/to/img10.jpg',
    user_name: 'Siti Rahayu',
    shift_name: 'Shift Pagi (Reguler)',
  },
  {
    id: 7,
    user_id: 106,
    shift_id: 2,
    date: '2025-10-30',
    check_in_time: '15:15:00',
    check_out_time: '23:05:00',
    check_in_status: 'Late',
    check_out_status: 'On Time',
    attedance_status: 'Present',
    check_in_image: '/path/to/img11.jpg',
    check_out_image: '/path/to/img12.jpg',
    user_name: 'Faisal Akbar',
    shift_name: 'Shift Sore (Produksi)',
  },
]

const rawAttendanceRecords = ref(defaultAttendanceData)
const columnHelper = createColumnHelper()
const sorting = ref([])
const globalFilter = ref('')

const dateFilter = ref({
  startDate: '',
  endDate: '',
})

const pagination = ref({
  pageIndex: 0,
  pageSize: 5,
})

const attendanceRecords = computed(() => {
  const start = dateFilter.value.startDate
  const end = dateFilter.value.endDate
  if (!start && !end) {
    return rawAttendanceRecords.value
  }
  const startDate = start ? new Date(start) : null
  const endDate = end ? new Date(end) : null
  if (endDate) {
    endDate.setDate(endDate.getDate() + 1)
  }
  return rawAttendanceRecords.value.filter((record) => {
    const recordDate = new Date(record.date)
    let isAfterStart = true
    if (startDate) {
      isAfterStart = recordDate >= startDate
    }
    let isBeforeEnd = true
    if (endDate) {
      isBeforeEnd = recordDate < endDate
    }
    return isAfterStart && isBeforeEnd
  })
})

const columns = [
  columnHelper.accessor('id', {
    header: 'ID',
    cell: (info) => info.getValue(),
    size: 50,
    enableSorting: false,
  }),
  columnHelper.accessor('user_name', {
    header: 'Karyawan',
    cell: (info) => h('span', { class: 'font-medium' }, info.getValue()),
    size: 150,
  }),
  columnHelper.accessor('date', {
    header: 'Tanggal',
    cell: (info) => formatDate(info.getValue()),
    size: 120,
  }),
  columnHelper.accessor('shift_name', {
    header: 'Shift',
    cell: (info) => info.getValue(),
    size: 150,
  }),
  columnHelper.accessor('check_in_time', {
    id: 'CheckIn',
    header: 'Absen Masuk',
    cell: ({ row }) => {
      const time = row.original.check_in_time ? row.original.check_in_time.substring(0, 5) : '-'
      const status = row.original.check_in_status
      return h('div', { class: 'flex flex-col' }, [
        h('span', time),
        status
          ? renderStatusBadge(status)
          : h('span', { class: 'text-xs text-gray-400 dark:text-gray-500' }, 'Belum Absen'),
      ])
    },
    enableSorting: false,
    size: 100,
  }),
  columnHelper.accessor('check_in_image', {
    id: 'CheckInPhoto',
    header: 'Foto Absen Masuk',
    cell: (info) => renderRawLink(info.getValue()),
    enableSorting: false,
    size: 140,
  }),
  columnHelper.accessor('check_out_time', {
    id: 'CheckOut',
    header: 'Absen Keluar',
    cell: ({ row }) => {
      const time = row.original.check_out_time ? row.original.check_out_time.substring(0, 5) : '-'
      const status = row.original.check_out_status
      return h('div', { class: 'flex flex-col' }, [
        h('span', time),
        status
          ? renderStatusBadge(status)
          : h('span', { class: 'text-xs text-gray-400 dark:text-gray-500' }, 'Belum Absen'),
      ])
    },
    enableSorting: false,
    size: 100,
  }),
  columnHelper.accessor('check_out_image', {
    id: 'CheckOutPhoto',
    header: 'Foto Absen Keluar',
    cell: (info) => renderRawLink(info.getValue()),
    enableSorting: false,
    size: 140,
  }),
  columnHelper.accessor('attedance_status', {
    id: 'OverallStatus',
    header: 'Status Kehadiran',
    cell: (info) => renderStatusBadge(info.getValue()),
    filterFn: 'equals',
    size: 120,
  }),
]

const table = useVueTable({
  data: attendanceRecords,
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

const date = ref(null)

const flatpickrConfig = {
  dateFormat: 'Y-m-d',
  altInput: true,
  altFormat: 'j F, Y',
  wrap: true,
}
</script>
