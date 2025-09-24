<script setup>
import { onMounted, ref } from 'vue';
import { Head, Link, useForm,router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Notifications from '@/Layouts/Notifications.vue';


const errors = ref({});
const success = ref({});
let processed = false;
onMounted(async () => {
    // Process Bio data
    if (!processed) {
        let x = processBioData();
        console.log(x)
        processed = true;
    }
    
});

async function processBioData(){
    try {
        const response = await fetch('/application/processing-bio-data');
        const data = await response.json();
        if(data){
            if((data.success === true)){
                processEmergencyContacts(data.data.return_value);
            }
        }
        return data;
    } catch (error) {
        console.error('Error fetching data:', error);
        // console.error(error.response?.data || error.message);
    }
}

async function processEmergencyContacts(applicantNo){
    try {
        const response = await fetch(`/application/processing-emergency-contacts/${applicantNo}`);
        const data = await response.json();
    } catch (error) {
        console.error('Error fetching data:', error);
    }
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