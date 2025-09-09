<script setup>
import { Head, Link, useForm,router } from '@inertiajs/vue3';
import ApplicationLayout from '@/Layouts/ApplicationLayout.vue';
import Notifications from '@/Layouts/Notifications.vue';
import FormInput from '@/Components/FormInput.vue';
import FormInputLabel from '@/Components/FormInputLabel.vue';
import { ref, onMounted } from 'vue';

const errors = ref({});
const success = ref({});
const form = useForm({
    studentPortalPWD: null,
    idVerificationCode: '',
});

function submit(){


    router.post('/application/post-student-id', form, {
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
    <Head title="Student ID" />
    <ApplicationLayout>
        <div class="flex flex-row space-x-6 items-center">
             <div>
                <div
                    class="inline-block rounded-md h-20 min-h-[1em] w-0.5 self-stretch bg-green-400 dark:bg-white/10"></div>
            </div>
            <div>
                <h1 class="font-monteserat text-xl tracking-wider md:text-4xl">
                    Access your Student ID:
                </h1>
            </div>

            <div>
                <Notifications :errors="errors" :success="success"/> 
            </div>
        </div>

        <div class="mt-4"> 
            <form action="" method="post" class="" @submit.prevent="submit">
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="max-w-sm" >
                        <div class="flex flex-row space-x-2">
                            <FormInputLabel for-input="student_portal_pwd" label-name="Student Portal Password" class="" />
                            <span class="font-josefin tracking-wider font-bold text-base text-red-500">
                                *
                            </span>
                        </div>
                        <FormInput 
                            type="text"
                            id="student_portal_pwd"
                            v-model="form.studentPortalPWD"
                            class="py-2.5 sm:py-3 px-4 block w-full text-base font-josefin font-bold tracking-wider"
                            
                            required/> 
                            
                        <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.studentPortalPWD">{{ form.errors.studentPortalPWD }}</div>
                        
                    </div>


                    <div class="max-w-sm" >
                        <div class="flex flex-row space-x-2">
                            <FormInputLabel for-input="id_verification_code" label-name="ID Verification Code" class="" />
                            <span class="font-josefin tracking-wider font-bold text-base text-red-500">
                                *
                            </span>
                        </div>
                        
                        <FormInput 
                            type="text"
                            id="id_verification_code"
                            v-model="form.idVerificationCode"
                            class="py-2.5 sm:py-3 px-4 block w-full font-josefin font-bold tracking-wider"
                            
                            required/> 
                            
                        <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.idVerificationCode">{{ form.errors.idVerificationCode }}</div>
                        
                    </div>



                    <div >
                            <div class="max-w-sm mt-5" id="sign-in-btn"> 
                                <button type="submit" :disabled="form.processing" class="py-2.5 sm:py-3 px-4  block w-full items-center gap-x-2 font-league tracking-wider text-sm font-medium rounded-lg border border-transparent 
                                    bg-primaryColor text-white hover:bg-darkPrimaryColor focus:outline-hidden focus:bg-primaryColor disabled:opacity-50 
                                    disabled:pointer-events-none">
                                    Submit
                                </button>
                            </div>
                    </div>
                   
                </div>
            </form>

        </div>





    </ApplicationLayout>
</template>
