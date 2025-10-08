<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const props = defineProps({
    user: Object,
});

const names = ref('');

onMounted(() => {
    names.value = props.user.first_name + ' ' + props.user.last_name;
})
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <section>
            <div>
                <h1 class="font-josefin font-bold text-xl tracking-wider">
                    Hello <span class="text-primaryColor">{{ names }}</span>
                </h1>
            </div>

            <div class="m-6">
                <p class="font-monteserat tracking-wider text-base">
                    Dashboard
                </p>
            </div>
        </section>

        <section class="m-6">
            <div class="bg-white p-6 rounded-lg">
                <div>
                    <h2 class="font-monteserat tracking-wider text-base text-gray-700">
                        Quick Actions
                    </h2>
                </div>

                <div class="flex space-x-4 justify-around mt-3">
                    <!-- Apply for a new course -->
                     <div>
                         <Link :href="route('mode.of.study')" class="flex justify-center flex-col items-center gap-2">
                    
                            <!-- Add new transaction -->
                            <div class="">
                                <div class="relative size-12 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-500 hover:text-white
                                    transition-all duration-300 ease-in-out 
                                    transform group-hover:-translate-y-1 group-hover:shadow-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                    <path d="M11.7 2.805a.75.75 0 0 1 .6 0A60.65 60.65 0 0 1 22.83 8.72a.75.75 0 0 1-.231 1.337 49.948 49.948 0 0 0-9.902 3.912l-.003.002c-.114.06-.227.119-.34.18a.75.75 0 0 1-.707 0A50.88 50.88 0 0 0 7.5 12.173v-.224c0-.131.067-.248.172-.311a54.615 54.615 0 0 1 4.653-2.52.75.75 0 0 0-.65-1.352 56.123 56.123 0 0 0-4.78 2.589 1.858 1.858 0 0 0-.859 1.228 49.803 49.803 0 0 0-4.634-1.527.75.75 0 0 1-.231-1.337A60.653 60.653 0 0 1 11.7 2.805Z" />
                                    <path d="M13.06 15.473a48.45 48.45 0 0 1 7.666-3.282c.134 1.414.22 2.843.255 4.284a.75.75 0 0 1-.46.711 47.87 47.87 0 0 0-8.105 4.342.75.75 0 0 1-.832 0 47.87 47.87 0 0 0-8.104-4.342.75.75 0 0 1-.461-.71c.035-1.442.121-2.87.255-4.286.921.304 1.83.634 2.726.99v1.27a1.5 1.5 0 0 0-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.66a6.727 6.727 0 0 0 .551-1.607 1.5 1.5 0 0 0 .14-2.67v-.645a48.549 48.549 0 0 1 3.44 1.667 2.25 2.25 0 0 0 2.12 0Z" />
                                    <path d="M4.462 19.462c.42-.419.753-.89 1-1.395.453.214.902.435 1.347.662a6.742 6.742 0 0 1-1.286 1.794.75.75 0 0 1-1.06-1.06Z" />
                                    </svg>

                                    </div>
                                </div>
                            <div>
                                <p class="font-josefin text-sm font-bold tracking-wider">
                                    Apply for a Course
                                </p>
                            </div>
                        </Link>
                     </div>

                      <div>
                         <Link :href="route('course.list')" class="flex justify-center flex-col items-center gap-2">
                    
                            <!-- Add new transaction -->
                            <div class="">
                                <div class="relative size-12 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-500 hover:text-white
                                    transition-all duration-300 ease-in-out 
                                    transform group-hover:-translate-y-1 group-hover:shadow-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                    <path fill-rule="evenodd" d="M17.303 5.197A7.5 7.5 0 0 0 6.697 15.803a.75.75 0 0 1-1.061 1.061A9 9 0 1 1 21 10.5a.75.75 0 0 1-1.5 0c0-1.92-.732-3.839-2.197-5.303Zm-2.121 2.121a4.5 4.5 0 0 0-6.364 6.364.75.75 0 1 1-1.06 1.06A6 6 0 1 1 18 10.5a.75.75 0 0 1-1.5 0c0-1.153-.44-2.303-1.318-3.182Zm-3.634 1.314a.75.75 0 0 1 .82.311l5.228 7.917a.75.75 0 0 1-.777 1.148l-2.097-.43 1.045 3.9a.75.75 0 0 1-1.45.388l-1.044-3.899-1.601 1.42a.75.75 0 0 1-1.247-.606l.569-9.47a.75.75 0 0 1 .554-.68Z" clip-rule="evenodd" />
                                    </svg>


                                    </div>
                                </div>
                            <div>
                                <p class="font-josefin text-sm font-bold tracking-wider">
                                    View Applications
                                </p>
                            </div>
                        </Link>
                     </div>
                </div>
            </div>
        </section>
    </AuthenticatedLayout>
</template>
