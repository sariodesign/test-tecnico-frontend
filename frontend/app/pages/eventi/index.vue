<script lang="ts" setup>
import type { Event } from '~/types/event'

const route = useRoute()
const router = useRouter()
const events = ref<Event[]>([])

const { data: categoriesData } = await useFetch<{ data: string[] }>('/api/events/categories')

const search = ref((route.query.search as string) ?? '')
const page = computed(() => Number(route.query.page ?? 1))

async function toggleFavorite(event: Event) {
  const wasFavorite = event.is_favorite
  event.is_favorite = !wasFavorite

  try {
    await $fetch(`/api/events/${event.id}/favorite`, {
      method: wasFavorite ? 'DELETE' : 'POST',
    })
  } catch {
    event.is_favorite = wasFavorite
  }
}

const { data, pending, error } = await useFetch<{
  data: Event[]
  meta: { current_page: number; last_page: number; total: number }
}>('/api/events', {
  query: computed(() => ({
    'filter[is_published]': 1,
    'filter[title]': (route.query.search as string) || undefined,
    'filter[category]': (route.query.category as string) || undefined,
    page: route.query.page || undefined,
  })),
})

function updateQuery(changes: Record<string, string | number | undefined>) {
  const query = { ...route.query, ...changes }
  Object.keys(query).forEach((k) => {
    if (!query[k]) delete query[k]
  })
  router.push({ query })
}

let searchTimer: ReturnType<typeof setTimeout>
watch(search, (value) => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => {
    updateQuery({ search: value || undefined, page: undefined }) 
  }, 400)
})

watch(data, (newData) => {
  events.value = newData?.data ?? []
}, { immediate: true })

const categoryModel = ref((route.query.category as string) ?? '')
watch(categoryModel, (value) => {
  updateQuery({ category: value || undefined, page: undefined })
})

function goToPage(p: number) {
  updateQuery({ page: p })
}
</script>

<template>
  <div class="container-xxl my-4">
    <Breadcrumb :items="[{ label: 'Home', to: '/' }, { label: 'Eventi' }]" />
    <h1 class="mb-4">Eventi</h1>

    <div class="row mb-4">
      <div class="col-12 col-md-6 mb-3">
        <InputSearch
          id="formSearch"
          v-model="search"
          label="Cerca eventi"
          :type="'text'"
        />
      </div>
      <div class="col-12 col-md-6 mb-3">
        <Select
          id="category"
          v-model="categoryModel"
          label="Categoria"
          :options="categoriesData?.data ?? []"
          placeholder="Tutte le categorie"
        />
      </div>
    </div>

    <p v-if="pending" aria-live="polite">Caricamento eventi…</p>

    <p v-else-if="error" class="alert alert-danger" role="alert">
      Impossibile caricare gli eventi.
    </p>

    <p v-else-if="!events.length" class="alert alert-info">
      Nessun evento trovato con questi criteri.
    </p>

    <template v-else>
      <div class="row align-items-stretch">
        <div class="col-12 col-md-6 col-lg-4 mb-3 mb-md-4" v-for="event in events" :key="event.id">
          <CardEvent class="h-100" :event="event" @toggle-favorite="toggleFavorite" />
        </div>
      </div>

      <Pagination
        :current-page="page"
        :last-page="data?.meta.last_page ?? 1"
        @change="goToPage"
      />
    </template>
  </div>
</template>