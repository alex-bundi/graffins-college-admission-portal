<script setup>
import { Head, Link, useForm,router } from '@inertiajs/vue3';
import ApplicationLayout from '@/Layouts/ApplicationLayout.vue';
import Notifications from '@/Layouts/Notifications.vue';
import FormInput from '@/Components/FormInput.vue';
import FormInputLabel from '@/Components/FormInputLabel.vue';
import { ref, onMounted } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';


const errors = ref({});
const success = ref({});
const form = useForm({
    firstName: '',
    secondName: '',
    lastName: '',
    email: '',
    password: '',
});
const disableSubmitBtn = ref(false);

function submit(){

    disableSubmitBtn.value = true;

    if (form.firstName == null){
        form.errors.firstName = 'First Name is required'; 
    }
    if (form.secondName == null){
        form.errors.secondName = 'Second Name is required'; 
    }
    if (form.lastName == null){
        form.errors.lastName = 'Last Name is required'; 
    }

    if (form.email == null){
        form.errors.email = 'Email is required'; 
    }
    if (form.password == null){
        form.errors.password = 'Password is required'; 
    }
    

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
    <Head title="Register" />
    <GuestLayout>
        <div class="flex flex-row space-x-6 items-center">
             <div>
                <div
                    class="inline-block rounded-md h-20 min-h-[1em] w-0.5 self-stretch bg-green-400 dark:bg-white/10"></div>
            </div>
            <div>
                <h1 class="font-monteserat text-xl tracking-wider md:text-4xl">
                    Register 
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
                            <FormInputLabel for-input="first_name" label-name="First Name" class="" />
                            <span class="font-josefin tracking-wider font-bold text-base text-red-500">
                                *
                            </span>
                        </div>
                        <FormInput 
                            type="text"
                            id="first_name"
                            v-model="form.firstName"
                            class="py-2.5 sm:py-3 px-4 block w-full text-base font-josefin font-bold tracking-wider"
                            
                            required/> 
                            
                        <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.firstName">{{ form.errors.firstName }}</div>
                        
                    </div>

                    <div class="max-w-sm" >
                        <div class="flex flex-row space-x-2">
                            <FormInputLabel for-input="second_name" label-name="Second Name" class="" />
                            <span class="font-josefin tracking-wider font-bold text-base text-red-500">
                                *
                            </span>
                        </div>
                        <FormInput 
                            type="text"
                            id="second_name"
                            v-model="form.secondName"
                            class="py-2.5 sm:py-3 px-4 block w-full text-base font-josefin font-bold tracking-wider"
                            
                            required/> 
                            
                        <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.secondName">{{ form.errors.secondName }}</div>
                        
                    </div>

                    <div class="max-w-sm" >
                        <div class="flex flex-row space-x-2">
                            <FormInputLabel for-input="last_name" label-name="Last Name" class="" />
                            <span class="font-josefin tracking-wider font-bold text-base text-red-500">
                                *
                            </span>
                        </div>
                        <FormInput 
                            type="text"
                            id="last_name"
                            v-model="form.lastName"
                            class="py-2.5 sm:py-3 px-4 block w-full text-base font-josefin font-bold tracking-wider"
                            
                            required/> 
                            
                        <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.lastName">{{ form.errors.lastName }}</div>
                        
                    </div>

                    <!--  Email -->
                    <div class="max-w-sm" >
                        <div class="flex flex-row space-x-2">
                            <FormInputLabel for-input="_email" label-name="Email" class="" />
                            <span class="font-josefin tracking-wider font-bold text-base text-red-500">
                                *
                            </span>
                        </div>
                        <FormInput 
                            type="email"
                            id="_email"
                            v-model="form.email"
                            class="py-2.5 sm:py-3 px-4 block w-full font-josefin font-bold tracking-wider"
                            
                            required/> 
                            
                        <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.email">{{ form.errors.email }}</div>
                        
                    </div>

                    <!-- Password -->
                    <div class="max-w-sm" >
                        <div class="flex flex-row space-x-2">
                            <FormInputLabel for-input="password" label-name="Password" class="" />
                            <span class="font-josefin tracking-wider font-bold text-base text-red-500">
                                *
                            </span>
                        </div>
                        
                        <FormInput 
                            type="text"
                            id="password"
                            v-model="form.password"
                            class="py-2.5 sm:py-3 px-4 block w-full font-josefin font-bold tracking-wider"
                            
                            required/> 
                            
                        <div class="text-red-500 tracking-wider font-josefin font-bold m-2 text-sm" v-if="form.errors.password">{{ form.errors.password }}</div>
                        
                    </div>

                    <div v-show="disableSubmitBtn == false" >
                            <div class="max-w-sm mt-5" id="sign-in-btn"> 
                                <button type="submit" :disabled="form.processing" class="py-2.5 sm:py-3 px-4  block w-full items-center gap-x-2 font-league tracking-wider text-sm font-medium rounded-lg border border-transparent 
                                    bg-primaryColor text-white hover:bg-darkPrimaryColor focus:outline-hidden focus:bg-primaryColor disabled:opacity-50 
                                    disabled:pointer-events-none">
                                    Create Account
                                </button>

                            
                            </div>


                        <div class="flex flex-col items-center space-y-2 justify-center max-w-sm mt-4 font-josefin font-bold tracking-widest text-sm">
                            <div class="">
                                <div class="flex flex-row space-x-2 ">
                                    <p>
                                        Already have an account?
                                    </p>
                                    <Link :href="route('login')" class="text-primaryColor hover:font-bold hover:text-darkPrimaryColor">Sign In</Link>
                                </div>
                            </div>

                            <div>
                                <Link :href="route('home')"  class="text-primaryColor hover:font-bold hover:text-darkPrimaryColor">
                                    Back to home page
                                </Link>
                            </div>

                            
                        </div>
                    </div>
                    <div v-show="disableSubmitBtn" class="max-w-sm mt-5">
                        <button disabled type="button" class="text-white bg-amber-700 hover:bg-amber-800 cursor-not-allowed
                            font-medium rounded-lg font-monteserat tracking-wider text-sm px-5 py-2.5 text-center me-2 inline-flex items-center">
                            <svg aria-hidden="true" role="status" class="inline w-4 h-4 me-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                            </svg>
                            Creating Account...
                        </button>
                    </div>
                </div>
            </form>

        </div>





    </GuestLayout>
</template>
