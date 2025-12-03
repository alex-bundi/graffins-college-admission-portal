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
    courseID: 0,

});

const showStepperMessage = ref(false);
const disableSubmitBtn = ref(false);


const showMessage = () => {
    showStepperMessage.value = true;
    setTimeout(() => {
        showStepperMessage.value = false;
    }, 70000);
};

function submitCourseSummary(courseID){
     disableSubmitBtn.value = true;
    form.courseID = courseID;
  
    router.post('/application/post-course-confirmation', form, {
        onError : (allErrors) => {
            for(let error in allErrors){
            errors.value[error] = allErrors[error]
            }
            disableSubmitBtn.value = false;

           
        },

    });

}



function deleteCourseLine(courseID){
     disableSubmitBtn.value = true;
    form.courseID = courseID;
    console.log(form.courseID);
  
    router.post('/application/delete-course-line', form, {
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
    <Head title="Courses Summary" />
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


        <section class="mt-4 bg-gray-50 ">
            <div class="w-full  px-4 mx-auto">
                <!-- Start coding here -->
                <div class="relative overflow-hidden bg-white shadow-md  sm:rounded-lg">
                    <div class="flex-row items-center justify-between p-4 space-y-3 sm:flex sm:space-y-0 sm:space-x-4">
                        <div>
                            <h5 class="mr-3 font-monteserat font-semibold ">Your Courses</h5>
                            <p class="text-gray-500 font-josefin font-bold tracking-wider ">Manage all your existing courses or add a new one</p>
                        </div>
                        <Link :href="route('add.new.course')" type="button"
                                class="flex items-center justify-center space-x-4 px-4 py-2 text-sm  text-white rounded-lg font-josefin font-bold tracking-wider
                                bg-primaryColor hover:bg-darkPrimaryColor focus:ring focus:ring-primaryColor focus:outline-none ">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                <path d="M11.7 2.805a.75.75 0 0 1 .6 0A60.65 60.65 0 0 1 22.83 8.72a.75.75 0 0 1-.231 1.337 49.948 49.948 0 0 0-9.902 3.912l-.003.002c-.114.06-.227.119-.34.18a.75.75 0 0 1-.707 0A50.88 50.88 0 0 0 7.5 12.173v-.224c0-.131.067-.248.172-.311a54.615 54.615 0 0 1 4.653-2.52.75.75 0 0 0-.65-1.352 56.123 56.123 0 0 0-4.78 2.589 1.858 1.858 0 0 0-.859 1.228 49.803 49.803 0 0 0-4.634-1.527.75.75 0 0 1-.231-1.337A60.653 60.653 0 0 1 11.7 2.805Z" />
                                <path d="M13.06 15.473a48.45 48.45 0 0 1 7.666-3.282c.134 1.414.22 2.843.255 4.284a.75.75 0 0 1-.46.711 47.87 47.87 0 0 0-8.105 4.342.75.75 0 0 1-.832 0 47.87 47.87 0 0 0-8.104-4.342.75.75 0 0 1-.461-.71c.035-1.442.121-2.87.255-4.286.921.304 1.83.634 2.726.99v1.27a1.5 1.5 0 0 0-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.66a6.727 6.727 0 0 0 .551-1.607 1.5 1.5 0 0 0 .14-2.67v-.645a48.549 48.549 0 0 1 3.44 1.667 2.25 2.25 0 0 0 2.12 0Z" />
                                <path d="M4.462 19.462c.42-.419.753-.89 1-1.395.453.214.902.435 1.347.662a6.742 6.742 0 0 1-1.286 1.794.75.75 0 0 1-1.06-1.06Z" />
                                </svg>

                                <span>
                                    Add new Course
                                </span>
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <div class="grid grid-cols-1 gap-6 mt-12"> 
            <div class="sm:m-4">
                <div class="flex flex-col">
                    <div class=" overflow-x-auto">
                    <div class="min-w-full inline-block align-middle"> 
                        
                        <div class="overflow-hidden ">
                            <table class=" min-w-full rounded-xl">
                                <thead>
                                    <tr class="bg-gray-300 text-gray-900 font-monteserat tracking-wider">
                                        <th scope="col" class="p-5 text-left text-sm leading-6 font-semibold  capitalize rounded-t-xl"> 
                                            Course
                                        </th>
                                        <th scope="col" class="p-5 text-left text-sm leading-6    font-semibold  capitalize"> 
                                        Level
                                        </th>
                                        <th scope="col" class="p-5 text-left text-sm leading-6 font-semibold  capitalize"> 
                                            Department 
                                        </th>
                                        <th scope="col" class="p-5 text-left text-sm leading-6 font-semibold  capitalize"> 
                                            Mode of Study
                                        </th>
                                        <th scope="col" class="p-5 text-left text-sm leading-6 font-semibold  capitalize"> 
                                            Intake
                                        </th>
                                        <th scope="col" class="p-5 text-left text-sm leading-6 font-semibold  capitalize"> 
                                            Tutor
                                        </th>
                                        <th scope="col" class="p-5 text-left text-sm leading-6 font-semibold  capitalize"> 
                                            Start Date
                                        </th>

                                        <th scope="col" class="p-5 text-left text-sm leading-6 font-semibold  capitalize rounded-t-xl"> Actions </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-300 ">
                                    <tr v-for="course in applicantCourse"
                                        class="bg-white font-josefin font-bold tracking-wider transition-all duration-500 hover:bg-gray-50">
                                        <td class="p-5 whitespace-nowrap text-sm leading-6  text-gray-900 ">
                                            {{ course.course_description }}
                                        </td>
                                        <td class="p-5 whitespace-nowrap text-sm leading-6  text-gray-900"> 
                                            {{ course.level_description }}
                                        </td>
                                        <td class="p-5 whitespace-nowrap text-sm leading-6  text-gray-900"> 
                                            {{ course.department_description }}
                                        </td>
                                        <td class="p-5 whitespace-nowrap text-sm leading-6  text-gray-900"> 
                                            {{ (course.mode_of_study == 1) ? 'Inclass' : 'Online' }}
                                        </td>
                                        <td class="p-5 whitespace-nowrap text-sm leading-6 text-gray-900"> 
                                            {{ course.intake_description }}
                                        </td>
                                        <td class="p-5 whitespace-nowrap text-sm leading-6 text-gray-900"> 
                                            {{ course.tutor_name }}
                                        </td>
                                        <td class="p-5 whitespace-nowrap text-sm leading-6  text-gray-900"> 
                                            {{ course.start_date }}
                                        </td>
                                        <td class=" p-5 ">
                                            <div v-if="course.application_status == 'new'" 
                                                class="flex items-center gap-1">
                                                <Link :href="route('edit.course.line', [course.applicant_id, course.id])" :class="{'cursor-not-allowed' : disableSubmitBtn}" class="flex items-center text-black text-sm p-2 border border-amber-400 hover:bg-gray-500 hover:text-white hover:border-0 
                                                        rounded-full  group transition-all duration-500 space-x-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                                                        <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                                        <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                                                        </svg>



                                                        <span>
                                                            Edit
                                                        </span>
                                                </Link>
                                                
                                                <form action="" method="post" @submit.prevent="submitCourseSummary(course.id)">
                                                    <button  :class="{'cursor-not-allowed' : disableSubmitBtn}" class="flex items-center text-black text-sm p-2 border border-black hover:bg-gray-500 hover:text-white hover:border-0 
                                                        rounded-full  group transition-all duration-500 space-x-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                                        <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                                                        </svg>


                                                        <span>
                                                            Confirm
                                                        </span>
                                                    </button>
                                                </form>

                                                <form action="" method="POST" @submit.prevent="deleteCourseLine(course.id)">
                                                    <button class="p-2 rounded-full  group transition-all duration-500  flex item-center
                                                        hover:bg-red-300 ">
                                                        <svg class="" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path class="fill-red-600 " d="M4.00031 5.49999V4.69999H3.20031V5.49999H4.00031ZM16.0003 5.49999H16.8003V4.69999H16.0003V5.49999ZM17.5003 5.49999L17.5003 6.29999C17.9421 6.29999 18.3003 5.94183 18.3003 5.5C18.3003 5.05817 17.9421 4.7 17.5003 4.69999L17.5003 5.49999ZM9.30029 9.24997C9.30029 8.80814 8.94212 8.44997 8.50029 8.44997C8.05847 8.44997 7.70029 8.80814 7.70029 9.24997H9.30029ZM7.70029 13.75C7.70029 14.1918 8.05847 14.55 8.50029 14.55C8.94212 14.55 9.30029 14.1918 9.30029 13.75H7.70029ZM12.3004 9.24997C12.3004 8.80814 11.9422 8.44997 11.5004 8.44997C11.0585 8.44997 10.7004 8.80814 10.7004 9.24997H12.3004ZM10.7004 13.75C10.7004 14.1918 11.0585 14.55 11.5004 14.55C11.9422 14.55 12.3004 14.1918 12.3004 13.75H10.7004ZM4.00031 6.29999H16.0003V4.69999H4.00031V6.29999ZM15.2003 5.49999V12.5H16.8003V5.49999H15.2003ZM11.0003 16.7H9.00031V18.3H11.0003V16.7ZM4.80031 12.5V5.49999H3.20031V12.5H4.80031ZM9.00031 16.7C7.79918 16.7 6.97882 16.6983 6.36373 16.6156C5.77165 16.536 5.49093 16.3948 5.29823 16.2021L4.16686 17.3334C4.70639 17.873 5.38104 18.0979 6.15053 18.2013C6.89702 18.3017 7.84442 18.3 9.00031 18.3V16.7ZM3.20031 12.5C3.20031 13.6559 3.19861 14.6033 3.29897 15.3498C3.40243 16.1193 3.62733 16.7939 4.16686 17.3334L5.29823 16.2021C5.10553 16.0094 4.96431 15.7286 4.88471 15.1366C4.80201 14.5215 4.80031 13.7011 4.80031 12.5H3.20031ZM15.2003 12.5C15.2003 13.7011 15.1986 14.5215 15.1159 15.1366C15.0363 15.7286 14.8951 16.0094 14.7024 16.2021L15.8338 17.3334C16.3733 16.7939 16.5982 16.1193 16.7016 15.3498C16.802 14.6033 16.8003 13.6559 16.8003 12.5H15.2003ZM11.0003 18.3C12.1562 18.3 13.1036 18.3017 13.8501 18.2013C14.6196 18.0979 15.2942 17.873 15.8338 17.3334L14.7024 16.2021C14.5097 16.3948 14.229 16.536 13.6369 16.6156C13.0218 16.6983 12.2014 16.7 11.0003 16.7V18.3ZM2.50031 4.69999C2.22572 4.7 2.04405 4.7 1.94475 4.7C1.89511 4.7 1.86604 4.7 1.85624 4.7C1.85471 4.7 1.85206 4.7 1.851 4.7C1.05253 5.50059 1.85233 6.3 1.85256 6.3C1.85273 6.3 1.85297 6.3 1.85327 6.3C1.85385 6.3 1.85472 6.3 1.85587 6.3C1.86047 6.3 1.86972 6.3 1.88345 6.3C1.99328 6.3 2.39045 6.3 2.9906 6.3C4.19091 6.3 6.2032 6.3 8.35279 6.3C10.5024 6.3 12.7893 6.3 14.5387 6.3C15.4135 6.3 16.1539 6.3 16.6756 6.3C16.9364 6.3 17.1426 6.29999 17.2836 6.29999C17.3541 6.29999 17.4083 6.29999 17.4448 6.29999C17.4631 6.29999 17.477 6.29999 17.4863 6.29999C17.4909 6.29999 17.4944 6.29999 17.4968 6.29999C17.498 6.29999 17.4988 6.29999 17.4994 6.29999C17.4997 6.29999 17.4999 6.29999 17.5001 6.29999C17.5002 6.29999 17.5003 6.29999 17.5003 5.49999C17.5003 4.69999 17.5002 4.69999 17.5001 4.69999C17.4999 4.69999 17.4997 4.69999 17.4994 4.69999C17.4988 4.69999 17.498 4.69999 17.4968 4.69999C17.4944 4.69999 17.4909 4.69999 17.4863 4.69999C17.477 4.69999 17.4631 4.69999 17.4448 4.69999C17.4083 4.69999 17.3541 4.69999 17.2836 4.69999C17.1426 4.7 16.9364 4.7 16.6756 4.7C16.1539 4.7 15.4135 4.7 14.5387 4.7C12.7893 4.7 10.5024 4.7 8.35279 4.7C6.2032 4.7 4.19091 4.7 2.9906 4.7C2.39044 4.7 1.99329 4.7 1.88347 4.7C1.86974 4.7 1.86051 4.7 1.85594 4.7C1.8548 4.7 1.85396 4.7 1.85342 4.7C1.85315 4.7 1.85298 4.7 1.85288 4.7C1.85284 4.7 2.65253 5.49941 1.85408 6.3C1.85314 6.3 1.85296 6.3 1.85632 6.3C1.86608 6.3 1.89511 6.3 1.94477 6.3C2.04406 6.3 2.22573 6.3 2.50031 6.29999L2.50031 4.69999ZM7.05028 5.49994V4.16661H5.45028V5.49994H7.05028ZM7.91695 3.29994H12.0836V1.69994H7.91695V3.29994ZM12.9503 4.16661V5.49994H14.5503V4.16661H12.9503ZM12.0836 3.29994C12.5623 3.29994 12.9503 3.68796 12.9503 4.16661H14.5503C14.5503 2.8043 13.4459 1.69994 12.0836 1.69994V3.29994ZM7.05028 4.16661C7.05028 3.68796 7.4383 3.29994 7.91695 3.29994V1.69994C6.55465 1.69994 5.45028 2.8043 5.45028 4.16661H7.05028ZM2.50031 6.29999C4.70481 6.29998 6.40335 6.29998 8.1253 6.29997C9.84725 6.29996 11.5458 6.29995 13.7503 6.29994L13.7503 4.69994C11.5458 4.69995 9.84724 4.69996 8.12529 4.69997C6.40335 4.69998 4.7048 4.69998 2.50031 4.69999L2.50031 6.29999ZM13.7503 6.29994L17.5003 6.29999L17.5003 4.69999L13.7503 4.69994L13.7503 6.29994ZM7.70029 9.24997V13.75H9.30029V9.24997H7.70029ZM10.7004 9.24997V13.75H12.3004V9.24997H10.7004Z" 
                                                            fill=""></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                                
                                              
                                            </div>

                                            <div v-if="course.application_status == 'submitted'">
                                                <!-- Application Status -->
                                                <div class="hidden sm:flex items-center justify-center space-x-2">
                                                    <span class="relative flex h-3 w-3">
                                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primaryColor opacity-75"></span>
                                                        <span class="relative inline-flex rounded-full h-3 w-3 bg-primaryColor"></span>
                                                    </span>
                                                    <p class="font-josefin font-bold tracking-wider text-sm">Submitted</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            


            <div class="w-1/4 m-4">
                <!-- <Link :href="route('start.bio.data')" class="flex items-center gap-2 px-6 py-3 text-white text-xl font-josefin tracking-wider font-bold 
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
                </Link> -->

                <Link :href="route('verify.course.lines')"  class="group relative inline-flex h-[calc(48px+8px)] items-center justify-center font-josefin font-bold tracking-wider rounded-full bg-neutral-950 py-1 pl-6 pr-14 text-neutral-50">
                    <span class="z-10 pr-2">
                        Next step
                    </span>
                    <div class="absolute right-1 inline-flex h-12 w-12 items-center justify-end rounded-full bg-primaryColor transition-[width] group-hover:w-[calc(100%-8px)]">
                            <div class="mr-3.5 flex items-center justify-center">
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-neutral-50">
                                <path d="M8.14645 3.14645C8.34171 2.95118 8.65829 2.95118 8.85355 3.14645L12.8536 7.14645C13.0488 7.34171 13.0488 7.65829 12.8536 7.85355L8.85355 11.8536C8.65829 12.0488 8.34171 12.0488 8.14645 11.8536C7.95118 11.6583 7.95118 11.3417 8.14645 11.1464L11.2929 8H2.5C2.22386 8 2 7.77614 2 7.5C2 7.22386 2.22386 7 2.5 7H11.2929L8.14645 3.85355C7.95118 3.65829 7.95118 3.34171 8.14645 3.14645Z" 
                                    fill="currentColor" fill-rule="evenodd" clip-rule="evenodd">
                                </path>
                            </svg>
                        </div>
                    </div>
                </Link>
            </div>
        </div>


      


    </AuthenticatedLayout>
</template>