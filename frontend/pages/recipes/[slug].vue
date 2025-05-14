<script setup lang="ts">
import { useRoute } from 'vue-router';
import { useFetch } from 'nuxt/app';
import { useRuntimeConfig } from '#app';
import type { Recipe, Resource } from '~/types/api';

const route = useRoute();
const config = useRuntimeConfig();

const { data, error } = await useFetch<Resource<Recipe>>(`${config.public.apiBaseUrl}/recipes/${route.params.slug}`, {
    server: false,
    key: `recipe-${route.params.slug}`
});

const recipe = data.value?.data;

if (error.value || !recipe) {
    throw createError({
        statusCode: 404,
        statusMessage: 'Recipe Not Found',
    });
}


</script>

<template>
    <div>
        <h2>Recipe Details</h2>
        <p>Name: {{ recipe.name }}</p>
        <p>Description: {{ recipe.description }}</p>
        <h3>Ingredients</h3>
        <ul>
            <li v-for="ingredient in recipe.ingredients" :key="ingredient.id">
                {{ ingredient.amount }} {{ ingredient.unit }} {{ ingredient.descriptor }} {{ ingredient.ingredient }}
            </li>
        </ul>
        <h3>Steps</h3>
        <ol>
            <li v-for="step in recipe.steps" :key="step.id">
                {{ step.description }}
            </li>
        </ol>
        <p>Author: {{ recipe.author.email }}</p>
    </div>
</template>