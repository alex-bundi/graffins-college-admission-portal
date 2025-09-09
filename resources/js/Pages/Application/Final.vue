<script setup>
import { ref } from 'vue';
import { Head, Link, useForm,router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Notifications from '@/Layouts/Notifications.vue';




const errors = ref({});
const success = ref({});
const form = useForm({
    startDate: '',

});

function submit(){

  
    router.post('/application/post-class-start-date', form, {
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
    <Head title="Last Step" />
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
                    🎉 Thank You!
                </h1>

                <p class="font-josefin font-bold text-base sm:text-xl tracking-wider">
                    Thank you for choosing Graffins College – we're excited to have you on board!
                </p>
            </div>
        </div>

      
        <div class="mt-4">
                <p class="font-josefin font-bold text-base sm:text-xl tracking-wider">
                    🎓 Your journey to success starts here, and we’ll be with you every step of the way.

                </p>

                 <p class="font-josefin font-bold text-base sm:text-xl tracking-wider">
                    If you need any help, our Student Support Team is always just a message away.
                    Welcome to the Graffins family! 💙

                </p>
               
        </div>

        <div class="mt-5">
            <img src="/storage/images/stamp.png"></img>
        </div>

        
       


    </AuthenticatedLayout>
</template>