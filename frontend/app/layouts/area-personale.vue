<script lang="ts" setup>
import '~/assets/css/tailwind.css'
const { user, logout } = useAuth()
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <header class="bg-brand text-white">
      <div class="max-w-4xl mx-auto px-6 py-4 flex justify-between items-center">
        <span class="font-semibold">Area personale di {{ user?.name }}</span>
        <div class="flex items-center gap-4">
          <UiButton variant="outline" size="sm" @click="logout">Esci</UiButton>
        </div>
      </div>
    </header>
    <main class="max-w-4xl mx-auto p-6">
      <slot />
    </main>
  </div>
</template>