<script setup>
import { ref, onMounted } from 'vue';
import { Head, Link, useForm,router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Notifications from '@/Layouts/Notifications.vue';
import StepperComponent from '@/Layouts/Stepper.vue';

const props = defineProps({
    intakes: Object,
    currentYr: String,
    completedSteps: {
        type: Array,
        default: () => []
    }
});

console.log(props.intakes)
const errors = ref({});
const success = ref({});
const form = useForm({
    academicYear: props.currentYr,
    intake: '',
    intakeDescription: '',
   
});
const initialMode = ref(null);

const disableSubmitBtn = ref(false);

// onMounted(() => {
//     if((props.applicantCourse != null)){
//         form.courseLevel = props.courseLevels.CourseLevelCode + '..' + props.courseLevels.CourseLevelDescription;
//         initialMode.value = props.courseLevels.CourseLevelCode + '..' + props.courseLevels.CourseLevelDescription;
//     } 
// });

function getDescription(description){
    form.intakeDescription = description;
}

function submit(){

    disableSubmitBtn.value = true;
    router.post('/application/post-intake', form, {
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
    <Head title="Intake" />
    <AuthenticatedLayout>
        <StepperComponent :completed-steps="completedSteps" />
        <div class="flex flex-row space-x-6 items-center">
             <div>
                <div
                    class="inline-block rounded-md h-20 min-h-[1em] w-0.5 self-stretch bg-green-400 dark:bg-white/10"></div>
            </div>
            <div>
                <h1 class="font-monteserat text-xl tracking-wider md:text-4xl">
                    📘 Course Intake?
                </h1>

                <p class="font-josefin font-bold text-base sm:text-xl tracking-wider">
                    Please select your intake so we can guide you accordingly.
                </p>
            </div>
        </div>

        <div>
                <Notifications :errors="errors" :success="success"/> 
        </div>

        <div class="mt-12"> 
            <form action="" method="post" class="flex flex-col space-y-6" @submit.prevent="submit">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                   

                    <section>
                        <!-- Header -->
                        <div class="p-6 underline underline-offset-4">
                            <h2 class="font-monteserat text-base tracking-wider">
                                Academic Year
                            </h2>
                        </div>

                        <div>
                            <ul>
                                <li>
                                    <input type="radio" v-model="form.currentYr" :id="currentYr" :name="currentYr" :value="currentYr" class="hidden peer" checked />
                                    <label :for="currentYr" class="inline-flex items-center justify-between w-full p-5 text-gray-500 
                                        bg-white border border-gray-200 rounded-lg cursor-pointer  
                                        peer-checked:border-primaryColor
                                        peer-checked:text-primaryColor hover:text-gray-600 hover:bg-gray-100 ">                           
                                        <div class="block">
                                            <div class="w-full text-lg font-semibold">{{ currentYr }}</div>
                                        </div>
                                        
                                    </label>
                                    <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.currentYr">{{ form.errors.currentYr }}</div>

                                </li>
                            </ul>
                        </div>
                       
                    </section>

                     <section>
                        <!-- Header -->
                        <div class="p-6 underline underline-offset-4">
                            <h2 class="font-monteserat text-base tracking-wider">
                                Intake
                            </h2>
                        </div>
                         
                        <ul class="grid w-full gap-6 md:grid-cols-3 mt-2">
                                <li v-for="intake in intakes" :key="intake.Code">
                                    <input type="radio" v-model="form.intake" :id="intake.Code" :name="intake.Code" :value="intake.Code" class="hidden peer"  
                                        @change="getDescription(intake.Description)"/>
                                    <label :for="intake.Code" class="inline-flex items-center justify-between w-full p-5 text-gray-500 
                                        bg-white border border-gray-200 rounded-lg cursor-pointer  
                                        peer-checked:border-primaryColor
                                        peer-checked:text-primaryColor hover:text-gray-600 hover:bg-gray-100 ">                           
                                        <div class="block">
                                            <div class="w-full text-lg font-semibold">{{ intake.Description }}</div>
                                        </div>
                                       
                                    </label>
                                    <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.intake">{{ form.errors.intake }}</div>

                                </li>
                             
                            </ul>
                    </section>
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