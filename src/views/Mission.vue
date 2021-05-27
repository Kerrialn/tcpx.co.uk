<template>
  <div class="container">
    <div class="row">
      <div class="col">
        <card :title="title" :content="content" />
      </div>
    </div>
  </div>
</template>

<script>
import Card from "../components/Card.vue"
export default {
  name: "About",
  components: {
    Card,
  },
  data() {
    return {
      title: "Mission",
      content:
        "Our mission is to bring  simplicity, order and methodism to chaotic systems and in the process increase maintainability, cost efficiency and narrow the gap between programmers and management.",
    }
  },
}
</script>
