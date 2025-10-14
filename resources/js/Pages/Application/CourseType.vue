<script setup>
import { ref, computed } from 'vue';
import { Head, Link, useForm,router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Notifications from '@/Layouts/Notifications.vue';
import StepperComponent from '@/Layouts/Stepper.vue';

const props = defineProps({
    units: Object,
    completedSteps: {
        type: Array,
        default: () => []
    }
});

const uniqueLevels = computed(() => {
  if (!props.units || !Array.isArray(props.units)) return []
  
  const levels = props.units
    .map(unit => unit.CourseLevel)
    .filter(Boolean) // removes any null/undefined values
  
  return [...new Set(levels)].sort()
});

const sortedUnits = Object.values(props.units)
    // .sort((a, b) => a.CourseLevel.localeCompare(b.CourseLevel))
    .sort((a, b) => a.UnitDescription.localeCompare(b.UnitDescription));



const errors = ref({});
const success = ref({});
const form = useForm({
    singleSubject: '',
    courseLevel: '',
    levelDescription: '',
    unitCode:'',
});

function getLevelDescription(levelDescription){
    form.levelDescription = levelDescription;
}

function getAllDescription(levelCode, levelDescription, unitDescription){
    form.courseLevel = levelCode;
    form.levelDescription = levelDescription;
    form.unitDescription = unitDescription;
}

function submit(){

  
    router.post('/application/post-course-type', form, {
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
    <Head title="Pick Course" />
    <AuthenticatedLayout>
        <StepperComponent :completed-steps="completedSteps" />
        <div class="flex flex-row space-x-6 items-center">
             <div>
                <div
                    class="inline-block rounded-md h-20 min-h-[1em] w-0.5 self-stretch bg-green-400 dark:bg-white/10"></div>
            </div>
            <div>
                <h1 class="font-monteserat text-xl tracking-wider md:text-4xl">
                    📘 Are you registering for a single subject or the full course?
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                     <section>
                        <!-- Header -->
                        <div class="p-6  ">
                            <h2 class="font-monteserat text-base tracking-wider underline underline-offset-4">
                                Full Course
                            </h2>

                            <div class="flex items-center p-4 mb-4 rounded-xl text-sm mt-4 bg-amber-50 text-amber-500" role="alert">
                                <svg class="w-5 h-5 mr-2" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.0043 13.3333V9.16663M9.99984 6.66663H10.0073M9.99984 18.3333C5.39746 18.3333 1.6665 14.6023 1.6665 9.99996C1.6665 5.39759 5.39746 1.66663 9.99984 1.66663C14.6022 1.66663 18.3332 5.39759 18.3332 9.99996C18.3332 14.6023 14.6022 18.3333 9.99984 18.3333Z" stroke="#F59E0B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span class="font-semibold mr-1 font-josefin text-base tracking-wider">If you are enrolling for the entire course, please choose the "Full Course" option.</span> 
                                
                            </div>
                        </div>
                         <!-- Business Courses -->
                        <ul class="grid w-full gap-6 md:grid-cols-1 mt-2">
                                <li v-for="level, index in uniqueLevels" :key="index">
                                   
                                    <input type="radio" v-model="form.courseLevel" :id="index" :name="index" :value="level" class="hidden peer" 
                                        @change="getLevelDescription(level)"/>
                                    <label :for="index" class="inline-flex items-center justify-between w-full p-5 text-gray-500 
                                        bg-white border border-gray-200 rounded-lg cursor-pointer  
                                        peer-checked:border-primaryColor
                                        peer-checked:text-primaryColor hover:text-gray-600 hover:bg-gray-100 
                                        dark:text-gray-400 ">                           
                                        <div class="block">
                                            <div class="w-full text-lg font-semibold">{{ level }} </div>
                                        </div>
                                        
                                    </label>
                                    <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.departmentCode">{{ form.errors.departmentCode }}</div>

                                </li>
                             
                            </ul>
                    </section>




                     <!-- It Courses section -->
                    <section>
                        <!-- Header -->
                        <div class="p-6 ">
                            <h2 class="font-monteserat text-base tracking-wider underline underline-offset-4 ">
                                Single Subject
                            </h2>

                            <div class="flex items-center p-4 mb-4 rounded-xl text-sm mt-4 bg-gray-900 text-white" role="alert">
                                <svg class="w-5 h-5 mr-2" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.0043 13.3333V9.16663M9.99984 6.66663H10.0073M9.99984 18.3333C5.39746 18.3333 1.6665 14.6023 1.6665 9.99996C1.6665 5.39759 5.39746 1.66663 9.99984 1.66663C14.6022 1.66663 18.3332 5.39759 18.3332 9.99996C18.3332 14.6023 14.6022 18.3333 9.99984 18.3333Z" stroke="#008000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span class="font-semibold mr-1 font-josefin text-base tracking-wider">If you are enrolling for only one unit in the course, please select that specific unit.</span> 
                                
                            </div>
                        </div>
                         <!-- IT Courses -->
                        <ul class="grid w-full gap-6 md:grid-cols-2 mt-2">
                                <li v-for="unit in sortedUnits" :key="unit.UnitCode">
                                    <input type="radio" v-model="form.singleSubject" :id="unit.UnitCode" :name="unit.UnitCode" :value="unit.UnitCode" class="hidden peer" 
                                        @change="getAllDescription(unit.CourseLevel, unit.CourseLevelDescription, unit.UnitDescription)"/>
                                    <label :for="unit.UnitCode" class="inline-flex items-center justify-between w-full p-5 text-gray-500 
                                        bg-white border border-gray-200 rounded-lg cursor-pointer  
                                        peer-checked:border-primaryColor
                                        peer-checked:text-primaryColor hover:text-gray-600 hover:bg-gray-100 
                                        dark:text-gray-400 ">                           
                                        <div class="block">
                                            <!-- <div class="w-full text-lg font-semibold">{{ unit.UnitDescription + ' | ' + unit.CourseLevel }}</div> -->
                                            <div class="font-josefin flex items-center font-bold tracking-wider text-sm">
                                                <p>
                                                    {{ unit.UnitDescription }} &nbsp
                                                </p>
                                                <span>
                                                    |
                                                </span>
                                                <p class="text-sm text-gray-300">
                                                    &nbsp {{ unit.CourseLevel }}
                                                </p>

                                            </div>
                                        </div>
                                        
                                    </label>
                                    <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.departmentCode">{{ form.errors.departmentCode }}</div>

                                </li>
                             
                        </ul>
                    </section>

                   
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


    </AuthenticatedLayout>
</template>