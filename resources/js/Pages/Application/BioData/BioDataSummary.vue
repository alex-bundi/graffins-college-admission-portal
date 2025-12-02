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

console.log(props.emergencyContact);
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


        <div class="m-8 bg-white p-10 rounded-lg">
            <!-- Header -->
            <div class="flex items-center space-x-6">
                <div>
                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w0NzEyNjZ8MHwxfHNlYXJjaHwyfHxhdmF0YXJ8ZW58MHwwfHx8MTY5MTg0NzYxMHww&ixlib=rb-4.0.3&q=80&w=1080"
                        class="w-32 group-hover:w-36 group-hover:h-36 h-32 object-center object-cover rounded-full transition-all duration-500 delay-500 transform"
                    />
                </div>

                <div class="font-josefin font-bold text-base  tracking-wider">
                    <h2>
                        {{ applicantData.first_name + ' ' + applicantData.second_name + ' ' + applicantData.last_name }}
                    </h2>

                    <p class="mt-3">
                        {{ applicantData.nationality }}
                    </p>
                </div>
            </div>


            <div class="mt-6">
                <div>
                    <h1 class="font-monteserat text-base tracking-wider md:text-2xl">
                        General
                    </h1>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-6 mt-4">
                    <div>
                        <h3 class="font-monteserat text-base tracking-wider ">
                            Phone Number
                        </h3>
                        <p class="bg-gray-300 rounded-md p-6 w-full font-josefin font-bold text-base  tracking-wider">
                            {{ applicantData.phone_no }}
                        </p>
                    </div>

                    <div>
                        <h3 class="font-monteserat text-base tracking-wider ">
                           📧  Email Address
                        </h3>
                        <p class="bg-gray-300 rounded-md p-6 w-full font-josefin font-bold text-base  tracking-wider">
                            {{ applicantData.email }}
                        </p>
                    </div>

                    <div>
                        <h3 class="font-monteserat text-base tracking-wider ">
                            📍 Lives in Nairobi: 
                        </h3>
                        <p class="bg-gray-300 rounded-md p-6 w-full font-josefin font-bold text-base  tracking-wider">
                            {{ applicantData.residence_description }}
                        </p>
                    </div>


                </div>
            </div>

             <div class="mt-6">
                <div>
                    <h1 class="font-monteserat text-base tracking-wider md:text-2xl">
                        Other
                    </h1>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-6 mt-4">
                    <div>
                        <h3 class="font-monteserat text-base tracking-wider ">
                            📣 Heard About Us From: 
                        </h3>
                        <p class="bg-gray-300 rounded-md p-6 w-full font-josefin font-bold text-base  tracking-wider">
                            {{ applicantData.marketing_description }}
                        </p>
                    </div>

                    <div>
                        <h3 class="font-monteserat text-base tracking-wider ">
                            ⚠️ Allergies: 
                        </h3>
                        <p class="bg-gray-300 rounded-md p-6 w-full font-josefin font-bold text-base  tracking-wider">
                            {{ applicantData.allergies == 1 ? 'Yes' : 'No' }}
                        </p>
                    </div>


                </div>
            </div>

            <div class="mt-6">
                <div>
                    <h1 class="font-monteserat text-base tracking-wider md:text-2xl">
                        Emergency Contact
                    </h1>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-6 mt-4">
                    <div>
                        <h3 class="font-monteserat text-base tracking-wider ">
                            🚨 Full Name:
                        </h3>
                        <p class="bg-gray-300 rounded-md p-6 w-full font-josefin font-bold text-base  tracking-wider">
                            {{ emergencyContact.full_name }}
                        </p>
                    </div>

                    <div>
                        <h3 class="font-monteserat text-base tracking-wider ">
                             📞 Phone No: 
                        </h3>
                        <p class="bg-gray-300 rounded-md p-6 w-full font-josefin font-bold text-base  tracking-wider">
                            {{ emergencyContact.phone_no }}
                        </p>
                    </div>


                </div>
            </div>

            <div>
                <hr class="w-48 h-1 mx-auto my-4 bg-primaryColor border-0 rounded-sm md:my-10"></hr>
            </div>

            <div class="mt-6">
                <div>
                    <h1 class="font-monteserat text-base tracking-wider md:text-2xl">
                        Actions
                    </h1>
                </div>

                <div class="grid grid-cols-1 place-content-between md:grid-cols-2 md:gap-6 mt-4">
                    <div>
                        <form action="" method="post">
                            <button :href="route('verify.course.lines')"  class="group relative inline-flex h-[calc(48px+8px)] items-center justify-center font-josefin font-bold tracking-wider rounded-full bg-neutral-950 py-1 pl-6 pr-14 text-neutral-50">
                                <span class="z-10 pr-2">
                                    Confirm
                                </span>
                                <div class="absolute right-1 inline-flex h-12 w-12 items-center justify-end rounded-full bg-primaryColor transition-[width] group-hover:w-[calc(100%-8px)]">
                                        <div class="mr-3.5 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                                            </svg>

                                    </div>
                                </div>
                            </button>
                        </form>
                        
                    </div>

                    <div>
                        <Link>
                            <div>
                                <div>
                                    <button class="flex space-x-3 p-2.5 bg-yellow-500 rounded-xl font-josefin font-bold tracking-wider hover:rounded-3xl hover:bg-yellow-600 transition-all duration-300 text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        <span>
                                            Edit
                                        </span>
                                    </button>
                                    
                                </div>
                            </div>
                        </Link>
                    </div>


                </div>
            </div>
        </div>


    </AuthenticatedLayout>
</template>