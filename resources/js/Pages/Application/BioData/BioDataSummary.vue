<script setup>
import { ref } from 'vue';
import { Head, Link, useForm,router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Notifications from '@/Layouts/Notifications.vue';
import FormInput from '@/Components/FormInput.vue';
import FormInputLabel from '@/Components/FormInputLabel.vue';
import StepperComponent from '@/Layouts/Stepper.vue';

const props = defineProps({
    applicantData: Object,
    emergencyContact: Object,
    completedSteps: {
        type: Array,
        default: () => []
    },
});
const errors = ref({});
const success = ref({});
const form = useForm({
    personalDataSummary: '',

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
const disableSubmitBtn = ref(false);


function confirmPersonalData(){
     disableSubmitBtn.value = true;

    form.personalDataSummary ='submitted';
    router.post('/application/post-personal-information-summary', form, {
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
    <Head title="Personal Information Summary" />
    <AuthenticatedLayout>
         <StepperComponent :completed-steps="completedSteps" />
        <div class="flex flex-row space-x-6 items-center">
             <div>
                <div
                    class="inline-block rounded-md h-20 min-h-[1em] w-0.5 self-stretch bg-green-400 dark:bg-white/10"></div>
            </div>
            <div>
                <h1 class="font-monteserat text-xl tracking-wider md:text-4xl">
                    📋 Personal Information Summary
                </h1>

                <p class="font-josefin font-bold text-base sm:text-xl tracking-wider">
                    Here’s what you’ve shared with us:
                </p>
            </div>
        </div>

        <div>
                <Notifications :errors="errors" :success="success"/> 
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-12"> 
            <div class="flex flex-col space-y-3">
                <div class="flex flex-row space-x-3">
                    <h3 class="font-monteserat text-base tracking-wider ">
                        👤 Full Name: 
                    </h3>
                    <p class="font-josefin font-bold text-base  tracking-wider">
                       {{ applicantData.first_name + ' ' + applicantData.second_name + ' ' + applicantData.last_name }}
                    </p>
                </div>

                <div class="flex flex-row space-x-3">
                    <h3 class="font-monteserat text-base tracking-wider ">
                        📞 Phone Number: 
                    </h3>
                    <p class="font-josefin font-bold text-base  tracking-wider">
                       {{ applicantData.phone_no }}
                    </p>
                </div>

                <div class="flex flex-row space-x-3">
                    <h3 class="font-monteserat text-base tracking-wider ">
                        🌍 Nationality: 
                    </h3>
                    <p class="font-josefin font-bold text-base  tracking-wider">
                        {{ applicantData.nationality }}
                    </p>
                </div>

                <div class="flex flex-row space-x-3">
                    <h3 class="font-monteserat text-base tracking-wider ">
                        📧 Email Address: 
                    </h3>
                    <p class="font-josefin font-bold text-base  tracking-wider">
                        {{ applicantData.email }}
                    </p>
                </div>

                <div class="flex flex-row space-x-3">
                    <h3 class="font-monteserat text-base tracking-wider ">
                        📍 Lives in Nairobi: 
                    </h3>
                    <p class="font-josefin font-bold text-base  tracking-wider">
                        {{ applicantData.residence_description }}
                    </p>
                </div>

                <div class="flex flex-row space-x-3">
                    <h3 class="font-monteserat text-base tracking-wider ">
                        📣 Heard About Us From: 
                    </h3>
                    <p class="font-josefin font-bold text-base  tracking-wider">
                        {{ applicantData.marketing_description }}
                    </p>
                </div>
                <div class="flex flex-row space-x-3">
                    <h3 class="font-monteserat text-base tracking-wider ">
                        ⚠️ Allergies: 
                    </h3>
                    <p class="font-josefin font-bold text-base  tracking-wider">
                         {{ applicantData.allergies == 1 ? 'Yes' : 'No' }}
                    </p>
                </div>

                <div class="flex flex-row space-x-3">
                    <h3 class="font-monteserat text-base tracking-wider ">
                        🚨 Emergency Contact:
                    </h3>
                    <p class="font-josefin font-bold text-base  tracking-wider">
                       {{ emergencyContact.full_name }}
                    </p>
                </div>

                <div class="flex flex-row space-x-3">
                    <h3 class="font-monteserat text-base tracking-wider ">
                        📎 Uploaded ID/Passport: 
                    </h3>
                    <p class="font-josefin font-bold text-base  tracking-wider">
                       ✅ Received

                    </p>
                </div>
            </div>

            <div class="flex flex-col space-y-3">
                <div class="flex flex-col space-y-3 items-center">
                    <p class="font-josefin font-bold text-base  tracking-wider">
                        If all details are correct,  Click <span class="font-monteserat tracking-wider font-bold">“Confirm” </span> to proceed.
                    </p>

                    <div>
                        <form action="" @submit.prevent="confirmPersonalData">
                            <input class="hidden" type="text" v-model="form.personalDataSummary" name="personalDataSummary" id="personalDataSummary" value="submitted">

                            <button :class="{'cursor-not-allowed' : disableSubmitBtn}"  class="text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
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

           
        </div>


    </AuthenticatedLayout>
</template>