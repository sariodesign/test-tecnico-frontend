<script lang="ts" setup>
import type { Event } from '~/types/event'

definePageMeta({
  layout: 'area-personale',
  middleware: 'auth',
})

const { user } = useAuth()

const { data, pending, error, refresh } = await useFetch<{ data: Event[] }>('/api/favorites')

const favorites = computed(() => data.value?.data ?? [])

async function removeFavorite(event: Event) {
  try {
    await $fetch(`/api/events/${event.id}/favorite`, { method: 'DELETE' })
    await refresh()
  } catch {
    // opzionale: mostrare un errore
  }
}
</script>

<template>
  <div>
    <!-- Sezione dati utente -->
    <section class="mb-10">
      <h1 class="text-xl font-bold text-brand mb-4 dark:text-white">I miei dati</h1>
      <UiCard variant="elevated">
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <dt class="text-sm text-gray-500 dark:text-gray-400">Nome</dt>
            <dd class="font-medium dark:text-white">{{ user?.name }}</dd>
          </div>
          <div>
            <dt class="text-sm text-gray-500 dark:text-gray-400">Email</dt>
            <dd class="font-medium dark:text-white">{{ user?.email }}</dd>
          </div>
          <div>
            <dt class="text-sm text-gray-500 dark:text-gray-400">Iscritto dal</dt>
            <dd class="font-medium dark:text-white">{{ formatDate(user?.created_at) }}</dd>
          </div>
        </dl>
      </UiCard>
    </section>

    <hr class="border-gray-200 mb-10">

    <section>
      <h2 class="text-xl font-bold text-brand mb-4 dark:text-white">I miei eventi preferiti</h2>

      <p v-if="pending" class="text-gray-500">Caricamento…</p>

      <p v-else-if="error" role="alert" class="text-red-600">
        Impossibile caricare i preferiti.
      </p>

      <p v-else-if="!favorites.length" class="text-gray-500">
        Non hai ancora eventi preferiti.
      </p>

      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <UiCard v-for="event in favorites" :key="event.id" variant="outlined">
          <div>
            <span class="inline-block text-xs font-medium text-brand bg-brand/10 rounded px-2 py-1 mb-2 dark:text-white dark:bg-gray-500">
              {{ event.category }}
            </span>
            <h3 class="font-semibold text-sm leading-tight dark:text-white">
              <NuxtLink :to="`/eventi/${event.slug}`" class="hover:text-brand hover:underline">
                {{ event.title }}
              </NuxtLink>
            </h3>
            <p class="text-gray-600 text-sm mt-1 dark:text-gray-200">{{ event.location }}</p>
          </div>
          <div class="mt-4 flex justify-end">
            <UiConfirmDialog
              title="Rimuovere dai preferiti?"
              :description="`Vuoi rimuovere &quot;${event.title}&quot; dai preferiti?`"
              confirm-label="Rimuovi"
              @confirm="removeFavorite(event)"
            >
              <template #trigger>
                <UiButton variant="ghost" size="sm">Rimuovi</UiButton>
              </template>
            </UiConfirmDialog>
          </div>
        </UiCard>
      </div>
    </section>
  </div>
</template>