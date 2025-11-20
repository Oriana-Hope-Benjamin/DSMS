<script setup>
import AppHeader from '@/assets/components/AppHeader.vue'
import AppSidePanel from '@/assets/components/AppSidePanel.vue'
import { ref, onMounted, reactive } from 'vue'
import axiosClient from '@/api/axios.js'
import { showSuccess, showError, showConfirm } from '@/utils/notifications';

const students = ref([])
const branches = ref([])
const error = ref(null)
const validationErrors = ref({})
const selectedStudent = ref(null)
const editingStudent = reactive({
  firstname: '',
  lastname: '',
  gender: '',
  email: '',
  phone_number: '',
  date_of_birth: '',
  nin: '',
  branch_id: '',
  address: '',
})

async function fetchStudents() {
  error.value = null

  try {
    const response = await axiosClient.get('api/students')

    if (response.data.data) {
      students.value = response.data.data
      showSuccess('Students fetched successfully!')
    } else {
      students.value = response.data
    }
  } catch (err) {
    console.error('Failed to fetch students:', err)
    error.value = 'An error occurred while fetching students. Please try again.'
  }
}

async function fetchBranches() {
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
  }
}

/**
 * Open edit modal and populate form with student data
 */
function editStudent(student) {
  validationErrors.value = {}
  selectedStudent.value = student
  editingStudent.firstname = student.user_firstname || ''
  editingStudent.lastname = student.user_lastname || ''
  editingStudent.gender = student.gender || ''
  editingStudent.email = student.user_email || ''
  editingStudent.phone_number = student.user_phone || ''
  editingStudent.date_of_birth = student.date_of_birth || ''
  editingStudent.nin = student.nin || ''
  editingStudent.branch_id = student.branch_id || ''
  editingStudent.address = student.address || ''
}

/**
 * Update student details
 */
async function updateStudent() {
  validationErrors.value = {}
  
  if (!selectedStudent.value) return

  try {
    await axiosClient.put(`/api/students/${selectedStudent.value.id}`, editingStudent)
    showSuccess('Student updated successfully!')

    // Close modal
    closeModal()
    // Refresh student list
    await fetchStudents()
  } catch (err) {
    if (err.response && err.response.status === 422) {
      validationErrors.value = err.response.data.errors || {}
      showError('Please check the form for errors.')
    } else {
      console.error('Failed to update student:', err)
      showError('An unexpected error occurred. Please try again.')
    }
  }
}

function closeModal() {
  

  // jQuery / Bootstrap 4
  if (window.jQuery) {
    window.jQuery('#studentUpdateModal').modal('hide')
    return
  }

  // fallback: simple DOM hide + remove backdrop
  const el = document.getElementById('studentUpdateModal')
  if (el) {
    el.classList.remove('show')
    el.style.display = 'none'
    el.setAttribute('aria-hidden', 'true')
  }
  const backdrop = document.querySelector('.modal-backdrop')
  if (backdrop && backdrop.parentNode) backdrop.parentNode.removeChild(backdrop)
}


