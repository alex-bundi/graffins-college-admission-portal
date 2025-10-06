<script setup>
import { ref } from 'vue';
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
    courseSummary: '',

});

const showStepperMessage = ref(false);

// Function to show message temporarily
const showMessage = () => {
    showStepperMessage.value = true;
    // Hide message after 3 seconds
    setTimeout(() => {
        showStepperMessage.value = false;
    }, 7000);
};

function submitCourseSummary(){
    form.courseSummary = 'submitted';
  
    router.post('/application/post-course-summary', form, {
        onError : (allErrors) => {
            for(let error in allErrors){
            errors.value[error] = allErrors[error]
            }

           
        },

    });

 
}
</script>

<template>
    <Head title="Class Start Date" />
    <AuthenticatedLayout>
        <StepperComponent :completed-steps="completedSteps" />
        <div class="flex flex-row space-x-6 items-center">
             <div>
                <div
                    class="inline-block rounded-md h-20 min-h-[1em] w-0.5 self-stretch bg-green-400 dark:bg-white/10"></div>
            </div>
            <div>
                <h1 class="font-monteserat text-xl tracking-wider md:text-4xl">
                    📄 Course Summary
                </h1>

                <p class="font-josefin font-bold text-base sm:text-xl tracking-wider">
                    Here’s a quick summary of the course you’ve selected:
                </p>
            </div>
        </div>

        <div>
                <Notifications :errors="errors" :success="success"/> 
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-12"> 
            <form action="">
                <div class="flex flex-col space-y-3">
                    <div class="flex flex-row space-x-3">
                        <h3 class="font-monteserat text-base tracking-wider ">
                            Course Name:
                        </h3>
                        <p class="font-josefin font-bold text-base  tracking-wider">
                            {{ applicantCourse.course_description }}
                        </p>
                    </div>



                    <div class="flex flex-row space-x-3">
                        <h3 class="font-monteserat text-base tracking-wider ">
                            Course Level:
                        </h3>
                        <p class="font-josefin font-bold text-base  tracking-wider">
                            {{ applicantCourse.course_level }}
                        </p>
                    </div>

                    <div class="flex flex-row space-x-3">
                        <h3 class="font-monteserat text-base tracking-wider ">
                            Department
                        </h3>
                        <p class="font-josefin font-bold text-base  tracking-wider">
                            {{ applicantCourse.department_description }}
                        </p>
                    </div>

                    <!-- <div class="flex flex-row space-x-3">
                        <h3 class="font-monteserat text-base tracking-wider ">
                            Duration
                        </h3>
                        <p class="font-josefin font-bold text-base  tracking-wider">
                            Adobe
                        </p>
                    </div> -->

                    <div class="flex flex-row space-x-3">
                        <h3 class="font-monteserat text-base tracking-wider ">
                            Mode of study
                        </h3>
                        <p class="font-josefin font-bold text-base  tracking-wider">
                            {{ (applicantCourse.mode_of_study == 1) ? 'Inclass' : 'Online'  }}
                        </p>
                    </div>

                    <div class="flex flex-row space-x-3">
                        <h3 class="font-monteserat text-base tracking-wider ">
                            Start Date
                        </h3>
                        <p class="font-josefin font-bold text-base  tracking-wider">
                            {{ applicantCourse.start_date }}
                        </p>
                    </div>
                    <!-- Course Type -->
                    <div v-show="applicantCourse.unit_status ==  'Single Subject'" class="flex flex-row space-x-3">
                        <h3 class="font-monteserat text-base tracking-wider ">
                            Course Type
                        </h3>
                        <p class="font-josefin font-bold text-base  tracking-wider">
                           Single Subject
                        </p>
                    </div>
                    
                </div>

                <!-- <div class="max-w-sm" >
                    <div class="flex flex-row space-x-2">
                        <FormInputLabel for-input="course_Name" label-name="Course Name:" class="" />
                        <span class="font-josefin tracking-wider font-bold text-base text-red-500">
                            *
                        </span>
                    </div>
                    <FormInput 
                        type="text"
                        id="course_Name"
                        v-model="form.courseName"
                        class="py-2.5 sm:py-3 px-4 block w-full font-josefin font-bold tracking-wider"
                        
                        required/> 
                        
                    <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.courseName">{{ form.errors.courseName }}</div>
                    
                </div> -->
            </form>
            

            <div class="flex flex-col space-y-3">
                <div class="flex flex-col space-y-3 items-center">
                    <p class="font-josefin font-bold text-base  tracking-wider">
                        If everything looks good, Click <span class="font-monteserat tracking-wider font-bold">“Confirm” </span> to proceed.
                    </p>

                    <div> 
                        <form action="" method="post" @submit.prevent="submitCourseSummary">
                            <input class="hidden" type="text" v-model="form.courseSummary" name="courseSummary" id="courseSummary" value="submitted">
                            <button class="text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                Confirm
                            </button>
                        </form>
                        
                    </div>
                </div>

                <div class="flex flex-col space-y-3 items-center">
                    <p class="font-josefin font-bold text-base tracking-wider">
                        Need to change something? Click
                        <span class="font-monteserat tracking-wider font-bold">Edit</span> 
                    </p>

                    <div>
                        <Link 
                            @click="showMessage"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                        >
                            Edit
                        </Link>


                
                    </div>





                    
                    <!-- Conditional message that appears on click -->
                    <div 
                        v-if="showStepperMessage" 
                        class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-2 rounded-lg text-sm text-center transition-all duration-300 ease-in-out"
                    >
                        💡 You can click on any step in the progress bar above to navigate directly!
                    </div>
                </div>
            </div>

            <!-- <div class="w-1/4">
                <Link :href="route('start.bio.data')" class="flex items-center gap-2 px-6 py-3 text-white text-xl font-josefin tracking-wider font-bold 
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
            </div> -->
        </div>


    </AuthenticatedLayout>
</template>