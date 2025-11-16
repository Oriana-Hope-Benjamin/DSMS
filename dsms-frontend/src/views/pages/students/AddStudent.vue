<template>
  <AppHeader />
  <AppSidePanel />
  <div class="page-wrapper">
    <div class="content">
      <div class="row">
        <div class="col-lg-8 offset-lg-2">
          <h4 class="page-title">Add Student</h4>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-8 offset-lg-2">
          <form v-on:submit.prevent>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label>First Name <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" v-model.trim="newStudent.firstname" />
                  <span v-if="validationErrors.firstname" class="error text-danger">
                    {{ validationErrors.firstname[0] }}
                  </span>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label>Last Name</label>
                  <input class="form-control" type="text" v-model.trim="newStudent.lastname" />
                  <span v-if="validationErrors.lastname" class="error text-danger">
                    {{ validationErrors.lastname[0] }}
                  </span>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label>Phone <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" v-model.trim="newStudent.phone_number" />
                  <span v-if="validationErrors.phone_number" class="error text-danger">
                    {{ validationErrors.phone_number[0] }}
                  </span>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label>Email <span class="text-danger">*</span></label>
                  <input class="form-control" type="email" v-model.trim="newStudent.email" />
                  <span v-if="validationErrors.email" class="error text-danger">
                    {{ validationErrors.email[0] }}
                  </span>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label>Password</label>
                  <input class="form-control" type="password" v-model="newStudent.password" />
                  <span v-if="validationErrors.password" class="error text-danger">
                    {{ validationErrors.password[0] }}
                  </span>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label>Confirm Password</label>
                  <input class="form-control" type="password" v-model="newStudent.password_confirmation" />
                  <span v-if="validationErrors.password_confirmation" class="error text-danger">
                    {{ validationErrors.password_confirmation[0] }}
                  </span>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label>Date of Birth</label>
                  <div>
                    <input type="date" class="form-control datetimepicker" v-model="newStudent.date_of_birth" />
                    <span v-if="validationErrors.date_of_birth" class="error text-danger">
                      {{ validationErrors.date_of_birth[0] }}
                    </span>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group gender-select">
                  <label class="gen-label">Gender:</label>
                  <div class="form-check-inline">
                    <label class="form-check-label">
                      <input type="radio" name="gender" class="form-check-input" v-model="newStudent.gender"
                        value="male" />Male
                    </label>
                  </div>
                  <div class="form-check-inline">
                    <label class="form-check-label">
                      <input type="radio" name="gender" class="form-check-input" v-model="newStudent.gender"
                        value="female" />Female
                    </label>
                  </div>
                  <span v-if="validationErrors.gender" class="error text-danger">
                    {{ validationErrors.gender[0] }}
                  </span>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label>Branch</label>
                  <select class="form-control select" v-model.number="newStudent.branch_id">
                    <option value="" disabled>Select branch</option>
                    <option v-for="branch in branches" :key="branch.id" :value="branch.id">
                      {{ branch.branch_name }}
                    </option>
                  </select>
                  <span v-if="validationErrors.branch_id" class="error text-danger">
                    {{ validationErrors.branch_id[0] }}
                  </span>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label>NIN <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" v-model="newStudent.nin" />
                  <span v-if="validationErrors.nin" class="error text-danger">
                    {{ validationErrors.nin[0] }}
                  </span>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label>Address</label>
                      <input type="text" class="form-control" v-model="newStudent.address" />
                      <span v-if="validationErrors.address" class="error text-danger">
                        {{ validationErrors.address[0] }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="m-t-20 text-center">
              <button class="btn btn-primary submit-btn" @click="saveStudent">Create Student</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import AppHeader from '@/assets/components/AppHeader.vue'
import AppSidePanel from '@/assets/components/AppSidePanel.vue'
import { ref, onMounted, reactive } from 'vue'
import { useRouter } from 'vue-router'
import axiosClient from '@/api/axios.js'
import { showSuccess, showError, showConfirm } from '@/utils/notifications'
const router = useRouter()

const error = ref(null)
const branches = ref([])
const validationErrors = ref({})
const newStudent = reactive({
  firstname: '',
  lastname: '',
  gender: '',
  email: '',
  phone_number: '',
  password: '',
  password_confirmation: '',
  date_of_birth: '',
  nin: '',
  branch_id: '',
  role_id: 3,
  address: '',
})

async function fetchBranches() {
  error.value = null

  try {
    const response = await axiosClient.get('api/branches')

    if (response.data.data) {
      branches.value = response.data.data
      console.log('Branches fetched:', branches.value)
    } else {
      branches.value = response.data
    }
  } catch (err) {
    console.error('Failed to fetch branches:', err)
    error.value = 'An error occurred while fetching branches. Please try again.'
  }
}

//Save Student
async function saveStudent() {
  validationErrors.value = {}
  try {

    await axiosClient.post(`/api/students/`, newStudent)
    showSuccess('Course updated successfully!')

    //redirect to students list
    router.push({ name: 'students' })
  } catch (error) {
    if (error.response && error.response.status === 422) {
      validationErrors.value = error.response.data.errors
      showError('Please check the form for errors.')
    } else {
      console.error('Failed to submit student:', error)
      showError('An unexpected error occurred. Please try again.')
    }
  }
}

onMounted(() => {
  (
    fetchBranches()
  )
})
</script>
