<script lang="ts" setup>
defineProps<{
  id: string
  label: string
  placeholder?: string
}>()

const model = defineModel<string>()
const inputRef = ref<HTMLInputElement | null>(null)

onMounted(async () => {
  const { Input } = await import('bootstrap-italia')
  if (inputRef.value) Input.getOrCreateInstance(inputRef.value)
})
</script>

<template>
  <div class="form-group">
    <div class="input-group">
      <span class="input-group-text">
        <svg class="icon icon-sm" aria-hidden="true">
          <use href="/sprites.svg#it-search"/>
        </svg>
      </span>
      <label :for="id">{{ label }}</label>
      <input
        :id="id"
        ref="inputRef"
        v-model="model"
        type="search"
        class="form-control"
        :placeholder="placeholder"
      >
    </div>
  </div>
</template>