<template>
    <div class="p-8">
      <!-- Using Custom Table component to make reusable component if needed -->
      <Table                                          
        title="Default Commissions"
        create-label="Create Commission Rule"
        :columns="columns"
        :data="commissions"
        @create="openCreateModal"
        @edit="openEditModal"
        @delete="deleteCommission"
      />
  
      <!-- To create or update use the modal -->
      <CommissionModal
        :show="modalOpen"
        :commission-data="selectedCommission"
        @close="modalOpen = false"
        @submit="handleSubmit"
      />
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import Table from '../../../utils/Table.vue'
  import CommissionModal from './Commission.vue'
  import Swal from 'sweetalert2';
  import api from '../../../axios'
  import { useStore } from 'vuex'
  
  const commissions = ref([])
  const modalOpen = ref(false)
  const selectedCommission = ref(null)
 
  
  const store = useStore()

  const token = store.getters.token
  
  const columns = [
    { key: 'origins', header: 'Origin' },
    { key: 'destinations', header: 'Destination' },
    { key: 'rate_value', header: 'Rate Value' },
    { key: 'rate_type', header: 'Type' },
    { key: 'actions', header: 'Actions' }
  ]
  
  const openCreateModal = () => {
    selectedCommission.value = null
    modalOpen.value = true
  }
  
  const openEditModal = (item) => {
    selectedCommission.value = item
    modalOpen.value = true
  }
  
  const deleteCommission = async (id) => {
    const result = await Swal.fire({
      title: 'Are you sure?',
      text: 'This commission rule will be permanently deleted.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel',
    });

    if (!result.isConfirmed) return;

    try {
        await api.delete(`/api/commission/${id}`, {
          headers: { Authorization: `Bearer ${token}` },
        });

        await Swal.fire('Deleted!', 'Commission rule has been deleted.', 'success');
        await fetchCommissions();
    } catch (error) {
        let message = 'Failed to delete commission rule.';
        if (error.response?.data?.message) {
          message = error.response.data.message;
        } else if (error.response?.status === 404) {
          message = 'Rule not found.';
        } else if (error.response?.status === 403) {
          message = 'Permission denied.';
        }
        else{
          message = 'Something went wrong.'
        }

        Swal.fire('Error', message, 'error');
    }
  };
  
  const handleSubmit = async (payload) => {
    const confirmText = payload.isEdit 
      ? 'Are you sure you want to update this commission rule?' 
      : 'Are you sure you want to create this new commission rule?';

    const confirmResult = await Swal.fire({
      title: 'Confirm',
      text: confirmText,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes, save it',
      cancelButtonText: 'Cancel',
    });

    if (!confirmResult.isConfirmed) {
      return;
    }

    try {
      let response;

      if (payload.isEdit) {
        response = await api.put(`/api/commission/${payload.id}`, {
          rules: payload.rules.map(rule => ({
            origins: rule.origins,
            destinations: rule.destinations,
            rate_value: rule.rate_value,
            rate_type: rule.rate_type,
          }))
        }, {
          headers: { Authorization: `Bearer ${token}` }
        });

        await Swal.fire('Updated!', 'Commission rule updated successfully.', 'success');
      } else {
        response = await api.post('/api/commission', {
          rules: payload.rules.map(rule => ({
            origins: rule.origins,
            destinations: rule.destinations,
            rate_value: rule.rate_value,
            rate_type: rule.rate_type,
          }))
        }, {
          headers: { Authorization: `Bearer ${token}` }
        });

        await Swal.fire('Created!', 'Commission rule created successfully.', 'success');
      }

      // Close modal and refresh data
      modalOpen.value = false;
      selectedCommission.value = null;
      await fetchCommissions();

    } catch (error) {

      let message = 'Something went wrong while saving the commission rule.';

      if (error.response) {
          const { status, data } = error.response;
          if (data?.message) {
            message = data.message;
          }

          if (status === 401) {
            message = 'You are not logged in or your session has expired. Please log in again.';
          }
          else if (status === 404 && payload.isEdit) {
            message = 'Commission rule not found. It may have been deleted by another user.';
          }
          else if (status === 422) {
            message = data?.message || 'Please check the form fields and try again.';
          }
          else if (status === 400) {
            message = data?.message || 'Invalid request. No data provided.';
          }
          else if (status >= 500) {
            message = data?.message || 'Server error. Please try again later or contact support.';
          }
      }
      else if (error.request && !error.response) {
        message = 'Network error. Please check your internet connection and try again.';
      }

      await Swal.fire({
        title: 'Error',
        text: message,
        icon: 'error',
        confirmButtonText: 'OK'
      });
    }
  };
  
  const fetchCommissions = async () => {
    const res = await api.get('/api/commission',{
      headers: { Authorization: `Bearer ${token}` },
    })

    commissions.value = res.data.data.rules || []
  }
  
  onMounted(() => {
    fetchCommissions()
  })
  </script>