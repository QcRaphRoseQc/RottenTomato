<template>
  <div id="filmList" class="row customRounded">
    <div v-if="film" id="film" class="col-xxl-6 d-flex flex-column justify-content-center align-items-center">
      <div class="row">
        <div id="imageDiv" class="row ">
          <img src="../assets/popcorn.png" alt="Rotten Tomatoes" />
        </div>
      </div>
      <div class=" row">
        <div id="stars" class="mx-auto text-muted font-weight-light">
          <star-rating style="display:inline-block" :read-only="true" :rating="parseInt(film.score)"
            :show-rating="false" :increment="0.5" :star-size="30"></star-rating> ({{ film.critics.length }})
        </div>
      </div>
    </div>
    <div id="filmInfo" class="col-lg-6 mx-auto ">
      <div>
        <h4 v-if="film" class="">{{ film.title }}</h4>
      </div>
      <div>
        <span v-if="film">
          <span class="text-muted text-decoration-underline float-left">Annee de parution:</span>
          {{ film.release_year }}
        </span>
      </div>
      <div>
        <span v-if="film">
          <span class="text-muted text-decoration-underline float-left ">rating: </span>{{ film.rating }}
        </span>
      </div>
      <div>
        <span v-if="film">
          <span class="text-muted text-decoration-underline float-left"> Duree: </span>{{ film.length }} Minutes </span>
      </div>
      <div>
        <span v-if="film">
          <span class="text-muted text-decoration-underline float-left">Synopsis: <br />
          </span>{{ film.description }}... </span>
      </div>
      <div class="row">
        <small class="text-muted text-decoration-underline float-left">Acteurs: </small>
        <p id="actors" class=""> {{ film.actors }}</p>
      </div>
    </div>
  </div>
</template>
<script>
import StarRating from 'vue-star-rating'
export default {
  name: "FilmDetails",
  props: {
    film: {
      type: Object,
      default: null,
    },
  },
  components: {
    StarRating,
  },
};
</script>
<style lang="scss" scoped>
h4 {
  text-align: center;
}
#imageDiv {
  margin: 0px auto;
  height: 100%;
}
img {
  width: 90%;
  height: auto;
  margin: 0px auto;
  margin-bottom: 10%;
}
#stars {
  margin: 0px auto;
  margin-top: 4%;
  margin-bottom: 10%;
}
#film {
  flex-direction: column;
}
.customPaddingCenter {
  padding-left: 23%;
}
.customRounded {
  border-radius: 25px;
}
div#filmList {
  padding: 10px;
  font-size: 0.90em;
  text-align: left;
  @media (min-width: 568px) {
    height: 100%;
  }
  border: 2px solid #f5f5f5;
}
#actors {
  font-size: .85em;
}
</style>