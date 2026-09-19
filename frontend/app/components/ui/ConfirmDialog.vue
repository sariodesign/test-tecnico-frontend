<script lang="ts" setup>
import {
  DialogRoot,
  DialogTrigger,
  DialogPortal,
  DialogOverlay,
  DialogContent,
  DialogTitle,
  DialogDescription,
  DialogClose,
} from 'reka-ui'

defineProps<{
  title: string
  description?: string
  confirmLabel?: string
  cancelLabel?: string
}>()

const emit = defineEmits<{ confirm: [] }>()
</script>

<template>
  <DialogRoot>
    <DialogTrigger as-child>
      <slot name="trigger" />
    </DialogTrigger>

    <DialogPortal>
      <DialogOverlay class="fixed inset-0 bg-black/50 z-40" />
      <DialogContent
        class="fixed left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-50 w-[90vw] max-w-md bg-white rounded-lg p-6 shadow-lg focus:outline-none"
      >
        <DialogTitle class="text-lg font-bold text-gray-900">
          {{ title }}
        </DialogTitle>
        <DialogDescription v-if="description" class="text-gray-600 mt-2">
          {{ description }}
        </DialogDescription>

        <div class="flex justify-end gap-3 mt-6">
          <DialogClose as-child>
            <UiButton variant="outline" size="sm">
              {{ cancelLabel ?? 'Annulla' }}
            </UiButton>
          </DialogClose>
          <DialogClose as-child>
            <UiButton variant="brand" size="sm" @click="emit('confirm')">
              {{ confirmLabel ?? 'Conferma' }}
            </UiButton>
          </DialogClose>
        </div>
      </DialogContent>
    </DialogPortal>
  </DialogRoot>
</template>