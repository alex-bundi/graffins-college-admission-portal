<script setup>
import { onMounted, ref } from 'vue';
import { Head, Link, useForm,router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Notifications from '@/Layouts/Notifications.vue';


const errors = ref({});
const success = ref({});
let processed = false;
onMounted(async () => {
    if (!processed) {
        try {
            // First operation
            const bioData = await processBioData();
            console.log('Bio data:', bioData);
            
            // Second operation (dependent on first)
            if (bioData?.success === true) {
                const emergencyData = await processEmergencyContacts(bioData.data.return_value);
                console.log('Emergency data:', emergencyData);

                    if(emergencyData?.success === true){
                        const courseData = await processApplicantCourse(bioData.data.return_value);
                         console.log('Course data:', courseData);

                         if(courseData?.success === true){
                            const applicationConversion = await processApplicationConversion(bioData.data.return_value);
                            console.log('Conversion data:', applicationConversion);
                            return;
                            router.visit('/payments/amount-payable')
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
        <div>
                <Notifications :errors="errors" :success="success"/> 
        </div>
        
        <section class="flex w-full mt-6">
            <!-- Header -->
            <div>
                <h1>
                    Application Processing...
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