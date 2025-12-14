<template>
    <div class="bg-white rounded-lg shadow overflow-hidden mx-10">
      <!-- Header -->
      <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
        <h2 class="text-xl font-semibold text-gray-800">{{ title }}</h2>
        
        <button
          v-if="canCreate"
          @click="$emit('create')"
          class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition cursor-pointer"
        >
          {{ createLabel || `Create ${title}` }}
        </button>
      </div>
  
      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th v-for="col in columns" :key="col.key" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ col.header }}
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="item in validItems" :key="item.id" class="hover:bg-gray-50">
              <td v-for="col in columns" :key="col.key" class="px-6 py-4 text-sm text-gray-900">

                <slot :name="col.key" :item="item">

                  <div v-if="col.key === 'actions'">
                    <button @click="$emit('edit', item)" class="text-indigo-600 hover:text-indigo-900 font-medium mr-4 cursor-pointer">Edit</button>
                    <button @click="$emit('delete', item.id)" class="text-red-600 hover:text-red-900 font-medium cursor-pointer">Delete</button>
                  </div>
  
                  <div v-else-if="Array.isArray(item[col.key]) && item[col.key][0]?.code" class="flex flex-wrap gap-1">
                    <span
                      v-for="apt in item[col.key]"
                      :key="apt.code"
                      class="inline-block px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800"
                    >
                      {{ apt.code === 'ALL' ? 'ALL' : apt.code }}
                    </span>
                  </div>
  
                  <span v-else>
                    {{ displayValue(item[col.key]) }}
                  </span>
                </slot>
              </td>
            </tr>
  
            <!-- Empty State Handle -->
            <tr v-if="validItems.length === 0">
              <td :colspan="columns.length" class="px-6 py-24 text-center text-gray-500">
                <p class="text-lg font-medium">No {{ title.toLowerCase() }} found</p>
                <p v-if="canCreate" class="text-sm mt-2">Click the button above to create one.</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </template>
  
  <script setup>
  import { computed } from 'vue'
  
  const props = defineProps({
    title: String,
    columns: Array,
    data: Array,
    createLabel: String
  })
  
  const emit = defineEmits(['create', 'edit', 'delete'])
  
  const isAdmin = computed(() => store.getters.isAdmin)
  const canCreate = computed(() => isAdmin && !!props.createLabel)
  
  const validItems = computed(() => {
    return Array.isArray(props.data) ? props.data.filter(item => item && item.id) : []
  })
  
  const displayValue = (val) => {
    if (val === null || val === undefined) return '—'
    if (typeof val === 'boolean') return val ? 'Yes' : 'No'
    return val
  }
  </script>