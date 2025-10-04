<script setup>
import { ref, reactive, watch, onMounted, computed } from 'vue'
import axios from 'axios'

const props = defineProps({
  apiUrl: {
    type: String,
    required: true
  },
  columns: {
    type: Array,
    required: true
  },
  perPageOptions: {
    type: Array,
    default: () => [10, 20, 50, 100]
  }
})

const filters = reactive({})
const sort = reactive({})
const perPage = ref(20)
const page = ref(1)
const isLoading = ref(false)

const tableData = ref([])
const pagination = reactive({
  current_page: 1,
  last_page: 1,
  total: 0
})

const visiblePages = computed(() => {
  const current = pagination.current_page
  const last = pagination.last_page
  const delta = 2
  const range = []

  for (let i = Math.max(2, current - delta); i <= Math.min(last - 1, current + delta); i++) {
    range.push(i)
  }

  if (current - delta > 2) {
    range.unshift('...')
  }
  if (current + delta < last - 1) {
    range.push('...')
  }

  range.unshift(1)
  if (last > 1) range.push(last)

  return range
})

const fetchData = async () => {
  isLoading.value = true
  try {
    const params = {
      page: page.value,
      per_page: perPage.value
    }

    if (Object.keys(filters).length > 0) {
      params.filter = filters
    }

    if (Object.keys(sort).length > 0) {
      params.sort = sort
    }

    const { data } = await axios.get(props.apiUrl, { params })
    tableData.value = data.data
    pagination.current_page = data.current_page
    pagination.last_page = data.last_page
    pagination.total = data.total
  } catch (error) {
    console.error('Ошибка загрузки данных:', error)
  } finally {
    isLoading.value = false
  }
}

const toggleSort = (field) => {
  if (!sort[field]) {
    sort[field] = 'asc'
  } else if (sort[field] === 'asc') {
    sort[field] = 'desc'
  } else {
    delete sort[field]
  }
  page.value = 1
  fetchData()
}

const formatValue = (value, type) => {
  switch (type) {
    case 'currency':
      return new Intl.NumberFormat('ru-RU', { style: 'currency', currency: 'RUB' }).format(value)
    case 'number':
      return new Intl.NumberFormat('ru-RU').format(value)
    case 'badge':
      return value
    default:
      return value
  }
}

const getBadgeClass = (value) => {
  const status = value?.toLowerCase()
  switch (status) {
    case 'active':
    case 'completed':
    case 'success':
      return 'bg-green-100 text-green-800'
    case 'pending':
    case 'processing':
      return 'bg-yellow-100 text-yellow-800'
    case 'inactive':
    case 'cancelled':
    case 'failed':
      return 'bg-red-100 text-red-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

const clearFilters = () => {
  Object.keys(filters).forEach(key => {
    delete filters[key]
  })
  page.value = 1
  fetchData()
}

watch([perPage, page], fetchData)
onMounted(fetchData)
</script>

<template>
  <div class="p-6">
    <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
      <div class="flex flex-wrap gap-2 flex-1">
        <template v-for="col in columns" :key="col.field">
          <div v-if="col.filterable" class="relative">
            <input
              v-model="filters[col.field]"
              :placeholder="col.label"
              @input="page = 1; fetchData()"
              class="w-full sm:w-48 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-sm"
            />
          </div>
        </template>

        <button
          @click="clearFilters"
          class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 transition-colors duration-200 flex items-center gap-2"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
          Сбросить
        </button>
      </div>

      <div class="flex items-center gap-4">
        <div class="flex items-center gap-2 text-sm text-gray-600">
          <span>На странице:</span>
          <select
            v-model="perPage"
            class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
          >
            <option v-for="opt in perPageOptions" :key="opt" :value="opt">{{ opt }}</option>
          </select>
        </div>
      </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 shadow-sm">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th
              v-for="col in columns"
              :key="col.field"
              @click="col.sortable && toggleSort(col.field)"
              :class="[
                  'px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer transition-colors duration-200',
                  { 'hover:bg-gray-100': col.sortable }
                ]"
            >
              <div class="flex items-center gap-2">
                <span>{{ col.label }}</span>
                <div class="flex flex-col">
                    <span
                      :class="[
                        'text-xs transition-all duration-200',
                        sort[col.field] === 'asc' ? 'text-blue-600 scale-110' : 'text-gray-300'
                      ]"
                    >▲</span>
                  <span
                    :class="[
                        'text-xs transition-all duration-200 -mt-1',
                        sort[col.field] === 'desc' ? 'text-blue-600 scale-110' : 'text-gray-300'
                      ]"
                  >▼</span>
                </div>
              </div>
            </th>
          </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
          <tr
            v-for="row in tableData"
            :key="row.id"
            class="hover:bg-gray-50 transition-colors duration-150"
          >
            <td
              v-for="col in columns"
              :key="col.field"
              class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
            >
                <span
                  v-if="col.type === 'badge'"
                  :class="['inline-flex px-2 py-1 text-xs font-medium rounded-full', getBadgeClass(row[col.field])]"
                >
                  {{ formatValue(row[col.field], col.type) }}
                </span>
              <span v-else>
                  {{ formatValue(row[col.field], col.type) }}
                </span>
            </td>
          </tr>

          <tr v-if="isLoading">
            <td :colspan="columns.length" class="px-6 py-8 text-center">
              <div class="flex justify-center items-center gap-3">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                <span class="text-gray-600">Загрузка данных...</span>
              </div>
            </td>
          </tr>

          <tr v-else-if="tableData.length === 0 && !isLoading">
            <td :colspan="columns.length" class="px-6 py-8 text-center text-gray-500">
              <div class="flex flex-col items-center gap-2">
                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Нет данных для отображения</span>
              </div>
            </td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="mt-6 flex flex-col sm:flex-row gap-4 justify-between items-center">
      <div class="text-sm text-gray-600">
        Показано
        <span class="font-medium">{{ ((pagination.current_page - 1) * perPage) + 1 }}</span>
        -
        <span class="font-medium">{{ Math.min(pagination.current_page * perPage, pagination.total) }}</span>
        из
        <span class="font-medium">{{ pagination.total.toLocaleString('ru-RU') }}</span>
        записей
      </div>

      <div class="flex items-center gap-1">
        <button
          :disabled="pagination.current_page === 1 || isLoading"
          @click="page = pagination.current_page - 1"
          class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 flex items-center gap-2"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
        </button>

        <button
          v-for="p in visiblePages"
          :key="p"
          @click="p !== '...' && (page = p)"
          :disabled="p === '...' || isLoading"
          :class="[
            'px-3 py-2 border border-gray-300 rounded-lg min-w-10 transition-all duration-200',
            pagination.current_page === p
              ? 'bg-blue-600 text-white border-blue-600'
              : 'hover:bg-gray-50',
            p === '...' ? 'cursor-default' : ''
          ]"
        >
          {{ p }}
        </button>

        <button
          :disabled="pagination.current_page === pagination.last_page || isLoading"
          @click="page = pagination.current_page + 1"
          class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 flex items-center gap-2"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
button, select, input {
  transition: all 0.2s ease-in-out;
}

.overflow-x-auto::-webkit-scrollbar {
  height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}
</style>
