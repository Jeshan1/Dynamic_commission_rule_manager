<template>
  <div
    v-if="show"
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
    @click.self="$emit('close')"
  >
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-6xl max-h-screen overflow-y-auto">
      <div class="p-8 pb-12">

        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
          <h2 class="text-2xl font-bold text-gray-900">
            {{ isEdit ? "Edit Default Commission" : "Default Commission" }}
          </h2>
          <button @click="$emit('close')" class="text-3xl text-gray-400 hover:text-gray-600">&times;</button>
        </div>

        <form @submit.prevent="submitForm" class="space-y-8">

          <div class="space-y-6">
            
            <div class="border border-gray-200 rounded-lg">

              <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-700">Sectors</h3>
              </div>
              
              <!-- Dynamic Rule Rows -->
              <div
                v-for="(rule, index) in rules"
                :key="index"
                class="p-6 relative border-b border-gray-200 last:border-b-0"
              >

                <button
                  v-if="rules.length > 1"
                  type="button"
                  @click="removeRule(index)"
                  class="absolute top-6 right-6 text-red-500 hover:text-red-700 text-2xl font-bold"
                >&times;</button>

                <div class="grid grid-cols-12 gap-6 items-center">

                  <div class="col-span-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Origin</label>
                    <div class="relative">
                      
                      <input
                        v-model="rule.originSearch"
                        @focus="rule.showOriginDropdown = true"
                        @blur="hideDropdown(rule, 'origin')"
                        placeholder="Search airports..."
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                      />

                      <div class="mb-2 mt-2 flex flex-wrap gap-4">
                        <label
                          v-for="airport in rule.origins"
                          :key="airport.code"
                          class="flex items-center gap-2 text-sm text-gray-800"
                        >
                          <span class="w-4 h-4 bg-blue-600 rounded flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                          </span>

                          <span class="font-medium">
                            {{ airport.code }}
                          </span>

                          <button
                            type="button"
                            @click="removeAirport(rule.origins, airport)"
                            class="text-red-500 hover:text-red-700 text-sm font-bold"
                          >
                            ×
                          </button>
                        </label>

                        <span
                          v-if="rule.origins.length === 0"
                          class="text-gray-400 text-sm"
                        >
                          No origin selected
                        </span>
                      </div>

                      <div
                        v-if="rule.showOriginDropdown"
                        class="absolute z-10 top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto"
                      >
                        <div
                          v-for="airport in filteredAirports(rule.originSearch)"
                          :key="airport.code"
                          @mousedown.prevent="selectAirport(rule.origins, airport, rule, 'origin')"
                          class="px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm"
                        >
                          <strong>{{ airport.code }}</strong>
                          <span v-if="airport.code !== 'ALL'" class="text-gray-500">
                            — {{ airport.city }}, {{ airport.country }}
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-span-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Destination</label>
                    <div class="relative">
                      <input
                        v-model="rule.destinationSearch"
                        @focus="rule.showDestinationDropdown = true"
                        @blur="hideDropdown(rule, 'destination')"
                        placeholder="Search airports..."
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                      />

                      <div class="mb-2 mt-2 flex flex-wrap gap-4">
                        <label
                          v-for="airport in rule.destinations"
                          :key="airport.code"
                          class="flex items-center gap-2 text-sm text-gray-800"
                        >
                          <span class="w-4 h-4 bg-blue-600 rounded flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                          </span>

                          <span class="font-medium">
                            {{ airport.code }}
                          </span>

                          <button
                            type="button"
                            @click="removeAirport(rule.destinations, airport)"
                            class="text-red-500 hover:text-red-700 text-sm font-bold"
                          >
                            ×
                          </button>
                        </label>

                        <span
                          v-if="rule.destinations.length === 0"
                          class="text-gray-400 text-sm"
                        >
                          No destination selected
                        </span>
                      </div>

                      <div
                        v-if="rule.showDestinationDropdown"
                        class="absolute z-10 top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto"
                      >
                        <div
                          v-for="airport in filteredAirports(rule.destinationSearch)"
                          :key="airport.code"
                          @mousedown.prevent="selectAirport(rule.destinations, airport, rule, 'destination')"
                          class="px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm"
                        >
                          <strong>{{ airport.code }}</strong>
                          <span v-if="airport.code !== 'ALL'" class="text-gray-500">
                            — {{ airport.city }}, {{ airport.country }}
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-span-3" :class="{ 'mt-[-32px]': index === 0 }">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rate</label>
                    <div class="flex items-center space-x-2">
                      <input
                        v-model.number="rule.rate_value"
                        type="number"
                        step="0.01"
                        min="0"
                        required
                        class="flex-1 px-4 py-2.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                        placeholder="0"
                      />
                      <select
                        v-model="rule.rate_type"
                        required
                        class="w-32 px-3 py-2.5 border border-blue-500 rounded-md bg-white text-blue-700 font-medium focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer text-sm"
                      >
                        <option value="percentage">Percentage</option>
                        <option value="flat">Flat</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="text-left py-4">
            <button
              type="button"
              @click="addRule"
              class="text-blue-600 hover:text-blue-800 font-medium text-lg flex items-center"
            >
              <span class="text-2xl mr-2">+</span> Add New Default Rate
            </button>
          </div>

          <div class="pt-8">
            <button
              type="submit"
              class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-lg text-lg transition duration-200"
            >
              {{ isEdit ? "Update" : "Save" }} Default Commission
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
  
