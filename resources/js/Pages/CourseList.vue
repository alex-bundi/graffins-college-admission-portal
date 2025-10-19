<script setup>
import { computed, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
const props = defineProps({
    applications: Object,
});


const newApplications = Object.fromEntries(
    Object.entries(props.applications).filter(([key, value]) => value.application_status == 'new')

)

const submittedApplications = Object.fromEntries(
    Object.entries(props.applications).filter(([key, value]) => value.application_status == 'submitted')

)

const applicationCount = computed(() => {
    return Object.keys(props.applications).length;
})

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
       

        <section v-if="applicationCount == 0" class=" flex flex-col items-center justify-center mt-6">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-2.625 6c-.54 0-.828.419-.936.634a1.96 1.96 0 0 0-.189.866c0 .298.059.605.189.866.108.215.395.634.936.634.54 0 .828-.419.936-.634.13-.26.189-.568.189-.866 0-.298-.059-.605-.189-.866-.108-.215-.395-.634-.936-.634Zm4.314.634c.108-.215.395-.634.936-.634.54 0 .828.419.936.634.13.26.189.568.189.866 0 .298-.059.605-.189.866-.108.215-.395.634-.936.634-.54 0-.828-.419-.936-.634a1.96 1.96 0 0 1-.189-.866c0-.298.059-.605.189-.866Zm-4.34 7.964a.75.75 0 0 1-1.061-1.06 5.236 5.236 0 0 1 3.73-1.538 5.236 5.236 0 0 1 3.695 1.538.75.75 0 1 1-1.061 1.06 3.736 3.736 0 0 0-2.639-1.098 3.736 3.736 0 0 0-2.664 1.098Z" clip-rule="evenodd" />
                </svg>

            </div>
            <div class="flex flex-col items-center space-y-5">
                <h1 class="font-monteserat tracking-wider text-black text-xl">
                    Nothing here yet.
                </h1>
                <p class="font-monteserat tracking-wider text-black text-base">
                    Why not start by adding an application?
                </p>

                <div>
                    

                    <Link :href="route('mode.of.study')" class="relative inline-block text-lg group">
                        <span class="relative z-10 block px-5 py-3 overflow-hidden font-medium leading-tight text-gray-800 transition-colors duration-300 ease-out border-2 border-gray-900 rounded-lg group-hover:text-white">
                            <span class="absolute inset-0 w-full h-full px-5 py-3 rounded-lg bg-primaryColor"></span>
                            <span class="absolute left-0 w-48 h-48 -ml-2 transition-all duration-300 origin-top-right -rotate-90 -translate-x-full translate-y-12 bg-gray-900 group-hover:-rotate-180 ease"></span>
                            <span class="relative"> Apply for a Course</span>
                        </span>
                        <span class="absolute bottom-0 right-0 w-full h-12 -mb-1 -mr-1 transition-all duration-200 ease-linear bg-gray-900 rounded-lg group-hover:mb-0 group-hover:mr-0" data-rounded="rounded-lg"></span>
                    </Link>
                </div>
            </div>
            
        </section> 

        <div v-else>
            <!-- New Applications -->
            <section class="mt-6 ml-6 md:ml-12">
                <div>
                    <h2 class="font-monteserat tracking-wider text-black text-base">
                        New Applications
                    </h2>

                    <hr class="h-px my-2 w-1/2 bg-gray-400 border-0 rounded-md">
                </div>

                <div class="mt-5">
                    <ul>
                        <li v-for="application in newApplications" :key="application.id"
                            class=" flex flex-row items-center max-w-screen-md justify-between space-x-8 overflow-hidden bg-white rounded-lg shadow-md 
                        border border-red-300 text-gray-700 transition hover:shadow-lg hover:border-red-500 p-4">
                        <div class="flex flex-row items-center  space-x-8">
                                <div class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-12 h-12">
                                    <path d="M11.7 2.805a.75.75 0 0 1 .6 0A60.65 60.65 0 0 1 22.83 8.72a.75.75 0 0 1-.231 1.337 49.948 49.948 0 0 0-9.902 3.912l-.003.002c-.114.06-.227.119-.34.18a.75.75 0 0 1-.707 0A50.88 50.88 0 0 0 7.5 12.173v-.224c0-.131.067-.248.172-.311a54.615 54.615 0 0 1 4.653-2.52.75.75 0 0 0-.65-1.352 56.123 56.123 0 0 0-4.78 2.589 1.858 1.858 0 0 0-.859 1.228 49.803 49.803 0 0 0-4.634-1.527.75.75 0 0 1-.231-1.337A60.653 60.653 0 0 1 11.7 2.805Z" />
                                    <path d="M13.06 15.473a48.45 48.45 0 0 1 7.666-3.282c.134 1.414.22 2.843.255 4.284a.75.75 0 0 1-.46.711 47.87 47.87 0 0 0-8.105 4.342.75.75 0 0 1-.832 0 47.87 47.87 0 0 0-8.104-4.342.75.75 0 0 1-.461-.71c.035-1.442.121-2.87.255-4.286.921.304 1.83.634 2.726.99v1.27a1.5 1.5 0 0 0-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.66a6.727 6.727 0 0 0 .551-1.607 1.5 1.5 0 0 0 .14-2.67v-.645a48.549 48.549 0 0 1 3.44 1.667 2.25 2.25 0 0 0 2.12 0Z" />
                                    <path d="M4.462 19.462c.42-.419.753-.89 1-1.395.453.214.902.435 1.347.662a6.742 6.742 0 0 1-1.286 1.794.75.75 0 0 1-1.06-1.06Z" />
                                    </svg>

                                </div>

                                <!-- Student Name -->
                                <div class="w-full">
                                    <div>
                                        <h2 class=" overflow-hidden text-lg font-monteserat tracking-wider font-semibold sm:text-xl">
                                            {{ application.first_name + ' ' + application.second_name + ' ' + application.last_name }}
                                        </h2>
                                        <hr class="h-px  w-1/2 bg-primaryColor border-0 rounded-md">
                                    </div>
                                    <div class="mt-2">
                                        <ul class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:space-x-12">
                                            <li class="flex items-center space-x-2">
                                                <div>
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#82dc9d" class=" size-4">
                                                    <path d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a3 3 0 0 1-3.144 0L1.5 8.67Z" />
                                                    <path d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z" />
                                                    </svg>

                                                </div>
                                                <div>
                                                    <p class="font-josefin font-bold tracking-wider">
                                                        {{ application.email }}
                                                    </p>
                                                </div>
                                            </li>

                                            <li class="hidden sm:block">
                                                <span class="inline-block h-5 min-h-[1em] w-0.5 self-stretch bg-neutral-100 dark:bg-white/10">

                                                </span>
                                            </li>

                                            <li class="flex items-center space-x-2">
                                                <div>
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#82dc9d" class="size-4">
                                                    <path fill-rule="evenodd" d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z" clip-rule="evenodd" />
                                                    </svg>


                                                </div>
                                                <div>
                                                    <p class="font-josefin font-bold tracking-wider">
                                                    {{ application.phone_no }}
                                                    </p>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>

                                    <!--  Course Details -->
                                    <div class="mt-4">
                                        <ul class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:space-x-6">
                                            <li class="flex items-center space-x-2">
                                                <div class="font-monteserat font-bold tracking-wider">
                                                    Course:

                                                </div>
                                                <div>
                                                    <p class="font-josefin font-bold tracking-wider">
                                                        {{ application.phone_no }}
                                                    </p>
                                                </div>
                                            </li>

                                            <li class="hidden sm:block">
                                                <span class="inline-block h-5 min-h-[1em] w-0.5 self-stretch bg-neutral-100 dark:bg-white/10">

                                                </span>
                                            </li>

                                            <li class="flex items-center space-x-2">
                                                <div class="font-monteserat font-bold tracking-wider">
                                                    Level:

                                                </div>
                                                <div>
                                                    <p class="font-josefin font-bold tracking-wider">
                                                        {{ application.phone_no }}
                                                    </p>
                                                </div>
                                            </li>

                                        
                                        </ul>
                                    </div>

                                    <div class="mt-4 w-full">
                                        <div class="flex items-center space-x-2">
                                            <div class="font-monteserat font-bold tracking-wider text-amber-700">
                                                Application Status:

                                            </div>
                                            <div>
                                                <p class="font-josefin font-bold tracking-wider uppercase">
                                                    {{ application.application_status }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                            
                            <!-- Edit button -->
                            <div class="justify-self-end">
                                <Link :href="route('edit.application', application.id)">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 hover:text-red-500">
                                    <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                    <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                                    </svg>

                                </Link>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="flex flex-col items-center space-y-5 mt-4">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8">
                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-2.625 6c-.54 0-.828.419-.936.634a1.96 1.96 0 0 0-.189.866c0 .298.059.605.189.866.108.215.395.634.936.634.54 0 .828-.419.936-.634.13-.26.189-.568.189-.866 0-.298-.059-.605-.189-.866-.108-.215-.395-.634-.936-.634Zm4.314.634c.108-.215.395-.634.936-.634.54 0 .828.419.936.634.13.26.189.568.189.866 0 .298-.059.605-.189.866-.108.215-.395.634-.936.634-.54 0-.828-.419-.936-.634a1.96 1.96 0 0 1-.189-.866c0-.298.059-.605.189-.866Zm2.023 6.828a.75.75 0 1 0-1.06-1.06 3.75 3.75 0 0 1-5.304 0 .75.75 0 0 0-1.06 1.06 5.25 5.25 0 0 0 7.424 0Z" clip-rule="evenodd" />
                        </svg>


                    </div>
                    <div class="flex flex-col items-center space-y-5">
                        <h1 class="font-monteserat tracking-wider text-black text-xl">
                           Don’t stop here —
                        </h1>
                        <p class="font-monteserat tracking-wider text-black text-base">
                            explore more courses and apply for another one!
                        </p>

                        <div>
                            

                            <Link :href="route('mode.of.study')" class="relative inline-block text-lg group">
                                <span class="relative z-10 block px-5 py-3 overflow-hidden font-medium leading-tight text-gray-800 transition-colors duration-300 ease-out border-2 border-gray-900 rounded-lg group-hover:text-white">
                                    <span class="absolute inset-0 w-full h-full px-5 py-3 rounded-lg bg-primaryColor"></span>
                                    <span class="absolute left-0 w-48 h-48 -ml-2 transition-all duration-300 origin-top-right -rotate-90 -translate-x-full translate-y-12 bg-gray-900 group-hover:-rotate-180 ease"></span>
                                    <span class="relative"> Apply for another Course</span>
                                </span>
                                <span class="absolute bottom-0 right-0 w-full h-12 -mb-1 -mr-1 transition-all duration-200 ease-linear bg-gray-900 rounded-lg group-hover:mb-0 group-hover:mr-0" data-rounded="rounded-lg"></span>
                            </Link>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Submitted Applications -->
            <section class="mt-6 ml-6 md:ml-12">
                <div>
                    <h2 class="font-monteserat tracking-wider text-black text-base">
                        Submitted Applications
                    </h2>

                    <hr class="h-px my-2 w-1/2 bg-gray-400 border-0 rounded-md">
                </div>

                <div class="mt-5">
                    <ul>
                        <li v-for="application in submittedApplications" :key="application.id"
                            class=" flex flex-row items-center max-w-screen-md justify-between space-x-8 overflow-hidden bg-white rounded-lg shadow-md 
                        border border-red-300 text-gray-700 transition hover:shadow-lg hover:border-amber-500 p-4">
                        <div class="flex flex-row items-center  space-x-8">
                                <div class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-12 h-12">
                                    <path d="M11.7 2.805a.75.75 0 0 1 .6 0A60.65 60.65 0 0 1 22.83 8.72a.75.75 0 0 1-.231 1.337 49.948 49.948 0 0 0-9.902 3.912l-.003.002c-.114.06-.227.119-.34.18a.75.75 0 0 1-.707 0A50.88 50.88 0 0 0 7.5 12.173v-.224c0-.131.067-.248.172-.311a54.615 54.615 0 0 1 4.653-2.52.75.75 0 0 0-.65-1.352 56.123 56.123 0 0 0-4.78 2.589 1.858 1.858 0 0 0-.859 1.228 49.803 49.803 0 0 0-4.634-1.527.75.75 0 0 1-.231-1.337A60.653 60.653 0 0 1 11.7 2.805Z" />
                                    <path d="M13.06 15.473a48.45 48.45 0 0 1 7.666-3.282c.134 1.414.22 2.843.255 4.284a.75.75 0 0 1-.46.711 47.87 47.87 0 0 0-8.105 4.342.75.75 0 0 1-.832 0 47.87 47.87 0 0 0-8.104-4.342.75.75 0 0 1-.461-.71c.035-1.442.121-2.87.255-4.286.921.304 1.83.634 2.726.99v1.27a1.5 1.5 0 0 0-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.66a6.727 6.727 0 0 0 .551-1.607 1.5 1.5 0 0 0 .14-2.67v-.645a48.549 48.549 0 0 1 3.44 1.667 2.25 2.25 0 0 0 2.12 0Z" />
                                    <path d="M4.462 19.462c.42-.419.753-.89 1-1.395.453.214.902.435 1.347.662a6.742 6.742 0 0 1-1.286 1.794.75.75 0 0 1-1.06-1.06Z" />
                                    </svg>

                                </div>

                                <!-- Student Name -->
                                <div class="w-full">
                                    <div>
                                        <h2 class=" overflow-hidden text-lg font-monteserat tracking-wider font-semibold sm:text-xl">
                                            {{ application.first_name + ' ' + application.second_name + ' ' + application.last_name }}
                                        </h2>
                                        <hr class="h-px  w-1/2 bg-primaryColor border-0 rounded-md">
                                    </div>
                                    <div class="mt-2">
                                        <ul class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:space-x-12">
                                            <li class="flex items-center space-x-2">
                                                <div>
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#82dc9d" class=" size-4">
                                                    <path d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a3 3 0 0 1-3.144 0L1.5 8.67Z" />
                                                    <path d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z" />
                                                    </svg>

                                                </div>
                                                <div>
                                                    <p class="font-josefin font-bold tracking-wider">
                                                        {{ application.email }}
                                                    </p>
                                                </div>
                                            </li>

                                            <li class="hidden sm:block">
                                                <span class="inline-block h-5 min-h-[1em] w-0.5 self-stretch bg-neutral-100 dark:bg-white/10">

                                                </span>
                                            </li>

                                            <li class="flex items-center space-x-2">
                                                <div>
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#82dc9d" class="size-4">
                                                    <path fill-rule="evenodd" d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z" clip-rule="evenodd" />
                                                    </svg>


                                                </div>
                                                <div>
                                                    <p class="font-josefin font-bold tracking-wider">
                                                    {{ application.phone_no }}
                                                    </p>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>

                                    <!--  Course Details -->
                                    <div class="mt-4">
                                        <ul class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:space-x-6">
                                            <li class="flex items-center space-x-2">
                                                <div class="font-monteserat font-bold tracking-wider">
                                                    Course:

                                                </div>
                                                <div>
                                                    <p class="font-josefin font-bold tracking-wider">
                                                        {{ application.phone_no }}
                                                    </p>
                                                </div>
                                            </li>

                                            <li class="hidden sm:block">
                                                <span class="inline-block h-5 min-h-[1em] w-0.5 self-stretch bg-neutral-100 dark:bg-white/10">

                                                </span>
                                            </li>

                                            <li class="flex items-center space-x-2">
                                                <div class="font-monteserat font-bold tracking-wider">
                                                    Level:

                                                </div>
                                                <div>
                                                    <p class="font-josefin font-bold tracking-wider">
                                                        {{ application.phone_no }}
                                                    </p>
                                                </div>
                                            </li>

                                        
                                        </ul>
                                    </div>

                                    <div class="mt-4 w-full">
                                        <div class="flex items-center space-x-2">
                                            <div class="font-monteserat font-bold tracking-wider text-green-700">
                                                Application Status:

                                            </div>
                                            <div>
                                                <p class="font-josefin font-bold tracking-wider uppercase">
                                                    {{ application.application_status }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                            
                            <!-- Edit button -->
                            <div class="justify-self-end">
                                 <Link :href="route('edit.application', application.id)">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 hover:text-red-500">
                                    <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                    <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                                    </svg>

                                </Link>
                            </div>
                        </li>
                    </ul>
                </div>
            </section>
        </div>

        

     
    </AuthenticatedLayout>
</template>