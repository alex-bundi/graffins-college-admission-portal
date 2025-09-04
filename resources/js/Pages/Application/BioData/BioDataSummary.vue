<script setup>
import { ref } from 'vue';
import { Head, Link, useForm,router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Notifications from '@/Layouts/Notifications.vue';
import FormInput from '@/Components/FormInput.vue';
import FormInputLabel from '@/Components/FormInputLabel.vue';



const errors = ref({});
const success = ref({});
const form = useForm({
    startDate: '',

});

function submit(){

  
    router.post('/application/post-class-start-date', form, {
        onError : (allErrors) => {
            for(let error in allErrors){
            errors.value[error] = allErrors[error]
            }
            disableSubmitBtn.value = false;

           
        },

    });

 
}
</script>

<template>
    <Head title="Personal Information Summary" />
    <AuthenticatedLayout>
        <div class="flex flex-row space-x-6 items-center">
             <div>
                <div
                    class="inline-block rounded-md h-20 min-h-[1em] w-0.5 self-stretch bg-green-400 dark:bg-white/10"></div>
            </div>
            <div>
                <h1 class="font-monteserat text-xl tracking-wider md:text-4xl">
                    📋 Personal Information Summary
                </h1>

                <p class="font-josefin font-bold text-base sm:text-xl tracking-wider">
                    Here’s what you’ve shared with us:
                </p>
            </div>
        </div>

        <div>
                <Notifications :errors="errors" :success="success"/> 
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-12"> 
            <div class="flex flex-col space-y-3">
                <div class="flex flex-row space-x-3">
                    <h3 class="font-monteserat text-base tracking-wider ">
                        👤 Full Name: 
                    </h3>
                    <p class="font-josefin font-bold text-base  tracking-wider">
                        Adobe
                    </p>
                </div>

                <div class="flex flex-row space-x-3">
                    <h3 class="font-monteserat text-base tracking-wider ">
                        📞 Phone Number: 
                    </h3>
                    <p class="font-josefin font-bold text-base  tracking-wider">
                        Adobe
                    </p>
                </div>

                <div class="flex flex-row space-x-3">
                    <h3 class="font-monteserat text-base tracking-wider ">
                        🌍 Nationality: 
                    </h3>
                    <p class="font-josefin font-bold text-base  tracking-wider">
                        Adobe
                    </p>
                </div>

                <div class="flex flex-row space-x-3">
                    <h3 class="font-monteserat text-base tracking-wider ">
                        📧 Email Address: 
                    </h3>
                    <p class="font-josefin font-bold text-base  tracking-wider">
                        Adobe
                    </p>
                </div>

                <div class="flex flex-row space-x-3">
                    <h3 class="font-monteserat text-base tracking-wider ">
                        📍 Lives in Nairobi: 
                    </h3>
                    <p class="font-josefin font-bold text-base  tracking-wider">
                        Adobe
                    </p>
                </div>

                <div class="flex flex-row space-x-3">
                    <h3 class="font-monteserat text-base tracking-wider ">
                        📣 Heard About Us From: 
                    </h3>
                    <p class="font-josefin font-bold text-base  tracking-wider">
                        Adobe
                    </p>
                </div>
                <div class="flex flex-row space-x-3">
                    <h3 class="font-monteserat text-base tracking-wider ">
                        ⚠️ Allergies: 
                    </h3>
                    <p class="font-josefin font-bold text-base  tracking-wider">
                        Adobe
                    </p>
                </div>

                <div class="flex flex-row space-x-3">
                    <h3 class="font-monteserat text-base tracking-wider ">
                        🚨 Emergency Contact:
                    </h3>
                    <p class="font-josefin font-bold text-base  tracking-wider">
                        Adobe
                    </p>
                </div>

                <div class="flex flex-row space-x-3">
                    <h3 class="font-monteserat text-base tracking-wider ">
                        📎 Uploaded ID/Passport: 
                    </h3>
                    <p class="font-josefin font-bold text-base  tracking-wider">
                       ✅ Received

                    </p>
                </div>
            </div>

            <div class="flex flex-col space-y-3">
                <div class="flex flex-col space-y-3 items-center">
                    <p class="font-josefin font-bold text-base  tracking-wider">
                        If all details are correct,  Click <span class="font-monteserat tracking-wider font-bold">“Confirm” </span> to proceed.
                    </p>

                    <div>
                        <Link class="text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            Confirm
                        </Link>
                    </div>
                </div>

                <div class="flex flex-col space-y-3 items-center">
                    <p class="font-josefin font-bold text-base  tracking-wider">
                        Need to make changes?   Click
                         <span class="font-monteserat tracking-wider font-bold">Edit </span> 
                         
                    </p>

                    <div>
                        <Link class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            Edit
                        </Link>
                    </div>
                </div>
            </div>

            <div class="w-1/4">
                <Link :href="route('amount.payable')" class="flex items-center gap-2 px-6 py-3 text-white text-xl font-josefin tracking-wider font-bold 
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