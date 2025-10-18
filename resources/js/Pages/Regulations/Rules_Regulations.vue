<script setup>
import { ref } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Notifications from '@/Layouts/Notifications.vue';
import StepperComponent from '@/Layouts/Stepper.vue';

const props = defineProps({
    completedSteps: {
        type: Array,
        default: () => []
    }
});

const errors = ref({});
const success = ref({});
const currentSection = ref(1);
const totalSections = 6;

const form = useForm({
    accepted: false,
});
const nextSection = () => {
    if (currentSection.value < totalSections) {
        currentSection.value++;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    } else {
        form.accepted = true;
        submit();
    }
};

const previousSection = () => {
    if (currentSection.value > 1) {
        currentSection.value--;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
};


function submit(){

    router.post('/regulations/post-rules-regulations', form, {
        onError : (allErrors) => {
            for(let error in allErrors){
            errors.value[error] = allErrors[error]
            }
            
        },

    });
 
}
</script>

<template>
    <Head title="Rules & Regulations" />
    <AuthenticatedLayout>
        <StepperComponent :completed-steps="completedSteps" />
        <div>
            <Notifications :errors="errors" :success="success"/> 
        </div>
        <div class="flex flex-row space-x-6 items-center">
            <div>
                <div class="inline-block rounded-md h-20 min-h-[1em] w-0.5 self-stretch bg-green-400 dark:bg-white/10"></div>
            </div>
            <div>
                <h1 class="font-monteserat text-xl tracking-wider md:text-4xl">
                    📘 Please Review & Accept the College Rules
                </h1>
                <p class="font-josefin font-bold text-base sm:text-xl tracking-wider">
                    Before continuing, kindly confirm that you've read and agreed to follow the Graffins College Rules & Regulations.
                </p>
            </div>
        </div>

        <!-- Progress Indicator -->
        <div class="mt-6 mb-4">
            <div class="flex justify-between mb-2">
                <span class="font-josefin text-sm font-medium text-gray-600">
                    Section {{ currentSection }} of {{ totalSections }}
                </span>
                <span class="font-josefin text-sm font-medium text-gray-600">
                    {{ Math.round((currentSection / totalSections) * 100) }}%
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div 
                    class="bg-green-500 h-2 rounded-full transition-all duration-300"
                    :style="{ width: (currentSection / totalSections * 100) + '%' }"
                ></div>
            </div>
        </div>

        <!-- Section 1: Tuition & Fees -->
        <div v-if="currentSection === 1" class="mt-4">
            <h2 class="font-monteserat text-base tracking-wider md:text-2xl">
                📘 Section 1: Tuition & Fees
            </h2>
            <div class="mt-4">
                <ul>
                    <li>
                        <p class="font-josefin font-bold text-base tracking-wider">
                            <span class="font-monteserat font-extrabold">Tuition Fees</span> must be paid at registration.
                        </p>
                    </li>
                    <li class="mt-2">
                        <p class="font-josefin font-bold text-base tracking-wider">
                            <span class="font-monteserat font-extrabold">Payment Plans</span> are available upon request.
                        </p>
                    </li>
                    <li class="mt-2">
                        <p class="font-josefin font-bold text-base tracking-wider">
                            <span class="font-monteserat font-extrabold">Late Payments</span> may incur additional fees.
                        </p>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Section 2: Dress Code & Campus Conduct -->
        <div v-if="currentSection === 2" class="mt-4">
            <h2 class="font-monteserat text-base tracking-wider md:text-2xl">
                📘 Section 2: Dress Code & Campus Conduct
            </h2>
            <div class="mt-4">
                <ul>
                    <li>
                        <p class="font-josefin font-bold text-base tracking-wider">
                            <span class="font-monteserat font-extrabold">Dress Code: </span> 
                            Modest attire only.
                        </p>
                        <ul class="flex flex-col space-y-4 p-4 font-josefin text-sm tracking-wider">
                            <li>
                                - No hats, caps, or low-neck clothing.
                            </li>
                            <li>
                                - Smoking is strictly prohibited on campus.
                            </li>
                            <li>
                                - Phones off during class.
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Section 3: Misconduct -->
        <div v-if="currentSection === 3" class="mt-4">
            <h2 class="font-monteserat text-base tracking-wider md:text-2xl">
                📘 Section 3: Misconduct & Disciplinary Actions
            </h2>
            <div class="mt-4">
                <ul>
                    <li>
                        <p class="font-josefin font-bold text-base tracking-wider">
                            <span class="font-monteserat font-extrabold">Misconduct or criminal acts</span> 
                            (e.g., theft, forgery) may result in suspension or expulsion.
                        </p>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Section 4: Liability -->
        <div v-if="currentSection === 4" class="mt-4">
            <h2 class="font-monteserat text-base tracking-wider md:text-2xl">
                📘 Section 4: Liability
            </h2>
            <div class="mt-4">
                <ul>
                    <li>
                        <p class="font-josefin font-bold text-base tracking-wider">
                            The college is  
                            <span class="font-monteserat font-extrabold">not liable</span> 
                            for injuries, loss, or damages on campus.
                        </p>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Section 5: Media Consent -->
        <div v-if="currentSection === 5" class="mt-4">
            <h2 class="font-monteserat text-base tracking-wider md:text-2xl">
                📘 Section 5: Media Consent
            </h2>
            <div class="mt-4">
                <ul>
                    <li>
                        <p class="font-josefin font-bold text-base tracking-wider">
                            By registering, students consent to the use of their 
                            <span class="font-monteserat font-extrabold">image, voice, or likeness</span> 
                            in educational media without compensation.
                        </p>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Section 6: Declaration -->
        <div v-if="currentSection === 6" class="mt-4">
            <h2 class="font-monteserat text-base tracking-wider md:text-2xl">
                📝 Declaration:
            </h2>
            <div class="mt-4">
                
                <ul>
                    <li>
                        <p class="font-josefin font-bold text-base tracking-wider">
                            I have read, understood, and agree to abide by the 
                            <span class="font-monteserat font-extrabold">Rules & Regulations of Graffins College.</span> 
                        </p>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex justify-between mt-8">
            <div>
                <button
                    v-if="currentSection > 1"
                    @click="previousSection"
                    class="flex items-center gap-2 px-6 py-3 text-white text-xl font-josefin tracking-wider font-bold 
                           rounded-full shadow-md 
                           bg-gradient-to-b from-gray-400 to-gray-500 
                           hover:from-gray-500 hover:to-gray-600 
                           active:scale-95 transition">
                    <span class="flex rotate-180">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="M13.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 0 1-1.06-1.06L11.69 12 4.72 5.03a.75.75 0 0 1 1.06-1.06l7.5 7.5Z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M19.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 1 1-1.06-1.06L17.69 12l-6.97-6.97a.75.75 0 0 1 1.06-1.06l7.5 7.5Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    PREVIOUS
                </button>
            </div>

            <div>
                <button
                    @click="nextSection"
                    class="flex items-center gap-2 px-6 py-3 text-white text-xl font-josefin tracking-wider font-bold 
                           rounded-full shadow-md 
                           bg-gradient-to-b from-lime-400 to-green-500 
                           hover:from-lime-500 hover:to-green-600 
                           active:scale-95 transition">
                    {{ currentSection === totalSections ? 'ACCEPT & CONTINUE' : 'NEXT' }}
                    <span class="flex">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="M13.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 0 1-1.06-1.06L11.69 12 4.72 5.03a.75.75 0 0 1 1.06-1.06l7.5 7.5Z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M19.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 1 1-1.06-1.06L17.69 12l-6.97-6.97a.75.75 0 0 1 1.06-1.06l7.5 7.5Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </button>
                
            </div>
        </div>
    </AuthenticatedLayout>
</template>