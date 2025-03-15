<template>
  <section class="content p-4">
    <div class="container-fluid">
      <h2 class="mb-4">Dashboard</h2>
      <div class="row">
        <CountCard
          :count="userCount"
          title="User Registrations"
          icon="fas fa-user-plus"
          bgColor="bg-warning"
        />

        <CountCard
          :count="batchCount"
          title="Batches Created"
          icon="fas fa-layer-group"
          bgColor="bg-primary"
        />

        <CountCard
          :count="150"
          title="New Orders"
          icon="fas fa-shopping-bag"
          bgColor="bg-info"
        />

        <CountCard
          :count="53"
          title="Bounce Rate"
          icon="fas fa-chart-line"
          bgColor="bg-success"
        />
      </div>
    </div>
  </section>
</template>

<script>
import api from "@/api/axiosInstance";
import CountCard from "@/components/CountCard.vue";

export default {
  components: {
    CountCard
  },
  data() {
    return {
      userCount: 0,
      batchCount: 0
    };
  },
  methods: {
    async fetchCounts() {
      try {
        const response = await api.get("/dashboard");
        this.userCount = response.data["user-count"];
        this.batchCount = response.data["batch-count"];
      } catch (error) {
        console.error("Error fetching counts:", error);
      }
    }
  },
  created() {
    this.fetchCounts();
  }
};
</script>
