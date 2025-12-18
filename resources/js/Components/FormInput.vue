 <script setup>
defineProps({
    type: {
        type: String,
        default: 'text'
    },
    id: {
        type: String
    },
  
    required: {
        type: Boolean,
        default: false,
    },
    placeholder: {
        type:String
    }
})

const model = defineModel({
    type: [String, Number, Date],
});

</script>

<template>

    <input :type="type" :id="id" v-model="model" :required="required" :placeholder="placeholder"
    class="rounded-md font-josefin font-bold focus:ring-primaryColor focus:border-primaryColor text-sm" >
</template>