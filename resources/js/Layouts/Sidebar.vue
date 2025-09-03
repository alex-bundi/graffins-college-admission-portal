<script setup>
import { Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
const companyName = import.meta.env.VITE_COMPANY_NAME

const isSidebarOpen = ref(false);
const isSalesMenuOpen = ref(false);


function toggleSidebar() {
    isSidebarOpen.value = !isSidebarOpen.value;
}

function closeSidebar() {
    isSidebarOpen.value = false;
}

function toggleSalesMenu(event) {
    event.preventDefault();
    event.stopPropagation();
    isSalesMenuOpen.value = !isSalesMenuOpen.value;
}
const form = useForm({
})
function logout(){
    router.post('/logout', form, {
    });
}
</script>

<template>
    <button 
        @click="toggleSidebar" 
        data-drawer-target="logo-sidebar"
        data-drawer-toggle="logo-sidebar" 
        aria-controls="logo-sidebar" 
        type="button" 
        class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
    >
        <span class="sr-only">Open sidebar</span>
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
        </svg>
    </button>

    <!-- Backdrop/Overlay for mobile -->
    <div 
        v-if="isSidebarOpen" 
        @click="closeSidebar"
        class="fixed inset-0 z-30 bg-black bg-opacity-50 md:hidden"
    ></div>

    <aside 
        :class="[
            'fixed top-0 left-0 z-40 flex flex-col w-64 h-screen px-5 py-8 overflow-y-auto bg-white border-r rtl:border-r-0 rtl:border-l',
            'transition-transform duration-300 ease-in-out',
            'md:translate-x-0', 
            isSidebarOpen ? 'translate-x-0' : '-translate-x-full' 
        ]"
    >
        <!-- <a href="#"> -->
            <!-- <img class="w-auto h-7" src="" alt="Logo"> -->
            <h2 class="font-monteserat tracking-wider text-primaryColor">
                {{ companyName }}
            </h2>
        <!-- </a> -->

        <div class="flex flex-col justify-between flex-1 mt-6">
            <nav class="-mx-3 space-y-6">
                <div class="space-y-3">
                    
                    <div>
                        <Link 
                            href="/dashboard" 
                            :class="{'active bg-gray-200 text-black' : $page.url === '/dashboard'}"
                            class="flex items-center px-3 py-2 transition-colors duration-300 transform rounded-lg  hover:bg-gray-100  hover:text-gray-700"
                            @click="closeSidebar"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z" />
                            <path d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z" />
                            </svg>

                            <span class="mx-2 text-sm font-medium font-caviar tracking-wider">
                                Dashboard
                            </span> 
                        </Link>
                    </div>
                </div>

                 <div class="space-y-3">
                    
                    <div>
                        <Link 
                            :href="route('course.list')"
                            :class="{'active bg-gray-200 text-black' : $page.url === '/apply-course'}"
                            class="flex items-center px-3 py-2 transition-colors duration-300 transform rounded-lg  hover:bg-gray-100  hover:text-gray-700"
                            @click="closeSidebar"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path d="M11.7 2.805a.75.75 0 0 1 .6 0A60.65 60.65 0 0 1 22.83 8.72a.75.75 0 0 1-.231 1.337 49.948 49.948 0 0 0-9.902 3.912l-.003.002c-.114.06-.227.119-.34.18a.75.75 0 0 1-.707 0A50.88 50.88 0 0 0 7.5 12.173v-.224c0-.131.067-.248.172-.311a54.615 54.615 0 0 1 4.653-2.52.75.75 0 0 0-.65-1.352 56.123 56.123 0 0 0-4.78 2.589 1.858 1.858 0 0 0-.859 1.228 49.803 49.803 0 0 0-4.634-1.527.75.75 0 0 1-.231-1.337A60.653 60.653 0 0 1 11.7 2.805Z" />
                            <path d="M13.06 15.473a48.45 48.45 0 0 1 7.666-3.282c.134 1.414.22 2.843.255 4.284a.75.75 0 0 1-.46.711 47.87 47.87 0 0 0-8.105 4.342.75.75 0 0 1-.832 0 47.87 47.87 0 0 0-8.104-4.342.75.75 0 0 1-.461-.71c.035-1.442.121-2.87.255-4.286.921.304 1.83.634 2.726.99v1.27a1.5 1.5 0 0 0-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.66a6.727 6.727 0 0 0 .551-1.607 1.5 1.5 0 0 0 .14-2.67v-.645a48.549 48.549 0 0 1 3.44 1.667 2.25 2.25 0 0 0 2.12 0Z" />
                            <path d="M4.462 19.462c.42-.419.753-.89 1-1.395.453.214.902.435 1.347.662a6.742 6.742 0 0 1-1.286 1.794.75.75 0 0 1-1.06-1.06Z" />
                            </svg>


                            <span class="mx-2 text-sm font-medium font-caviar tracking-wider">
                                Apply Course
                            </span> 
                        </Link>
                    </div>
                </div>

                

                

                <div class="space-y-3">
                    <button 
                        @click="logout" 
                        class="flex items-center px-3 py-2 transition-colors duration-300 transform rounded-lg hover:bg-gray-100 hover:text-gray-700 w-full"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                        </svg>
                        <span class="mx-2 text-sm font-medium font-caviar tracking-wider">Logout</span>
                    </button>
                </div>
            </nav>
        </div>
    </aside>
</template>