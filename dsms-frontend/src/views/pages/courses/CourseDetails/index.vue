<script setup>
import { useRoute } from 'vue-router'
import { computed, onMounted } from 'vue'
import AppSidePanel from '@/assets/components/AppSidePanel.vue'
import AppHeader from '@/assets/components/AppHeader.vue'
import Details from './Details.vue';
import AddOns from './AddOns.vue';
import { showLoading, hideLoading } from '@/utils/notifications'
import Lessons from './Lessons.vue';

onMounted(() => {
    showLoading('Loading course details...')
})

const route = useRoute()
// expose courseId as a prop value (computed so reactivity works)
const courseId = computed(() => route.params.id)
</script>
<template>
  <AppHeader />
  <AppSidePanel />
  <div class="page-wrapper">
    <div class="content">
      <div class="">
        <div class="card-box">
          <h6 class="card-title">Course Name</h6>
          <ul class="nav nav-tabs nav-tabs-bottom">
            <li class="nav-item">
              <a class="nav-link active" href="#bottom-tab1" data-toggle="tab">Overview</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#bottom-tab2" data-toggle="tab">Add-Ons</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#bottom-tab3" data-toggle="tab">Lessons</a>
            </li>
             <li class="nav-item">
              <a class="nav-link" href="#bottom-tab4" data-toggle="tab">Students</a>
            </li>
             <li class="nav-item">
              <a class="nav-link" href="#bottom-tab5" data-toggle="tab">Payments</a>
            </li>
          </ul>

          <div class="tab-content">
            <div class="tab-pane show active" id="bottom-tab1"> <Details :course-id="courseId" /></div>
            <div class="tab-pane" id="bottom-tab2"><AddOns/></div>
            <div class="tab-pane" id="bottom-tab3"><Lessons :course-id="courseId" /></div>
            <div class="tab-pane" id="bottom-tab4">Tab content 4</div>
            <div class="tab-pane" id="bottom-tab5">Tab content 5</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
