<script setup>
import { Head, Link } from '@inertiajs/vue3';


</script>

<template>
    <Head title="Welcome" />
    <div class="flex flex-col justify-around h-screen p-6">
        <div class="flex flex-row space-x-6">
            <div>
                <div
                    class="inline-block rounded-md h-24 min-h-[1em] w-0.5 self-stretch bg-green-400 dark:bg-white/10"></div>
            </div>
            <div>
                <h1 class="font-monteserat text-xl tracking-wider md:text-4xl">
                    👋 Hello and welcome to Graffins College!
                </h1>
            </div>
            
        </div>

        <div>
            <h2 class="font-josefin font-bold text-xl tracking-wider md:text-4xl">
                🎓 We’re excited to help you begin your journey with us.
            </h2>
        </div>

        <div>
            <h2 class="font-josefin font-bold text-xl tracking-wider md:text-4xl">
                👉 Please <span class="font-monteserat">
                    Click Next to view the menu
                </span> and get started with your registration.

            </h2>
        </div>

        <div class="w-1/4">
            <Link :href="route('registration.status')" class="flex items-center gap-2 px-6 py-3 text-white text-xl font-josefin tracking-wider font-bold 
                            rounded-full shadow-md 
                            bg-gradient-to-b from-lime-400 to-green-500 
                            hover:from-lime-500 hover:to-green-600 
                            active:scale-95 transition">
                NEXT
                <span class="flex">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                    <path fill-rule="evenodd" d="M13.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 0 1-1.06-1.06L11.69 12 4.72 5.03a.75.75 0 0 1 1.06-1.06l7.5 7.5Z" clip-rule="evenodd" />
                    <path fill-rule="evenodd" d="M19.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 1 1-1.06-1.06L17.69 12l-6.97-6.97a.75.75 0 0 1 1.06-1.06l7.5 7.5Z" clip-rule="evenodd" />
                    </svg>

                </span>
            </Link>
        </div>
    </div>
</template>
