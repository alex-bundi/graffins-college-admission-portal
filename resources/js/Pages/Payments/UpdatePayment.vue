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
console.log(props.studentPayments)


const showPaymentModal = ref(false);
const selectedCourse = ref(null);
const paymentAmount = ref('');
const noOfStudentCourses = ref(0);
const totalFees = ref(0);
let paymentMode = ref(null);
const showEditPaymentModal = ref(false);
const selectedPayment = ref(null);

const editForm = useForm({
    amountPaid: null,
    datePaid: null,
    modeOfPayment: null,
    paymentReference: null,
    paymentID: null,
    paymentNo: null,
});

const errors = ref({});
const success = ref({});
const form = useForm({
    amountPaid: props.studentPayments ? props.studentPayments.Amount_to_pay : null,
    datePaid: props.studentPayments ? props.studentPayments.Payment_Date : null,
    modeOfPayment: paymentMode,
    paymentReference: props.studentPayments ? props.studentPayments.Payment_Reference_No : null,
    courseID: null,
});

console.log(props.courseLines);


function openPaymentModal(courseLine, courseLineID) {
    selectedCourse.value = courseLine;
    paymentAmount.value = '';
    showPaymentModal.value = true;
    form.courseID = courseLineID;
}



if((props.studentPayments != null) && (props.studentPayments.Payment_Mode == 'Bank')){
    paymentMode = 1;
} else if((props.studentPayments != null) && (props.studentPayments.Payment_Mode == 'Mpesa')){
    paymentMode = 2;
}else if((props.studentPayments != null) && (props.studentPayments.Payment_Mode == 'Cheque')){
    paymentMode = 3;
}



const hasChanged = computed(() => {
    return (
        
        form.amountPaid !== (props.studentPayments?.Amount_to_pay ?? null) ||
        form.datePaid !== (props.studentPayments?.Payment_Date ?? null) ||
        form.modeOfPayment !== (paymentMode ?? null) ||
        form.paymentReference !== (props.studentPayments?.Payment_Reference_No ?? null) 
    );
});


onMounted(async () => {
    const feeDetailsData = await getFeeDetails();
    console.log(feeDetailsData.data)

    if(feeDetailsData.success === true){
        totalFees.value = new Intl.NumberFormat('en-KE', {
            style: 'currency',
            currency: 'KES',
        }).format(feeDetailsData.data.totalFees);

    }else if(feeDetailsData.error === true){
        errors.value.message = bioData.message;
        return;
    }

    noOfStudentCourses.value = Object.keys(props.courseLines).length;
})

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



async function getFeeDetails() {
    const response = await fetch('/payments/fee-details');
    return await response.json();
}


function openEditPaymentModal(payment) {
    selectedPayment.value = payment;
    
    // Populate edit form with existing payment data
    editForm.amountPaid = payment.Amount_to_pay;
    editForm.datePaid = payment.Payment_Date;
    editForm.paymentReference = payment.Payment_Reference_No;
    editForm.paymentNo = payment.Student_Payment_No
    
    // Set payment mode based on Payment_Mode
    if (payment.Payment_Mode === 'Bank') {
        editForm.modeOfPayment = 1;
    } else if (payment.Payment_Mode === 'Mpesa') {
        editForm.modeOfPayment = 2;
    } else if (payment.Payment_Mode === 'Cheque') {
        editForm.modeOfPayment = 3;
    }
    
    editForm.paymentID = payment.id || payment.Payment_Reference_No;
    showEditPaymentModal.value = true;
}

function closeEditModal() {
    showEditPaymentModal.value = false;
    selectedPayment.value = null;
    editForm.reset();
}

