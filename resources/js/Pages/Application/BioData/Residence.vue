<script setup>
import { Head, Link, useForm,router } from '@inertiajs/vue3';
import ApplicationLayout from '@/Layouts/ApplicationLayout.vue';
import Notifications from '@/Layouts/Notifications.vue';
import FormInput from '@/Components/FormInput.vue';
import FormInputLabel from '@/Components/FormInputLabel.vue';
import { ref, onMounted, computed } from 'vue';

const props = defineProps({
    residences: Object,
    applicant:Object,
});

const placeOfResidence = ref(props.applicant ? (props.applicant.residence + '..' + props.applicant.residence_description) : null)
const errors = ref({});
const success = ref({});
const form = useForm({
    residence: placeOfResidence,
});

const hasChanged = computed(() => {
    return (
        form.residence !== (placeOfResidence.value ?? null)
    );
});
function submit(){
    if (hasChanged.value == true) {
        router.post('/application/post-residence', form, {
            onError : (allErrors) => {
                for(let error in allErrors){
                errors.value[error] = allErrors[error]
                }
                disableSubmitBtn.value = false;

            
            },

        });
    } else {
        router.visit('/application/marketing');
    }
    

 
}
</script>

<template>
    <Head title="Residence" />
    <ApplicationLayout>
        <div class="flex flex-row space-x-6 items-center">
             <div>
                <div
                    class="inline-block rounded-md h-20 min-h-[1em] w-0.5 self-stretch bg-green-400 dark:bg-white/10"></div>
            </div>
            <div>
                <h1 class="font-monteserat text-xl tracking-wider md:text-4xl">
                    📍 Where Do You Live in Nairobi?
                </h1>
                <p class="font-josefin font-bold text-base sm:text-xl tracking-wider">
                    Let us know — Do you currently live in Nairobi?
                </p>
            </div>

            <div>
                <Notifications :errors="errors" :success="success"/> 
            </div>
        </div>

        <div class="mt-4"> 
            <form action="" method="post" class="flex flex-col space-y-6" @submit.prevent="submit">
                
                <ul class="grid w-full gap-6 md:grid-cols-1 mt-2">
                    <li v-for="residence in residences" :key="residence.Title_Code">
                        <input type="radio" v-model="form.residence" :id="residence.Title_Code" :name="residence.Title_Code" :value="residence.Title_Code + '..' + residence.Description" class="hidden peer" />
                        <label :for="residence.Title_Code" class="inline-flex items-center justify-between w-full p-5 text-gray-500 
                            bg-white border border-gray-200 rounded-lg cursor-pointer  
                            peer-checked:border-primaryColor
                            peer-checked:text-primaryColor hover:text-gray-600 hover:bg-gray-100 
                            dark:text-gray-400 ">                           
                            <div class="block">
                                <div class="w-full text-lg font-semibold">{{ residence.Description }}</div>
                            </div>
                            
                        </label>
                        <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.residence">{{ form.errors.residence }}</div>

                    </li>
                             
                </ul>
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





    </ApplicationLayout>
</template>
