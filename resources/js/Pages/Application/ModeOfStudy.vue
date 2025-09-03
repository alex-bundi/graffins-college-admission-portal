<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLayout from '@/Layouts/ApplicationLayout.vue';
</script>

<template>
    <Head title="Mode of Study" />
    <ApplicationLayout>
        <div class="flex flex-row space-x-6 items-center">
             <div>
                <div
                    class="inline-block rounded-md h-20 min-h-[1em] w-0.5 self-stretch bg-green-400 dark:bg-white/10"></div>
            </div>
            <div>
                <h1 class="font-monteserat text-xl tracking-wider md:text-4xl">
                    Select your Mode of Study
                </h1>
            </div>
        </div>


    </ApplicationLayout>
</template>