<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue'

const props = defineProps({
    books: {
        type: Object,
    },
});

const data = ref(props.books.data);
</script>

<template>
    <Head title="New York Times Best Sellers" />
    <div class="max-w-screen-lg p-4">
        <div class="flex justify-between">
            <h1 class="text-xl font-bold mb-4">New York Times Best Sellers List &mdash; {{ data.list_name }}</h1>
            <Link :href="route('lists')">List Search</Link>
        </div>
        <ul class="divide-y">
            <li v-for="(book, _) in data.books" class="p-4">
                <a :href="book.url" target="_blank" class="inline-block mb-2 hover:underline">
                    <span v-text="book.title"></span>
                    &mdash;
                    <span v-text="book.author"></span>
                </a>
                <p class="text-sm" v-text="book.description"></p>
            </li>
        </ul>
    </div>
</template>
