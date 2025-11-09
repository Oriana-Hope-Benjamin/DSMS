<script setup>
import AppSidePanel from '@/assets/components/AppSidePanel.vue'
import AppHeader from '@/assets/components/AppHeader.vue'
</script>
<template>
  <AppHeader />
  <AppSidePanel />
  <div class="page-wrapper">
    <div class="content">
      <div class="">
        <div class="card-box">
          <h6 class="card-title">Branches</h6>
          <ul class="nav nav-tabs nav-tabs-bottom">
            <li class="nav-item">
              <a class="nav-link active" href="#bottom-tab1" data-toggle="tab">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#bottom-tab2" data-toggle="tab">Profile</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#bottom-tab3" data-toggle="tab">Messages</a>
            </li>
          </ul>

          <div class="tab-content">
            <div class="tab-pane show active" id="bottom-tab1">Tab content 1</div>
            <div class="tab-pane" id="bottom-tab2">Tab content 2</div>
            <div class="tab-pane" id="bottom-tab3">Tab content 3</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
