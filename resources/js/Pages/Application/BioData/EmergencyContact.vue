<script setup>
import { Head, Link, useForm,router } from '@inertiajs/vue3';
import ApplicationLayout from '@/Layouts/ApplicationLayout.vue';
import Notifications from '@/Layouts/Notifications.vue';
import FormInput from '@/Components/FormInput.vue';
import FormInputLabel from '@/Components/FormInputLabel.vue';
import { ref, onMounted, computed } from 'vue';

const props = defineProps({
    emergencyContact: Object,
});
console.log(props.emergencyContact)
let relationshipStatus = ref(null);
if((props.emergencyContact != null) && (props.emergencyContact.relationship == 1)){
    relationshipStatus = 'parent';
} else if ((props.emergencyContact != null) && (props.emergencyContact.relationship == 3)){
    relationshipStatus = 'sibling';
}else if ((props.emergencyContact != null) && (props.emergencyContact.relationship == 4)){
    relationshipStatus = 'relative';
}else if ((props.emergencyContact != null) && (props.emergencyContact.relationship == 5)){
    relationshipStatus = 'spouse';

}

const errors = ref({});
const success = ref({});
const form = useForm({
    fullName: props.emergencyContact ? props.emergencyContact.full_name : null,
    relationship: relationshipStatus,
    phoneNo: props.emergencyContact ? props.emergencyContact.phone_no : null,
});

const hasChanged = computed(() => {
    return (
        
        form.fullName !== (props.emergencyContact?.full_name ?? null) ||
        form.relationship !== (relationshipStatus ?? null) ||
        form.phoneNo !== (props.emergencyContact?.phone_no ?? null)
    );
});


function submit(){

    if (hasChanged.value == true) {
        router.post('/application/post-emergency-contact', form, {
            onError : (allErrors) => {
                for(let error in allErrors){
                errors.value[error] = allErrors[error]
                }

            
            },

        });
    } else {
        router.visit('/application/upload-id-passport');


        
    }
   

    

 
}
</script>

<template>
    <Head title="Emergency Contact" />
    <ApplicationLayout>
        <div class="flex flex-row space-x-6 items-center">
             <div>
                <div
                    class="inline-block rounded-md h-20 min-h-[1em] w-0.5 self-stretch bg-green-400 dark:bg-white/10"></div>
            </div>
            <div>
                <h1 class="font-monteserat text-xl tracking-wider md:text-4xl">
                    🚨 Emergency Contact
                </h1>
                <p class="font-josefin font-bold text-base sm:text-xl tracking-wider">
                    Please provide the following:
                </p>
            </div>

            <div>
                <Notifications :errors="errors" :success="success"/> 
            </div>
        </div>

        <div class="mt-4"> 
            <form action="" method="post" class="flex flex-col space-y-6" @submit.prevent="submit">
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="max-w-sm" >
                        <div class="flex flex-row space-x-2">
                            <FormInputLabel for-input="full_name" label-name="Full Name" class="" />
                            <span class="font-josefin tracking-wider font-bold text-base text-red-500">
                                *
                            </span>
                        </div>
                        <FormInput 
                            type="text"
                            id="full_name"
                            v-model="form.fullName"
                            class="py-2.5 sm:py-3 px-4 block w-full font-josefin font-bold tracking-wider"
                            
                            required/> 
                            
                        <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.fullName">{{ form.errors.fullName    }}</div>
                        
                    </div>

                    
                    <div class="max-w-sm" >
                        <div class="flex flex-row space-x-2">
                            <FormInputLabel for-input="phone_no" label-name="Phone No" class="" />
                            <span class="font-josefin tracking-wider font-bold text-base text-red-500">
                                *
                            </span>
                        </div>
                        <FormInput 
                            type="text"
                            id="phone_no"
                            v-model="form.phoneNo"
                            class="py-2.5 sm:py-3 px-4 block w-full font-josefin font-bold tracking-wider"
                            
                            required/> 
                            
                        <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.phoneNo">{{ form.errors.phoneNo    }}</div>
                        
                    </div>
  
                </div>
                <div>
                    <div class="max-w-sm" >
                        <div class="flex flex-row space-x-2">
                            <FormInputLabel for-input="relationship" label-name="Relationship" class="" />
                            <span class="font-josefin tracking-wider font-bold text-base text-red-500">
                                *
                            </span>
                        </div>
                         <ul class="grid w-full grid-cols-1 gap-6 md:grid-cols-3 mt-2">
                            <li  >
                                <input type="radio" v-model="form.relationship" id="parent" name="parent" value="parent" class="hidden peer" />
                                <label for="parent" class="inline-flex items-center justify-between w-full p-5 text-gray-500 
                                    bg-white border border-gray-200 rounded-lg cursor-pointer  
                                    peer-checked:border-primaryColor
                                    peer-checked:text-primaryColor hover:text-gray-600 hover:bg-gray-100 
                                    dark:text-gray-400 ">                           
                                    <div class="block">
                                        <div class="w-full text-lg font-semibold">Parent</div>
                                    </div>
                                    
                                </label>

                            </li>

                            <li>
                                <input type="radio" v-model="form.relationship" id="sibling" name="sibling" value="sibling" class="hidden peer" />
                                <label for="sibling" class="inline-flex items-center justify-between w-full p-5 text-gray-500 
                                    bg-white border border-gray-200 rounded-lg cursor-pointer  
                                    peer-checked:border-primaryColor
                                    peer-checked:text-primaryColor hover:text-gray-600 hover:bg-gray-100 
                                    dark:text-gray-400 ">                           
                                    <div class="block">
                                        <div class="w-full text-lg font-semibold">Sibling</div>
                                    </div>
                                    
                                </label>

                            </li>
                            
                            <li>
                                <input type="radio" v-model="form.relationship" id="relative" name="relative" value="relative" class="hidden peer" />
                                <label for="relative" class="inline-flex items-center justify-between w-full p-5 text-gray-500 
                                    bg-white border border-gray-200 rounded-lg cursor-pointer  
                                    peer-checked:border-primaryColor
                                    peer-checked:text-primaryColor hover:text-gray-600 hover:bg-gray-100 
                                    dark:text-gray-400 ">                           
                                    <div class="block">
                                        <div class="w-full text-lg font-semibold">Relative</div>
                                    </div>
                                    
                                </label>

                            </li> 
                            <li>
                                <input type="radio" v-model="form.relationship" id="spouse" name="spouse" value="spouse" class="hidden peer" />
                                <label for="spouse" class="inline-flex items-center justify-between w-full p-5 text-gray-500 
                                    bg-white border border-gray-200 rounded-lg cursor-pointer  
                                    peer-checked:border-primaryColor
                                    peer-checked:text-primaryColor hover:text-gray-600 hover:bg-gray-100 
                                    dark:text-gray-400 ">                           
                                    <div class="block">
                                        <div class="w-full text-lg font-semibold">Spouse</div>
                                    </div>
                                    
                                </label>

                            </li> 
                                    
                        </ul>
                       
                        <!-- <FormInput 
                            type="text"
                            id="relationship"
                            v-model="form.relationship"
                            class="py-2.5 sm:py-3 px-4 block w-full font-josefin font-bold tracking-wider"
                            
                            required/>  -->
                            
                        <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.relationship">{{ form.errors.relationship    }}</div>
                        
                    </div>
                </div>

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
