<script setup>
import { Head, Link } from '@inertiajs/vue3';


</script>

<template>
    <Head title="Home" />
    <div class="grid grid-cols-1 grid-rows-1 md:grid-rows-2 sm:gap-2  h-screen">
        <!-- Student Image -->
        <section class="hidden md:block">
             <div class="relative h-full">
                <img src="/storage/images/school-bg.png" alt="Login Illustration" class="w-full h-full object-cover" onerror="this.className='w-full h-full object-cover bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white'; this.innerHTML='Demo School Background'; this.alt='';">
                <div class="absolute inset-0 bg-green-500 bg-opacity-40 z-10 pointer-events-none"></div>
            </div>
        </section>
        <!-- Welcome Message -->
        <section>
            <div class="flex flex-col space-y-2 h-screen md:flex-row p-6 sm:space-x-6 justify-around">
                <!-- School Logo -->
                <div>
                    <img src="/storage/images/logo.png" alt="school logo" class="relative z-10" style="">

                </div>
                <div class="flex flex-row space-x-6">
                    <div>
                        <h1 class="font-monteserat text-xl tracking-wider md:text-4xl">
                            GRAFFINS COLLEGE
                        </h1>
                        <h2 class="font-monteserat text-xl tracking-wider md:text-3xl">
                            ADMISSION FORM
                        </h2>
                    </div>
                    <div>
                        <div
                            class="inline-block rounded-md h-24 min-h-[1em] w-0.5 self-stretch bg-green-400 dark:bg-white/10"></div>
                    </div>
                </div>
                <div class="flex flex-col space-y-12">
                    <p class="font-josefin font-bold text-base tracking-wider md:text-2xl">
                        Click here to get started!
                    </p>

                    <div>
                        <Link :href="route('welcome')" class="flex items-center gap-2 px-6 py-3 text-white text-xl font-josefin tracking-wider font-bold 
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
            </div>
        </section>

    </div>
</template>