onMounted(() => {
  fetchStudents(),
  fetchBranches()
})
</script>
<template>
  <!-- App Header -->
  <AppHeader />
  <!-- App Header -->

  <!-- App side Panel -->
  <AppSidePanel />
  <div class="main-wrapper">
    <div class="page-wrapper">
      <div class="content">
        <div class="row">
          <div class="col-sm-4 col-3">
            <h4 class="page-title">Students</h4>
          </div>
          <div class="col-sm-8 col-9 text-right m-b-20">
            <router-link :to="{ name: 'add-student' }">
              <span href="#" class="btn btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> Add
                Student</span>
            </router-link>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive">
              <table class="table table-border table-striped custom-table datatable mb-0">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Student-No</th>
                    <th>Branch</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th class="text-right">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="student in students" :key="student.id">
                    <router-link :to="{ name: 'studentdetails', params: { id: student.id } }">
                    <td>
                      <img width="28" height="28" src="/assets/img/user.jpg" class="rounded-circle m-r-5" alt="" />
                      {{ student.user_firstname }} {{ student.user_lastname }}
                    </td>
                    </router-link>
                    <td>{{ student.student_number }}</td>
                    <td>{{ student.branch_name }}</td>
                    <td>{{ student.user_phone }}</td>
                    <td>{{ student.user_email }}</td>
                    <td class="text-right">
                      <div class="dropdown dropdown-action">
                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i
                            class="fa fa-ellipsis-v"></i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                          <a class="dropdown-item" href="#" @click="editStudent(student)" data-toggle="modal" data-target="#studentUpdateModal"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                          <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_patient"><i
                              class="fa fa-trash-o m-r-5"></i> Delete</a>
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
  </div>

  <!-- Edit Student Modal -->
  <div class="modal fade" id="studentUpdateModal" tabindex="-1" aria-labelledby="studentUpdateModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
      <div class="modal-content shadow-lg">
        <div class="modal-header">
          <h5 class="modal-title" id="studentUpdateModalLabel">Update Student Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
          <p class="text-muted mb-4">Make the necessary changes and click 'Update' to save.</p>

          <!-- Student Update Form -->
          <form id="student-update-form">
            <div class="row">
              <!-- First Name -->
              <div class="col-md-6 mb-3">
                <label for="firstName">First Name</label>
                <input type="text" class="form-control" id="firstName" v-model="editingStudent.firstname">
                <span v-if="validationErrors.firstname" class="text-danger small">
                  {{ validationErrors.firstname[0] }}
                </span>
              </div>

              <!-- Last Name -->
              <div class="col-md-6 mb-3">
                <label for="lastName">Last Name</label>
                <input type="text" class="form-control" id="lastName" v-model="editingStudent.lastname">
                <span v-if="validationErrors.lastname" class="text-danger small">
                  {{ validationErrors.lastname[0] }}
                </span>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="gender">Gender</label>
                <select class="form-control" id="gender" v-model="editingStudent.gender">
                  <option value="female">Female</option>
                  <option value="male">Male</option>
                </select>
                <span v-if="validationErrors.gender" class="text-danger small">
                  {{ validationErrors.gender[0] }}
                </span>
              </div>

              <!-- Branch ID -->
              <div class="col-md-6 mb-3">
                <label for="branch">Branch</label>
                <select class="form-control" id="branch" v-model.number="editingStudent.branch_id">
                  <option value="" disabled>Select branch</option>
                    <option v-for="branch in branches" :key="branch.id" :value="branch.id">
                      {{ branch.branch_name }}
                    </option>
                </select>
                <span v-if="validationErrors.branch_id" class="text-danger small">
                  {{ validationErrors.branch_id[0] }}
                </span>
              </div>
            </div>

            <div class="row">
              <!-- Email -->
              <div class="col-md-6 mb-3">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" v-model="editingStudent.email">
                <span v-if="validationErrors.email" class="text-danger small">
                  {{ validationErrors.email[0] }}
                </span>
              </div>

              <!-- Phone Number -->
              <div class="col-md-6 mb-3">
                <label for="phoneNumber">Phone Number</label>
                <input type="tel" class="form-control" id="phoneNumber" v-model="editingStudent.phone_number">
                <span v-if="validationErrors.phone_number" class="text-danger small">
                  {{ validationErrors.phone_number[0] }}
                </span>
              </div>
            </div>

            <div class="row">
              <!-- NIN -->
              <div class="col-md-6 mb-3">
                <label for="nin">NIN</label>
                <input type="text" class="form-control" id="nin" v-model="editingStudent.nin">
                <span v-if="validationErrors.nin" class="text-danger small">
                  {{ validationErrors.nin[0] }}
                </span>
              </div>

              <!-- Date of Birth -->
              <div class="col-md-6 mb-3">
                <label for="dob">Date of Birth</label>
                <input type="date" class="form-control" id="dob" v-model="editingStudent.date_of_birth">
                <span v-if="validationErrors.date_of_birth" class="text-danger small">
                  {{ validationErrors.date_of_birth[0] }}
                </span>
              </div>
            </div>

            <div class="row">
              <!-- Address (Full Width) -->
              <div class="col-12 mb-3">
                <label for="address">Address</label>
                <textarea class="form-control" id="address" rows="3" v-model="editingStudent.address"></textarea>
                <span v-if="validationErrors.address" class="text-danger small">
                  {{ validationErrors.address[0] }}
                </span>
              </div>
            </div>
          </form>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" @click="updateStudent">Update</button>
        </div>
      </div>
    </div>
  </div>
</template>
