<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
    lists: {
        type: Object,
    },
})

const searchTerm = ref('')

const filteredList = computed(() => {

    if (!searchTerm.value) {
        return {}
    }

    return Object.fromEntries(
        Object.entries(props.lists).filter(([_, value]) =>
            value.toLowerCase().includes(searchTerm.value.toLowerCase())
        ).sort(([a], [b]) => a.length - b.length)
    )
})
</script>

<template>
    <Head title="New York Times Best Sellers" />
    <div class="max-w-screen-lg p-4">
        <h1 class="text-xl font-bold mb-4">New York Times Best Sellers</h1>
        <h2 class="text-lg font-bold mb-4">Best Seller Lists</h2>
        <input type="text" v-model="searchTerm" placeholder="Search for a list" class="w-full mb-4">
        <div v-if="filteredList">
            <ul class="flex flex-wrap">
                <li v-for="(item, key) in filteredList" class="">
                    <Link :href="route('list', { list: key })" class="inline-block border rounded-full m-1 px-4 py-2 hover:bg-gray-100">
                    {{ item }}
                    </Link>
                </li>
            </ul>
        </div>
    </div>
</template>
