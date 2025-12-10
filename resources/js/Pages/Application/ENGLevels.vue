<script setup>
import { ref, onMounted, computed } from 'vue';
import { Head, Link, useForm,router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Notifications from '@/Layouts/Notifications.vue';
import StepperComponent from '@/Layouts/Stepper.vue';

const props = defineProps({
    courseLevels: Object,
    applicantCourse: Object,
    completedSteps: {
        type: Array,
        default: () => []
    }
});

const errors = ref({});
const success = ref({});
const form = useForm({
    courseLevel: props.applicantCourse?.course_level ?? null,
    levelDescription: props.applicantCourse.level_description  ?? null,
   
});
const initialMode = ref(null);

const disableSubmitBtn = ref(false);

onMounted(() => {
    if((props.applicantCourse != null)){
        form.courseLevel = props.applicantCourse.course_level;
        initialMode.value = props.applicantCourse.course_level ;
    } 
});

function getLevelDescription(levelDescription){
    form.levelDescription = levelDescription;

} 

const hasChanged = computed(() => {
    return (
        
        form.courseLevel !== (props.applicantCourse?.course_level ?? null) ||
        form.levelDescription !== (props.applicantCourse.level_description  ?? null)
    );
});

function submit(){

    disableSubmitBtn.value = true;


     if (hasChanged.value == true) {
         router.post('/application/post-course-levels', form, {
            onError : (allErrors) => {
                for(let error in allErrors){
                errors.value[error] = allErrors[error]
                }
                disableSubmitBtn.value = false;
            },

        });
    } else {
        router.visit('/application/class-start-date');
    }
    

   
}


</script>

<template>
    <Head title="Mode of Study" />
    <AuthenticatedLayout>
        <StepperComponent :completed-steps="completedSteps" />
        <div class="flex flex-row space-x-6 items-center">
             <div>
                <div
                    class="inline-block rounded-md h-20 min-h-[1em] w-0.5 self-stretch bg-green-400 dark:bg-white/10"></div>
            </div>
            <div>
                <h1 class="font-monteserat text-xl tracking-wider md:text-4xl">
                    📘 English Levels?
                </h1>

                <p class="font-josefin font-bold text-base sm:text-xl tracking-wider">
                    Please select one so we can guide you accordingly.
                </p>
            </div>
        </div>

        <div>
                <Notifications :errors="errors" :success="success"/> 
        </div>

        <div class="mt-12"> 
            <form action="" method="post" class="flex flex-col space-y-6" @submit.prevent="submit">
                <div class="">
                   

                    <section>
                        <!-- Header -->
                        <div class="p-6 underline underline-offset-4 ">
                            <h2 class="flex justify-center font-monteserat text-xl tracking-wider">
                                Course Levels
                            </h2>
                        </div>
                         <!-- English Courses -->
                        <ul class="grid grid-cols-1 w-full gap-6 sm:grid-cols-3 mt-2">
                                <li v-for="level in courseLevels" :key="level.CourseLevelCode">
                                    <input type="radio" v-model="form.courseLevel" :id="level.CourseLevelCode" :name="level.CourseLevelCode" :value="level.CourseLevelCode" class="hidden peer" 
                                        @change="getLevelDescription(level.CourseLevelDescription)"/>
                                    <label :for="level.CourseLevelCode" class="inline-flex items-center justify-between w-full p-5 text-gray-500 
                                        bg-white border border-gray-200 rounded-lg cursor-pointer  
                                        peer-checked:border-primaryColor
                                        peer-checked:text-primaryColor hover:text-gray-600 hover:bg-gray-100 
                                        dark:text-gray-400 ">                           
                                        <div class="block">
                                            <div class="w-full text-lg font-semibold">{{ level.CourseLevelDescription }}</div>
                                        </div>
                                        
                                    </label>
                                    <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.courseLevel">{{ form.errors.courseLevel }}</div>

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