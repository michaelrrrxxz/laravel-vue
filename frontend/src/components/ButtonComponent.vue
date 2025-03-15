<template>
    <button :class="['btn', buttonClass, 'me-2']" @click="onClick">
      <i :class="['fa', icon, 'me-1']"></i> {{ label }}
    </button>
  </template>
  
  <script setup>
  import { computed } from "vue";
  
  defineProps({
    label: String, // Text for the button
    icon: String,  // FontAwesome icon class (e.g., "fa-eye")
    type: String,  // Button type (primary, danger, etc.)
    onClick: Function // Click event handler
  });
  
  // Compute button class based on type
  const buttonClass = computed(() => {
    return {
      primary: "btn-primary",
      danger: "btn-danger",
    }[defineProps.type] || "btn-secondary";
  });
  </script>
  