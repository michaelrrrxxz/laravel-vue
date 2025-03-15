<template>
  <footer class="main-footer">
    <strong>Copyright &copy; 2003-{{ currentYear }} <a href="https://github.com/michaelrrrxxz">Michael Mangaoang</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0
    </div>
  </footer>
</template>

<script>
export default {
  data() {
    return {
      currentYear: new Date().getFullYear(),
    };
  },
};
</script>