<script setup>
  import { ref, watch } from "vue";
  import Swal from "sweetalert2";
  
  const props = defineProps({
    show: Boolean,
    commissionData: Object
  });
  const emit = defineEmits(["close", "submit"]);
  
  const isEdit = ref(false);
  
  // provided sample airports data
  const airports = [
    { code: "ALL", name: "All Airports", city: "", country: "" },
    { code: "KTM", name: "Tribhuvan International Airport", city: "Kathmandu", country: "Nepal" },
    { code: "DEL", name: "Indira Gandhi International Airport", city: "Delhi", country: "India" },
    { code: "BOM", name: "Chhatrapati Shivaji Maharaj International Airport", city: "Mumbai", country: "India" },
    { code: "DXB", name: "Dubai International Airport", city: "Dubai", country: "UAE" },
    { code: "DOH", name: "Hamad International Airport", city: "Doha", country: "Qatar" },
    { code: "SIN", name: "Singapore Changi Airport", city: "Singapore", country: "Singapore" },
    { code: "BKK", name: "Suvarnabhumi Airport", city: "Bangkok", country: "Thailand" },
    { code: "HKG", name: "Hong Kong International Airport", city: "Hong Kong", country: "China" },
    { code: "KUL", name: "Kuala Lumpur International Airport", city: "Kuala Lumpur", country: "Malaysia" },
    { code: "IST", name: "Istanbul Airport", city: "Istanbul", country: "Türkiye" },
    { code: "LHR", name: "London Heathrow Airport", city: "London", country: "United Kingdom" },
    { code: "FRA", name: "Frankfurt Airport", city: "Frankfurt", country: "Germany" },
    { code: "SYD", name: "Sydney Kingsford Smith Airport", city: "Sydney", country: "Australia" },
    { code: "JFK", name: "John F. Kennedy International Airport", city: "New York", country: "USA" },
    { code: "LAX", name: "Los Angeles International Airport", city: "Los Angeles", country: "USA" },
  ];
  
  const rules = ref([
    {
      origins: [],
      destinations: [],
      rate_value: null,
      rate_type: "",
      originSearch: "",
      destinationSearch: "",
      showOriginDropdown: false,
      showDestinationDropdown: false,
    }
  ]);
  
  // Watch for edit mode
  watch(() => props.commissionData, (data) => {
    if (data && (data.origins || data.destinations)) {
      isEdit.value = true;
      let ruleList = [];

      if (data.rules) {
        ruleList = data.rules;
      } else if (data.origins || data.destinations) {
        ruleList = [{
          origins: data.origins || [],
          destinations: data.destinations || [],
          rate_value: data.rate_value || null,
          rate_type: data.rate_type || ""
        }];
      }

      rules.value = ruleList.map(r => ({
        origins: r.origins || [],
        destinations: r.destinations || [],
        rate_value: r.rate_value || null,
        rate_type: r.rate_type || "",
        originSearch: "",
        destinationSearch: "",
        showOriginDropdown: false,
        showDestinationDropdown: false,
      }));
    } else {
      isEdit.value = false;
      rules.value = [{
        origins: [], destinations: [], rate_value: null, rate_type: "",
        originSearch: "", destinationSearch: "",
        showOriginDropdown: false, showDestinationDropdown: false
      }];
    }
  }, { immediate: true });
  
  // Filter airports
  const filteredAirports = (query) => {
    if (!query) return airports;
    return airports.filter(a =>
      a.code.toLowerCase().includes(query.toLowerCase()) ||
      a.city.toLowerCase().includes(query.toLowerCase()) ||
      a.country.toLowerCase().includes(query.toLowerCase())
    );
  };
  
  const selectAirport = (list, airport, rule, type) => {
    if (airport.code === "ALL") {
      list.splice(0, list.length, airport);
    } else {
      const allIndex = list.findIndex(a => a.code === "ALL");
      if (allIndex > -1) list.splice(allIndex, 1);
      if (!list.find(a => a.code === airport.code)) {
        list.push(airport);
      }
    }
    if (type === 'origin') rule.originSearch = "";
    if (type === 'destination') rule.destinationSearch = "";
  };
  
  const removeAirport = (list, airport) => {
    const index = list.findIndex(a => a.code === airport.code);
    if (index > -1) list.splice(index, 1);
  };
  
  const hideDropdown = (rule, type) => {
    setTimeout(() => {
      if (type === 'origin') rule.showOriginDropdown = false;
      if (type === 'destination') rule.showDestinationDropdown = false;
    }, 200);
  };
  
  const addRule = () => {
    rules.value.push({
      origins: [], destinations: [], rate_value: null, rate_type: "",
      originSearch: "", destinationSearch: "",
      showOriginDropdown: false, showDestinationDropdown: false
    });
  };
  
  const removeRule = (index) => {
    if (rules.value.length > 1) {
      rules.value.splice(index, 1);
    }
  };
  
  const submitForm = () => {
    const valid = rules.value.every(r =>
      r.origins.length > 0 &&
      r.destinations.length > 0 &&
      r.rate_value > 0 &&
      r.rate_type
    );
  
    if (!valid) {
      Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Please fill in all the required fields for each rule.",
        });
      return;
    }
  
    emit("submit", {
      isEdit: isEdit.value,
      id: props.commissionData?.id,
      rules: rules.value.map(r => ({
        origins: r.origins,
        destinations: r.destinations,
        rate_value: r.rate_value,
        rate_type: r.rate_type
      }))
    });
  };
</script>