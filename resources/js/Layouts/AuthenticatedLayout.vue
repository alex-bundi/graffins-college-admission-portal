<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import Sidebar from './Sidebar.vue';
import Stepper from './Stepper.vue';
</script>

<template>
    <sidebar />
    <main class="p-4 sm:ml-64 ">
        <slot></slot>
    </main>
</template>