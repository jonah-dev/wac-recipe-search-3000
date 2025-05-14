<script setup lang="ts">
import { UApp } from '#components';

const config = useRuntimeConfig();

</script>

<template>
    <UApp>
      <UContainer class="flex flex-col items-center min-h-screen">
        <h1 class="my-4">{{ config.public.appName }}</h1>
        <USeparator />
        <NuxtPage />
      </UContainer>
    </UApp>
</template>
