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
let processed = false;
onMounted(async () => {
    if (!processed) {
        try {
            // First operation
            const bioData = await processBioData();
            console.log('Bio data:', bioData);
            
            if (bioData?.success === true) {
                const emergencyData = await processEmergencyContacts(bioData.data.return_value);
                console.log('Emergency data:', emergencyData);

                    if(emergencyData?.success === true){
                        const courseData = await processApplicantCourse(bioData.data.return_value);
                         console.log('Course data:', courseData);

                         if(courseData?.success === true){
                            const applicationConversion = await processApplicationConversion(bioData.data.return_value);
                            console.log('Conversion data:', applicationConversion);
                            if(applicationConversion?.success === true){
                                router.visit('/payments/amount-payable')

                            }
                            // return;
                         }
                    }
            }
            
            processed = true;
        } catch (error) {
            console.error('Error in onMounted:', error);
        }
    }
});

// Simplified functions that just handle their specific logic
async function processBioData() {
    const response = await fetch('/application/processing-bio-data');
    return await response.json();
}

async function processEmergencyContacts(applicantNo) {
    const response = await fetch(`/application/processing-emergency-contacts/${applicantNo}`);
    return await response.json();
}
async function processApplicantCourse(applicantNo) {
    const response = await fetch(`/application/processing-applicant-coourse/${applicantNo}`);
    return await response.json();
}

async function processApplicationConversion(applicantNo) {
    const response = await fetch(`/application/processing-converting-application/${applicantNo}`);
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
            </div>

            <div>
                <Notifications :errors="errors" :success="success"/> 
            </div>
        </div>
        
        <section class="flex w-full mt-6">
            <!-- Header -->
            <div>
                <h1>
                    
                </h1>
            </div>


            <div>
                <ul>
                    <li>
                        <h2>
                            Processing Bio Data
                        </h2>
                        <p>
                            Verifying your personal information.
                        </p>
                    </li>
                    <li>
                        <h2>
                            Processing Emergency Contacts
                        </h2>
                        <p>
                            Reviewing emergency contact details.
                        </p>
                    </li>

                    <li>
                        <h2>
                            Processing Applicant Course
                        </h2>
                        <p>
                            Finalizing your course selection.
                        </p>
                    </li>
                    <li>
                        <h2>
                            Converting Applicant to Student
                        </h2>
                        <p>
                            Completing your registration.
                        </p>
                    </li>
                </ul>
            </div>
        </section>

        
       


    </AuthenticatedLayout>
</template>