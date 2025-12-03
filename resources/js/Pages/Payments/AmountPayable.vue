<script setup>
import { onMounted, ref } from 'vue';
import { Head, Link, useForm,router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Notifications from '@/Layouts/Notifications.vue';


const props = defineProps({
    applicantCourse: Object,
    totalFees: Number,
    studentUnits: Object,
});

onMounted(() => {
    const formattedFee = new Intl.NumberFormat('en-KE', {
        style: 'currency',
        currency: 'KES',
    }).format(props.totalFees);
})

// console.log(props.applicantCourse)
// console.log(props.totalFees)
// console.log(props.studentUnits)

const errors = ref({});
const success = ref({});

</script>

<template>
    <Head title="Amount Payable" />
    <AuthenticatedLayout>
        <div>
                <Notifications :errors="errors" :success="success"/> 
        </div>
        <div class="flex flex-row space-x-6 items-center">
             <div>
                <div
                    class="inline-block rounded-md h-20 min-h-[1em] w-0.5 self-stretch bg-green-400 dark:bg-white/10"></div>
            </div>
            <div>
                <h1 class="font-monteserat text-xl tracking-wider md:text-4xl">
                    💰 Amount Due
                </h1>

                <p class="font-josefin font-bold text-base sm:text-xl tracking-wider">
                    Your registration details are complete!
                </p>
            </div>
        </div>

      
        <div class="mt-4">
            <!-- <p class="font-josefin font-bold text-base tracking-wider">
                <span class="font-monteserat font-extrabold">🧾 Total Invoice Amount: KES <span class="text-primaryColor text-xl">
                    {{ formattedFee }}
                </span>:</span> 

            </p> -->
            <ul>

                <!-- <li>
                    <p class="font-josefin font-bold text-base tracking-wider">
                        <span class="font-josefin font-extrabold">This includes tuition, registration, and applicable materials fees for your selected course.:</span> 

                    </p>
                    <ul class="flex flex-col font-bold space-y-4 p-4 font-josefin text-sm tracking-wider">
                      
                        <li>
                            <h3>
                                Department:
                            </h3>
                            <p class="font-monteserat text-black font-bold pl-4 pt-3">
                                {{ applicantCourse.department_description }}
                            </p>
                        </li>
                        <li>
                            - Course:

                            <p class="font-monteserat text-black font-bold pl-4 pt-3">
                                {{ applicantCourse.course_description }}
                            </p>
                        </li>
                        <li>
                            - Level:
                            <p class="font-monteserat text-black font-bold pl-4 pt-3">
                                {{ applicantCourse.level_description }}
                            </p>
                        </li>
                        <li>
                            - Units:
                            <ul>
                                <li v-for="unit in studentUnits" class="font-monteserat text-black font-bold pl-4 pt-3">
                                   -> {{ unit.Unit_Description }}
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li> -->
            </ul>

            <div>

            </div>
        </div>


        <div class="m-2 sm:m-6">

            <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-md border border-default">
                <table class="w-full text-sm text-left rtl:text-right text-body">
                    <thead class="text-white rounded-md tracking-wider font-monteserat text-sm text-body bg-black border-b border-default-medium">
                        <tr>
                            
                            <th scope="col" class="px-6 py-3 font-medium">
                                Department
                            </th>
                            <th scope="col" class="px-6 py-3 font-medium">
                                 Course
                            </th>
                            <th scope="col" class="px-6 py-3 font-medium">
                                Level
                            </th>
                            <th scope="col" class="px-6 py-3 font-medium">
                                Units
                            </th>
                            <th scope="col" class="px-6 py-3 font-medium">
                                Fee Amount
                            </th>
                          
                            <th scope="col" class="px-6 py-3  font-medium">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-neutral-primary-soft border-b border-default hover:bg-neutral-secondary-medium">
                          
                            <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                                Apple MacBook Pro 17"
                            </th>
                            <td class="px-6 py-4">
                                Silver
                            </td>
                            <td class="px-6 py-4">
                                Laptop
                            </td>
                            <td class="px-6 py-4">
                                Yes
                            </td>
                            <td class="px-6 py-4">
                                Yes
                            </td>
                            <td class="flex items-center px-6 py-4">
                                <a href="#" class="font-medium text-fg-brand hover:underline">Edit</a>
                                <a href="#" class="font-medium text-danger hover:underline ms-3">Remove</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

        
        <div>
             <div class="w-1/4">
                <Link :href="route('payment.instructions')" class="flex items-center gap-2 px-6 py-3 text-white text-xl font-josefin tracking-wider font-bold 
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
                </Link>
            </div>
       </div>


    </AuthenticatedLayout>
</template>