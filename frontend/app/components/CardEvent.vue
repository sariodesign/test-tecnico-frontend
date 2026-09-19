<script lang="ts" setup>
import type { Event } from '~/types/event'

const props = defineProps<{ event: Event }>()

const emit = defineEmits<{ toggleFavorite: [event: Event] }>()

const { isLoggedIn } = useAuth()

const dateFormat = new Intl.DateTimeFormat('it-IT', { day: 'numeric', month: 'long' })
const timeFormat = new Intl.DateTimeFormat('it-IT', { hour: '2-digit', minute: '2-digit' })

const startDate = computed(() => new Date(props.event.starts_at))
const endDate = computed(() => new Date(props.event.ends_at))

const fullDateFormat = new Intl.DateTimeFormat('it-IT', {
  day: 'numeric',
  month: 'long',
  year: 'numeric',
})

const eventCreatedDate = computed(() => fullDateFormat.format(new Date(props.event.created_at)))

const dayLabel = computed(() => dateFormat.format(startDate.value))
const startTime = computed(() => timeFormat.format(startDate.value))
const endTime = computed(() => timeFormat.format(endDate.value))
</script>

<template>
  <article class="it-card rounded border shadow-sm mb-3">
    <h3 class="font-serif fs-3 it-card-title">
      <NuxtLink :to="`/eventi/${event.slug}`">{{ event.title }}</NuxtLink>
    </h3>
    <div class="it-card-body">
      <p class="it-card-subtitle font-monospace fs-6 fw-bold">
        <span class="visually-hidden">Data evento:</span>
        <time :datetime="event.starts_at">{{ dayLabel }}</time>,
        <span class="visually-hidden">Orario:</span>
        <time :datetime="event.starts_at">{{ startTime }}</time> – <time :datetime="event.ends_at">{{ endTime }}</time>
      </p>
      <p class="it-card-text">{{ event.description }}</p>
      <footer class="it-card-related">
        <div class="it-card-taxonomy">
          <span class="it-card-category">
            <span class="visually-hidden">Categoria: </span>{{ event.category }}
          </span>
        </div>
        <time class="it-card-date" :datetime="eventCreatedDate">{{ eventCreatedDate }}</time>
      </footer>
    </div>
    <div class="it-card-footer" aria-label="Link correlati:" v-if="isLoggedIn">
      <button
        type="button"
        class="btn btn-sm btn-outline-primary mt-2"
        :aria-pressed="event.is_favorite"
        @click="emit('toggleFavorite', event)"
    >
        {{ event.is_favorite ? 'Rimuovi dai preferiti' : 'Aggiungi ai preferiti' }}
    </button>
    </div>
  </article>
</template>