<script setup>
import { Head, Link, useForm,router } from '@inertiajs/vue3';
import ApplicationLayout from '@/Layouts/ApplicationLayout.vue';
import Notifications from '@/Layouts/Notifications.vue';
import FormInput from '@/Components/FormInput.vue';
import FormInputLabel from '@/Components/FormInputLabel.vue';
import { ref, onMounted } from 'vue';
import StepperComponent from '@/Layouts/Stepper.vue';

const props = defineProps({
    completedSteps: {
        type: Array,
        default: () => []
    }
});
const errors = ref({});
const success = ref({});
const form = useForm({
    passportImage: null,
});


function submit(){


   

    router.post('/application/post-upload-photo', form, {
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
    <Head title="Upload Passport Photo" />
    <ApplicationLayout>
        <StepperComponent :completed-steps="completedSteps" />
        <div class="flex flex-row space-x-6 items-center">
             <div>
                <div
                    class="inline-block rounded-md h-20 min-h-[1em] w-0.5 self-stretch bg-green-400 dark:bg-white/10"></div>
            </div>
            <div>
                <h1 class="font-monteserat text-xl tracking-wider md:text-4xl">
                    🆔 Upload Your Passport size photo
                </h1>
                <p class="font-josefin font-bold text-base sm:text-xl tracking-wider">
                    Kindly upload a clear photo or scan of your photo
                </p>
            </div>

            <div>
                <Notifications :errors="errors" :success="success"/> 
            </div>
        </div>

        <div class="mt-4"> 
            <form action="" method="post" class="flex flex-col space-y-6" @submit.prevent="submit" enctype="multipart/form-data">
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="grid gap-4 md:grid-cols-2">
                    <div class="max-w-sm" >
                        <div class="flex flex-row space-x-2">
                            <FormInputLabel for-input="pharmacy_image" label-name="Photo" class="" />
                            
                        </div>
                        <input @input="form.passportImage = $event.target.files[0]" id="your_photo" type="file" class="py-2.5 sm:py-3 px-4 block w-full text-sm text-gray-600 
                        bg-white border border-gray-200 rounded-lg file:bg-gray-200 file:text-gray-700 file:text-sm 
                            file:px-4 file:py-1 file:border-none file:rounded-full placeholder-gray-400/70 focus:outline-none 
                            focus:ring-1 focus:ring-opacity-40  focus:ring-primaryColor focus:border-primaryColor  " accept="image/png, image/jpeg"/>
                            

                    </div>
  
                </div>
  
                </div>

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





    </ApplicationLayout>
</template>
