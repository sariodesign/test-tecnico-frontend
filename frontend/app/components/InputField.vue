<script lang="ts" setup>
import type { InputField } from '~/types/form'

const props = withDefaults(defineProps<InputField>(), {
  type: 'text',
  disabled: false,
  readonly: false,
  required: false,
})

const model = defineModel<string>()
const inputRef = ref<HTMLInputElement | null>(null)

onMounted(async () => {
  const { Input } = await import('bootstrap-italia')
  if (inputRef.value) Input.getOrCreateInstance(inputRef.value)
})
</script>

<template>
  <div class="form-group">
    <label :for="props.id">{{ props.label }}</label>
    <input
      :id="props.id"
      ref="inputRef"
      v-model="model"
      :type="props.type"
      :placeholder="props.placeholder"
      :disabled="props.disabled"
      :readonly="props.readonly"
      :required="props.required"
      :aria-invalid="props.error ? 'true' : undefined"
      :aria-describedby="props.error ? `${props.id}-error` : undefined"
      class="form-control"
      :class="{ 'is-invalid': props.error }"
    >
    <small
      v-if="props.error"
      :id="`${props.id}-error`"
      class="form-text text-danger"
    >
      {{ props.error }}
    </small>
  </div>
</template>