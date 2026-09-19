<script lang="ts" setup>
const props = defineProps<{
  id: string
  label: string
  options: string[]
  placeholder?: string
}>()

const model = defineModel<string>()
</script>

<template>
  <div class="select-wrapper">
    <label :for="props.id">{{ props.label }}</label>
    <select :id="props.id" v-model="model">
      <option value="">{{ props.placeholder ?? 'Scegli un\'opzione' }}</option>
      <option v-for="opt in props.options" :key="opt" :value="opt">
        {{ opt }}
      </option>
    </select>
  </div>
</template>