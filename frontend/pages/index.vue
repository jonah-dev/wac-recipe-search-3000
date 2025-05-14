<script setup lang="ts">
import type { Paginated, Recipe, User } from '~/types/api';

const config = useRuntimeConfig();
const route = useRoute();

// Constraint Refs
const page = ref(1);
const perPage = ref(5);
const authorSearchTerm = ref('');
const keywordSearchTerm = ref('');
const debouncedKeywordSearchTerm = ref('');
const ingredientSearchTerm = ref('');
const debouncedIngredientSearchTerm = ref('');

const { data, status, error, refresh } = await useAsyncData<Paginated<Recipe>>(
    'recipes',
    () => {
        return $fetch(`${config.public.apiBaseUrl}/recipes`, {
            server: false,
            params: {
                page: route.query.page || page.value,
                per_page: perPage.value,
                author: authorSearchTerm.value,
                keyword: debouncedKeywordSearchTerm.value,
                ingredient: debouncedIngredientSearchTerm.value,
            },
        });
    },
    {
        server: false,
        watch: [
            page,
            perPage,
            authorSearchTerm,
            debouncedKeywordSearchTerm,
            debouncedIngredientSearchTerm,
        ],
    },
);

const recipes = ref(data.value?.data || []);
const meta = ref(data.value?.meta || null);
const links = ref(data.value?.links || null);

// Watchers - Recipe List
watch(
    () => data.value,
    (newData) => {
        recipes.value = newData?.data || [];
        meta.value = newData?.meta || null;
        links.value = newData?.links || null;
    },
    { immediate: true }
);

// Watchers - Filters 
watch(
    () => keywordSearchTerm.value,
    (newTerm) => {
        clearTimeout(debouncedKeywordSearchTerm.value);
        setTimeout(() => {
            debouncedKeywordSearchTerm.value = newTerm;
        }, 500);
    },
    { immediate: true }
);

watch(
    () => ingredientSearchTerm.value,
    (newTerm) => {
        clearTimeout(debouncedIngredientSearchTerm.value);
        setTimeout(() => {
            debouncedIngredientSearchTerm.value = newTerm;
        }, 500);
    },
    { immediate: true }
);

// Watchers - Pagination 
watch(
    authorSearchTerm,
    (newTerm) => {
        if (newTerm) {
            page.value = 1;
        }
    },
    { immediate: true }
);

watch(
    debouncedKeywordSearchTerm,
    (newTerm) => {
        if (newTerm) {
            page.value = 1;
        }
    },
    { immediate: true }
);

watch(
    debouncedIngredientSearchTerm,
    (newTerm) => {
        if (newTerm) {
            page.value = 1;
        }
    },
    { immediate: true }
);

</script>

<template>
    <UContainer class="max-w-4/6 min-w-1/2 mb-6">
        <!-- Filters -->
        <div class="flex flex-col mb-4">
            <h3 class="mb-4">Filters</h3>
            <div class="flex flex-row">
                <UInput v-model="authorSearchTerm" placeholder="" :ui="{ base: 'peer', trailing: 'pe-0' }" class="w-64">
                    <label
                        class="pointer-events-none absolute left-0 -top-2.5 text-highlighted text-xs font-medium px-1.5 transition-all peer-focus:-top-2.5 peer-focus:text-highlighted peer-focus:text-xs peer-focus:font-medium peer-placeholder-shown:text-sm peer-placeholder-shown:text-dimmed peer-placeholder-shown:top-1.5 peer-placeholder-shown:font-normal">
                        <span class="inline-flex bg-default px-1">Author Email</span>
                    </label>
                    <template #trailing>
                        <UButton color="neutral" variant="link" size="xs" icon="i-lucide-circle-x"
                            aria-label="Clear input" @click="authorSearchTerm = ''" />
                    </template>
                </UInput>
                <UInput v-model="keywordSearchTerm" placeholder="" :ui="{ base: 'peer', trailing: 'pe-0' }"
                    class="w-64 ml-4">
                    <label
                        class="pointer-events-none absolute left-0 -top-2.5 text-highlighted text-xs font-medium px-1.5 transition-all peer-focus:-top-2.5 peer-focus:text-highlighted peer-focus:text-xs peer-focus:font-medium peer-placeholder-shown:text-sm peer-placeholder-shown:text-dimmed peer-placeholder-shown:top-1.5 peer-placeholder-shown:font-normal">
                        <span class="inline-flex bg-default px-1">Keyword</span>
                    </label>
                    <template #trailing>
                        <UButton color="neutral" variant="link" size="xs" icon="i-lucide-circle-x"
                            aria-label="Clear input" @click="keywordSearchTerm = ''" />
                    </template>
                </UInput>
                <UInput v-model="ingredientSearchTerm" placeholder="" :ui="{ base: 'peer', trailing: 'pe-0' }"
                    class="w-64 ml-4">
                    <label
                        class="pointer-events-none absolute left-0 -top-2.5 text-highlighted text-xs font-medium px-1.5 transition-all peer-focus:-top-2.5 peer-focus:text-highlighted peer-focus:text-xs peer-focus:font-medium peer-placeholder-shown:text-sm peer-placeholder-shown:text-dimmed peer-placeholder-shown:top-1.5 peer-placeholder-shown:font-normal">
                        <span class="inline-flex bg-default px-1">Ingredient</span>
                    </label>
                    <template #trailing>
                        <UButton color="neutral" variant="link" size="xs" icon="i-lucide-circle-x"
                            aria-label="Clear input" @click="ingredientSearchTerm = ''" />
                    </template>
                </UInput>
            </div>
        </div>
        <div v-if="links && meta" class="pagination">
            <UPagination v-model:page="page" :total="meta.total" :items-per-page="perPage" />
        </div>
        <!-- List -->
        <UCard v-if="recipes.length" v-for="recipe in recipes" :key="recipe.id" class="mb-4">
            <template #header>
                <h3>{{ recipe.name }}</h3> by <span @click="authorSearchTerm = recipe.author.email"
                    class="text-highlighted cursor-pointer">{{ recipe.author.email }}</span>
            </template>
            <div class="flex flex-col">
                <p>{{ recipe.description }}</p>
            </div>
            <template #footer>
                <div class="flex w-full justify-between items-center">
                    <div>
                        <UBadge v-for="ingredient in recipe.ingredients" :key="ingredient.id"
                            class="mr-2 cursor-pointer" color="info" @click="ingredientSearchTerm = ingredient.ingredient">
                            {{ ingredient.ingredient }}
                        </UBadge>
                    </div>
                    <ULink :to="`/recipes/${recipe.slug}`" class="justify-self-end">
                        <UButton>View Recipe</UButton>
                    </ULink>
                </div>
            </template>
        </UCard>
        <div v-else-if="recipes.length === 0 && status === 'success'">
            <h3 class="text-center text-dimmed">No recipes found</h3>
        </div>
        <div v-else-if="error">
            <strong>An error has occurred!</strong>
            <pre>{{ error.message }}</pre>
        </div>
        <USkeleton v-else v-for="i in perPage" :key="i" class="h-48 my-6">
            <template #header>
                <h3>Recipes</h3>
            </template>
        </USkeleton>

        <!-- Pagination -->
        <div v-if="links && meta" class="pagination">
            <UPagination v-model:page="page" :total="meta.total" />
        </div>
    </UContainer>

</template>

<style scoped>
.pagination {
    display: flex;
    justify-content: space-between;
    margin: 20px 0;
}
</style>