<template>
  <div class="container mx-auto">
    <div class="">
      <div id="Reviews" class="mx-auto">
        <div class="row">
          <h2>REVIEWS</h2>
        </div>
        <div class="row d-flex justify-content-center">
          <div id="review" class="col-6" v-for="critic in Critics" v-bind:key="critic.id">
            <span>{{ critic.first_name }} {{ critic.last_name }} : {{ critic.comment }}
              <br>{{ critic.updated_at }}
            </span>
            <br>
            <star-rating style="display:inline-block" :read-only="true" :rating="parseInt(critic.score)"
              :show-rating="false" :increment="0.5" :star-size="25"></star-rating>
          </div>
        </div>
      </div>
      <div class="mx-auto">
        <div id="commentaire" class="row mx-auto" v-if="isConnected">
          <div class="text-decoration-underline mx-auto">
            <h2>{{ btnText }}</h2>
            <p id="error">{{ error }}</p>
          </div>
          <div class="mx-auto w-75">
            <div class="row">
              <textarea v-model="userCommentaire" class="form-control" aria-label="With textarea"></textarea>
            </div>
            <div class="row my-2">
              <label> Donnez une note!</label>
            </div>
            <div class="row">
              <span class="mb-3 text-align-center">
                <star-rating style="display:inline-block" :show-rating="false" @update:rating="setRating = $event"
                  :increment="0.5" :star-size="30"></star-rating>
              </span>
              <button @click="postCritics" type="button" class="btn">{{ btnText }}!</button>
            </div>
          </div>
        </div>
        <p id="erreur"></p>
        <button v-if="isAdmin" @click="onSubmitSupprimer" type="button" class="btn">Supprimer le film !</button>
      </div>
    </div>
  </div>
</template>
<script>
import RechercheFilmsService from '@/services/RechercheFilmsService.js';
import RechercheFilmSeul from '@/services/RechercheFilmSeul.js';
import StarRating from 'vue-star-rating';
import moment from 'moment';
import 'moment/locale/fr';
import AdminMenuService from '@/services/AdminMenuService';
export default {
  name: "Commentaires",
  data() {
    return {
      Critics: [],
      isConnected: false,
      isAdmin: false,
      score: 0,
      token: "",
      role: "",
      userId: "",
      userCommentaire: "",
      setRating: 0,
      CriticsFiltrer: [],
      currentUserCritics: "",
      isUserCritics: false,
      userCriticId: "",
      btnText: "",
      error: "",
    }
  },
  props: {
    IDFilmChoisis: {
      type: String,
      default: null
    },
  },
  components: {
    StarRating,
  },
  created() {
    this.afficherCritiques();
    this.setRating = 0;
  },
  watch: {
    IDFilmChoisis() {
      this.afficherCritiques();
    },
    isUserCritics() {
    }
  },
  methods: {
    postCritics() {
      document.getElementById("error").style.color = "red";
      if (this.setRating == undefined) {
        this.error = "Veuillez donner une note!";
      }
      else if (this.userCommentaire == "") {
        this.error = "Veuillez donner un commentaire!";
      } else {
        if (this.isUserCritics == true) {
          RechercheFilmsService.putCritics(this.userCriticId, this.userCommentaire, this.setRating).then(response => {
            console.log(response);
            this.afficherCritiques();
            if (response.status == 200) {
              document.getElementById("error").style.color = "green";
              this.error = "Commentaire mis a jour";             
            }
          }).catch(error => {
            this.error = "Une erreur est survenue";
          });
        }
        else {
          RechercheFilmsService.ajoutCritics(this.userCommentaire, this.IDFilmChoisis, this.setRating).then(response => {
            console.log(response);
            this.Critics = response.data;
            if (response.status == 201) {
              document.getElementById("error").style.color = "green";
              this.error = "Commentaire ajouté";
               setTimeout(() => {
               window.location.reload();
              }, 50);             
            }
          }).catch(error => {
            console.log(error);
          });
        }
      }


    },
    afficherCritiques() {
      this.token = localStorage.getItem("token");
      this.role = localStorage.getItem("role");
      this.userId = localStorage.getItem("id");
      this.userId = parseInt(this.userId);
      if (this.token != "" && this.role == "1" || this.role == "2") {
        this.isConnected = true;
      } else {
        this.isConnected = false;
      }
      if (this.role == 1) {
        this.isAdmin = true;
      } else {
        this.isAdmin = false;
      }
      if (this.IDFilmChoisis != null) {
        RechercheFilmSeul.search(this.IDFilmChoisis).then(response => {
          console.log(response);
        }).catch(error => {
          //go back to the home page
          window.location.href = "/";
        });
      }
      RechercheFilmsService.getCritics(this.IDFilmChoisis).then(response => {
        for (let i = 0; i < response.data.data.length; i++) {
          if (response.data.data[i].user_id !== this.userId) {
            this.Critics.push(response.data.data[i]);
            this.btnText = "Modifier";
          }
          else {
            this.btnText = "Supprimer";
            this.userCriticId = response.data.data[i].id;
            this.isUserCritics = true;
            this.userCommentaire = response.data.data[i].comment;
          }
        }
        if (this.isUserCritics == true) {
          this.btnText = "Modifier le commentaire";
        }
        else {
          this.btnText = "Ajouter un commentaire";
        }
        this.setRating = response.data.data.score;
        moment.locale('fr');
        for (let i = 0; i < this.Critics.length; i++) {
          this.Critics[i].updated_at = moment (new Date(this.Critics[i].updated_at)).format('DD MMMM YYYY');
        }
      }).catch(error => {
        console.log(error);
      });
    },
    onSubmitSupprimer() {
      let erreur = document.getElementById("erreur");
      erreur.innerText = "";
      let id = this.$route.params.id;
      AdminMenuService.supprimerFilm(id).then(response => {
        console.log(response);
        erreur.innerText = "Film supprime";
      }).catch(error => {
        console.log(error);
        erreur.innerText = "Erreur lors de la suppression du film";
      })
    }
  }
}
</script>
<style lang="scss" scoped>
body {
  background-color: #f7f6f6;
}

.card {
  width: 350px;
  border: none;
  box-shadow: 5px 6px 6px 2px #e9ecef;
  border-radius: 12px;
}

.circle-image img {
  border: 6px solid #fff;
  border-radius: 100%;
  padding: 0px;
  top: -28px;
  position: relative;
  width: 70px;
  height: 70px;
  border-radius: 100%;
  z-index: 1;
  background: #e7d184;
  cursor: pointer;
}

.dot {
  height: 18px;
  width: 18px;
  background-color: blue;
  border-radius: 50%;
  display: inline-block;
  position: relative;
  border: 3px solid #fff;
  top: -48px;
  left: 186px;
  z-index: 1000;
}

.name {
  margin-top: -21px;
  font-size: 18px;
}

button {
  margin-top: auto;
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

#Reviews {
  width: 50%;
  padding: 20px;
  margin: 10px;
  border: 1px solid #e9ecef;
  box-shadow: 5px 5px 24px 15px rgba(0, 0, 0, 0.31);
  font-size: 0.85em;
  ;
}

#commentaire {
  width: 50%;
  margin: 20px;
  box-shadow: 5px 5px 24px 15px rgba(0, 0, 0, 0.31);
  padding-top: 15px;
}

h2 {
  font-size: 20px;
}
</style>