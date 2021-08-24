<template>
  <div>
    <div class="card">
      <div class="card-header card-header-image card-header-primary">
        <img style="width: 100%" :src="image.picture.url" :alt="image.src" />
      </div>
      <div class="card-body">
        <div class="card-text">{{ image.caption }}</div>
      </div>
      <div class="card-footer">
        <div>
          <button
            @click="editPicture"
            class="btn btn-fab btn-primary btn-round"
          >
            <span class="material-icons">edit</span>
          </button>
          <button
            @click="deletePicture"
            class="btn btn-fab btn-danger btn-round"
          >
            <span class="material-icons">delete</span>
          </button>
        </div>
      </div>
    </div>
    <modal :show="edit">
      <template>
        <h4 class="text-uppercase">Edit Picture</h4>
      </template>
      <template>
          <div>
            <img id="image" src="/vendor/admin/img/placeholder.png" />
          </div>
          <div class="form-group">
            <input
              type="text"
              class="form-control"
              name="title"
              id="title"
              aria-describedby="titleHelpId"
              placeholder="Title"
            />
            <small id="titleHelpId" class="form-text text-muted"
              >Enter Title</small
            >
          </div>
      </template>
      <template slot="footer">
        <button class="btn btn-danger" @click="edit = false">Close</button>
        <button
          :loading="loading"
          class="btn btn-primary"
          @click="savePicture()"
        >
          Save changes
        </button>
      </template>
    </modal>
  </div>
</template>
<script>
import Card from "./Cards/Card.vue";
import Modal from "../components/Modal.vue";
export default {
  props: {
    image: {
      src: "image.png",
      caption: "",
    },
  },
  data() {
    return {
      edit: false,
      delete: false,
      loading: false,
    };
  },
  methods: {
    editPicture() {
      this.edit = true;
    },
    deletePicture() {
      alert("DELETE");
    },
  },
  components: {
    Modal,
    Card,
  },
};
</script>

<style scoped>
#image {
  max-width: 100%;
}
</style>