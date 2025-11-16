<script setup>
import AppHeader from '@/assets/components/AppHeader.vue'
import AppSidePanel from '@/assets/components/AppSidePanel.vue'
import { ref, onMounted} from 'vue'
import axiosClient from '@/api/axios.js'
import { showSuccess, showError, showConfirm } from '@/utils/notifications';

const students = ref([])
const error = ref(null)

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


onMounted(() => {
  fetchStudents()
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
            <span href="#" class="btn btn btn-primary btn-rounded float-right"
              ><i class="fa fa-plus"></i> Add Student</span
            >
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
                    <td>
                      <img
                        width="28"
                        height="28"
                        src="/assets/img/user.jpg"
                        class="rounded-circle m-r-5"
                        alt=""
                      />
                      {{student.user_firstname}} {{student.user_lastname}}
                    </td>
                    <td>{{student.student_number}}</td>
                    <td>{{ student.branch_name }}</td>
                    <td>{{ student.user_phone }}</td>
                    <td>{{ student.user_email }}</td>
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
                          <a class="dropdown-item" href="edit-patient.html"
                            ><i class="fa fa-pencil m-r-5"></i> Edit</a
                          >
                          <a
                            class="dropdown-item"
                            href="#"
                            data-toggle="modal"
                            data-target="#delete_patient"
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
  </div>
</template>
