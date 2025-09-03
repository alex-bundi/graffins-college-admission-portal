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
                <div class="flex flex-col space-x-6">
                    <div>
                        <h1 class="font-monteserat text-xl tracking-wider md:text-4xl">
                            
                        </h1>
                        <h2 class="font-monteserat text-xl tracking-wider md:text-3xl">
                            New Student Registration
                        </h2>
                    </div>
                    <div>
                        <Link :href="route('register')" class="py-2.5 sm:py-3 px-4  block w-1/2 items-center gap-x-2 font-league tracking-wider text-sm font-medium rounded-lg border border-transparent 
                                    bg-primaryColor text-white hover:bg-darkPrimaryColor focus:outline-hidden focus:bg-primaryColor disabled:opacity-50 
                                    disabled:pointer-events-none">
                            Account Registration

                        </Link>
                    </div>
                </div>
                <div class="flex flex-col space-x-6">
                    <div>
                        <h1 class="font-monteserat text-xl tracking-wider md:text-4xl">
                            
                        </h1>
                        <h2 class="font-monteserat text-xl tracking-wider md:text-3xl">
                           Continuing Student
                        </h2>
                    </div>
                    <div>
                        <Link :href="route('login')" class="py-2.5 sm:py-3 px-4  block w-1/2 items-center gap-x-2 font-league tracking-wider text-sm font-medium rounded-lg border border-transparent 
                                    bg-primaryColor text-white hover:bg-darkPrimaryColor focus:outline-hidden focus:bg-primaryColor disabled:opacity-50 
                                    disabled:pointer-events-none">
                           Login

                        </Link>
                    </div>
                </div>
            </div>
        </section>

    </div>
</template>
    