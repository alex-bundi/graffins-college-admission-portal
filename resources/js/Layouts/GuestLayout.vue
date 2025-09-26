<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import Sidebar from './Sidebar.vue';

</script>

<template>
    <main class="p-6">
        <slot></slot>
    </main>
</template>