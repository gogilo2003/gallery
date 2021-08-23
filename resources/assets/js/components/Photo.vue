<template>
  <div class="card">
    <div class="card-header card-header-image card-header-primary">
      <img style="width: 100%" :src="image.picture.url" :alt="image.src" />
    </div>
    <div class="card-body">
      <div class="card-text">{{ image.caption }}</div>
    </div>
    <div class="card-footer">
      <div>
        <button @click="editPicture" class="btn btn-fab btn-primary btn-round">
          <span class="material-icons">edit</span>
        </button>
        <button @click="deletePicture" class="btn btn-fab btn-danger btn-round">
          <span class="material-icons">delete</span>
        </button>
      </div>
    </div>
    <modal :show="edit">
      <card>
        <template>
          <h4 class="card-title">Edit Picture</h4>
        </template>
        <div class="card-body">
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
        </div>
        <template slot="footer">
          <base-button type="secondary" @click="edit = false"
            >Close</base-button
          >
          <base-button :loading="loading" type="primary" @click="savePicture()"
            >Save changes</base-button
          >
        </template>
      </card>
    </modal>
  </div>
</template>
<script>
import Card from "../components/Card.vue";
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