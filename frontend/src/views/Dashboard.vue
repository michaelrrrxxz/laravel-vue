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
          :count="enrolledStudentCount"
          title="Enrolled Students"
          icon="fas fa-user-graduate"
          bgColor="bg-info"
        />

        <CountCard
          :count="53"
          title="Bounce Rate"
          icon="fas fa-chart-line"
          bgColor="bg-success"
        />
      </div>

      <div class="row mt-4">
        <div class="col-12">
          <apexchart type="bar" height="350" :options="chartOptions" :series="chartSeries"></apexchart>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import api from "@/api/axiosInstance";
import CountCard from "@/components/CountCard.vue";
import VueApexCharts from "vue3-apexcharts";

export default {
  components: {
    CountCard,
    apexchart: VueApexCharts
  },
  data() {
    return {
      userCount: 0,
      batchCount: 0,
      enrolledStudentCount: 0,
      studentsByCourse: [],
      chartOptions: {
        chart: {
          id: "students-by-course"
        },
        xaxis: {
          categories: []
        }
      },
      chartSeries: [
        {
          name: "Students",
          data: []
        }
      ]
    };
  },
  methods: {
    async fetchCounts() {
      try {
        const response = await api.get("/dashboard");
        this.userCount = response.data["user-count"];
        this.batchCount = response.data["batch-count"];
        this.enrolledStudentCount = response.data["enrolled-student-count"];
        this.studentsByCourse = response.data["students-by-course"];

        // Prepare chart data
        this.chartOptions.xaxis.categories = this.studentsByCourse.map(item => item.course);
        this.chartSeries[0].data = this.studentsByCourse.map(item => item.count);
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