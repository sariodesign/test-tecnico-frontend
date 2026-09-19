<script lang="ts" setup>
import { cva, type VariantProps } from 'class-variance-authority'

const buttonVariants = cva(
  'inline-flex items-center justify-center rounded-md font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand disabled:opacity-50 disabled:pointer-events-none',
  {
    variants: {
      variant: {
        brand: 'bg-brand text-white hover:bg-brand-hover',
        outline: 'border border-brand text-white hover:bg-brand-hover/90',
        ghost: 'text-brand hover:bg-gray-100',
      },
      size: {
        sm: 'h-8 px-3 text-sm',
        md: 'h-10 px-4 text-base',
        lg: 'h-12 px-6 text-lg',
      },
    },
    defaultVariants: {
      variant: 'brand',
      size: 'md',
    },
  }
)

type ButtonVariants = VariantProps<typeof buttonVariants>

const props = defineProps<{
  variant?: ButtonVariants['variant']
  size?: ButtonVariants['size']
  disabled?: boolean
}>()
</script>

<template>
  <button
    :class="cn(buttonVariants({ variant: props.variant, size: props.size }))"
    :disabled="props.disabled"
  >
    <slot />
  </button>
</template>