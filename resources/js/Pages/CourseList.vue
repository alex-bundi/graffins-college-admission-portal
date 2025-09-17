<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
const props = defineProps({
    applications: Object,
});
console.log(props.applications);
</script>

<template>
    <Head title="Course List" />
    <AuthenticatedLayout>
        <div class="flex flex-col space-y-4">
            <div>
                <h1 class="font-monteserat tracking-wider text-primaryColor text-sm">Course List</h1>
            </div>
        </div>

         <!-- Header -->
        <section class=" flex items-center mt-6">
            <div class="max-w-screen-xl px-4 mx-auto lg:px-12 w-full">
                <!-- Start coding here -->
                <div class="relative bg-white shadow-md  sm:rounded-lg">
                <div class="flex flex-col items-center justify-between p-4 space-y-3 md:flex-row md:space-y-0 md:space-x-4">
                    <div class="w-full md:w-1/2">
                    <!-- <form class="flex items-center">
                        <label for="simple-search" class="sr-only">Search</label>
                        <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" id="simple-search" class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 
                            focus:border-primary-500 " placeholder="Search" required="">
                        </div>
                    </form> -->
                    </div>
                    <div class="flex flex-col items-stretch justify-end flex-shrink-0 w-full space-y-2 md:w-auto md:flex-row md:space-y-0 md:items-center md:space-x-3">
                        <Link :href="route('mode.of.study')" class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-primaryColor hover:bg-darkPrimaryColor
                            focus:ring-4 focus:ring-primary-300 focus:outline-none ">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                            </svg>

                            Add Course
                        </Link>
                    
                    </div>
                </div>
                </div>
            </div>
        </section>

        <section class=" flex flex-col items-center mt-6">
            <div>
                <h2 class="font-monteserat tracking-wider text-black text-base">
                    Your Applications
                </h2>
            </div>
            <ul>
                

                <li v-for="application in applications" :key="application.Application_No">
                    <div class="m-5">
                        <div class="group mx-2 mt-10 grid max-w-screen-md grid-cols-12 space-x-8 overflow-hidden bg-white rounded-lg shadow-md border py-8 text-gray-700 transition hover:shadow-lg sm:mx-auto">
                            <a href="#" class="order-2 col-span-1 mt-4 -ml-14 text-left text-gray-600 hover:text-gray-700 sm:-order-1 sm:ml-4">
                            <div class="group relative h-16 w-16 overflow-hidden rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-full h-full">
                                <path d="M11.7 2.805a.75.75 0 0 1 .6 0A60.65 60.65 0 0 1 22.83 8.72a.75.75 0 0 1-.231 1.337 49.948 49.948 0 0 0-9.902 3.912l-.003.002c-.114.06-.227.119-.34.18a.75.75 0 0 1-.707 0A50.88 50.88 0 0 0 7.5 12.173v-.224c0-.131.067-.248.172-.311a54.615 54.615 0 0 1 4.653-2.52.75.75 0 0 0-.65-1.352 56.123 56.123 0 0 0-4.78 2.589 1.858 1.858 0 0 0-.859 1.228 49.803 49.803 0 0 0-4.634-1.527.75.75 0 0 1-.231-1.337A60.653 60.653 0 0 1 11.7 2.805Z" />
                                <path d="M13.06 15.473a48.45 48.45 0 0 1 7.666-3.282c.134 1.414.22 2.843.255 4.284a.75.75 0 0 1-.46.711 47.87 47.87 0 0 0-8.105 4.342.75.75 0 0 1-.832 0 47.87 47.87 0 0 0-8.104-4.342.75.75 0 0 1-.461-.71c.035-1.442.121-2.87.255-4.286.921.304 1.83.634 2.726.99v1.27a1.5 1.5 0 0 0-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.66a6.727 6.727 0 0 0 .551-1.607 1.5 1.5 0 0 0 .14-2.67v-.645a48.549 48.549 0 0 1 3.44 1.667 2.25 2.25 0 0 0 2.12 0Z" />
                                <path d="M4.462 19.462c.42-.419.753-.89 1-1.395.453.214.902.435 1.347.662a6.742 6.742 0 0 1-1.286 1.794.75.75 0 0 1-1.06-1.06Z" />
                                </svg>

                            </div>
                            </a>
                            <div class="col-span-11 flex flex-col pr-8 text-left sm:pl-4">
                            <h3 class="text-sm font-josefin font-bold tracking-wider text-gray-600">{{ application.Application_No  }}</h3>
                            <a href="#" class="mb-3 overflow-hidden pr-7 text-lg font-monteserat tracking-wider font-semibold sm:text-xl"> {{ application.First_Name + ' ' + application.Last_Name }}</a>
                            <p class="overflow-hidden pr-7 text-sm font-josefin font-bold tracking-wider text-gray-600">
                                {{ application.Email  }}
                            </p>

                            <div class="mt-5 flex flex-col space-y-3 text-sm font-medium text-gray-500 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-2">
                                <div class="">Application Date:<span class="ml-2 mr-3 rounded-full bg-green-100 px-2 py-0.5 text-green-900"> 2 Years </span></div>
                                <div class="">
                                    Application Status:
                                    <span class="ml-2 mr-3 rounded-full bg-blue-100 px-2 py-0.5 text-blue-900">
                                    {{ application.Application_Status  }}
                                    </span>
                            </div>
                            </div>
                            </div>
                        </div>
                    </div>

                </li>
            </ul>
        </section>


    </AuthenticatedLayout>
</template>