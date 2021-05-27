<template>
  <div class="container">
    <div class="row">
      <div class="col">
        <router-link
          class="card-link"
          v-for="service in services"
          :key="service.title"
          :to="{ name: 'Service', params: { slug: service.slug } }"
        >
          <card
            class="card"
            link
            :color="service.color"
            :title="service.title"
            :content="service.description"
          />
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex"
import Card from "../components/Card.vue"
export default {
  components: { Card },
  name: "Services",
  computed: {
    ...mapGetters({
      services: "services/getServices",
    }),
  },
}
</script>

<style scoped></style>
