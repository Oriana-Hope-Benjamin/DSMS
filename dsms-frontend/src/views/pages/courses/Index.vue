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
          <h4 class="page-title">Courses</h4>
        </div>
        <div class="col-sm-8 col-9 text-right m-b-20">
          <button
            type="button"
            class="btn btn-primary float-right btn-rounded"
            @click="openModal()"
          >
            <i class="fa fa-plus"></i> Add Course
          </button>
        </div>
      </div>
      <div class="row filter-row">
        <div class="col-sm-6 col-md-3">
          <div class="form-group form-focus">
            <label class="focus-label">Course Name</label>
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
                  <th style="min-width: 200px">Course Name</th>
                  <th>Duration</th>
                  <th>Price</th>
                  <th>Students</th>
                  <th>Status</th>
                  <th class="text-right">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="course in courses" :key="course.id">
                  <router-link :to="{ name: 'coursesdetails', params: { id: course.id } }">
                    <td>
                      <h2 style="color: blue">{{ course.name }}</h2>
                    </td>
                  </router-link>
                  <td>{{ course.duration }} {{ course.duration_value }}</td>
                  <td>{{ course.base_price }}</td>
                  <td>{{ course.students || 0 }}</td>
                  <td>
                    <span
                      class="custom-badge"
                      :class="course.status_id === 'active' ? 'status-green' : 'status-red'"
                      >{{ course.status_id }}</span
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
                        <a class="dropdown-item" href="#" @click.prevent="openModal(course)"
                          ><i class="fa fa-pencil m-r-5"></i> Edit</a
                        >
                        <a class="dropdown-item" href="#" @click.prevent="confirmDelete(course)"
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
          <h5 class="modal-title" id="exampleModalLabel">
            {{ isEditing ? 'Edit Course' : 'Add New Course' }}
          </h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
            @click="closeModal"
          >
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
                    >Course Name <span class="text-danger">*</span></label
                  >
                  <input class="form-control" type="text" v-model="newCourse.name" />
                  <span v-if="validationErrors.name" class="error text-danger">
                    {{ validationErrors.name[0] }}
                  </span>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="col-form-label">Price</label>
                  <input class="form-control" type="email" v-model="newCourse.base_price" />
                </div>
                <span v-if="validationErrors.base_price" class="error text-danger">
                  {{ validationErrors.base_price[0] }}
                </span>
              </div>
              <div class="col-md-12">
                <label class="col-form-label">Duration<span class="text-danger">*</span></label>
                <div class="input-group">
                  <input
                    type="number"
                    class="form-control"
                    aria-label="Duration quantity"
                    v-model.number="newCourse.duration"
                    min="0"
                  />
                  <span v-if="validationErrors.duration" class="error text-danger">
                    {{ validationErrors.duration[0] }}
                  </span>
                  <div class="input-group-append">
                    <button
                      class="btn btn-outline-secondary dropdown-toggle"
                      type="button"
                      data-toggle="dropdown"
                      aria-haspopup="true"
                      aria-expanded="false"
                    >
                      {{ newCourse.duration_value || 'Select' }}
                    </button>
                    <div class="dropdown-menu">
                      <template v-if="durations && durations.length">
                        <a
                          v-for="duration in durations"
                          :key="duration.id"
                          class="dropdown-item"
                          href="#"
                          @click.prevent="selectDuration(duration)"
                        >
                          {{ duration.name }}
                        </a>
                      </template>
                      <span v-else class="dropdown-item disabled">No durations</span>
                    </div>
                  </div>
                </div>
                <span v-if="validationErrors.duration_value" class="error text-danger">
                  {{ validationErrors.duration_value[0] }}
                </span>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label class="col-form-label">Description</label>
                  <textarea
                    rows="3"
                    class="form-control"
                    v-model="newCourse.description"
                  ></textarea>
                </div>
                <span v-if="validationErrors.phone_number" class="error text-danger">
                  {{ validationErrors.description[0] }}
                </span>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
          <button
            type="button"
            @click="submitCourse"
            :disabled="formLoading"
            class="btn btn-primary"
          >
            {{ isEditing ? 'Save Changes' : 'Add Course' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted, reactive, nextTick } from 'vue'
import axiosClient from '@/api/axios.js'
import { showSuccess, showError, showConfirm } from '@/utils/notifications'
import AppSidePanel from '@/assets/components/AppSidePanel.vue'
import AppHeader from '@/assets/components/AppHeader.vue'

const courses = ref([])
const durations = ref([])
const loading = ref(true)
const error = ref(null)
const formLoading = ref(false)
const validationErrors = ref({})

const newCourse = reactive({
  name: '',
  duration: '',
  base_price: '',
  description: '',
  duration_value: '',
})

const isEditing = ref(false)
const editingId = ref(null)

// fetch courses
async function fetchCourses() {
  loading.value = true
  error.value = null

  try {
    const response = await axiosClient.get('api/courses')

    if (response.data.data) {
      courses.value = response.data.data
    } else {
      courses.value = response.data
    }
  } catch (err) {
    console.error('Failed to fetch courses:', err)
    error.value = 'An error occurred while fetching branches. Please try again.'
  } finally {
    loading.value = false
  }
}

async function fetchDurations() {
  try {
    const response = await axiosClient.get('api/durations')

    if (response.data.data) {
      durations.value = response.data.data
      console.log(durations.value)
    } else {
      durations.value = response.data
    }
  } catch (err) {
    console.error('Failed to fetch courses:', err)
    error.value = 'An error occurred while fetching courses. Please try again.'
  }
}
/**
 * Open the modal for creating or editing a branch.
 * If `branch` is provided we'll populate the form and switch to edit mode.
 */
function openModal(course = null) {
  validationErrors.value = {}
  if (course) {
    isEditing.value = true
    editingId.value = course.id
    newCourse.name = course.name
    newCourse.duration = course.duration
    newCourse.duration_value = course.duration_value
    newCourse.base_price = course.base_price
    newCourse.description = course.description
  } else {
    isEditing.value = false
    editingId.value = null
    newCourse.branch_name = ''
    newCourse.address = ''
    newCourse.email = ''
    newCourse.phone_number = ''
  }

  // show the modal (Bootstrap 5, jQuery fallback, or DOM fallback)
  try {
    const modalEl = document.getElementById('exampleModal')
    if (window.bootstrap && window.bootstrap.Modal) {
      const bsModal =
        window.bootstrap.Modal.getInstance(modalEl) || new window.bootstrap.Modal(modalEl)
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
async function submitCourse() {
  formLoading.value = true
  validationErrors.value = {}

  try {
    if (isEditing.value && editingId.value) {
      await axiosClient.put(`/api/courses/${editingId.value}`, newCourse)
      showSuccess('Course updated successfully!')
    } else {
      await axiosClient.post('/api/courses', newCourse)
      showSuccess('Course created successfully!')
    }

    // clear form
    newCourse.name = ''
    newCourse.duration = ''
    newCourse.base_price = ''
    newCourse.description = ''
    newCourse.duration_value = ''
    isEditing.value = false
    editingId.value = null

    // ensure DOM updates before closing (optional)
    await nextTick()
    closeModal()
    await fetchCourses()
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
 * Set duration unit/value from dropdown (e.g. 'weeks', 'days')
 */
function selectDuration(duration) {
  if (!duration) return
  // prefer an explicit value field, else use name or id
  newCourse.duration_value = duration.id ?? ''
}

/**
 * Ask for confirmation then delete a branch.
 */
async function confirmDelete(course) {
  const id = course.id
  if (!id) {
    showError('Unable to determine course id to delete.')
    return
  }
  // use Notiflix-style confirm helper
  showConfirm(
    'Delete Course',
    `Are you sure you want to delete course "${course.name || ''}"?`,
    async () => {
      await deleteBranch(id)
    },
    () => {
      // user cancelled - no action needed
    },
  )
}

async function deleteBranch(id) {
  formLoading.value = true
  try {
    await axiosClient.delete(`/api/courses/${id}`)
    showSuccess('Course deleted successfully!')
    await fetchCourses()
  } catch (err) {
    console.error('Failed to delete course:', err)
    showError('Failed to delete course. Please try again.')
  } finally {
    formLoading.value = false
  }
}

function closeModal() {
  try {
    const modalEl = document.getElementById('exampleModal')

    // Bootstrap 5 native API
    if (window.bootstrap && window.bootstrap.Modal) {
      const bsModal =
        window.bootstrap.Modal.getInstance(modalEl) || new window.bootstrap.Modal(modalEl)
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
  ;(fetchCourses(), fetchDurations())
})
</script>
