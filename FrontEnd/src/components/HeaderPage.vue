<template>
  <nav class="navbar navbar-expand ">
    <div class=" row w-100">
      <div class=" col-4">
        <div class="col-lg-4 my-auto h-100">
          <routerLink to="/" href="#">
            <img id="logo" src="../assets/rottenTomatoes.png" alt="RottenTomatoes" />
          </routerLink>
        </div>
      </div>
      <div class=" col-4 mx-auto my-auto">
        <input class="form-control font-weight-bold-italic" type="search" placeholder="RECHERCHER UN FILM"
          aria-label="RECHERCHER UN FILM" v-model="RegexSearch" id="rechercheInput" onfocus="this.placeholder=''"
          onblur="this.placeholder='RECHERCHER UN FILM'" autocomplete="off">
      </div>
      <div class=" col-4 my-auto">
        <div class=" navbar-collapse justify-content-end" id="navbarSupportedContent">
          <ul v-if="isConnected" id="menu" class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">{{ this.nomUtilisateur }}
              </a>
              <ul id="drop" class="dropdown-menu " aria-labelledby="navbarDropdown">
                <li>
                  <router-link to="/" class="dropdown-item">Accueil</router-link>
                </li>
                <li>
                  <router-link to="/Profil" class="dropdown-item">Profil</router-link>
                </li>
                <li v-if="isConnected && isAdmin">
                  <router-link to="/AdminMenu" class="dropdown-item">Admin Menu</router-link>
                </li>
                <li @click="logout">
                  <button id="Logout" class="dropdown-item" type="submit"> Deconnection </button>
                </li>
              </ul>
            </li>
          </ul>
          <form v-if="!isConnected" id="formButton" class="d-flex flex-column justify-content-center px-0 mx-0">
            <div class="row">
              <router-link to="/Connexion">
                <button @click="connectSubmit" class="w-100 mb-2" type="submit"> Connexion </button>
              </router-link>
            </div>
            <div class="row">
              <router-link to="/CreationCompte">
                <button class="w-100 mb-2" type="submit"> Creez un Compte </button>
              </router-link>
            </div>
          </form>
        </div>
      </div>
    </div>
  </nav>
  <div class="position-absolute w-75 mx-auto zIndex-2 rounded-top" id="filmlist2" @click="filmClique()"
    v-if="RechercheEnCours">
    <FilmList :RegexSearch="RegexSearch"></FilmList>
  </div>
</template>
<script>
import FilmList from "@/components/FilmList.vue";
export default {
  name: "HeaderPage",
  data() {
    return {
      isConnected: false,
      isAdmin: false,
      btnText: "",
      nomUtilisateur: "",
      RegexSearch: "",
      RechercheEnCours: false,
      listFilm: [],
      isFocus: false,
      nextPreview: false,
      userUrlBeforeClick: "",
    };
  },
  created() {
    localStorage.setItem("keywords", "");
    let token = localStorage.getItem("token");
    let admin = localStorage.getItem("role");
    this.nomUtilisateur = localStorage.getItem("login");
    this.nomUtilisateur = this.nomUtilisateur;
    if (token == "" || token == null || token == undefined) {
      this.isConnected = false;
      this.btnText = "Creez votre compte";
      this.nomUtilisateur = "";
    } else {
      this.isConnected = true;
      this.btnText = "Modifier votre compte";
      if (admin == "1") {
        this.isAdmin = true;
      } else {
        this.isAdmin = false;
      }
    }
    localStorage.setItem("isConnected", this.isConnected);
  },
  methods: {
    logout() {
      localStorage.setItem("token", "");
      localStorage.setItem("id", "");
      localStorage.setItem("role", "");
      this.nomUtilisateur = "";
      this.isConnected = false;
      this.$router.push("/");
    },
    filmClique() {
      document.getElementById("rechercheInput").value = "";
      this.RechercheEnCours = false;
    },
    recherche() {
      this.RechercheEnCours = false;
    },
    connectSubmit() {
      this.userUrlBeforeClick = window.location.pathname;
      localStorage.setItem("userUrlBeforeClick", this.userUrlBeforeClick);
    },
  },
  components: {
    FilmList,
  },
  watch: {
    RegexSearch(newValue, oldValue) {
      if (newValue != oldValue) {
        if (newValue.trim(" ").length === 0) {
          this.RechercheEnCours = false;
        } else {
          this.RechercheEnCours = true;
        }
        localStorage.setItem("keywords", newValue);
      }
    },
    RechercheEnCours(newValue, oldValue) {
      if (localStorage.getItem("nextPrevious") == "true") {
        this.RechercheEnCours = true;
      }
      else {
        this.RechercheEnCours = false;
      }
      this.RegexSearch = localStorage.getItem("keywords");
      if (this.RegexSearch == "") {
        this.RechercheEnCours = false;
      } else {
        this.RechercheEnCours = true;
      }
    }
  }
};
</script>
<style lang="scss" scoped>
a {
  font-size: 1.55rem;
}
.nav {
  background-color: #333;
  overflow: hidden;
  color: red;
  margin: 0px;
}
#rechercheInput {
  text-align: center;
  color: red;
  font-size: 20px;
  position: relative;
  left: 13px;
}
#rechercheInput::-webkit-input-placeholder {
  color: red;
}
a.nav-link {
  color: red;
}
button {
  background-color: red;
  color: white;
  border: none;
  font-size: 20px;
  text-align: center;
}
button:hover {
  background-color: white;
  color: red;
}
img#logo {
  width: 65%;
  height: 65%;
  float: left;
  margin-top: 8px;
  margin-left: 25px;
}
input:focus::placeholder {
  color: transparent;
}
#filmlist:hover {
  background-color: #f5f5f5;
}
#filmlist2 {
  left: 12.5%;
  top: 9%;
  overflow: auto;
  background-color: white;
  transition: all .4s ease;
}
li.dropdown:last-child .dropdown-menu {
  right: 0;
  left: auto;
}
</style>