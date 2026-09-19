<script lang="ts" setup>
definePageMeta({
  middleware: 'auth',
})

const { user } = useAuth()

</script>

<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-4">
                <h1 class="mb-4">Area Personale di {{ user?.name }}</h1>
            </div>
        </div>
    </div>
</template>