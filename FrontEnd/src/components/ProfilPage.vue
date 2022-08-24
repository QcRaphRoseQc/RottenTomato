<template>
    <div>
        <div class="d-flex flex-column justify-content-center w-50 mx-auto mt-3 ">
            <form id="userInfo" @submit.prevent="onSubmitUser" action="https://vuejs.org/" method="post">
                <p id="resultatMAJ"></p>
                <div class="row mb-4 mx-4 mb-4">
                    <div class="row">
                        <h3 class="form-label float-center text-danger">Modifier Information Personelle</h3>
                        <label for="userInfo" class="form-label float-start">Adresse Email</label>
                        <input id="email" v-model="email" type="email" class="form-control text-center"
                            placeholder="Adresse Email">
                        <div v-if="v$.email.$error">
                            <p v-for="error of v$.email.$errors" :key="error.$uid" class="text-danger">{{ error.$message
                            }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <label for="userInfo" class="form-label float-start">Prenom </label>
                        <input id="firstName" v-model="firstName" type="text" class="form-control text-center"
                            placeholder="Prenom">
                        <div v-if="v$.firstName.$error">
                            <p v-for="error of v$.firstName.$errors" :key="error.$uid" class="text-danger">{{
                                    error.$message
                            }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <label for="userInfo" class="form-label float-start">Nom de famille </label>
                        <input id="lastName" v-model="lastName" type="text" class="form-control text-center"
                            placeholder="Nom de famille">
                        <div v-if="v$.lastName.$error">
                            <p v-for="error of v$.lastName.$errors" :key="error.$uid" class="text-danger">{{
                                    error.$message
                            }}</p>
                        </div>
                    </div>
                </div>
                <button id="userInfoBtn" type="submit" class="btn  w-75">Mettre a jour informations</button>
            </form>
            <form id="passwordInfo" class="mt-4" @submit.prevent="onSubmitPassword" action="https://vuejs.org/"
                method="post">
                <p id="resultat" class=""></p>
                <div class="row mb-4 mx-4">
                    <h3 class="form-label float-center text-danger">Modifier Mot De Passe</h3>
                    <div class="row">
                        <label for="password" class="form-label float-start">Mot de passe courant</label>
                        <input id="currentPassword" v-model="v_currentPassword" type="password"
                            class="form-control text-center" placeholder="Mot de passe courrant">
                    </div>
                    <div class="row">
                        <label for="passwordInfo" class="form-label float-start">Nouveau Mot de passe </label>
                        <input id="newPassword" v-model="v_newPassword" type="password" class="form-control text-center"
                            placeholder="Nouveau Mot de passe">
                    </div>
                    <div class="row">
                        <label for="passwordInfo" class="form-label float-start">Confirmation de mot de passe </label>
                        <input id="confirmPassword" v-model="v_confirmPassword" type="password"
                            class="form-control text-center" placeholder="Confirmer le mot de passe">
                    </div>
                </div>
                <button id="passwordBtn" type="submit" class="btn  w-75">Mettre a jour mot de passe</button>
            </form>
        </div>
    </div>
</template>
<script>
import HeaderPage from '@/components/HeaderPage.vue';
import ModificationProfileService from '@/services/ModificationProfileService';
import useVuelidate from '@vuelidate/core'
import {
    helpers,
    required,
    email,
    alpha,
    maxLength
} from '@vuelidate/validators'
export default {
    name: 'ModificationProfilePage',
    components: {
        HeaderPage
    },
    setup() {
        return {
            v$: useVuelidate()
        }
    },
    data() {
        return {
            email: "",
            firstName: "",
            lastName: "",
            v_currentPassword: "",
            v_newPassword: "",
            v_confirmPassword: "",
        }
    },
    validations() {
        return {
            email: {
                required: helpers.withMessage('Ce champ ne peut pas etre vide', required),
                email: helpers.withMessage('Ceci n\'est pas une adresse email valide', email),
                maxLength: helpers.withMessage("Le email ne peut contenir plus de 50 caracteres", maxLength(50)),
            },
            firstName: {
                required: helpers.withMessage('Ce champ ne peut pas etre vide', required),
                maxLength: helpers.withMessage("Votre Prenom ne peut contenir plus de 50 caractères", maxLength(50)),
                alpha: helpers.withMessage('Votre prénom doit contenir seulement des caracteres de l\'alphabet', alpha)
            },
            lastName: {
                required: helpers.withMessage('Ce champ ne peut pas etre vide', required),
                maxLength: helpers.withMessage("Votre Nom ne peut contenir plus de 50 caractères", maxLength(50)),
                alpha: helpers.withMessage('Votre prénom doit contenir seulement des caracteres de l\'alphabet', alpha)
            },
        }
    },
    mounted() {
        document.title = "Profile";
    },
    methods: {
        async onSubmitUser() {
            const result = await this.v$.$validate();
            if (result) {
                ModificationProfileService.updateUserInfo(this.email, this.firstName, this.lastName).then(response => {
                    let resultatMAJ = document.getElementById("resultatMAJ");
                    resultatMAJ.innerText = "";
                    if (response.status == 200) {
                        resultatMAJ.className = "text-success"
                        resultatMAJ.innerHTML = "Vos informations on ete sauvegardé!";
                    }
                    else {
                        resultatMAJ.className = "text-danger"
                        resultatMAJ.innerHTML = "Erreur lors de la modification";
                    }
                    console.log(response);
                })
            };
        },
        onSubmitPassword() {
            let currentPassword = localStorage.getItem("password");
            let resultat = document.getElementById("resultat");
            resultat.innerText = "";
            if (this.v_currentPassword != currentPassword) {
                resultat.className = "text-danger"
                resultat.innerText = "Mot de passe courant incorrect";
            } else if (this.v_newPassword.length > 50 || this.v_confirmPassword.length > 50) {
                resultat.className = "text-danger"
                resultat.innerText = "Le mot de passe ne peut contenir plus de 50 caracteres";
            } else if (this.v_confirmPassword == "" || this.v_newPassword == "") {
                resultat.className = "text-danger"
                resultat.innerText = "Veuillez remplir tous les champs";
            } else if (this.v_newPassword != this.v_confirmPassword) {
                resultat.className = "text-danger"
                resultat.innerText = "Les mots de passe ne correspondent pas";
            } else if (this.v_newPassword == this.v_currentPassword) {
                resultat.className = "text-danger"
                resultat.innerText = "Le nouveau mot de passe est identique";
            } else {
                ModificationProfileService.updatePasswordInfo(this.v_newPassword, this.v_currentPassword, this.v_confirmPassword).then(response => {
                    console.log(response);
                    if (response.status == 200) {
                        resultat.className = "text-success"
                        resultat.innerText = "Le nouveau mot de passe a ete sauvegarde!";
                        localStorage.setItem("password", this.v_newPassword);
                    }
                    else {
                        resultat.className = "text-danger"
                        resultat.innerText = "Erreur lors de la modification";
                    }
                });
            }
        }
    }
}
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
form#userInfo {
    border: 0.5px solid rgb(225, 225, 225);
    margin-bottom: 15px;
    box-shadow: 5px 5px 24px 15px rgba(0, 0, 0, 0.31);
}
form#passwordInfo {
    border: 0.5px solid rgb(225, 225, 225);
    box-shadow: 5px 5px 24px 15px rgba(0, 0, 0, 0.31);
}
label {
    padding: 5px;
}
</style>