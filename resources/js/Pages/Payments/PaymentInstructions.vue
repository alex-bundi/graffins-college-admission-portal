<script setup>
import { ref } from 'vue';
import { Head, Link, useForm,router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Notifications from '@/Layouts/Notifications.vue';




const errors = ref({});
const success = ref({});

</script>

<template>
    <Head title="Rules & Regulations" />
    <AuthenticatedLayout>
        <div>
                <Notifications :errors="errors" :success="success"/> 
        </div>
        <div class="flex flex-row space-x-6 items-center">
             <div>
                <div
                    class="inline-block rounded-md h-20 min-h-[1em] w-0.5 self-stretch bg-green-400 dark:bg-white/10"></div>
            </div>
            <div>
                <h1 class="font-monteserat text-xl tracking-wider md:text-4xl">
                    💰 How to PAY
                </h1>

                <p class="font-josefin font-bold text-base sm:text-xl tracking-wider">
                    Follow the instructions below to pay fees!
                </p>
            </div>
        </div>

      
        <div class="mt-4">
            <p class="font-josefin font-bold text-base tracking-wider">
                <span class="font-monteserat font-extrabold">📲 MPESA Manual Payment Instructions:</span> 

            </p>
            <ul>

                <li>
                    
                    <ul class="flex flex-col font-bold space-y-4 p-4 font-josefin text-base tracking-wider">
                        <li>
                            1. Go to <span class="font-monteserat">Lipa na Mpesa</span>

                        </li>
                        <li >
                            2. Select <span class="font-monteserat">Paybill</span>
                        </li>
                        <li>
                            <span  class="font-monteserat" > Business Number:</span> 982 800
                        </li>
                        <li>
                           <span class="font-monteserat">
                            Account Number: 
                           </span> 300 001 7028 
                        </li>
                        <li>
                           <span class="font-monteserat"> Amount:</span> KES [Enter course fee]
                        </li>
                        <li>
                            Enter your MPESA PIN and confirm.

                        </li>
                    </ul>
                </li>
            </ul>
        </div>

        
        <div>
             <div class="w-1/4">
                <Link :href="route('update.payment')" class="flex items-center gap-2 px-6 py-3 text-white text-xl font-josefin tracking-wider font-bold 
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


    </AuthenticatedLayout>
</template>