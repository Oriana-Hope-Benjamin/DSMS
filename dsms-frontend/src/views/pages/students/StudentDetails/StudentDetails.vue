<script setup>
import { useRoute } from 'vue-router'
import { ref, onMounted, watch } from 'vue'
import axiosClient from '@/api/axios.js'
import AppSidePanel from '@/assets/components/AppSidePanel.vue'
import AppHeader from '@/assets/components/AppHeader.vue'

const route = useRoute()

// Accept an optional `studentId` prop from the parent; fall back to route param
const props = defineProps({
    studentId: {
        type: [String, Number],
        default: null,
    },
})

const student = ref({})
const loading = ref(false)
const error = ref(null)

async function fetchStudent() {
    const id = props.studentId ?? route.params.id
    if (!id) return
    loading.value = true

    try {
        const res = await axiosClient.get(`/api/students/${id}`)
        // The show() method returns an array, so get the first element
        student.value = Array.isArray(res.data) ? res.data[0] : res.data
        console.log('Student details fetched:', student.value)
    } catch (err) {
        console.error('Failed to fetch student details:', err)
        error.value = 'Failed to load student details.'
    } finally {
        loading.value = false
    }
}

onMounted(() => {
    fetchStudent()
})

// If parent passes a prop and it changes, fetch the new student
watch(() => props.studentId, (newId) => {
    if (newId) fetchStudent()
})

</script>
<template>
    <AppHeader />
    <AppSidePanel />

    <div class="row">
        <div class="col-sm-7 col-6">
            <h6 class="page-title">Student Profile</h6>
        </div>

        <div class="col-sm-5 col-6 text-right m-b-30">
            <a href="edit-profile.html" class="btn btn-primary btn-rounded"><i class="fa fa-plus"></i> Edit
                Profile</a>
        </div>
    </div>
    <div class="card-box profile-header" v-if="student">
        <div class="row">
            <div class="col-md-12">
                <div class="profile-view">
                    <div class="profile-img-wrap">
                        <div class="profile-img">
                            <a href="#"><img class="avatar" src="/assets/img/user.jpg" alt=""></a>
                        </div>
                    </div>
                    <div class="profile-basic">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="profile-info-left">
                                    <h3 class="user-name m-t-0 mb-0">{{ student.user_firstname }} {{
                                        student.user_lastname
                                        }}</h3>
                                    <!--  <small class="text-muted">Gynecologist</small> -->
                                    <div class="staff-id">{{ student.student_number }}</div>
                                    <div class="staff-msg"><a href="chat.html" class="btn btn-primary">Send
                                            Message</a></div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <ul class="personal-info">
                                    <li>
                                        <span class="title">Phone:</span>
                                        <span class="text"><a href="#">{{ student.user_phone }}</a></span>
                                    </li>
                                    <li>
                                        <span class="title">Email:</span>
                                        <span class="text"><a href="#">{{ student.user_email }}</a></span>
                                    </li>
                                    <li>
                                        <span class="title">Birthday:</span>
                                        <span class="text">{{ student.date_of_birth }}</span>
                                    </li>
                                    <li>
                                        <span class="title">Address:</span>
                                        <span class="text">{{ student.address || "No Address" }}</span>
                                    </li>
                                    <li>
                                        <span class="title">Gender:</span>
                                        <span class="text">{{ student.gender }}</span>
                                    </li>
                                    <li>
                                        <span class="title">NIN:</span>
                                        <span class="text">{{ student.nin }}</span>
                                    </li>
                                    <li>
                                        <span class="title">Branch:</span>
                                        <span class="text">{{ student.branch_name }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="profile-tabs">
        <div class="tab-pane show active" id="about-cont">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-box">
                        <h3 class="card-title">Education Informations</h3>
                        <div class="experience-box">
                            <ul class="experience-list">
                                <li>
                                    <div class="experience-user">
                                        <div class="before-circle"></div>
                                    </div>
                                    <div class="experience-content">
                                        <div class="timeline-content">
                                            <a href="#/" class="name">International College of Medical
                                                Science (UG)</a>
                                            <div>MBBS</div>
                                            <span class="time">2001 - 2003</span>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="experience-user">
                                        <div class="before-circle"></div>
                                    </div>
                                    <div class="experience-content">
                                        <div class="timeline-content">
                                            <a href="#/" class="name">International College of Medical
                                                Science (PG)</a>
                                            <div>MD - Obstetrics & Gynaecology</div>
                                            <span class="time">1997 - 2001</span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-box mb-0">
                        <h3 class="card-title">Experience</h3>
                        <div class="experience-box">
                            <ul class="experience-list">
                                <li>
                                    <div class="experience-user">
                                        <div class="before-circle"></div>
                                    </div>
                                    <div class="experience-content">
                                        <div class="timeline-content">
                                            <a href="#/" class="name">Consultant Gynecologist</a>
                                            <span class="time">Jan 2014 - Present (4 years 8 months)</span>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="experience-user">
                                        <div class="before-circle"></div>
                                    </div>
                                    <div class="experience-content">
                                        <div class="timeline-content">
                                            <a href="#/" class="name">Consultant Gynecologist</a>
                                            <span class="time">Jan 2009 - Present (6 years 1 month)</span>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="experience-user">
                                        <div class="before-circle"></div>
                                    </div>
                                    <div class="experience-content">
                                        <div class="timeline-content">
                                            <a href="#/" class="name">Consultant Gynecologist</a>
                                            <span class="time">Jan 2004 - Present (5 years 2 months)</span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
