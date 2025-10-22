<script setup>
import { onMounted, ref } from 'vue';
import { Head, Link, useForm,router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Notifications from '@/Layouts/Notifications.vue';
import StepperComponent from '@/Layouts/Stepper.vue';


const props = defineProps({
   
    completedSteps: {
        type: Array,
        default: () => []
    },
});

const errors = ref({}); 
const success = ref({});
let processed = ref(false);

// Application steps
const personalInfo = ref(false);
const emergencyContactsInfo = ref(false);
const courseInfo = ref(false);
const convertingApplication = ref(false)
const isRegistrationComplete = ref(false)

onMounted(async () => {
    if (!processed.value) {
        try {
            // First operation
            const bioData = await processBioData();
            console.log('Bio data:', bioData);
            
            if (bioData?.success === true) {
                personalInfo.value = true;

                const emergencyData = await processEmergencyContacts(bioData.data.return_value);
                console.log('Emergency data:', emergencyData);
                if(emergencyData?.success === true){
                    emergencyContactsInfo.value = true;

                    const courseData = await processApplicantCourse(bioData.data.return_value);
                    console.log('Course data:', courseData);
                    if(courseData?.success === true){
                        courseInfo.value = true;

                        const applicationConversion = await processApplicationConversion(bioData.data.return_value);
                        console.log('Conversion data:', applicationConversion);
                        if(applicationConversion?.success === true){
                            convertingApplication.value = true;
                            isRegistrationComplete.value = true;
                            router.visit('/payments/amount-payable')

                        }else {
                            errors.value.message = applicationConversion.message;
                            return;
                        }


                    }else {
                        errors.value.message = courseData.message;
                        return;
                    }

                }else {
                    errors.value.message = emergencyData.message;
                    return;
                }
            } else if(bioData.error === true){
                errors.value.message = bioData.message;
                return;
            }
            
            processed.value = true;
        } catch (error) {
            errors.value.error = error;
            console.error('Error is :', error);
        }
    }
});



// Simplified functions that just handle their specific logic
async function processBioData() {
    const response = await fetch('/application/processing-bio-data');
    return await response.json();
}

async function processEmergencyContacts(applicantNo) {
    const response = await fetch(`/application/processing-emergency-contacts`);
    return await response.json();
}
async function processApplicantCourse(applicantNo) {
    const response = await fetch(`/application/processing-applicant-course`);
    return await response.json();
}

async function processApplicationConversion(applicantNo) {
    const response = await fetch(`/application/processing-converting-application`);
    return await response.json();
}


</script>

<template>
    <Head title="Last Step" />
    <AuthenticatedLayout>

         <StepperComponent :completed-steps="completedSteps" />

        <div>
            <Notifications :errors="errors" :success="success"/> 
        </div>

        <div class="flex flex-row space-x-6 items-center">
             <div>
                <div
                    class="inline-block rounded-md h-20 min-h-[1em] w-0.5 self-stretch bg-green-400 dark:bg-white/10"></div>
            </div>
            <div>
                <h1 class="font-monteserat text-xl tracking-wider md:text-4xl">
                    Application Processing...
                </h1>
                 <p class="font-josefin text-red-500 tracking-wider font-bold mt-4 text-base">
                    Please Don’t refresh the page — the page will redirect automatically.
                </p>
            </div>
        </div>
        
        <section class="flex w-full mt-6">
          

           <div class="m-8">
                
                <ol class="items-center sm:flex sm:space-x-6">
                    <li class="relative mb-6 sm:mb-0 h-full">
                        <div :class="[personalInfo ? 'text-primaryColor' : 'text-amber-400']" 
                            class="flex items-center">
                            <div 
                                class="z-10 flex items-center justify-center w-6 h-6  rounded-full ring-0 ring-white  sm:ring-8 shrink-0">
                                <svg class="w-2.5 h-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                </svg>
                            </div>
                            <div :class="[personalInfo ? 'bg-black' : 'bg-amber-400']" 
                             class="hidden sm:flex w-full  h-0.5"></div>
                        </div>
                        <div  class="mt-3 sm:pe-8">
                            <h3 :class="[personalInfo ? 'text-primaryColor' : 'text-amber-500']"  
                                class="text-lg font-semibold  tracking-wider 
                                font-monteserat">Processing Bio Data</h3>
                            <p class="text-base font-bold font-josefin tracking-wider text-gray-500">Verifying your personal information.</p>
                        </div>
                    </li>

                    <li class="relative mb-6 sm:mb-0">
                        <div :class="[emergencyContactsInfo ? 'text-primaryColor' : 'text-amber-400']"
                            class="flex items-center">
                            <div class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-white  sm:ring-8 shrink-0">
                                <svg class="w-2.5 h-2.5 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                </svg>
                            </div>
                            <div :class="[emergencyContactsInfo ? 'bg-black' : 'bg-amber-400']" 
                                class="hidden sm:flex w-full  h-0.5 "></div>
                        </div>
                        <div class="mt-3 sm:pe-8">
                            <h3 :class="[emergencyContactsInfo ? 'text-primaryColor' : 'text-amber-500']"  
                                class="text-lg font-semibold tracking-wider  font-monteserat">Processing Emergency Contacts</h3>
                            <p class="text-base font-bold font-josefin tracking-wider text-gray-500 ">Reviewing emergency contact details.</p>
                        </div>
                    </li>

                    <li class="relative mb-6 sm:mb-0">
                        <div :class="[courseInfo ? 'text-primaryColor' : 'text-amber-400']"
                            class="flex items-center">
                            <div class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-white  sm:ring-8 shrink-0">
                                <svg class="w-2.5 h-2.5 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                </svg>
                            </div>
                            <div :class="[courseInfo ? 'bg-black' : 'bg-amber-400']" 
                                class="hidden sm:flex w-full  h-0.5 "></div>
                        </div>
                        <div class="mt-3 sm:pe-8">
                            <h3 :class="[courseInfo ? 'text-primaryColor' : 'text-amber-500']"
                                class="text-lg font-semibold  tracking-wider font-monteserat">
                                Processing Applicant Course</h3>
                            <p class="text-base font-bold font-josefin tracking-wider text-gray-500 dark:text-gray-400">Finalizing your course selection.</p>
                        </div>
                    </li>

                    <li class="relative mb-6 sm:mb-0">
                        <div :class="[convertingApplication ? 'text-primaryColor' : 'text-amber-400']"
                            class="flex items-center">
                            <div class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-white  sm:ring-8 shrink-0">
                                <svg class="w-2.5 h-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                </svg>
                            </div>
                            <div :class="[convertingApplication ? 'bg-black' : 'bg-amber-400']" 
                                class="hidden sm:flex w-full  h-0.5"></div>
                        </div>
                        <div class="mt-3 sm:pe-8">
                            <h3 :class="[convertingApplication ? 'text-primaryColor' : 'text-amber-500']"
                                class="text-lg font-semibold tracking-wider  font-monteserat">Converting Applicant to Student</h3>
                            <p class="text-base font-bold font-josefin tracking-wider text-gray-500 ">Completing your registration.</p>
                        </div>
                    </li>

                    <li class="relative mb-6 sm:mb-0">
                        <div :class="[isRegistrationComplete ? 'text-primaryColor' : 'text-amber-400']" class="flex items-center">
                            <div class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-white  sm:ring-8 shrink-0">
                                <svg class="w-2.5 h-2.5 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                </svg>
                            </div>
                            <div :class="[isRegistrationComplete ? 'bg-black' : 'bg-amber-400']" 
                                class="hidden sm:flex w-full  h-0.5 "></div>
                        </div>
                        <div class="mt-3 sm:pe-8">
                            <h3 :class="[isRegistrationComplete ? 'text-primaryColor' : 'text-amber-500']"
                                class="text-lg font-semibold tracking-wider font-monteserat">Redirecting to next page</h3>
                            <p class="text-base font-bold font-josefin tracking-wider text-gray-500 dark:text-gray-400">Registration Complete</p>
                        </div>
                    </li>

                </ol>


            </div>
        </section>

        
       


    </AuthenticatedLayout>
</template>