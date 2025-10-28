<script setup>
import { onMounted, ref } from 'vue';
import { Head, Link, useForm,router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Notifications from '@/Layouts/Notifications.vue';
import StepperComponent from '@/Layouts/Stepper.vue';


const props = defineProps({
    courses: Object,
    department: String,
    applicantCourse: Object,
    completedSteps: {
        type: Array,
        default: () => []
    }
});
console.log(props.applicantCourse)

const itCourses = Object.values(props.courses)
    .filter(course => course.DepartmentCode == 'WCAPS')
    .sort((a, b) => a.CourseDescription.localeCompare(b.CourseDescription));

const engCourses = Object.values(props.courses)
    .filter(course => course.DepartmentCode == 'WENG')
    .sort((a, b) => a.CourseDescription.localeCompare(b.CourseDescription));

const businessCourses = Object.values(props.courses)
    .filter(course => course.DepartmentCode == 'WBM')
    .sort((a, b) => a.CourseDescription.localeCompare(b.CourseDescription));

const errors = ref({});
const success = ref({});
const form = useForm({
    courseCode: '',
    courseDescription: '',
});
const initialMode = ref(null);
const disableSubmitBtn = ref(false);


onMounted(() => {
    if((props.applicantCourse != null)){
        form.courseCode = props.applicantCourse.course_code ;
        initialMode.value = props.applicantCourse.course_code;
    } 
});

function getDescription(description){
    form.courseDescription = description;
}

function submit(){
    disableSubmitBtn.value = true;

    if (form.courseCode === initialMode.value) {
        router.visit('/application/course-type')
    } else {
        router.post('/application/post-pick-course', form, {
            onError : (allErrors) => {
                for(let error in allErrors){
                errors.value[error] = allErrors[error]
                }

                disableSubmitBtn.value = false;
                
            },

        });

    }
 
}

const listItems = ref({});
const filteredItems = ref({});

onMounted(() => {
    if (props.department == 'WCAPS'){
        listItems.value = itCourses
    } else if (props.department == 'WENG'){
        listItems.value = engCourses
    } else if (props.department == 'WBM'){
        listItems.value = businessCourses
    }
    filteredItems.value = { ...listItems.value };
})

function filterList(){
    var searchInput, searchInputValue;

    searchInput = document.getElementById('search_input');
    searchInputValue = searchInput.value.toLowerCase();

    if(searchInputValue != ''){
        const filtered = {};
        
        for(let i = 0; i < Object.keys(listItems.value).length; i++){
            const courseDescription = listItems.value[i]['CourseDescription'].toLowerCase();
            
            if(courseDescription.includes(searchInputValue)){
                filtered[i] = listItems.value[i];
            }
        }
        
        filteredItems.value = filtered;
    } else {
        filteredItems.value = { ...listItems.value };
    }
}
</script>

<template>
    <Head title="Pick Course" />
    <AuthenticatedLayout>
        <StepperComponent :completed-steps="completedSteps" />
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

        <div class="mt-4">
            <form>   
                <div class="flex rounded-full border border-green-500 overflow-hidden max-w-md mx-auto">
                    <input type="text" placeholder="Search Something..." :onkeyup="filterList" id="search_input"
                    class="w-full focus:border-green-500 font-josefin font-bold tracking-wider bg-white text-gray-600 text-sm px-4 py-3" />
                    <button type='button' class="flex items-center justify-center bg-green-900 px-5">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192.904 192.904" width="16px" class="fill-white">
                        <path d="m190.707 180.101-47.078-47.077c11.702-14.072 18.752-32.142 18.752-51.831C162.381 36.423 125.959 0 81.191 0 36.422 0 0 36.423 0 81.193c0 44.767 36.422 81.187 81.191 81.187 19.688 0 37.759-7.049 51.831-18.751l47.079 47.078a7.474 7.474 0 0 0 5.303 2.197 7.498 7.498 0 0 0 5.303-12.803zM15 81.193C15 44.694 44.693 15 81.191 15c36.497 0 66.189 29.694 66.189 66.193 0 36.496-29.692 66.187-66.189 66.187C44.693 147.38 15 117.689 15 81.193z">
                        </path>
                    </svg>
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-12 ">
            
            <form action="" method="post" class="flex flex-col space-y-6" @submit.prevent="submit">
               <div class="">
                    <!-- It Courses section -->
                    <section >
                        <!-- Header -->
                        <div class="p-6 underline underline-offset-4">
                            <h2 class="flex justify-center font-monteserat text-xl tracking-wider">
                                <span v-show="department == 'WCAPS'">
                                    💼 Technology Department
                                </span>

                                 <span v-show="department == 'WBM'">
                                    🖥️ Business Department
                                </span>

                                <span v-show="department == 'WENG'">
                                    🗣️ Language Skills Department
                                </span>
                            </h2>
                        </div>
                         <!-- IT Courses -->
                        <ul class="grid grid-cols-1 w-full gap-6 md:grid-cols-3 mt-2">
                                <li v-for="course in filteredItems" :key="course.CourseCode">
                                    <input type="radio" v-model="form.courseCode" :id="course.CourseCode" :name="course.CourseCode" :value="course.CourseCode" class="hidden peer" 
                                        @change="getDescription(course.CourseDescription)"/>
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
                    <button type="submit" :disabled="disableSubmitBtn" :class="{'cursor-not-allowed' : disableSubmitBtn}"
                        class="flex items-center gap-2 px-6 py-3 text-white text-xl font-josefin tracking-wider font-bold 
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