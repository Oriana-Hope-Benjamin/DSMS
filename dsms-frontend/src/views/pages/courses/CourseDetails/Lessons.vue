<template>


    <div class="row">
        <div class="col-sm-4 col-3">
            <h4 class="page-title"></h4>
        </div>
        <div class="col-sm-8 col-9 text-right m-b-20">
            <button type="button" class="btn btn-primary float-right btn-rounded" @click="openModal()"><i
                    class="fa fa-plus"></i> Add Lesson</button>
        </div>
    </div>
    <div class="row filter-row">
        <div class="col-sm-6 col-md-3">
            <div class="form-group form-focus">
                <label class="focus-label">Lesson Name</label>
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
                            <th style="min-width: 200px">Title</th>
                            <th>Lesson number</th>
                            <th>Description</th>
                            <th>Is Mandatory</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="lesson in lessons" :key="lesson.id">
                            <td>
                                <h2>{{ lesson.title }}</h2>
                            </td>
                            <td>{{ lesson.order }}</td>
                            <td>{{ lesson.description }}</td>
                            <td>{{ lesson.is_mandatory }}</td>

                            <td class="text-right">
                                <div class="dropdown dropdown-action">
                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                                        aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#" @click.prevent="openModal(lesson)"><i
                                                class="fa fa-pencil m-r-5"></i> Edit</a>
                                        <a class="dropdown-item" href="#" @click.prevent="confirmDelete(lesson)"><i
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


    <!-- Add Branch Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ isEditing ? 'Edit Lesson' : 'Add New Lesson' }}
                    </h5>
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
                                    <label class="col-form-label">Lesson Name <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" v-model="newLesson.title" />
                                    <span v-if="validationErrors.title" class="error text-danger">
                                        {{ validationErrors.title[0] }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-form-label">Lesson Number</label>
                                    <input class="form-control" type="order" v-model="newLesson.order" />
                                </div>
                                <span v-if="validationErrors.order" class="error text-danger">
                                    {{ validationErrors.order[0] }}
                                </span>
                            </div>
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="defaultCheck1 "
                                        v-model="newLesson.is_mandatory">
                                    <label class="form-check-label" for="defaultCheck1">
                                        Mandatory Lesson
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-form-label">Description</label>
                                    <textarea rows="3" class="form-control" v-model="newLesson.description"></textarea>
                                </div>
                                <span v-if="validationErrors.description" class="error text-danger">
                                    {{ validationErrors.description[0] }}
                                </span>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
                    <button type="button" @click="submitBranch" :disabled="formLoading" class="btn btn-primary">
                        {{ isEditing ? 'Save Changes' : 'Add Lesson' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted, watch, reactive, nextTick } from 'vue'
import axiosClient from '@/api/axios.js'
import { showLoading, hideLoading, showSuccess, showError, showConfirm } from '@/utils/notifications'

const props = defineProps({
    courseId: {
        type: [String, Number],
        required: true,
    },
})

const course = ref(null)
const lessons = ref([])
const loading = ref(true)
const error = ref(null)
const formLoading = ref(false)
const validationErrors = ref({})

const isEditing = ref(false)
const editingId = ref(null)

async function fetchCourseLessons(id) {
    if (!id) return
    error.value = null
    showLoading('Loading course lessons...')
    try {
        const res = await axiosClient.get(`/api/course-syllabus/${id}`)
        // API may return either an array (res.data) or an object with a `data` array (res.data.data).
        // Prefer the inner `data` array when present, otherwise use the response data directly.
        lessons.value = Array.isArray(res.data) ? res.data : (res.data.data || res.data)
    } catch (err) {
        console.error('Failed to fetch course lessons:', err)
        error.value = 'Failed to load course lessons.'
    } finally {
        hideLoading()
    }
}


const newLesson = reactive({
    title: '',
    order: '',
    description: '',
    is_mandatory: '',
    course_id: props.courseId,
})


/**
 * Open the modal for creating or editing a branch.
 * 
 */
function openModal(lesson = null) {
    validationErrors.value = {}
    if (lesson) {
        isEditing.value = true
        editingId.value = lesson.id || lesson._id || null
        newLesson.title = lesson.title || ''
        newLesson.order = lesson.order || ''
        newLesson.description = lesson.description || ''
        newLesson.is_mandatory = lesson.is_mandatory || false
    } else {
        isEditing.value = false
        editingId.value = null
        newLesson.title = ''
        newLesson.description = ''
        newLesson.is_mandatory = false
        newLesson.course_id = props.courseId
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
           try {
                await axiosClient.put(`/api/course-syllabus/${editingId.value}`, newLesson)
                showSuccess('Lesson updated successfully!')
            } catch (error) {
                console.error('Failed to update lesson:', error)
                showError(`${error.response && error.response.data && error.response.data.message ? error.response.data.message : 'Failed to update lesson.'}`)
            }
        } else {
            try {
                await axiosClient.post('/api/course-syllabus', newLesson)
                showSuccess('Lesson created successfully!')
            } catch (error) {
                console.error('Failed to create lesson:', error)
                showError(`${error.response && error.response.data && error.response.data.message ? error.response.data.message : 'Failed to create lesson.'}`)
            }
        }

        // clear form
        newLesson.title = ''
        newLesson.order = ''
        newLesson.description = ''
        newLesson.is_mandatory = false
        isEditing.value = false
        editingId.value = null

        // ensure DOM updates before closing (optional)
        await nextTick()
        closeModal()
        await fetchCourseLessons(props.courseId)
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
async function confirmDelete(lesson) {
    const id = lesson.id || lesson._id || null
    if (!id) {
        showError('Unable to determine lesson id to delete.')
        return
    }
    // use Notiflix-style confirm helper
    showConfirm(
        'Delete lesson',
        `Are you sure you want to delete lesson "${lesson.title || ''}"?`,
        async () => {
            await deleteLesson(id)
        },
        () => {
            // user cancelled - no action needed
        }
    )
}

async function deleteLesson(id) {
    formLoading.value = true
    try {
        await axiosClient.delete(`/api/course-syllabus/${id}`)
        showSuccess('Lesson deleted successfully!')
        fetchCourseLessons(props.courseId)
    } catch (err) {
        console.error('Failed to delete lesson:', err)
        showError('Failed to delete lesson. Please try again.')
    } finally {
        formLoading.value = false
    }
}

function closeModal() {
    // jQuery / Bootstrap 4
    if (window.jQuery) {
        window.jQuery('#exampleModal').modal('hide')
        return
    }

}

onMounted(() => {
    fetchCourseLessons(props.courseId)
})

watch(
    () => props.courseId,
    (newId) => {
        fetchCourseLessons(newId)
    },
)
</script>
