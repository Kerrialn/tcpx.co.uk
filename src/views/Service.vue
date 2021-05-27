<template>
  <div class="container">
    <div class="row">
      <div class="col col-3">
        <card
          :title="service.title"
          :color="service.color"
          :content="service.description"
        />
        <div class="muted">Call us for a free quotation</div>
      </div>
      <div class="col col-9">
        <card
          v-for="step in service.steps"
          :key="step.title"
          :color="service.color"
          :number="step.order"
          :title="step.title"
          :content="step.description"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex"
import Card from "../components/Card.vue"
export default {
  components: { Card },
  name: "Service",
  props: {
    slug: String,
  },
  computed: {
    ...mapGetters({
      service: "services/getService",
    }),
  },
  mounted() {
    this.$store.dispatch("services/fetchService", this.slug)
  },
}
</script>

<style></style>
