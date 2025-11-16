<template>
  <div class="container my-5" v-if="course">
    <div class="card shadow-sm border-0 rounded-3">
      <div class="card-body p-0">
        <table class="table table-borderless align-middle mb-0">
          <tbody>
            <tr>
              <td class="fw-semibold fs-5 py-3">Course Name</td>
              <td class="text-end fs-5">{{ course.name }}</td>
            </tr>

            <tr>
              <td class="fw-semibold fs-5 py-3">Duration</td>
              <td class="text-end fs-5">{{ course.duration }}</td>
            </tr>

            <tr>
              <td class="fw-semibold fs-5 py-3">Price</td>
              <td class="text-end fs-5 text-primary fw-bold">
                {{ course.base_price }}
              </td>
            </tr>

            <tr>
              <td class="fw-semibold fs-5 py-3">Addon</td>
              <td class="text-end fs-5">{{ course.addon || "No Addon" }}</td>
            </tr>

            <tr>
              <td class="fw-semibold fs-5 py-3">Students Enrolled</td>
              <td class="text-end fs-5">{{ course.students_enrolled || "No students Enrolled"}}</td>
            </tr>

            <tr>
              <td class="fw-semibold fs-5 py-3">Description</td>
              <td class="text-end fs-5 text-muted">{{ course.description }}</td>
            </tr>

            <tr>
              <td class="fw-semibold fs-5 py-3">Created By</td>
              <td class="text-end fs-5">{{ course.created_by }}</td>
            </tr>

            <tr class="border-bottom">
              <td class="fw-semibold fs-5 py-3">Created At</td>
              <td class="text-end fs-5">{{ course.created_at }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import axiosClient from '@/api/axios.js'
import { showLoading, hideLoading } from '@/utils/notifications'

const props = defineProps({
  courseId: {
    type: [String, Number],
    required: true,
  },
})

const course = ref(null)
const loading = ref(false)
const error = ref(null)

async function fetchCourse(id) {
  if (!id) return
  loading.value = true
  error.value = null
  showLoading('Loading course details...')
  try {
    const res = await axiosClient.get(`/api/courses/${id}`)
    course.value = res.data
  } catch (err) {
    console.error('Failed to fetch course details:', err)
    error.value = 'Failed to load course details.'
  } finally {
    loading.value = false
    hideLoading()
  }
}

onMounted(() => {
  fetchCourse(props.courseId)
})

watch(
  () => props.courseId,
  (newId) => {
    fetchCourse(newId)
  },
)
</script>
<style scoped>
.card {
  transition:
    transform 0.3s ease,
    box-shadow 0.3s ease;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15);
}
</style>
