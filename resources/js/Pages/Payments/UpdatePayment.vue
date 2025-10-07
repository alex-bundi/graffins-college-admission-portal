<script setup>
import { Head, Link, useForm,router } from '@inertiajs/vue3';
import ApplicationLayout from '@/Layouts/ApplicationLayout.vue';
import Notifications from '@/Layouts/Notifications.vue';
import FormInput from '@/Components/FormInput.vue';
import FormInputLabel from '@/Components/FormInputLabel.vue';
import { ref, onMounted, computed } from 'vue';

const props = defineProps({
    studentPayments: Object,
});

let paymentMode = ref(null);

if((props.studentPayments != null) && (props.studentPayments.Payment_Mode == 'Bank')){
    paymentMode = 1;
} else if((props.studentPayments != null) && (props.studentPayments.Payment_Mode == 'Mpesa')){
    paymentMode = 2;
}else if((props.studentPayments != null) && (props.studentPayments.Payment_Mode == 'Cheque')){
    paymentMode = 3;
}

const errors = ref({});
const success = ref({});
const form = useForm({
    amountPaid: props.studentPayments ? props.studentPayments.Amount_to_pay : null,
    datePaid: props.studentPayments ? props.studentPayments.Payment_Date : null,
    modeOfPayment: paymentMode,
    paymentReference: props.studentPayments ? props.studentPayments.Payment_Reference_No : null,
});

const hasChanged = computed(() => {
    return (
        
        form.amountPaid !== (props.studentPayments?.Amount_to_pay ?? null) ||
        form.datePaid !== (props.studentPayments?.Payment_Date ?? null) ||
        form.modeOfPayment !== (paymentMode ?? null) ||
        form.paymentReference !== (props.studentPayments?.Payment_Reference_No ?? null) 
    );
});
function submit(){
    if (hasChanged.value == true) {
        router.post('/payments/post-update-payment', form, {
            onError : (allErrors) => {
                for(let error in allErrors){
                errors.value[error] = allErrors[error]
                }

            
            },

        });
    } else {
        router.visit('/application/student-id');


        
    }

    

 
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
                        <!-- <div class="flex flex-row space-x-2">
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
                         -->

                         <ul class="grid w-full gap-6 md:grid-cols-1 mt-2">
                            <FormInputLabel for-input="mode_of_payment" label-name="Mode of Payment" class="" />

                            <li>
                                <input type="radio" v-model="form.modeOfPayment" id="bank" name="bank" value="1" class="hidden peer" />
                                <label for="bank" class="inline-flex items-center justify-between w-full p-5 text-gray-500 
                                    bg-white border border-gray-200 rounded-lg cursor-pointer  
                                    peer-checked:border-primaryColor
                                    peer-checked:text-primaryColor hover:text-gray-600 hover:bg-gray-100 
                                    dark:text-gray-400 ">                           
                                    <div class="block">
                                        <div class="w-full text-lg font-semibold">BANK</div>
                                    </div>
                                    
                                </label>
                                <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.departmentCode">{{ form.errors.departmentCode }}</div>

                            </li>
                            <li>
                                <input type="radio" v-model="form.modeOfPayment" id="mpesa" name="mpesa" value="2" class="hidden peer" />
                                <label for="mpesa" class="inline-flex items-center justify-between w-full p-5 text-gray-500 
                                    bg-white border border-gray-200 rounded-lg cursor-pointer  
                                    peer-checked:border-primaryColor
                                    peer-checked:text-primaryColor hover:text-gray-600 hover:bg-gray-100 
                                    dark:text-gray-400 ">                           
                                    <div class="block">
                                        <div class="w-full text-lg font-semibold">MPESA</div>
                                    </div>
                                    
                                </label>
                                <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.departmentCode">{{ form.errors.departmentCode }}</div>

                            </li>
                            <li>
                                <input type="radio"  v-model="form.modeOfPayment" id="cheque" name="cheque" value="3" class="hidden peer">
                                <label for="cheque" class="inline-flex items-center justify-between w-full p-5 text-gray-500 
                                    bg-white border border-gray-200 rounded-lg cursor-pointer  
                                    peer-checked:border-primaryColor
                                    peer-checked:text-primaryColor hover:text-gray-600 hover:bg-gray-100 
                                    dark:text-gray-400 ">
                                    <div class="block">
                                        <div class="w-full text-lg font-semibold">CHEQUE</div>
                                    <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.departmentCode">{{ form.errors.departmentCode }}</div>

                                    </div>
                                </label>
                            </li>

                            <!-- <li>
                                <input type="radio"  v-model="form.modeOfPayment" id="cash" name="cash" value="cash" class="hidden peer">
                                <label for="cash" class="inline-flex items-center justify-between w-full p-5 text-gray-500 
                                    bg-white border border-gray-200 rounded-lg cursor-pointer  
                                    peer-checked:border-primaryColor
                                    peer-checked:text-primaryColor hover:text-gray-600 hover:bg-gray-100 
                                    dark:text-gray-400 ">
                                    <div class="block">
                                        <div class="w-full text-lg font-semibold">CASH</div>
                                    <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.departmentCode">{{ form.errors.departmentCode }}</div>

                                    </div>
                                </label>
                            </li> -->
                                    
                        </ul>
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
                            class="py-2.5 sm:py-3 px-4 block w-full font-josefin font-bold tracking-wider uppercase"
                            
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
