<script lang="ts" setup>
useHead({
  link: [
    {
      rel: 'stylesheet',
      href: 'https://cdn.jsdelivr.net/npm/bootstrap-italia@2.18.3/dist/css/bootstrap-italia.min.css',
    },
  ],
})
</script>

<template>
  <div>
    <Header />
    <slot />
    <Footer />
  </div>
</template>
