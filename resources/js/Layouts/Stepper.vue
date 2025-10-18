<script setup>
import { Link } from '@inertiajs/vue3';

// Accept completed steps from Laravel controller
const props = defineProps({
  completedSteps: {
    type: Array,
    default: () => []
  }
});

// Application steps based on Laravel routes
const steps = [
  // Application routes
  { id: 1, title: "Study Mode", route: "mode.of.study", completed: props.completedSteps.includes('mode.of.study') },
  { id: 2, title: "Department", route: "department", completed: props.completedSteps.includes('department') },
  { id: 3, title: "Pick Course", route: "pick.course", completed: props.completedSteps.includes('pick.course') },
  { id: 4, title: "Course Levels", route: "course.levels", completed: props.completedSteps.includes('course.levels') },
  { id: 5, title: "Course Type", route: "course-type", completed: props.completedSteps.includes('course-type') },
  { id: 6, title: "Start Date", route: "class.start.date", completed: props.completedSteps.includes('class.start.date') },
  { id: 7, title: "Start Time", route: "class.start.time", completed: props.completedSteps.includes('class.start.time') },
  { id: 8, title: "Course Summary", route: "course.summary", completed: props.completedSteps.includes('course.summary') },
  { id: 9, title: "Bio Data", route: "start.bio.data", completed: props.completedSteps.includes('start.bio.data') },
  
  // Bio Data routes
  { id: 10, title: "Full Name", route: "full.name", completed: props.completedSteps.includes('full.name') },
  { id: 11, title: "Contacts", route: "contacts", completed: props.completedSteps.includes('contacts') },
  { id: 12, title: "Nationality", route: "nationality", completed: props.completedSteps.includes('nationality') },
  { id: 13, title: "Email", route: "email.address", completed: props.completedSteps.includes('email.address') },
  { id: 14, title: "Residence", route: "residence", completed: props.completedSteps.includes('residence') },
  { id: 15, title: "Marketing", route: "marketing", completed: props.completedSteps.includes('marketing') },
  { id: 16, title: "Allergies", route: "allergies", completed: props.completedSteps.includes('allergies') },
  { id: 17, title: "Allergy Details", route: "allergy.description", completed: props.completedSteps.includes('allergy.description') },
  { id: 18, title: "Emergency Contact", route: "emergency.contact", completed: props.completedSteps.includes('emergency.contact') },
  { id: 19, title: "Upload ID", route: "upload.id", completed: props.completedSteps.includes('upload.id') },
  { id: 20, title: "Upload Photo", route: "upload.photo", completed: props.completedSteps.includes('upload.photo') },
  { id: 21, title: "Bio Summary", route: "bio.data.summary", completed: props.completedSteps.includes('bio.data.summary') },
  { id: 22, title: "Student ID", route: "student.id", completed: props.completedSteps.includes('student.id') },
  
  // Regulations routes
  { id: 23, title: "Rules & Regulations", route: "rules.regulations", completed: props.completedSteps.includes('rules.regulations') },
  
  // Payment routes
  { id: 32, title: "Amount Payable", route: "amount.payable", completed: props.completedSteps.includes('amount.payable') },
  { id: 33, title: "Payment Info", route: "payment.instructions", completed: props.completedSteps.includes('payment.instructions') },
  { id: 34, title: "Update Payment", route: "update.payment", completed: props.completedSteps.includes('update.payment') },
  
  // Final steps
  { id: 35, title: "Admission Letter", route: "admission.letter", completed: props.completedSteps.includes('admission.letter') },
  { id: 36, title: "Complete", route: "final", completed: props.completedSteps.includes('final') }
];
</script>

<template>
  <!-- Horizontal scrollable container -->
  <div class="w-full overflow-x-auto">
    <ol class="flex items-center min-w-max space-x-4 px-4 py-2">
      <li 
        v-for="(step, index) in steps" 
        :key="step.id"
        class="relative flex-shrink-0"
      >
        <Link 
          :href="route(step.route)" 
          class="flex items-center font-medium whitespace-nowrap cursor-pointer hover:opacity-80 transition-opacity"
          :class="step.completed ? 'text-indigo-600' : 'text-gray-900'"
        >
          <span 
            class="w-6 h-6 border rounded-full flex justify-center items-center mr-3 text-sm lg:w-8 lg:h-8"
            :class="step.completed 
              ? 'bg-indigo-600 border-transparent text-white' 
              : 'bg-gray-50 border-gray-200 text-gray-900'"
          >
            {{ step.id }}
          </span>
          <div class="block">
            <h4 
              class="text-base"
              :class="step.completed ? 'text-indigo-600' : 'text-gray-900'"
            >
              {{ step.title }}
            </h4>
          </div>
          <!-- Arrow icon - hide on last item -->
          <svg 
            v-if="index < steps.length - 1"
            class="w-5 h-5 ml-2 sm:ml-4" 
            :class="step.completed ? 'stroke-indigo-600' : 'stroke-gray-900'"
            viewBox="0 0 24 24" 
            fill="none" 
            xmlns="http://www.w3.org/2000/svg"
          >
            <path 
              d="M5 18L9.67462 13.0607C10.1478 12.5607 10.3844 12.3107 10.3844 12C10.3844 11.6893 10.1478 11.4393 9.67462 10.9393L5 6M12.6608 18L17.3354 13.0607C17.8086 12.5607 18.0452 12.3107 18.0452 12C18.0452 11.6893 17.8086 11.4393 17.3354 10.9393L12.6608 6" 
              stroke="currentColor" 
              stroke-width="1.6" 
              stroke-linecap="round" 
            />
          </svg>
                  </Link>
      </li>
    </ol>
  </div>
  
  <!-- Optional: Add scroll indicators -->
  <div class="flex justify-center mt-2">
    <div class="text-xs text-gray-500">
      ← Scroll horizontally to see all steps →
    </div>
  </div>
</template>