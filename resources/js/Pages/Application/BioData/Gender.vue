<script setup>
import { Head, Link, useForm,router } from '@inertiajs/vue3';
import ApplicationLayout from '@/Layouts/ApplicationLayout.vue';
import Notifications from '@/Layouts/Notifications.vue';
import FormInput from '@/Components/FormInput.vue';
import FormInputLabel from '@/Components/FormInputLabel.vue';
import { ref, onMounted,computed } from 'vue';
import StepperComponent from '@/Layouts/Stepper.vue';

const props = defineProps({
    applicant: Object,
    completedSteps: {
        type: Array,
        default: () => []
    }
});

// let genderStatus = ref(null);
// if((props.applicant.allergies != null) && (props.applicant.allergies == 1)){
//     genderStatus = 'yes';
// } else if ((props.applicant.allergies != null) && (props.applicant.allergies == 2)){
//     genderStatus = 'no';

// }

const errors = ref({});
const success = ref({});
const form = useForm({
    gender: 0,
});

// const hasChanged = computed(() => {
//     return (
//         form.gender !== (props.applicant.gender ?? null)
//     );
// });
const disableSubmitBtn = ref(false);


function submit(){
    disableSubmitBtn.value = true;

      router.post('/application/post-gender', form, {
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
    <Head title="Allergies" />
    <ApplicationLayout>
        <StepperComponent :completed-steps="completedSteps" />
        <div class="flex flex-row space-x-6 items-center">
             <div>
                <div
                    class="inline-block rounded-md h-20 min-h-[1em] w-0.5 self-stretch bg-green-400 dark:bg-white/10"></div>
            </div>
            <div>
                <h1 class="font-monteserat text-xl tracking-wider md:text-4xl">
                   What is your Gender?
                </h1>
            </div>

            <div>
                <Notifications :errors="errors" :success="success"/> 
            </div>
        </div>

        <div class="mt-4"> 
            <form action="" method="post" class="flex flex-col space-y-6" @submit.prevent="submit">
                
                <ul class="grid w-full gap-6 md:grid-cols-2 mt-2">
                    <li>
                        <input type="radio" v-model="form.gender" id="1" name="1" value="1" class="hidden peer" />
                        <label for="1" class="inline-flex items-center justify-between w-full p-5 text-gray-500 
                            bg-white border border-gray-200 rounded-lg cursor-pointer  
                            peer-checked:border-primaryColor
                            peer-checked:text-primaryColor hover:text-gray-600 hover:bg-gray-100 
                            dark:text-gray-400 ">                           
                            <div class="block">
                                <div class="w-full text-lg font-semibold">Male</div>
                            </div>
                            
                        </label>
                        <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.gender">{{ form.errors.gender }}</div>

                    </li>
                    <li>
                        <input type="radio"  v-model="form.gender" id="2" name="2" value="2" class="hidden peer">
                        <label for="2" class="inline-flex items-center justify-between w-full p-5 text-gray-500 
                            bg-white border border-gray-200 rounded-lg cursor-pointer  
                            peer-checked:border-primaryColor
                            peer-checked:text-primaryColor hover:text-gray-600 hover:bg-gray-100 
                            dark:text-gray-400 ">
                            <div class="block">
                                <div class="w-full text-lg font-semibold">Female</div>
                            <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.gender">{{ form.errors.gender }}</div>

                            </div>
                        </label>
                    </li>
                             
                </ul>
                <div class="w-1/4">
                    <button type="submit" :class="{'cursor-not-allowed' : disableSubmitBtn}"
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





    </ApplicationLayout>
</template>
