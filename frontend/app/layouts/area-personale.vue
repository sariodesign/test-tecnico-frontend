<script lang="ts" setup>
import '~/assets/css/tailwind.css'
const { user, logout } = useAuth()

const theme = useCookie<'light' | 'dark'>('theme', {
  default: () => 'light',
  maxAge: 60 * 60 * 24 * 365, // 1 anno
})

function toggleTheme() {
  theme.value = theme.value === 'dark' ? 'light' : 'dark'
}
</script>

<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900" :data-theme="theme">
    <header class="bg-brand text-white dark:bg-brand-dark">
      <div class="max-w-4xl mx-auto px-6 py-4 flex justify-between items-center">
        <span class="font-semibold">Area personale di {{ user?.name }}</span>
        <div class="flex items-center gap-4">
          <button
            type="button"
            :aria-pressed="theme === 'dark'"
            aria-label="Attiva o disattiva tema scuro"
            class="rounded-md p-2 hover:bg-white/10 transition-colors hover:cursor-pointer"
            @click="toggleTheme"
          >
            <span v-if="theme === 'dark'" aria-hidden="true">☀️</span>
            <span v-else aria-hidden="true">🌙</span>
          </button>

          <UiButton variant="outline" size="sm" @click="logout">Esci</UiButton>
        </div>
      </div>
      <nav aria-label="Navigazione area personale" class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-4xl mx-auto px-6">
          <ul class="flex gap-6">
            <li>
              <NuxtLink
                to="/"
                class="inline-block py-3 text-sm text-gray-700 dark:text-gray-300 border-b-2 border-transparent hover:text-brand hover:border-brand transition-colors"
              >
                Home
              </NuxtLink>
            </li>
            <li>
              <NuxtLink
                to="/eventi"
                class="inline-block py-3 text-sm text-gray-700 dark:text-gray-300 border-b-2 border-transparent hover:text-brand hover:border-brand transition-colors"
              >
                Eventi
              </NuxtLink>
            </li>
          </ul>
        </div>
      </nav>
    </header>
    <main class="max-w-4xl mx-auto p-6">
      <slot />
    </main>
  </div>
</template>