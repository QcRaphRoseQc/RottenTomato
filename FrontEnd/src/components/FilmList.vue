<template>
  <div class="row">
    <div class="bg-white">
      <p v-if="nextPrevious" id="nombreFilms" class="text-decoration-underline">{{ nombreFilms }}</p>
      <p v-if="nextPrevious" id="nombreFilms" class="text-decoration-underline"> {{pageActuelle}} {{seperator}} {{ nombrePages }} </p>
    </div>
    <ul>
  <a v-if="nextPrevious" @click="previousFilms" id="previous">&#8249;</a>
  <a v-if="nextPrevious" @click="nextFilms" id="next">&#8250;</a>
    </ul>
    <div class="col-xl-6 m-0 p-0" v-for="film in Films.slice(premierFilm, dernierFilm)" v-bind:key="film.id" @click="filmClique(film.id)">
      <FilmDetails :film="film"></FilmDetails>
    </div>
  </div>
</template>
<script>
import FilmDetails from '@/components/FilmDetails.vue';
import RechercheFilmsService from '@/services/RechercheFilmsService.js';
export default {
  name: "FilmList",
  data() {
    return {
      Films: [],
      FilmChoisis: "",
      RegexSearch: this.RegexSearch,
      nombreFilms: "",
      premierFilm: 0,
      dernierFilm: 0,
      filmsLength: 0,
      nextPrevious: false,
      nombrePages: "",
      pageActuelle: "",
      seperator: "",
    }
  },
  props: {
    RegexSearch: {
      type: String,
      default: ""
    },
  },
  components: {
    FilmDetails,
    RechercheFilmsService
  },
  created() {
    this.nombreFilms = "";
    localStorage.setItem("nextPrevious", this.nextPrevious);
  },
  methods: {
    filmClique(p_IDFilmChoisis) {
      localStorage.setItem("IDFilmChoisis", p_IDFilmChoisis);
      this.nextPrevious = false;
      localStorage.setItem("nextPrevious", this.nextPrevious);
      this.$router.push("/FilmPage/" + p_IDFilmChoisis);
      localStorage.setItem("keywords","");
      setTimeout(function() {
        location.reload();
      }, 5);
    },
    filteredProducts() {
      this.nextPrevious = true;
      localStorage.setItem("nextPrevious", true);
      RechercheFilmsService.search(this.RegexSearch).then(response => {
        this.Films = response.data;
        this.Films = this.Films.data;
        for (let i = 0; i < this.Films.length; i++) {
          let acteurs = "";
          for (let j = 0; j < this.Films[i].actors.length; j++) {
            acteurs += this.Films[i].actors[j] + ", ";
          }
          this.Films[i].actors = acteurs;
        }
        this.premierFilm = 0;
        this.dernierFilm = 4;
        this.filmsLength = this.Films.length;
        if (response.status == 404) {
          this.nombreFilms = 0;
          this.nombrePages = 0;
          this.seperator = "";
        } else {
          this.nombreFilms = this.Films.length;
          this.nombrePages = Math.ceil(this.Films.length / 4);
          this.pageActuelle = 1;
          this.seperator = " / ";
        }
        this.nombreFilms = "Nombre de film trouve : " + this.Films.length;
        if (this.nombreFilms == "Nombre de film trouve : " + 0) {
          this.nombreFilms = "Aucun film trouvé";
        }
      }).catch(error => {
        console.log(error);
      });
      this.previousFilms();
      return this.Films;
    },
    nextFilms(){
      this.nextPrevious = true;
      localStorage.setItem("nextPrevious",  this.nextPrevious);
      if(this.dernierFilm >= this.filmsLength){
        this.premierFilm =0;
        this.dernierFilm = 4;
        this.pageActuelle = 1;
      }
      else{
        this.premierFilm += 4;
        this.dernierFilm += 4;
        this.pageActuelle++;
        document.getElementById("previous").style.display = "block";        
      }     
    },
    previousFilms(){
      this.nextPrevious = true;
      localStorage.setItem("nextPrevious",  this.nextPrevious);
      if(this.premierFilm <= 0){
        this.premierFilm = this.filmsLength - 4;
        this.dernierFilm = this.filmsLength;
        this.pageActuelle = this.nombrePages;
      }
      else{
        this.premierFilm -= 4;
        this.dernierFilm -= 4;
        this.pageActuelle--;
      }  
    }
  },
  watch: {
    RegexSearch(newValue, oldValue) {
      this.filteredProducts();
    },
  },
}
</script>
<style lang="scss" scoped>
h1 {
  margin-bottom: 10px;
}
#filmList {
  background: white;
  margin-top: 10px;
  margin-bottom: 10px;
  border: 0.5px solid rgb(104, 104, 104);
}
#filmList:hover {
  background-color: rgb(231, 231, 231);
  width: 100%;
}
#film {
  padding: 0px;
}
img {
  width: 6%;
  height: 100%;
}
div#Page.row {
  border: 1px solid rgb(104, 104, 104);
  margin-bottom: 10px;
}
#previous {
   position: absolute;
    left: 0;
   top: 37%;
    font-size: 9em;
    text-decoration: none;
    color:red;
        user-select: none;
        -moz-user-select: none;
        -khtml-user-select: none;
        -webkit-user-select: none;
        -o-user-select: none;
}
#previous:hover {
  color: #2c3e50;
}
#next {
 position: absolute;
    right: 0;
    top: 37%;
    font-size: 9em;
    text-decoration: none;
    color:red;
    user-select: none;
        -moz-user-select: none;
        -khtml-user-select: none;
        -webkit-user-select: none;
        -o-user-select: none;
}
#next:hover {
  color: #2c3e50;
}
ul{
list-style-type:none;
}
</style>