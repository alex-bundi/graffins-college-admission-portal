<script setup>
import { Head, Link, useForm,router } from '@inertiajs/vue3';
import ApplicationLayout from '@/Layouts/ApplicationLayout.vue';
import Notifications from '@/Layouts/Notifications.vue';
import FormInput from '@/Components/FormInput.vue';
import FormInputLabel from '@/Components/FormInputLabel.vue';
import { ref, onMounted, computed } from 'vue';

const props = defineProps({
    studentPayments: Object,
    names: String,
    courseLines: Object,
});

const showPaymentModal = ref(false);
const selectedCourse = ref(null);
const paymentAmount = ref('');

function openPaymentModal(courseLine) {
    selectedCourse.value = courseLine;
    paymentAmount.value = '';
    showPaymentModal.value = true;
}

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


        <!-- Course Table -->
        <div class="flex flex-col">
            <div class=" overflow-x-auto pb-4">
                <div class="min-w-full inline-block align-middle">
                    <div class="overflow-hidden  border rounded-lg border-gray-300">
                        <table class="table-auto min-w-full rounded-xl">
                            <thead>
                                <tr class="bg-gray-50 font-monteserat tracking-wider">
                                    <th scope="col" class="p-5 text-left whitespace-nowrap text-sm leading-6  text-gray-900 capitalize"> 
                                        Student Name 
                                    </th>
                                    <th scope="col" class="p-5 text-left whitespace-nowrap text-sm leading-6 text-gray-900 capitalize"> 
                                        Course 
                                    </th>
                                    <th scope="col" class="p-5 text-left whitespace-nowrap text-sm leading-6 text-gray-900 capitalize"> 
                                        Level 
                                    </th>
                                    <th scope="col" class="p-5 text-left whitespace-nowrap text-sm leading-6  text-gray-900 capitalize">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-300 ">
                                <tr v-for="(courseLine, index) in courseLines" :key="index"
                                    class="bg-white transition-all duration-500 font-josefin tracking-wider font-bold hover:bg-gray-50">
                                   
                                    <td class="p-5 whitespace-nowrap text-sm leading-6  text-gray-900 ">{{ names }}</td>
                                    <td class="p-5 whitespace-nowrap text-sm leading-6 text-gray-900"> {{ courseLine.course_description }} </td>
                                   
                                    <td class="p-5 whitespace-nowrap text-sm leading-6  text-gray-900"> {{ courseLine.level_description }} </td>
                                  
                                    <td class="flex p-5 items-center gap-0.5">
                                        <button  @click="openPaymentModal(courseLine)"
                                            class="flex items-center text-black text-sm p-2 border border-amber-400 hover:bg-gray-500 hover:text-white hover:border-0 
                                                rounded-full  group transition-all duration-500 space-x-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                                                <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                                <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                                                </svg>



                                                <span>
                                                    Update Payment
                                                </span>
                                        </button >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <!-- 💬 PAYMENT MODAL -->
<div 
    v-if="showPaymentModal" 
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
>
    <div class="bg-white rounded-lg p-6 w-1/2 shadow-xl">
        <h2 class="text-xl font-monteserat tracking-wider font-bold mb-4">Capture Payment</h2>

        <p class="text-gray-700 mb-3">
            Updating payment for:  
            <strong>{{ selectedCourse?.course_description }}</strong>
        </p>

        <div>
            <form action="">
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

                     <div class="max-w-sm" >
                      

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

                                    
                        </ul>
                    </div>
                </div>
            </form>
        </div>

        <div class="flex justify-end space-x-2">
            <button 
                @click="showPaymentModal = false"
                class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
            >
                Cancel
            </button>

            <button 
                @click="submitPayment"
                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
            >
                Save
            </button>
        </div>
    </div>
</div>

        <!-- <div class="mt-4"> 
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

                    <div class="max-w-sm" >
                      

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

        </div> -->





    </ApplicationLayout>
</template>
