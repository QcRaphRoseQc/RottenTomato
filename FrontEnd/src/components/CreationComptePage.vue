<template>
  <div>
    <div class="d-flex flex-column justify-content-center w-50 mx-auto text-center">
      <form id="register" @submit.prevent="onSubmit" action="https://vuejs.org/" method="post">
        <p id="erreur"></p>
        <div class="row mb-4 mx-4 text-center">
          <div class="col">
            <label for="email" class="form-label text-center">Adresse Email</label>
            <input id="email" v-model="email" class="form-control text-center" placeholder="Adresse Email" />
            <div v-if="v$.email.$error">
              <p v-for="error of v$.email.$errors" :key="error.$uid" class="text-danger">
                {{ error.$message }}
              </p>
            </div>
          </div>
          <div class="col" v-if="!isConnected">
            <label for="login" class="form-label float-center">Nom Utilisateur</label>
            <input id="login" v-model="login" class="form-control text-center" placeholder="Nom d'utilisateur"
              aria-label="First name" />
            <div v-if="v$.login.$error">
              <p v-for="error of v$.login.$errors" :key="error.$uid" class="text-danger">
                {{ error.$message }}
              </p>
            </div>
          </div>
        </div>
        <div class="row mb-4 mx-4">
          <div class="col">
            <label for="password" class="form-label text-center">Mot de passe</label>
            <input id="password" v-model="password" type="password" class="form-control text-center"
              placeholder="Mot de passe" aria-label="First name" />
            <div v-if="v$.password.$error">
              <p v-for="error of v$.password.$errors" :key="error.$uid" class="text-danger">
                {{ error.$message }}
              </p>
            </div>
          </div>
          <div class="col">
            <label for="passwordVerify" class="form-label float-center text-center">Confirmation du mot de passe</label>
            <input id="passwordVerify" class="form-control text-center" placeholder="Confirmation du mot de passe"
              aria-label="Comfirmation du mot de passe" v-model="passwordVerify" type="password" />
            <div v-if="v$.passwordVerify.$error">
              <p v-for="error of v$.passwordVerify.$errors" :key="error.$uid" class="text-danger">
                {{ error.$message }}
              </p>
            </div>
          </div>
        </div>
        <div class="row mb-4 mx-4">
          <div class="col">
            <label for="first_name" class="form-label float-center">Prenom</label>
            <input id="first_name" v-model="first_name" class="form-control text-center" placeholder="Prénom"
              aria-label="First name" />
            <div v-if="v$.first_name.$error">
              <p v-for="error of v$.first_name.$errors" :key="error.$uid" class="text-danger">
                {{ error.$message }}
              </p>
            </div>
          </div>
          <div class="col">
            <label for="last_name" class="form-label float-center">Nom</label>
            <input id="last_name" v-model="last_name" class="form-control text-center" placeholder="Nom"
              aria-label="First name" />
            <div v-if="v$.last_name.$error">
              <p v-for="error of v$.last_name.$errors" :key="error.$uid" class="text-danger">
                {{ error.$message }}
              </p>
            </div>
          </div>
        </div>
        <button type="submit" class="btn w-75">Creez un Compte</button>
      </form>
    </div>
  </div>
</template>
<script>
import HeaderPage from "@/components/HeaderPage.vue";
import RegisterService from "@/services/RegisterService.js";
document.title = "Creation Compte";
import useVuelidate from "@vuelidate/core";
import {
  helpers,
  required,
  email,
  minLength,
  maxLength,
  sameAs,
  alpha,
  alphaNum,
} from "@vuelidate/validators";
export default {
  name: "CreationComptePage",
  components: {
    HeaderPage,
  },
  setup() {
    return {
      v$: useVuelidate(),
    };
  },
  data() {
    return {
      email: "",
      password: "",
      passwordVerify: "",
      login: "",
      first_name: "",
      last_name: "",
      isConnected: false,
      token: "",
    };
  },
  validations() {
    return {
      email: {
        required: helpers.withMessage(
          "Ce champ ne peut pas etre vide",
          required
        ),
        email: helpers.withMessage(
          "Ceci n'est pas une adresse email valide",
          email
        ),
        maxLength: helpers.withMessage(
          "Le email ne peut contenir plus de 50 caracteres",
          maxLength(50)
        ),
      },
      login: {
        required: helpers.withMessage(
          "Ce champ ne peut pas etre vide",
          required
        ),
        alphaNum: helpers.withMessage(
          "Ce champ doit contenir seulement des caracteres alphaNumerique sans espaces",
          alphaNum
        ),
        minLength: helpers.withMessage(
          "Le nom d'utilisateur doit contenir au minimum 5 caracteres",
          minLength(5)
        ),
        maxLength: helpers.withMessage(
          "Le nom d'utilisateur ne peut contenir plus de 50 caracteres",
          maxLength(50)
        ),
      },
      password: {
        required: helpers.withMessage(
          "Ce champ ne peut pas etre vide",
          required
        ),
        maxLength: helpers.withMessage(
          "Le mot de passe ne peut contenir plus de 50 caracteres",
          maxLength(50)
        ),
      },
      passwordVerify: {
        required: helpers.withMessage(
          "Ce champ ne peut pas etre vide",
          required
        ),
        sameAs: helpers.withMessage(
          "Les mot de passe doivent etre identique",
          sameAs(this.password)
        ),
        maxLength: helpers.withMessage(
          "Le mot de passe ne peut contenir plus de 50 caracteres",
          maxLength(50)
        ),
      },
      first_name: {
        required: helpers.withMessage(
          "Ce champ ne peut pas etre vide",
          required
        ),
        alpha: helpers.withMessage(
          "Votre prénom doit contenir seulement des caractères de l'alphabet",
          alpha
        ),
        maxLength: helpers.withMessage(
          "Votre Prenom ne peut contenir plus de 50 caracteres",
          maxLength(50)
        ),
      },
      last_name: {
        required: helpers.withMessage(
          "Ce champ ne peut pas etre vide",
          required
        ),
        alpha: helpers.withMessage(
          "Votre prenom doit contenir seulement des caracteres de l'alphabet",
          alpha
        ),
        maxLength: helpers.withMessage(
          "Votre Nom ne peut contenir plus de 50 caracteres",
          maxLength(50)
        ),
      },
    };
  },
  created() {
    document.title = "Creation Compte";
    this.token = localStorage.getItem("token");
    if (this.token != "" && this.token != null && this.token != undefined) {
      this.$router.push('/');
    }
  },
  methods: {
    async onSubmit() {
      const result = await this.v$.$validate();
      if (result) {
        RegisterService.register(
          this.login,
          this.email,
          this.password,
          this.first_name,
          this.last_name
        ).then((response) => {
          let sessionUser = {
            token: response.data.token,
            id: response.data.id,
            role_id: response.data.role_id,
          };
          if (response.status == 201) {
            this.$router.push("/Connexion");
          }
          if (response.status == 405) {
          }
          console.log(response);
        })
          .catch((error) => {
            let erreur = document.getElementById("erreur");
            erreur.style.color = "red";
            erreur.innerText =
              "Erreur lors de la creation du compte";
          });
      }
    },
  },
};
</script>
<style lang="scss" scoped>
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
</style>