<template>
  <div v-if="loading" class="loader-wrapper" @click="close">
    <div class="loader-container">
      <div class="loader"></div>
      <div class="text">
        <span v-if="text">{{ text }}</span>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    loading: {
      type: Boolean,
      default: false,
    },
    text: {
      type: String,
      default: null,
    },
    closable: {
      type: Boolean,
      default: false,
    },
  },
  methods: {
    close() {
      if (this.closable) {
        this.$emit("update:loading", false);
      }
    },
  },
};
</script>
<style scoped lang="scss">
div.loader-wrapper {
  display: flex;
  position: fixed;
  height: 100vh;
  width: 100%;
  background-color: rgba(0, 0, 0, 0.25);
  z-index: 9999;
  top: 0;
  left: 0;
  align-items: center;
  justify-content: center;
  .loader-container {
    position: relative;
    .text {
      display: flex;
      position: absolute;
      align-items: center;
      justify-content: center;
      color: #8bc34a;
      font-weight: 600;
      font-size: 12pt;
      text-align: center;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
    }
    .loader {
      border: 2px solid #f3f3f3; /* Light grey */
      border-top: 2px solid #8bc34a; /* Blue */
      border-radius: 50%;
      width: 100px;
      height: 100px;
      animation: spin 2s linear infinite;
    }
  }
}
@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>
