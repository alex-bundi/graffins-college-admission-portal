<script setup>
import { ref } from 'vue';
import { Head, Link, useForm,router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Notifications from '@/Layouts/Notifications.vue';



const errors = ref({});
const success = ref({});
const form = useForm({
    departmentCode: '',
});

function submit(){

  
    router.post('/application/post-department', form, {
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
    <Head title="Department" />
    <AuthenticatedLayout>
        
        <div class="flex flex-row space-x-6 items-center">
             <div>
                <div
                    class="inline-block rounded-md h-20 min-h-[1em] w-0.5 self-stretch bg-green-400 dark:bg-white/10"></div>
            </div>
            <div>
                <h1 class="font-monteserat text-xl tracking-wider md:text-4xl">
                    🏫 Select Department:
                </h1>
            </div>
        </div>

        <div>
                <Notifications :errors="errors" :success="success"/> 
        </div>

        <div class="flex flex-col space-y-11 sm:flex-row sm:justify-around mt-12 items-center w-full">
            <div>
                <p class="font-josefin font-bold text-base sm:text-xl tracking-wider">
                    Please pick the department you’re <br> joining to help us guide you better

                </p>
            </div>
            <form action="" method="post" class="flex flex-col space-y-6" @submit.prevent="submit">
                <ul class="grid w-full gap-6 md:grid-cols-1">
                    <li>
                        <input type="radio" v-model="form.departmentCode" id="inclass" name="inclass" value="inclass" class="hidden peer" />
                        <label for="inclass" class="inline-flex items-center justify-between w-full p-5 text-gray-500 
                            bg-white border border-gray-200 rounded-lg cursor-pointer  
                            peer-checked:border-primaryColor
                            peer-checked:text-primaryColor hover:text-gray-600 hover:bg-gray-100 
                            dark:text-gray-400 ">                           
                            <div class="block">
                                <div class="w-full text-lg font-semibold">English</div>
                            </div>
                            
                        </label>
                        <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.departmentCode">{{ form.errors.departmentCode }}</div>

                    </li>
                    <li>
                        <input type="radio"  v-model="form.departmentCode" id="online" name="online" value="online" class="hidden peer">
                        <label for="online" class="inline-flex items-center justify-between w-full p-5 text-gray-500 
                            bg-white border border-gray-200 rounded-lg cursor-pointer  
                            peer-checked:border-primaryColor
                            peer-checked:text-primaryColor hover:text-gray-600 hover:bg-gray-100 
                            dark:text-gray-400 ">
                            <div class="block">
                                <div class="w-full text-lg font-semibold">Business</div>
                            <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.departmentCode">{{ form.errors.departmentCode }}</div>

                            </div>
                        </label>
                    </li>
                    <li>
                        <input type="radio"  v-model="form.inclass" id="online" name="online" value="online" class="hidden peer">
                        <label for="online" class="inline-flex items-center justify-between w-full p-5 text-gray-500 
                            bg-white border border-gray-200 rounded-lg cursor-pointer  
                            peer-checked:border-primaryColor
                            peer-checked:text-primaryColor hover:text-gray-600 hover:bg-gray-100 
                            dark:text-gray-400 ">
                            <div class="block">
                                <div class="w-full text-lg font-semibold">IT</div>
                            <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.departmentCode">{{ form.errors.departmentCode }}</div>

                            </div>
                        </label>
                    </li>
                </ul>

                 <div class="w-1/4">
                    <button type="submit" class="flex items-center gap-2 px-6 py-3 text-white text-xl font-josefin tracking-wider font-bold 
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
                    </button>
                </div>
            </form>
        </div>

    </AuthenticatedLayout>
</template>