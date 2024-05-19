<script setup>
import {Link} from '@inertiajs/vue3'

const props = defineProps({
    links: {
        type: Array,
        required: true,
    },
})

</script>

<template>
    <template v-if="links.length > 3">
        <div class="pagination">
            <template v-for="(link, key) in links">
                <div v-if="link.url === null" :key="key" v-html="link.label" class="pagination-button"/>
                <Link v-else :key="`link-${key}`" class="pagination-link" :class="{ 'link-active': link.active }" :href="link.url" v-html="link.label"/>
            </template>
        </div>
    </template>
</template>

<style scoped>
.pagination {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 1rem;
    margin-bottom: 1rem;
}

.pagination .pagination-button, .pagination-link {
    border-radius: 0.25rem;
    min-width: 2rem;
    height: 2rem;
    padding: 0.5rem;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #f3f4f6;
    font-size: 14px;
    color: #4a5568;
}

.pagination .pagination-link.link-active {
    background: #3182ce;
    color: #ffffff;
}
</style>