function submitEdit() {
    router.post('/payments/post-edit-payment', editForm, {
        onSuccess: () => {
            closeEditModal();
        },
        onError: (allErrors) => {
            for (let error in allErrors) {
                errors.value[error] = allErrors[error]
            }
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
                                  
                                  
                                    <td  class="flex p-5 items-center gap-0.5">
                                        <div v-if="courseLine.payment_updated == 0">
                                            <button  @click="openPaymentModal(courseLine, courseLine.id )"
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
                                        </div>
                                        <div v-else>
                                            <div class="hidden sm:flex items-center justify-center space-x-2">
                                                <span class="relative flex h-3 w-3">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                                </span>
                                                <p class="font-josefin font-bold tracking-wider text-sm">Payment Updated</p>
                                            </div>
                                        </div>
                                        
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <div class="mt-4">
            <section class="flex items-center mb-6">
                <div class="w-full max-w-screen-xl px-4 mx-auto lg:px-12">
                    <!-- Start coding here -->
                    <div class="relative overflow-hidden bg-white rounded-b-lg shadow-md dark:bg-gray-800">
                    <nav class="flex flex-col sm:flex-row gap-4 items-center justify-between p-4"
                        aria-label="Table navigation">
                        <div class="flex space-x-4">
                            <h2 class="font-monteserat tracking-wider">
                                Total Courses:
                            </h2>
                            <span class="font-josefin font-bold tracking-wider">
                                {{ noOfStudentCourses }}
                            </span>
                        </div>
                        <p class="flex items-center space-x-4">
                            <span class="font-monteserat tracking-wider text-xl text-primaryColor">Total Fees:</span>
                            <span class="font-monteserat tracking-wider text-base text-black">{{ totalFees }}</span>
                        </p>
                    </nav>
                    </div>
                </div>
            </section>
        </div>


        <!--  PAYMENT MODAL -->
        <div 
            v-if="showPaymentModal" 
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-lg p-6 w-3/4 md:w-1/2 shadow-xl">
                <h2 class="text-xl font-monteserat tracking-wider font-bold mb-4">Capture Payment</h2>

                <p class="text-gray-700 mb-3 font-josefin tracking-wider font-bold">
                    Updating payment for:  
                    <strong class="text-primaryColor">{{ selectedCourse?.course_description  }}</strong>
                </p>

                <div>
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

                            
                        </div>
                        <div class="max-w-sm mt-2" >
                            
                            <FormInputLabel for-input="mode_of_payment" label-name="Mode of Payment" class="" />
                            <ul class="grid w-full grid-cols-1 gap-6 md:grid-cols-3 mt-2">
                                

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


                        <div class="flex justify-between space-x-2 mt-5">
                            <button 
                                type="submit"
                                :disabled="form.processing" 
                                class="py-2.5 sm:py-3 px-4  block w-1/2 items-center gap-x-2 font-league tracking-wider text-sm font-medium rounded-lg border border-transparent 
                                            bg-primaryColor text-white hover:bg-darkPrimaryColor focus:outline-hidden focus:bg-primaryColor disabled:opacity-50 
                                                    disabled:pointer-events-none"
                            >
                                Save
                            </button>
                            <button 
                                @click="showPaymentModal = false"
                                class="flex space-x-3 bg-black rounded-full text-white py-2.5 sm:py-3 px-4 hover:bg-gray-700 font-josefin font-bold tracking-wider"
                            >
                                <span>
                                    Cancel
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                </svg>

                                
                            </button>

                        
                        </div>
                    </form>
                </div>

            
            </div>
        </div>


        <!-- Student Payments -->
        <div class="mt-9">
            <h3 class="font-monteserat text-base text-primaryColor tracking-wider md:text-2xl">
                Updated Payments
            </h3>
         </div>
        <div class="flex flex-col  m-6">
            <div class=" overflow-x-auto pb-4">
                <div class="min-w-full inline-block align-middle">
                    <div class="overflow-hidden  border rounded-lg border-gray-300">
                        <table class="table-auto min-w-full rounded-xl">
                            <thead>
                                <tr class="bg-black text-white font-monteserat tracking-wider">

                                    <th scope="col" class="p-5 text-left whitespace-nowrap text-sm leading-6  capitalize"> Course </th>
                                    <th scope="col" class="p-5 text-left whitespace-nowrap text-sm leading-6  capitalize"> Level </th>
                                    <th scope="col" class="p-5 text-left whitespace-nowrap text-sm leading-6  capitalize"> Paid Amount </th>
                                    <th scope="col" class="p-5 text-left whitespace-nowrap text-sm leading-6  capitalize"> Payment Date </th>
                                    <th scope="col" class="p-5 text-left whitespace-nowrap text-sm leading-6  capitalize"> Payment Reference </th>
                                    <th scope="col" class="p-5 text-left whitespace-nowrap text-sm leading-6  capitalize"> Payment Mode </th>
                                    <th scope="col" class="p-5 text-left whitespace-nowrap text-sm leading-6  capitalize"> Actions </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-300 ">
                                <tr v-for="payment in studentPayments"
                                    class="bg-white transition-all duration-500 font-josefin tracking-wider font-bold hover:bg-gray-50">
                                
                                    <td class="p-5 whitespace-nowrap text-sm leading-6  text-gray-900 "> {{ payment.Course_Description }}</td>
                                    <td class="p-5 whitespace-nowrap text-sm leading-6  text-gray-900"> {{ payment.Level_Description }} </td>
                                    
                                    <td class="p-5 whitespace-nowrap text-sm leading-6  text-gray-900"> {{ payment.Amount_to_pay }}</td>
                                    <td class="p-5 whitespace-nowrap text-sm leading-6  text-gray-900"> {{ payment.Payment_Date }}</td>
                                    <td class="p-5 whitespace-nowrap text-sm leading-6  text-gray-900"> {{ payment.Payment_Reference_No }}</td>
                                    <td class="p-5 whitespace-nowrap text-sm leading-6  text-gray-900"> {{ payment.Payment_Mode }} </td>
                                
                                    <td class="flex p-5 items-center gap-0.5">
                                        <button @click="openEditPaymentModal(payment)" class="p-2  rounded-full bg-white group transition-all duration-500 hover:bg-indigo-600 flex item-center">
                                            <svg class="cursor-pointer" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path class="fill-indigo-500 group-hover:fill-white" d="M9.53414 8.15675L8.96459 7.59496L8.96459 7.59496L9.53414 8.15675ZM13.8911 3.73968L13.3215 3.17789V3.17789L13.8911 3.73968ZM16.3154 3.75892L15.7367 4.31126L15.7367 4.31127L16.3154 3.75892ZM16.38 3.82658L16.9587 3.27423L16.9587 3.27423L16.38 3.82658ZM16.3401 6.13595L15.7803 5.56438L16.3401 6.13595ZM11.9186 10.4658L12.4784 11.0374L11.9186 10.4658ZM11.1223 10.9017L10.9404 10.1226V10.1226L11.1223 10.9017ZM9.07259 10.9951L8.52556 11.5788L8.52556 11.5788L9.07259 10.9951ZM9.09713 8.9664L9.87963 9.1328V9.1328L9.09713 8.9664ZM9.05721 10.9803L8.49542 11.5498H8.49542L9.05721 10.9803ZM17.1679 4.99458L16.368 4.98075V4.98075L17.1679 4.99458ZM15.1107 2.8693L15.1171 2.06932L15.1107 2.8693ZM9.22851 8.51246L8.52589 8.12992L8.52452 8.13247L9.22851 8.51246ZM9.22567 8.51772L8.52168 8.13773L8.5203 8.1403L9.22567 8.51772ZM11.5684 10.7654L11.9531 11.4668L11.9536 11.4666L11.5684 10.7654ZM11.5669 10.7662L11.9507 11.4681L11.9516 11.4676L11.5669 10.7662ZM11.3235 3.30005C11.7654 3.30005 12.1235 2.94188 12.1235 2.50005C12.1235 2.05822 11.7654 1.70005 11.3235 1.70005V3.30005ZM18.3 9.55887C18.3 9.11705 17.9418 8.75887 17.5 8.75887C17.0582 8.75887 16.7 9.11705 16.7 9.55887H18.3ZM3.47631 16.5237L4.042 15.9581H4.042L3.47631 16.5237ZM16.5237 16.5237L15.958 15.9581L15.958 15.9581L16.5237 16.5237ZM10.1037 8.71855L14.4606 4.30148L13.3215 3.17789L8.96459 7.59496L10.1037 8.71855ZM15.7367 4.31127L15.8013 4.37893L16.9587 3.27423L16.8941 3.20657L15.7367 4.31127ZM15.7803 5.56438L11.3589 9.89426L12.4784 11.0374L16.8998 6.70753L15.7803 5.56438ZM10.9404 10.1226C10.3417 10.2624 9.97854 10.3452 9.72166 10.3675C9.47476 10.3888 9.53559 10.3326 9.61962 10.4113L8.52556 11.5788C8.9387 11.966 9.45086 11.9969 9.85978 11.9615C10.2587 11.9269 10.7558 11.8088 11.3042 11.6807L10.9404 10.1226ZM8.31462 8.8C8.19986 9.33969 8.09269 9.83345 8.0681 10.2293C8.04264 10.6393 8.08994 11.1499 8.49542 11.5498L9.619 10.4107C9.70348 10.494 9.65043 10.5635 9.66503 10.3285C9.6805 10.0795 9.75378 9.72461 9.87963 9.1328L8.31462 8.8ZM9.61962 10.4113C9.61941 10.4111 9.6192 10.4109 9.619 10.4107L8.49542 11.5498C8.50534 11.5596 8.51539 11.5693 8.52556 11.5788L9.61962 10.4113ZM15.8013 4.37892C16.0813 4.67236 16.2351 4.83583 16.3279 4.96331C16.4073 5.07234 16.3667 5.05597 16.368 4.98075L17.9678 5.00841C17.9749 4.59682 17.805 4.27366 17.6213 4.02139C17.451 3.78756 17.2078 3.53522 16.9587 3.27423L15.8013 4.37892ZM16.8998 6.70753C17.1578 6.45486 17.4095 6.21077 17.5876 5.98281C17.7798 5.73698 17.9607 5.41987 17.9678 5.00841L16.368 4.98075C16.3693 4.90565 16.4103 4.8909 16.327 4.99749C16.2297 5.12196 16.0703 5.28038 15.7803 5.56438L16.8998 6.70753ZM14.4606 4.30148C14.7639 3.99402 14.9352 3.82285 15.0703 3.71873C15.1866 3.62905 15.1757 3.66984 15.1044 3.66927L15.1171 2.06932C14.6874 2.06591 14.3538 2.25081 14.0935 2.45151C13.8518 2.63775 13.5925 2.9032 13.3215 3.17789L14.4606 4.30148ZM16.8941 3.20657C16.6279 2.92765 16.373 2.65804 16.1345 2.46792C15.8774 2.26298 15.5468 2.07273 15.1171 2.06932L15.1044 3.66927C15.033 3.66871 15.0226 3.62768 15.1372 3.71904C15.2704 3.82522 15.4387 3.999 15.7367 4.31126L16.8941 3.20657ZM8.96459 7.59496C8.82923 7.73218 8.64795 7.90575 8.5259 8.12993L9.93113 8.895C9.92075 8.91406 9.91465 8.91711 9.93926 8.88927C9.97002 8.85445 10.0145 8.80893 10.1037 8.71854L8.96459 7.59496ZM9.87963 9.1328C9.9059 9.00925 9.91925 8.94785 9.93124 8.90366C9.94073 8.86868 9.94137 8.87585 9.93104 8.89515L8.5203 8.1403C8.39951 8.36605 8.35444 8.61274 8.31462 8.8L9.87963 9.1328ZM8.52452 8.13247L8.52168 8.13773L9.92967 8.89772L9.9325 8.89246L8.52452 8.13247ZM11.3589 9.89426C11.27 9.98132 11.2252 10.0248 11.1909 10.055C11.1635 10.0791 11.1658 10.0738 11.1832 10.0642L11.9536 11.4666C12.1727 11.3462 12.3427 11.1703 12.4784 11.0374L11.3589 9.89426ZM11.3042 11.6807C11.4912 11.6371 11.7319 11.5878 11.9507 11.4681L11.1831 10.0643C11.2007 10.0547 11.206 10.0557 11.1697 10.0663C11.1248 10.0793 11.0628 10.0941 10.9404 10.1226L11.3042 11.6807ZM11.1837 10.064L11.1822 10.0648L11.9516 11.4676L11.9531 11.4668L11.1837 10.064ZM16.399 6.10097L13.8984 3.60094L12.7672 4.73243L15.2677 7.23246L16.399 6.10097ZM10.8333 16.7001H9.16667V18.3001H10.8333V16.7001ZM3.3 10.8334V9.16672H1.7V10.8334H3.3ZM9.16667 3.30005H11.3235V1.70005H9.16667V3.30005ZM16.7 9.55887V10.8334H18.3V9.55887H16.7ZM9.16667 16.7001C7.5727 16.7001 6.45771 16.6984 5.61569 16.5851C4.79669 16.475 4.35674 16.2728 4.042 15.9581L2.91063 17.0894C3.5722 17.751 4.40607 18.0369 5.4025 18.1709C6.37591 18.3018 7.61793 18.3001 9.16667 18.3001V16.7001ZM1.7 10.8334C1.7 12.3821 1.6983 13.6241 1.82917 14.5976C1.96314 15.594 2.24905 16.4279 2.91063 17.0894L4.042 15.9581C3.72726 15.6433 3.52502 15.2034 3.41491 14.3844C3.3017 13.5423 3.3 12.4273 3.3 10.8334H1.7ZM10.8333 18.3001C12.3821 18.3001 13.6241 18.3018 14.5975 18.1709C15.5939 18.0369 16.4278 17.751 17.0894 17.0894L15.958 15.9581C15.6433 16.2728 15.2033 16.475 14.3843 16.5851C13.5423 16.6984 12.4273 16.7001 10.8333 16.7001V18.3001ZM16.7 10.8334C16.7 12.4274 16.6983 13.5423 16.5851 14.3844C16.475 15.2034 16.2727 15.6433 15.958 15.9581L17.0894 17.0894C17.7509 16.4279 18.0369 15.594 18.1708 14.5976C18.3017 13.6241 18.3 12.3821 18.3 10.8334H16.7ZM3.3 9.16672C3.3 7.57275 3.3017 6.45776 3.41491 5.61574C3.52502 4.79674 3.72726 4.35679 4.042 4.04205L2.91063 2.91068C2.24905 3.57225 1.96314 4.40612 1.82917 5.40255C1.6983 6.37596 1.7 7.61798 1.7 9.16672H3.3ZM9.16667 1.70005C7.61793 1.70005 6.37591 1.69835 5.4025 1.82922C4.40607 1.96319 3.5722 2.24911 2.91063 2.91068L4.042 4.04205C4.35674 3.72731 4.79669 3.52507 5.61569 3.41496C6.45771 3.30175 7.5727 3.30005 9.16667 3.30005V1.70005Z" fill="#818CF8"></path>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <div v-if="showEditPaymentModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-3/4 md:w-1/2 shadow-xl">
                <h2 class="text-xl font-monteserat tracking-wider font-bold mb-4">Edit Payment</h2>

                <p class="text-gray-700 mb-3 font-josefin tracking-wider font-bold">
                    Editing payment for:
                    <strong class="text-primaryColor">{{ selectedPayment?.Course_Description }}</strong>
                </p>

                <div>
                    <form @submit.prevent="submitEdit">
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="max-w-sm">
                                <div class="flex flex-row space-x-2">
                                    <FormInputLabel for-input="edit_amount_paid" label-name="Amount Paid" />
                                    <span class="font-josefin tracking-wider font-bold text-base text-red-500">*</span>
                                </div>
                                <FormInput type="number" id="edit_amount_paid" v-model="editForm.amountPaid"
                                    class="py-2.5 sm:py-3 px-4 block w-full text-base font-josefin font-bold tracking-wider"
                                    required />
                                <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm"
                                    v-if="editForm.errors.amountPaid">
                                    {{ editForm.errors.amountPaid }}
                                </div>
                            </div>

                            <div class="hidden">
                                <FormInput type="text" id="payment_no" v-model="editForm.paymentNo"
                                    class="py-2.5 sm:py-3 px-4 block w-full text-base font-josefin font-bold tracking-wider"
                                    required />
                            </div>

                            <div class="max-w-sm">
                                <div class="flex flex-row space-x-2">
                                    <FormInputLabel for-input="edit_date_paid" label-name="Date Paid" />
                                    <span class="font-josefin tracking-wider font-bold text-base text-red-500">*</span>
                                </div>
                                <FormInput type="date" id="edit_date_paid" v-model="editForm.datePaid"
                                    class="py-2.5 sm:py-3 px-4 block w-full font-josefin font-bold tracking-wider"
                                    required />
                                <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm"
                                    v-if="editForm.errors.datePaid">
                                    {{ editForm.errors.datePaid }}
                                </div>
                            </div>

                            <div class="max-w-sm">
                                <div class="flex flex-row space-x-2">
                                    <FormInputLabel for-input="edit_payment_ref" label-name="Payment Reference" />
                                    <span class="font-josefin tracking-wider font-bold text-base text-red-500">*</span>
                                </div>
                                <FormInput type="text" id="edit_payment_ref" v-model="editForm.paymentReference"
                                    class="py-2.5 sm:py-3 px-4 block w-full font-josefin font-bold tracking-wider uppercase"
                                    required />
                                <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm"
                                    v-if="editForm.errors.paymentReference">
                                    {{ editForm.errors.paymentReference }}
                                </div>
                            </div>
                        </div>

                        <div class="max-w-sm mt-2">
                            <FormInputLabel for-input="edit_mode_of_payment" label-name="Mode of Payment" />
                            <ul class="grid w-full grid-cols-1 gap-6 md:grid-cols-3 mt-2">
                                <li>
                                    <input type="radio" v-model="editForm.modeOfPayment" id="edit_bank" 
                                        name="edit_bank" value="1" class="hidden peer" />
                                    <label for="edit_bank"
                                        class="inline-flex items-center justify-between w-full p-5 text-gray-500 
                                        bg-white border border-gray-200 rounded-lg cursor-pointer  
                                        peer-checked:border-primaryColor peer-checked:text-primaryColor 
                                        hover:text-gray-600 hover:bg-gray-100">
                                        <div class="block">
                                            <div class="w-full text-lg font-semibold">BANK</div>
                                        </div>
                                    </label>
                                </li>

                                <li>
                                    <input type="radio" v-model="editForm.modeOfPayment" id="edit_mpesa"
                                        name="edit_mpesa" value="2" class="hidden peer" />
                                    <label for="edit_mpesa"
                                        class="inline-flex items-center justify-between w-full p-5 text-gray-500 
                                        bg-white border border-gray-200 rounded-lg cursor-pointer  
                                        peer-checked:border-primaryColor peer-checked:text-primaryColor 
                                        hover:text-gray-600 hover:bg-gray-100">
                                        <div class="block">
                                            <div class="w-full text-lg font-semibold">MPESA</div>
                                        </div>
                                    </label>
                                </li>

                                <li>
                                    <input type="radio" v-model="editForm.modeOfPayment" id="edit_cheque"
                                        name="edit_cheque" value="3" class="hidden peer" />
                                    <label for="edit_cheque"
                                        class="inline-flex items-center justify-between w-full p-5 text-gray-500 
                                        bg-white border border-gray-200 rounded-lg cursor-pointer  
                                        peer-checked:border-primaryColor peer-checked:text-primaryColor 
                                        hover:text-gray-600 hover:bg-gray-100">
                                        <div class="block">
                                            <div class="w-full text-lg font-semibold">CHEQUE</div>
                                        </div>
                                    </label>
                                </li>
                            </ul>
                        </div>

                        <div class="flex justify-between space-x-2 mt-5">
                            <button type="submit" :disabled="editForm.processing"
                                class="py-2.5 sm:py-3 px-4 block w-1/2 items-center gap-x-2 font-league tracking-wider text-sm font-medium rounded-lg border border-transparent 
                                bg-primaryColor text-white hover:bg-darkPrimaryColor focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none">
                                Update
                            </button>
                            <button type="button" @click="closeEditModal"
                                class="flex space-x-3 bg-black rounded-full text-white py-2.5 sm:py-3 px-4 hover:bg-gray-700 font-josefin font-bold tracking-wider">
                                <span>Cancel</span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="size-6">
                                    <path fill-rule="evenodd"
                                        d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>





    </ApplicationLayout>
</template>
