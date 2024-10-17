<script setup>
import { ref, computed, onMounted } from 'vue'
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
    slug: {
        type: String,
    },
});

const error = ref('')
const searchTerm = ref('')
const lists = ref([])
const listsLoaded = ref(false)
const list = ref([])
const listLoaded = ref(false)

onMounted(async () => {
    try {

        if (props.slug) {
            const listResponse = await fetch(`/api/books/lists/${props.slug}`)
            if (!listResponse.ok) {
                throw new Error(`Books response status: ${listResponse.status}`)
            }
            list.value = await listResponse.json()
            listLoaded.value = true
        }

        const listsResponse = await fetch('/api/books/lists')

        if (!listsResponse.ok) {
            throw new Error(`List response status: ${listsResponse.status}`)
        }

        lists.value = await listsResponse.json();
        listsLoaded.value = true

        if (list.value.name) {
            searchTerm.value = list.value.name
        }

    } catch (error) {
        error.value = 'Error fetching items:', error
    }
})

const placeholder = computed(() => {
    if (listsLoaded.value == true) {
        return 'Search for a best sellers list'
    }
    return 'Loading best sellers list'
})

const filteredList = computed(() => {
    if (!searchTerm.value) {
        return {}
    }
    return lists.value
        .filter(listObj => listObj.name.toLowerCase().includes(searchTerm.value.toLowerCase()))
        .sort((a, b) => a.name.length - b.name.length)
})
</script>

<template>

    <Head title="New York Times Best Sellers Lists" />
    <div class="max-w-screen-lg p-4">
        <h1 class="text-xl font-bold mb-4">New York Times Best Sellers Lists</h1>
        <h2 class="text-lg font-extrabold mb-8" v-if="list.name" v-text="list.name"></h2>
        <input type="text" v-model="searchTerm" :placeholder="placeholder" :disabled="!listsLoaded" class="w-full mb-4">
        <div v-if="error" v-text="error" class="text-red-800 bg-red-100 -mt-3 mb-4 px-4 py-2 rounded-md"></div>
        <div v-if="filteredList" class="mb-8">
            <ul class="flex flex-wrap">
                <li v-for="listObj in filteredList">
                    <Link :href="route('list', { list: listObj.slug })"
                        class="inline-block border rounded-full m-1 px-4 py-2 text-sm hover:bg-gray-100">
                    {{ listObj.name }}
                    </Link>
                </li>
            </ul>
        </div>
        <div v-if="searchTerm.length && filteredList.length <= 0">
            No best sellers lists found with your search term: <span v-text="searchTerm" class="italic"></span>
        </div>
        <div v-if="list.books">
            <ul class="divide-y">
                <li v-for="bookObj in list.books" class="flex gap-4 p-4 items-center">
                    <div class="basis-1/6">
                        <img :src="bookObj.book_image" class="h-32 w-auto mx-auto">
                    </div>
                    <div class="basis-5/6">
                        <a class="inline-block mb-2 font-bold hover:underline" :href="bookObj.url" target="_blank" v-text="`${bookObj.title} &mdash; ${bookObj.author}`"></a>
                        <div v-text="bookObj.description"></div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>
