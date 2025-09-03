<script setup>
import { ref, defineProps, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    errors: {
        type: Object,
        required: true,
        default: () => ({})
    },

    success: {
        type: Object,
        required: true,
        default: () => ({})
    },
})

const page = usePage()

const successMessage = computed(() => {
    return page.props?.flash?.success || null
})

const accountStatus = computed(() => {
    return page.props?.flash?.accountStatus || null
})

function hideDiv(elementId){
    const element = document.getElementById(elementId);
    element.classList.add('hidden')
}
</script>
<template>
<!-- Errors -->
<div v-if="Object.entries(errors).length > 0"> 
    <ul>
        <li v-for="(error, key, index) in errors" :key="error.id" :id="`error-item-${index}`">
            
            <div class="flex justify-between w-full mb-3 overflow-hidden bg-white rounded-lg shadow-md dark:bg-gray-800">
                <div class="flex flex-row">
                    <div class="flex items-center justify-center w-12 bg-red-500">
                        <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 3.36667C10.8167 3.36667 3.3667 10.8167 3.3667 20C3.3667 29.1833 10.8167 36.6333 20 36.6333C29.1834 36.6333 36.6334 29.1833 36.6334 20C36.6334 10.8167 29.1834 3.36667 20 3.36667ZM19.1334 33.3333V22.9H13.3334L21.6667 6.66667V17.1H27.25L19.1334 33.3333Z" />
                        </svg>
                    </div>
                
                    <div class=" flex items-center px-4 py-2 -mx-3">
                        <div class="mx-3">
                            <!-- <span class="font-semibold font-monteserat text-red-500 dark:text-red-400">{{ index }}</span> -->
                            <p class="text-sm font-josefin font-bold tracking-wider text-red-800 md:text-base dark:text-gray-200">
                                {{ error }}
                            </p>
                        </div>
                    </div>
                </div>
                

                <div class="flex items-center m-4">
                    <button type="button"  @click="hideDiv(`error-item-${index}`)" class="ms-auto -mx-1.5 -my-1.5 bg-gray-50 text-black rounded-lg focus:ring-2 p-1.5 
                    hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-blue-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-1" aria-label="Close">
                        <span class="sr-only">Close</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 hover:text-red-600 hover:fill-gray-300">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </button>
                    
                </div>
            </div>
        </li>
    </ul>
    
</div>

<div class="m-6" v-if="successMessage">
    <div class="flex justify-between w-full   overflow-hidden bg-white rounded-lg shadow-md dark:bg-gray-800" id="success_div">
        <div class="flex flex-row">


            <div class="flex items-center justify-center w-12 bg-emerald-500">
                <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM16.6667 28.3333L8.33337 20L10.6834 17.65L16.6667 23.6166L29.3167 10.9666L31.6667 13.3333L16.6667 28.3333Z" />
                </svg>
            </div>

            <div class="flex items-center justify-between px-4 py-2 -mx-3">
                <div class="mx-3">
                    <p class="font-josefin font-bold tracking-wider 
                        text-sm text-gray-600 dark:text-gray-200">
                        {{ successMessage }}
                    </p>
                </div>
                
            
            </div>
        </div>
        
        <div class="flex items-center m-4" >
            <button type="button" @click="hideDiv('success_div')"  class="ms-auto -mx-1.5 -my-1.5 bg-gray-50 text-black rounded-lg focus:ring-2 p-1.5 
            hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-blue-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-1" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 hover:text-red-600 hover:fill-gray-300">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </button>
            
        </div>
    </div>
</div>

<div class="m-6" v-if="accountStatus">
    <div class="flex justify-between w-full   overflow-hidden bg-white rounded-lg shadow-md dark:bg-gray-800" id="account_status_div">
        <div class="flex flex-row">


            <div class="flex items-center justify-center w-12 bg-emerald-500">
                <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM16.6667 28.3333L8.33337 20L10.6834 17.65L16.6667 23.6166L29.3167 10.9666L31.6667 13.3333L16.6667 28.3333Z" />
                </svg>
            </div>

            <div class="flex items-center justify-between px-4 py-2 -mx-3">
                <div class="mx-3">
                    <p class="font-josefin font-bold tracking-wider 
                        text-sm text-gray-600 dark:text-gray-200">
                        {{ accountStatus }}
                    </p>
                </div>
                
            
            </div>
        </div>
        
        <div class="flex items-center m-4" >
            <button type="button" @click="hideDiv('account_status_div')"  class="ms-auto -mx-1.5 -my-1.5 bg-gray-50 text-black rounded-lg focus:ring-2 p-1.5 
            hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-blue-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-1" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 hover:text-red-600 hover:fill-gray-300">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </button>
            
        </div>
    </div>
</div>

</template>