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
      title: "About us",
      content:
        "We are a small team of passionate programmers, based in Prague, who are dedecated to providing high quality IT solution services.",
    }
  },
}
</script>
