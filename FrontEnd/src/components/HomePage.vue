<template>
  <body>
    <div class="customMargin">
      <h1>ROTTEN TOMATOES</h1>
      <p class="text-decoration-underline">Derniers films ajoutee</p>
      <div class="row">
        <div id="filmDiv" class="col-12 col-lg-3 mx-auto" v-for="film in Films" v-bind:key="film.id">
          <img class="card-img-top" src="../assets/tomato.png" alt="Card image cap" />
          <div class="row">
            <div class="card-body text-align-center">
              <h5 class="card-title">{{ film.title }}</h5>
              <p id="description" style="min-height: 20px" class="card-text">{{ film.description.slice(0, 100) }}...</p>
              <p class="card-text">
                <small class="text-muted text-decoration-underline">Annee: {{ film.release_year }}</small>
              </p>
              <p class="card-text">
                <small class="text-muted text-decoration-underline">Duree: {{ film.length }} minutes</small>
              </p>
              <div class="row">
                <small class="text-muted text-decoration-underline">Acteurs: </small>
                <p id="actors" class="font-weight-light"> {{ film.actors }}
                </p>
              </div>
            </div>
          </div>
          <div class="row ">
            <p class="card-text">
              <small class="text-muted ">Score: {{ film.score }}</small>
            </p>
            <div class=" col text-align-center">
              <star-rating style="display:inline-block" :read-only="true" :rating="parseInt(film.score)"
                :show-rating="false" :increment="0.5" :star-size="30"></star-rating>
            </div>
            <router-link style="max-height:50px" @click="voirFilm(film.id)" :to="'/FilmPage/' + film.id">
              <button id="submit" type="submit" class="btn  w-75">Voir le film</button>
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </body>
</template>
<script>
import StarRating from 'vue-star-rating'
import HeaderPage from "@/components/HeaderPage.vue";
import RechercheFilmService from "@/services/RechercheFilmsService.js";
export default {
  name: "HomePage",
  components: {
    HeaderPage,
    StarRating,
  },
  data() {
    return {
      isConnected: false,
      isAdmin: false,
      btnText: "",
      nomUtilisateur: "",
      Films: [],
      filmChoisi: "",
      rating: 0,
      acteurs: "",
    };
  },
  mounted() {
    document.title = "Acceuil";
    RechercheFilmService.searchLastAddedFilms().then((response) => {
      console.log(response);
      this.Films = response.data;
      for (let i = 0; i < this.Films.length; i++) {
        let acteurs = "";
        for (let j = 0; j < this.Films[i].actors.length; j++) {
          acteurs += this.Films[i].actors[j] + ", ";
        }
        this.Films[i].actors = acteurs;
      }
      for (let i = 0; i < this.Films.length; i++) {
        if (this.Films[i].actors == "") {
          this.Films[i].actors = "AUCUN ACTEUR";
        }
      }
    }).catch((error) => {
      console.log(error);
    })
  },
  methods: {
    voirFilm(p_id) {
      this.filmChoisi = p_id;
      localStorage.setItem("IDFilmChoisis", "");
      localStorage.setItem("IDFilmChoisis", this.filmChoisi);
    },
  },
};
</script>
<style lang="scss" scoped>
@font-face {
  font-family: 'Inter';
  src: url('../assets/fonts/brown/Steak.otf') format('truetype');
}
img {
  width: 75%;
  height: auto;
  margin: auto;
}
body {
  font-family: Inter;
  font-family: 'Inter';
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}
h1 {
  text-align: center;
  font-size: 2em;
  font-weight: bold;
}
p {
  font-size: 1em;
}
button {
  margin-bottom: 20px;
  background-color: red;
  border-color: none;
  color: white;
}
button:hover {
  background-color: white;
  color: red;
  border-color: none;
}
#filmDiv {
  padding-bottom: 2%;
  border-radius: 0% 0% 0% 0% / 0% 0% 0% 0%;
  box-shadow: 20px 20px 20px 20px rgba(0, 0, 0, .15);
  transition: all .4s ease;
}
#filmDiv:hover {
  border-radius: 0% 0% 50% 50% / 0% 0% 5% 5%;
  box-shadow: 10px 10px rgba(0, 0, 0, .25);
}
#submit {
  margin-top: 15px;
}
#actors {
  font-size: 0.8em;
  margin-bottom: 10px;
  font-weight: lighter;
}
#description {
  font-size: 0.8em;
}
</style>