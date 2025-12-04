<script setup>
import { computed, onMounted, ref } from 'vue';
import { Head, Link, useForm,router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Notifications from '@/Layouts/Notifications.vue';


const props = defineProps({
    applicantCourse: Object,
    totalFees: Number,
    studentCourses: Object,
});

console.log(props.studentCourses);

const noOfStudentCourses = ref(0);
const formattedFee = ref(0);

onMounted(() => {
    formattedFee.value = new Intl.NumberFormat('en-KE', {
        style: 'currency',
        currency: 'KES',
    }).format(props.totalFees);

    noOfStudentCourses.value = Object.keys(props.studentCourses).length;
})

const formattedStudentCourse = computed(() => {
  if (!props.studentCourses) return []
  
  return Object.entries(props.studentCourses).map(([key, course]) => ({
    ...course,
    id: key, // Preserve the original key
    Unit_Fees: new Intl.NumberFormat('en-KE', {
      style: 'currency',
      currency: 'KES',
    }).format(Number(course.Unit_Fees) || 0)
  }))
})


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
                        <tr v-for="(courseLine, index) in formattedStudentCourse" :key="index"
                            class="font-josefin font-bold tracking-wider border-b border-default hover:bg-neutral-secondary-medium">
                          
                            <th scope="row" class="px-6 py-4 text-heading whitespace-nowrap">
                                {{ courseLine.Department_Description }}
                            </th>
                            <td class="px-6 py-4">
                                {{ courseLine.Course_Description }}
                            </td>
                            <td class="px-6 py-4">
                                {{ courseLine.Course_Level_Description }}
                            </td>
                            <td class="px-6 py-4">
                                Yes
                            </td>
                            <td class="px-6 py-4 text-primaryColor">
                                {{ courseLine.Unit_Fees }}
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
                            <span class="font-monteserat tracking-wider text-base text-black">{{ formattedFee }}</span>
                        </p>
                    </nav>
                    </div>
                </div>
            </section>
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