<script lang="ts" setup>
definePageMeta({
  middleware: 'guest',
})

const { login } = useAuth()

const loading = ref(false)
const email = ref('')
const password = ref('')
const errors = ref<{ email?: string; password?: string }>({})
const errorMsg = ref('')

function validate(): boolean {
  errors.value = {}
  if (!email.value.trim()) errors.value.email = 'Il campo email è obbligatorio'
  if (!password.value.trim()) errors.value.password = 'Il campo password è obbligatorio'
  return Object.keys(errors.value).length === 0
}

async function onSubmit() {
  errorMsg.value = ''
  if (!validate()) return
  loading.value = true
  try {
    await login(email.value, password.value)
    await navigateTo('/area-personale')
  } catch (e: any) {
    errorMsg.value = e?.data?.data?.message ?? 'Credenziali non valide'
  } finally {
    loading.value = false
  }
}
</script>

<template>
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-12 px-lg-4">
                <Breadcrumb :items="[{ label: 'Home', to: '/' }, { label: 'Accedi' }]" />
            </div>
        </div>

        <div class="row justify-content-center">
          <div class="col-12">
            <h1 class="display-1 mb-2">Autenticati</h1>
            <p class="mb-5">Da qui potrai verificare i tuoi dati ed accedere ai servizi da autenticato</p>

            <form @submit.prevent="onSubmit" novalidate class="mb-5">

              <InputField
                id="formEmail"
                v-model="email"
                label="Email"
                type="email"
                :error="errors.email"
              />

              <InputField
                id="formPassword"
                v-model="password"
                label="Password"
                type="password"
                :error="errors.password"
              />

              <button type="submit" class="btn btn-primary" :disabled="loading">
                {{ loading ? 'Accesso in corso…' : 'Accedi' }}
              </button>
            </form>

            <div v-if="errorMsg" role="alert" class="alert alert-danger">
              {{ errorMsg }}
            </div>
          </div>
        </div>
    </div>
</template>