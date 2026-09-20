<script lang="ts" setup>
const props = defineProps<{
  currentPage: number
  lastPage: number
}>()

const emit = defineEmits<{ change: [page: number] }>()

const pages = computed(() =>
  Array.from({ length: props.lastPage }, (_, i) => i + 1)
)

function goTo(page: number) {
  if (page >= 1 && page <= props.lastPage && page !== props.currentPage) {
    emit('change', page)
  }
}
</script>

<template>
  <nav v-if="lastPage > 1" class="pagination-wrapper justify-content-end" aria-label="Navigazione pagine">
    <ul class="pagination">
      <li class="page-item" :class="{ disabled: currentPage <= 1 }">
        <button
          type="button"
          class="page-link"
          :disabled="currentPage <= 1"
          @click="goTo(currentPage - 1)"
        >
          <svg class="icon icon-primary" aria-hidden="true"><use href="/sprites.svg#it-chevron-left"/></svg>
          <span class="visually-hidden">Pagina precedente</span>
        </button>
      </li>

      <li v-for="page in pages" :key="page" class="page-item">
        <button
          type="button"
          class="page-link"
          :class="{ active: page === currentPage }"
          :aria-current="page === currentPage ? 'page' : undefined"
          @click="goTo(page)"
        >
          {{ page }}
        </button>
      </li>

      <li class="page-item" :class="{ disabled: currentPage >= lastPage }">
        <button
          type="button"
          class="page-link"
          :disabled="currentPage >= lastPage"
          @click="goTo(currentPage + 1)"
        >
          <span class="visually-hidden">Pagina successiva</span>
          <svg class="icon icon-primary" aria-hidden="true"><use href="/sprites.svg#it-chevron-right"/></svg>
        </button>
      </li>
    </ul>
  </nav>
</template>