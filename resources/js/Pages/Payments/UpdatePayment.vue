<script setup>
import { Head, Link, useForm,router } from '@inertiajs/vue3';
import ApplicationLayout from '@/Layouts/ApplicationLayout.vue';
import Notifications from '@/Layouts/Notifications.vue';
import FormInput from '@/Components/FormInput.vue';
import FormInputLabel from '@/Components/FormInputLabel.vue';
import { ref, onMounted } from 'vue';

const errors = ref({});
const success = ref({});
const form = useForm({
    amountPaid: null,
    datePaid: '',
    modeOfPayment: '',
    paymentReference: '',
});

function submit(){


    router.post('/post-register', form, {
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
    <Head title="Update Payment" />
    <ApplicationLayout>
        <div class="flex flex-row space-x-6 items-center">
             <div>
                <div
                    class="inline-block rounded-md h-20 min-h-[1em] w-0.5 self-stretch bg-green-400 dark:bg-white/10"></div>
            </div>
            <div>
                <h1 class="font-monteserat text-xl tracking-wider md:text-4xl">
                    Updating Payment:
                </h1>
            </div>

            <div>
                <Notifications :errors="errors" :success="success"/> 
            </div>
        </div>

        <div class="mt-4"> 
            <form action="" method="post" class="" @submit.prevent="submit">
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="max-w-sm" >
                        <div class="flex flex-row space-x-2">
                            <FormInputLabel for-input="amount_paid" label-name="Amount Paid" class="" />
                            <span class="font-josefin tracking-wider font-bold text-base text-red-500">
                                *
                            </span>
                        </div>
                        <FormInput 
                            type="number"
                            id="amount_paid"
                            v-model="form.amountPaid"
                            class="py-2.5 sm:py-3 px-4 block w-full text-base font-josefin font-bold tracking-wider"
                            
                            required/> 
                            
                        <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.amountPaid">{{ form.errors.amountPaid }}</div>
                        
                    </div>

                    <!--  Email -->
                    <div class="max-w-sm" >
                        <div class="flex flex-row space-x-2">
                            <FormInputLabel for-input="date_paid" label-name="Date Paid" class="" />
                            <span class="font-josefin tracking-wider font-bold text-base text-red-500">
                                *
                            </span>
                        </div>
                        <FormInput 
                            type="date"
                            id="date_paid"
                            v-model="form.datePaid"
                            class="py-2.5 sm:py-3 px-4 block w-full font-josefin font-bold tracking-wider"
                            
                            required/> 
                            
                        <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.datePaid">{{ form.errors.datePaid }}</div>
                        
                    </div>

                    <!-- Password -->
                    <div class="max-w-sm" >
                        <div class="flex flex-row space-x-2">
                            <FormInputLabel for-input="payment_mode" label-name="Mode of Payment" class="" />
                            <span class="font-josefin tracking-wider font-bold text-base text-red-500">
                                *
                            </span>
                        </div>
                        
                        <FormInput 
                            type="text"
                            id="payment_mode"
                            v-model="form.modeOfPayment"
                            class="py-2.5 sm:py-3 px-4 block w-full font-josefin font-bold tracking-wider"
                            
                            required/> 
                            
                        <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.modeOfPayment">{{ form.errors.modeOfPayment }}</div>
                        
                    </div>

                    <div class="max-w-sm" >
                        <div class="flex flex-row space-x-2">
                            <FormInputLabel for-input="payment_ref" label-name="Payment Reference" class="" />
                            <span class="font-josefin tracking-wider font-bold text-base text-red-500">
                                *
                            </span>
                        </div>
                        
                        <FormInput 
                            type="text"
                            id="payment_ref"
                            v-model="form.paymentReference"
                            class="py-2.5 sm:py-3 px-4 block w-full font-josefin font-bold tracking-wider"
                            
                            required/> 
                            
                        <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.paymentReference">{{ form.errors.paymentReference }}</div>
                        
                    </div>

                    <div >
                            <div class="max-w-sm mt-5" id="sign-in-btn"> 
                                <button type="submit" :disabled="form.processing" class="py-2.5 sm:py-3 px-4  block w-full items-center gap-x-2 font-league tracking-wider text-sm font-medium rounded-lg border border-transparent 
                                    bg-primaryColor text-white hover:bg-darkPrimaryColor focus:outline-hidden focus:bg-primaryColor disabled:opacity-50 
                                    disabled:pointer-events-none">
                                    Submit
                                </button>
                            </div>
                    </div>
                   
                </div>
            </form>

        </div>





    </ApplicationLayout>
</template>
