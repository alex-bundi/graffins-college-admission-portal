<script setup>
// defineProps(['type', 'id', 'inputname'])
defineProps({
    forInput: {
        type: String
    },
    labelName: {
        type: String,
        required: true
    }
  
})


</script>

<template>

    <label :for="forInput" class="block font-monteserat tracking-wider font-medium mb-2 " >{{ labelName }}</label>
</template>