<template>
  <card class="card-primary">
    <div slot="image">
      <img
        style="width: 100%"
        :src="image.picture.url"
        :alt="image.picture.filename"
      />
    </div>
    <div class="card-body">
      <div class="card-text">{{ image.caption }}</div>
    </div>
    <div slot="footer">
      <div>
        <button @click="editPicture" class="btn btn-fab btn-primary btn-round">
          <span class="material-icons">edit</span>
        </button>
        <button
          @click="deletePicture(image.id)"
          class="btn btn-fab btn-danger btn-round"
        >
          <span class="material-icons">delete</span>
        </button>
        <button
          @click="publishPicture(image.id)"
          class="btn btn-fab btn-info btn-round"
        >
          <span class="material-icons" v-if="image.published"
            >visibility_off</span
          >
          <span class="material-icons" v-else>visibility</span>
        </button>
      </div>
    </div>
  </card>
</template>
<script>
import Card from "./Cards/Card.vue";
export default {
  props: {
    image: {
      picture: { original: "/vendor/admin/img/placeholde.png" },
      caption: "",
    },
  },
  data() {
    return {};
  },
  methods: {
    editPicture() {
      this.$emit("edit", this.image);
    },
    deletePicture(id) {
      let myParams = { api_token, id };

      let data = new URLSearchParams(myParams).toString();

      axios
        .delete(`/api/admin/pictures?${data}`)
        .then((response) => {
          this.$emit('deleted',id)
        })
        .catch((error) => {
          console.log(error.message);
        });
    },
    publishPicture(id) {
      axios
        .post(
          "/api/admin/pictures/publish",
          { id, api_token },
          {
            headers: {
              Accept: "application/json",
            },
          }
        )
        .then((response) => {
          if (response.data.success) {
            this.$emit("published", response.data.picture);
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    status(value) {
      return value ? "visibility_off" : "visibility";
    },
  },
  components: {
    Card,
  },
};
</script>
