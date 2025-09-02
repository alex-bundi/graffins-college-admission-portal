<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import Sidebar from './Sidebar.vue';
</script>

<template>
    <sidebar />
    <main class=" p-6 sm:p-12 ">
        <slot></slot>
    </main>
</template>