<script setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter, useRoute } from 'vue-router'
import { showSuccess, showError } from '@/utils/notifications'

const authStore = useAuthStore()
const router = useRouter()
const route = useRoute()

const form = ref({
  email: '',
  password: '',
})

async function handleLogin() {
  authStore.errors = {}

  try {
    await authStore.login(form.value)
   

    const redirectPath = route.query.redirect

    if (redirectPath) {
      router.push(redirectPath)
    } else {
      router.push({ name: 'home' })
    }
  } catch (error) {
    console.error('Login failed in component:', error)
    showError('Login failed. Please check your credentials.')
  }
}
</script>
<template>
  <div class="main-wrapper account-wrapper">
    <div class="account-page">
      <div class="account-center">
        <div class="account-box">
          <div v-if="authStore.errors.message" class="alert alert-danger">
            {{ authStore.errors.message }}
          </div>
          <div v-if="Object.keys(authStore.errors).length > 0 && !authStore.errors.message" class="alert alert-danger">
            Please check the errors below.
          </div>
          <form class="form-signin" @submit.prevent="handleLogin">
            <div class="account-logo">
              <a href="index-2.html"><img src="/assets/img/logo-car.png" alt="" /></a>
            </div>
            <div class="form-group">
              <label>Email</label>
              <input type="email" class="form-control" v-model="form.email" required />
            </div>
            <div v-if="authStore.errors.email" class="small text-danger mt-1">
              {{ Array.isArray(authStore.errors.email) ? authStore.errors.email[0] : authStore.errors.email }}
            </div>
            <div class="form-group">
              <label>Password</label>
              <input type="password" class="form-control" v-model="form.password" required />
            </div>
            <div v-if="authStore.errors.password" class="small text-danger mt-1">
              {{ Array.isArray(authStore.errors.password) ? authStore.errors.password[0] : authStore.errors.password }}
            </div>
            <div class="form-group text-right">
              <a href="forgot-password.html">Forgot your password?</a>
            </div>
            <div class="form-group text-center">
              <button type="submit" class="btn btn-primary account-btn">Login</button>
            </div>
            <div class="text-center register-link">
              Don’t have an account? <a href="register.html">Register Now</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
