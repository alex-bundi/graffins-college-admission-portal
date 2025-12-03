<script setup>
import { onMounted, ref } from 'vue';
import { Head, Link, useForm,router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Notifications from '@/Layouts/Notifications.vue';
import FormInput from '@/Components/FormInput.vue';
import FormInputLabel from '@/Components/FormInputLabel.vue';
import StepperComponent from '@/Layouts/Stepper.vue';

const props = defineProps({
    applicantCourse: Object,
    completedSteps: {
        type: Array,
        default: () => []
    }
});
const errors = ref({});
const success = ref({});
const form = useForm({
    dob: '',

});

const initialMode = ref(null);
const disableSubmitBtn = ref(false);


onMounted(() => {

    if((props.applicantCourse != null)){
        form.dob = props.applicantCourse.start_date ;
        initialMode.value = props.applicantCourse.start_date ;
    } 
});


function submit(){
    disableSubmitBtn.value = true;

    if (form.dob === initialMode.value) {
        // router.visit('/application/intake')
    } else {
        router.post('/application/post-dob', form, {
            onError : (allErrors) => {
                for(let error in allErrors){
                errors.value[error] = allErrors[error]
                }
                disableSubmitBtn.value = false;
                
            },

        });

    }
  
    

 
}
</script>

<template>
    <Head title="Date of Birth" />
    <AuthenticatedLayout>
        <StepperComponent :completed-steps="completedSteps" />
        <div class="flex flex-row space-x-6 items-center">
             <div>
                <div
                    class="inline-block rounded-md h-20 min-h-[1em] w-0.5 self-stretch bg-green-400 dark:bg-white/10"></div>
            </div>
            <div>
                <h1 class="font-monteserat text-xl tracking-wider md:text-4xl">
                    📅 What is your date of birth?
                </h1>

             
            </div>
        </div>

        <div>
                <Notifications :errors="errors" :success="success"/> 
        </div>

        <div class="mt-12"> 
            <form action="" method="post" class="flex flex-col space-y-6" @submit.prevent="submit">
                <!--  Email -->
                <div class="max-w-sm" >
                    <div class="flex flex-row space-x-2">
                        <FormInputLabel for-input="start_date" label-name="Start Date" class="" />
                        <span class="font-josefin tracking-wider font-bold text-base text-red-500">
                            *
                        </span>
                    </div>
                    <FormInput 
                        type="date"
                        id="start_date"
                        v-model="form.dob"
                        class="py-2.5 sm:py-3 px-4 block w-full font-josefin font-bold tracking-wider"
                        
                        required/> 
                        
                    <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.dob">{{ form.errors.dob }}</div>
                    
                </div>

                 <div class="w-1/4">
                    <button type="submit" :disabled="disableSubmitBtn" :class="{'cursor-not-allowed' : disableSubmitBtn}"
                        class="flex items-center gap-2 px-6 py-3 text-white text-xl font-josefin tracking-wider font-bold 
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