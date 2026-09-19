<script lang="ts" setup>
import type { BreadcrumbItem } from '~/types/breadcrumb'

const props = defineProps<{ items: BreadcrumbItem[] }>()
</script>

<template>
  <nav class="breadcrumb-container" aria-label="Percorso di navigazione">
    <ol class="breadcrumb">
      <li
        v-for="(item, index) in props.items"
        :key="index"
        class="breadcrumb-item"
        :class="{ active: index === props.items.length - 1 }"
        :aria-current="index === props.items.length - 1 ? 'page' : undefined"
      >
        <NuxtLink v-if="item.to" :to="item.to">{{ item.label }}</NuxtLink>
        <span v-else>{{ item.label }}</span>
        <span
          v-if="index < props.items.length - 1"
          class="separator"
          aria-hidden="true"
        >&gt;</span>
      </li>
    </ol>
  </nav>
</template>