<script setup>
import { ref } from 'vue';
import { Head, Link, useForm,router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Notifications from '@/Layouts/Notifications.vue';

const props = defineProps({
    courses: Object,
    department: String,
});
console.log(props.department);

const itCourses = Object.fromEntries(
    Object.entries(props.courses).filter(([key, value]) => value.DepartmentCode == 'WCAPS')
)

const engCourses = Object.fromEntries(
    Object.entries(props.courses).filter(([key, value]) => value.DepartmentCode == 'WENG')
)

const businessCourses = Object.fromEntries(
    Object.entries(props.courses).filter(([key, value]) => value.DepartmentCode == 'WBM')
)


const errors = ref({});
const success = ref({});
const form = useForm({
    courseCode: '',
});

function submit(){

  
    router.post('/application/post-pick-course', form, {
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
    <Head title="Pick Course" />
    <AuthenticatedLayout>
        
        <div class="flex flex-row space-x-6 items-center">
             <div>
                <div
                    class="inline-block rounded-md h-20 min-h-[1em] w-0.5 self-stretch bg-green-400 dark:bg-white/10"></div>
            </div>
            <div>
                <h1 class="font-monteserat text-xl tracking-wider md:text-4xl">
                    🎓 Please pick the course you are joining
                </h1>
                <p class="font-josefin font-bold text-base sm:text-xl tracking-wider">
                    This will help us provide the right information and support for your registration.
                </p>
            </div>
        </div>

        <div>
                <Notifications :errors="errors" :success="success"/> 
        </div>

        <div class="mt-12 ">
            
            <form action="" method="post" class="flex flex-col space-y-6" @submit.prevent="submit">
               <div class="">
                    <!-- It Courses section -->
                    <section v-show="props.department == 'WCAPS'">
                        <!-- Header -->
                        <div class="p-6 bg-white rounded-md ">
                            <h2 class="flex justify-center font-monteserat text-base tracking-wider">
                                💼 Technology Department
                            </h2>
                        </div>
                         <!-- IT Courses -->
                        <ul class="grid w-full gap-6 md:grid-cols-1 mt-2">
                                <li v-for="course in itCourses" :key="course.CourseCode">
                                    <input type="radio" v-model="form.courseCode" :id="course.CourseCode" :name="course.CourseCode" :value="course.CourseCode + '..' + course.CourseDescription" class="hidden peer" />
                                    <label :for="course.CourseCode" class="inline-flex items-center justify-between w-full p-5 text-gray-500 
                                        bg-white border border-gray-200 rounded-lg cursor-pointer  
                                        peer-checked:border-primaryColor
                                        peer-checked:text-primaryColor hover:text-gray-600 hover:bg-gray-100 
                                        dark:text-gray-400 ">                           
                                        <div class="block">
                                            <div class="w-full text-lg font-semibold">{{ course.CourseDescription }}</div>
                                        </div>
                                        
                                    </label>
                                    <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.courseCode">{{ form.errors.courseCode }}</div>

                                </li>
                               
                             
                        </ul>
                    </section>

                    <!-- Business Courses section -->
                    <section v-show="props.department == 'WBM'">
                        <!-- Header -->
                        <div class="p-6 bg-white rounded-md ">
                            <h2 class="font-monteserat text-base tracking-wider">
                                🖥️ Business Department
                            </h2>
                        </div>
                         <!-- Business Courses -->
                        <ul class="grid w-full gap-6 md:grid-cols-1 mt-2">
                                <li v-for="course in businessCourses" :key="course.CourseCode">
                                    <input type="radio" v-model="form.courseCode" :id="course.CourseCode" :name="course.CourseCode" :value="course.CourseCode + '..' + course.CourseDescription" class="hidden peer" />
                                    <label :for="course.CourseCode" class="inline-flex items-center justify-between w-full p-5 text-gray-500 
                                        bg-white border border-gray-200 rounded-lg cursor-pointer  
                                        peer-checked:border-primaryColor
                                        peer-checked:text-primaryColor hover:text-gray-600 hover:bg-gray-100 
                                        dark:text-gray-400 ">                           
                                        <div class="block">
                                            <div class="w-full text-lg font-semibold">{{ course.CourseDescription }}</div>
                                        </div>
                                        
                                    </label>
                                    <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.courseCode">{{ form.errors.courseCode }}</div>

                                </li>
                             
                            </ul>
                    </section>

                    <!-- English Courses section -->
                    <section v-show="props.department == 'WENG'">
                        <!-- Header -->
                        <div class="p-6 bg-white rounded-md ">
                            <h2 class="font-monteserat text-base tracking-wider">
                                🗣️ Language Skills Department
                            </h2>
                        </div>
                         <!-- English Courses -->
                        <ul class="grid w-full gap-6 md:grid-cols-1 mt-2">
                                <li v-for="course in engCourses" :key="course.CourseCode">
                                    <input type="radio" v-model="form.courseCode" :id="course.CourseCode" :name="course.CourseCode" :value="course.CourseCode + '..' + course.CourseDescription" class="hidden peer" />
                                    <label :for="course.CourseCode" class="inline-flex items-center justify-between w-full p-5 text-gray-500 
                                        bg-white border border-gray-200 rounded-lg cursor-pointer  
                                        peer-checked:border-primaryColor
                                        peer-checked:text-primaryColor hover:text-gray-600 hover:bg-gray-100 
                                        dark:text-gray-400 ">                           
                                        <div class="block">
                                            <div class="w-full text-lg font-semibold">{{ course.CourseDescription }}</div>
                                        </div>
                                        
                                    </label>
                                    <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.courseCode">{{ form.errors.courseCode }}</div>

                                </li>
                             
                            </ul>
                    </section>
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

    </AuthenticatedLayout>
</template>