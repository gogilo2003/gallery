<template>
  <div class="list-group">
    <div
      class="list-group-item list-group-item-action"
      @click="modal.show = true"
    >
      Create Album
    </div>
    <div
      @click="changeAlbum({ id: 0 })"
      key="-1"
      class="list-group-item list-group-item-action"
      :class="{ active: selectedAlbum.id == 0 }"
    >
      All
    </div>
    <div
      @click="changeAlbum(album)"
      v-for="(album, index) in albums"
      :key="album.id"
      class="list-group-item list-group-item-action"
      :class="{ active: album.id == selectedAlbum.id }"
    >
      <div class="text-uppercase">
        {{ album.title }}
      </div>
      <div class="buttons">
        <button
          class="btn-fab btn btn-link text-danger btn-sm btn-round"
          @click="deleteAlbum(album.id, index)"
        >
          <span class="material-icons">delete</span>
        </button>
        <button
          class="btn-fab btn btn-link text-primary btn-sm btn-round"
          @click="editAlbum(album)"
        >
          <span class="material-icons">edit</span>
        </button>
      </div>
    </div>
    <modal :show.sync="modal.show">
      <card>
        <template slot="header">Album</template>
        <template>
          <div class="form-group">
            <label for="titleInput">Title</label>
            <input
              type="text"
              class="form-control"
              name="titleInput"
              id="titleInput"
              aria-describedby="titleInputId"
              placeholder="Title"
              v-model="selectedAlbum.title"
            />
          </div>
          <div class="form-group">
            <label for="descriptionInput">Description</label>
            <textarea
              v-model="selectedAlbum.description"
              class="form-control"
              name="descriptionInput"
              id="descriptionInput"
              rows="3"
            ></textarea>
          </div>
        </template>
        <template slot="footer">
          <button class="btn btn-danger" @click="modal.show = false">
            Close
          </button>
          <button class="btn btn-primary" @click="saveAlbum">Save</button>
        </template>
      </card>
    </modal>
  </div>
</template>

<script>
import Card from "./Cards/Card.vue";
import Modal from "./Modal.vue";

export default {
  components: { Card, Modal },
  props: {
    pictures: [],
  },
  data() {
    return {
      albums: [],
      photos: [],
      selectedAlbum: { id: 0 },
      modal: {
        show: false,
      },
      edit: false,
    };
  },
  methods: {
    getAlbums() {
      axios.get("/api/admin/albums?api_token=" + api_token).then((response) => {
        this.albums = response.data;
        this.$emit("updated:albums", response.data);
      });
    },
    getPictures() {
      axios
        .get("/api/admin/pictures?api_token=" + api_token)
        .then((response) => {
          this.photos = response.data;
          this.$emit("changed:pictures", this.photos);
        });
    },
    changeAlbum(album) {
      this.selectedAlbum = album;
      this.edit = true;
      if (this.selectedAlbum.id == 0) {
        this.$emit("changed:pictures", this.photos);
      } else {
        this.$emit("changed:pictures", album.pictures);
      }
    },
    saveAlbum() {
      this.selectedAlbum.api_token = api_token;
      if (this.edit) {
        axios
          .patch("/api/admin/albums", this.selectedAlbum)
          .then((response) => {
            this.edit = false;
            this.modal.show = false;
            this.selectedAlbum = { id: 0 };
          });
      } else {
        axios.post("/api/admin/albums", this.selectedAlbum).then((response) => {
          this.edit = false;
          this.modal.show = false;
          this.selectedAlbum = { id: 0 };
          this.albums.push(response.data.album);
        });
      }
    },
    deleteAlbum(id, index) {
      axios
        .delete(`/api/admin/albums?id=${id}&api_token=${api_token}`)
        .then((response) => {
          if (response.data.success) {
            this.albums.splice(index, 1);
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    editAlbum(album) {
      this.selectedAlbum = album;
      this.edit = true;
      this.modal.show = true;
    },
  },
  created() {
    this.getAlbums();
    this.getPictures();
  },
};
</script>

<style>
.list-group-item.active {
  background-color: #8bc34a !important;
}
.list-group-item.active .text-primary{
  color: #fff !important;
}
</style>