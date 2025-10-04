<script setup>
import EntityTable from "@/components/EntityTable.vue";
</script>

<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 space-y-8">
      <div class="grid gap-8 lg:grid-cols-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
          <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6">
            <h2 class="text-xl font-semibold text-white">Пользователи</h2>
          </div>
          <EntityTable
            api-url="http://localhost:8001/api/v1/users"
            :columns="[
              { label: 'ID', field: 'id', sortable: true, filterable: false, type: 'number' },
              { label: 'Имя', field: 'name', sortable: true, filterable: true, type: 'text' },
            ]"
          />
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
          <div class="bg-gradient-to-r from-green-500 to-green-600 p-6">
            <h2 class="text-xl font-semibold text-white">Заказы</h2>
          </div>
          <EntityTable
            api-url="http://localhost:8001/api/v1/orders"
            :columns="[
              { label: 'ID', field: 'id', sortable: true, filterable: false, type: 'number' },
              { label: 'ID Пользователя', field: 'user_id', sortable: true, filterable: false, type: 'number' },
              { label: 'Название', field: 'title', sortable: true, filterable: false, type: 'text' },
              { label: 'Цена', field: 'cost', sortable: true, filterable: false, type: 'currency' },
            ]"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.container {
  transition: all 0.3s ease;
}
</style>
