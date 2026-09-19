<script lang="ts" setup>
import { cva, type VariantProps } from 'class-variance-authority'

const cardVariants = cva(
  'rounded-lg bg-white transition-shadow',
  {
    variants: {
      variant: {
        elevated: 'shadow-sm hover:shadow-md border border-gray-100',
        outlined: 'border-2 border-gray-200',
      },
      padding: {
        sm: 'p-4',
        md: 'p-6',
      },
    },
    defaultVariants: {
      variant: 'elevated',
      padding: 'md',
    },
  }
)

type CardVariants = VariantProps<typeof cardVariants>

const props = defineProps<{
  variant?: CardVariants['variant']
  padding?: CardVariants['padding']
  class?: string
}>()
</script>

<template>
  <div :class="cn(cardVariants({ variant: props.variant, padding: props.padding }), props.class)">
    <slot />
  </div>
</template>