<script lang="ts" setup>
import type { Event } from '~/types/event'

const route = useRoute()
const slug = route.params.slug as string

const { data, pending, error } = await useFetch<{ data: Event }>(`/api/events/${slug}`)

if (error.value) {
  throw createError({ statusCode: 404, statusMessage: 'Evento non trovato' })
}

const event = computed(() => data.value!.data)

useSeoMeta({
  title: () => event.value.title,
  description: () => event.value.description,
  ogTitle: () => event.value.title,
  ogDescription: () => event.value.description,
  ogType: 'website',
})
</script>

<template>
  <div class="container-xxl my-4">
    <p v-if="pending">Caricamento evento…</p>
    <template v-else-if="data">
      <Breadcrumb :items="[
        { label: 'Home', to: '/' },
        { label: 'Eventi', to: '/eventi' },
        { label: event.title },
      ]" />
      <span class="chip chip-simple"><span class="chip-label">{{ event.category }}</span></span>
      <h1 class="mt-2">{{ event.title }}</h1>
      <p class="text-muted">{{ event.location }}</p>
      <p>{{ event.description }}</p>
    </template>
  </div>
</template>