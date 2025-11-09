<template>
  <!-- App Header -->
  <AppHeader />
  <!-- App Header -->

  <!-- App side Panel -->
  <AppSidePanel />
  <div class="page-wrapper">
    <div class="content">
      <div class="row">
        <div class="col-sm-4 col-3">
          <h4 class="page-title">Branches</h4>
        </div>
        <div class="col-sm-8 col-9 text-right m-b-20">
          <button
            type="button"
            class="btn btn-primary float-right btn-rounded"
            @click="openModal()"
          ><i class="fa fa-plus"></i> Add Branch</button>
        </div>
      </div>
      <div class="row filter-row">
        <div class="col-sm-6 col-md-3">
          <div class="form-group form-focus">
            <label class="focus-label">Branch Name</label>
            <input type="text" class="form-control floating" />
          </div>
        </div>
        <div class="col-sm-6 col-md-3">
          <a href="#" class="btn btn-success btn-block"> Search </a>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="table-responsive">
            <table class="table table-striped custom-table">
              <thead>
                <tr>
                  <th style="min-width: 200px">Name</th>
                  <th>Address</th>
                  <th>Email</th>
                  <th>Telephone</th>
                  <th>Status</th>
                  <th class="text-right">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="branch in branches" :key="branch.id">
                  <td>
                    <h2>{{ branch.branch_name }}</h2>
                  </td>
                  <td>{{ branch.address }}</td>
                  <td>{{ branch.email }}</td>
                  <td>{{ branch.phone_number }}</td>
                  <td>
                    <span
                      class="custom-badge"
                      :class="branch.status === 'active' ? 'status-green' : 'status-red'"
                      >{{ branch.status_id }}</span
                    >
                  </td>
                  <td class="text-right">
                    <div class="dropdown dropdown-action">
                      <a
                        href="#"
                        class="action-icon dropdown-toggle"
                        data-toggle="dropdown"
                        aria-expanded="false"
                        ><i class="fa fa-ellipsis-v"></i
                      ></a>
                      <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#" @click.prevent="openModal(branch)"
                          ><i class="fa fa-pencil m-r-5"></i> Edit</a
                        >
                        <a class="dropdown-item" href="#" @click.prevent="confirmDelete(branch)"
                          ><i class="fa fa-trash-o m-r-5"></i> Delete</a
                        >
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Branch Modal -->
  <div
    class="modal fade"
    id="exampleModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ isEditing ? 'Edit Branch' : 'Add New Branch' }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModal">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div v-if="validationErrors.message" class="error-message">
          {{ validationErrors.message }}
        </div>
        <div class="modal-body">
          <form v-on:submit.prevent>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="col-form-label"
                    >Branch Name <span class="text-danger">*</span></label
                  >
                  <input class="form-control" type="text" v-model="newBranch.branch_name" />
                  <span v-if="validationErrors.branch_name" class="error text-danger">
                    {{ validationErrors.branch_name[0] }}
                  </span>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="col-form-label">Address</label>
                  <textarea rows="3" class="form-control" v-model="newBranch.address"></textarea>
                </div>
                <span v-if="validationErrors.address" class="error text-danger">
                  {{ validationErrors.address[0] }}
                </span>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-form-label">Email</label>
                  <input class="form-control" type="email" v-model="newBranch.email" />
                </div>
                <span v-if="validationErrors.email" class="error text-danger">
                  {{ validationErrors.email[0] }}
                </span>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-form-label">Phone Number</label>
                  <input class="form-control" type="text" v-model="newBranch.phone_number" />
                </div>
                <span v-if="validationErrors.phone_number" class="error text-danger">
                  {{ validationErrors.phone_number[0] }}
                </span>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
          <button type="button" @click="submitBranch" :disabled="formLoading" class="btn btn-primary">
            {{ isEditing ? 'Save Changes' : 'Add Branch' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted, reactive, nextTick } from 'vue'
import axiosClient from '@/api/axios.js'
import { showSuccess, showError, showConfirm } from '@/utils/notifications';
import AppSidePanel from '@/assets/components/AppSidePanel.vue'
import AppHeader from '@/assets/components/AppHeader.vue'

const branches = ref([])
const loading = ref(true)
const error = ref(null)
const formLoading = ref(false)
const validationErrors = ref({})

const newBranch = reactive({
  branch_name: '',
  address: '',
  email: '',
  phone_number: '',
})

const isEditing = ref(false)
const editingId = ref(null)

// fetch branches
async function fetchBranches() {
  loading.value = true
  error.value = null

  try {
    const response = await axiosClient.get('api/branches')

    if (response.data.data) {
      branches.value = response.data.data
    } else {
      branches.value = response.data
    }
  } catch (err) {
    console.error('Failed to fetch branches:', err)
    error.value = 'An error occurred while fetching branches. Please try again.'
  } finally {
    loading.value = false
  }
}

/**
 * Open the modal for creating or editing a branch.
 * If `branch` is provided we'll populate the form and switch to edit mode.
 */
function openModal(branch = null) {
  validationErrors.value = {}
  if (branch) {
    isEditing.value = true
    editingId.value = branch.id || branch._id || null
    newBranch.branch_name = branch.branch_name || ''
    newBranch.address = branch.address || ''
    newBranch.email = branch.email || ''
    newBranch.phone_number = branch.phone_number || ''
  } else {
    isEditing.value = false
    editingId.value = null
    newBranch.branch_name = ''
    newBranch.address = ''
    newBranch.email = ''
    newBranch.phone_number = ''
  }

  // show the modal (Bootstrap 5, jQuery fallback, or DOM fallback)
  try {
    const modalEl = document.getElementById('exampleModal')
    if (window.bootstrap && window.bootstrap.Modal) {
      const bsModal = window.bootstrap.Modal.getInstance(modalEl) || new window.bootstrap.Modal(modalEl)
      bsModal.show()
      return
    }
  } catch (e) {
    // ignore
  }

  if (window.jQuery) {
    window.jQuery('#exampleModal').modal('show')
    return
  }

  const el = document.getElementById('exampleModal')
  if (el) {
    el.classList.add('show')
    el.style.display = 'block'
    el.setAttribute('aria-hidden', 'false')
    // add backdrop
    const backdrop = document.createElement('div')
    backdrop.className = 'modal-backdrop fade show'
    document.body.appendChild(backdrop)
  }
}

/**
 * Submit the branch form. Uses POST for create, PUT for update.
 */
async function submitBranch() {
  formLoading.value = true
  validationErrors.value = {}

  try {
    if (isEditing.value && editingId.value) {
      await axiosClient.put(`/api/branches/${editingId.value}`, newBranch)
      showSuccess('Branch updated successfully!')
    } else {
      await axiosClient.post('/api/branches', newBranch)
      showSuccess('Branch created successfully!')
    }

    // clear form
    newBranch.branch_name = ''
    newBranch.address = ''
    newBranch.email = ''
    newBranch.phone_number = ''
    isEditing.value = false
    editingId.value = null

    // ensure DOM updates before closing (optional)
    await nextTick()
    closeModal()
    await fetchBranches()
  } catch (error) {
    if (error.response && error.response.status === 422) {
      validationErrors.value = error.response.data.errors
      showError('Please check the form for errors.')
    } else {
      console.error('Failed to submit branch:', error)
      showError('An unexpected error occurred. Please try again.')
    }
  } finally {
    formLoading.value = false
  }
}

/**
 * Ask for confirmation then delete a branch.
 */
async function confirmDelete(branch) {
  const id = branch.id || branch._id || null
  if (!id) {
    showError('Unable to determine branch id to delete.')
    return
  }
  // use Notiflix-style confirm helper
  showConfirm(
    'Delete Branch',
    `Are you sure you want to delete branch "${branch.branch_name || ''}"?`,
    async () => {
      await deleteBranch(id)
    },
    () => {
      // user cancelled - no action needed
    }
  )
}

async function deleteBranch(id) {
  formLoading.value = true
  try {
    await axiosClient.delete(`/api/branches/${id}`)
    showSuccess('Branch deleted successfully!')
    await fetchBranches()
  } catch (err) {
    console.error('Failed to delete branch:', err)
    showError('Failed to delete branch. Please try again.')
  } finally {
    formLoading.value = false
  }
}

function closeModal() {
  try {
    const modalEl = document.getElementById('exampleModal')

    // Bootstrap 5 native API
    if (window.bootstrap && window.bootstrap.Modal) {
      const bsModal = window.bootstrap.Modal.getInstance(modalEl) || new window.bootstrap.Modal(modalEl)
      bsModal.hide()
      return
    }
  } catch (e) {
    // ignore
  }

  // jQuery / Bootstrap 4
  if (window.jQuery) {
    window.jQuery('#exampleModal').modal('hide')
    return
  }

  // fallback: simple DOM hide + remove backdrop
  const el = document.getElementById('exampleModal')
  if (el) {
    el.classList.remove('show')
    el.style.display = 'none'
    el.setAttribute('aria-hidden', 'true')
  }
  const backdrop = document.querySelector('.modal-backdrop')
  if (backdrop && backdrop.parentNode) backdrop.parentNode.removeChild(backdrop)
}

onMounted(() => {
  fetchBranches()
})
</script>
