<template>
    <div>
        <div class="d-flex flex-column justify-content-center mt-4">
            <p id="erreurAjout"></p>
            <form id="AjoutFilm" @submit.prevent="onSubmitAjout" action="https://vuejs.org/" method="post">
                <h3 class="form-label float-center text-danger">Ajouter un film</h3>
                <div class="row mb-1 mx-3">
                    <div class="col">
                        <label for="AjoutFilm" class="form-label float-center">Titre du Film</label>
                        <input id="login" v-model="v_titre" type="text" class="form-control text-center"
                            placeholder="Titre du Film">
                        <div v-if="v$.v_titre.$error">
                            <p v-for="error of v$.v_titre.$errors" :key="error.$uid" class="text-danger">
                                {{ error.$message }}
                            </p>
                        </div>
                    </div>
                    <div class="col">
                        <label for="AjoutFilm" class="form-label float-center">Date de sortie</label>
                        <input id="imageLink" v-model="v_year" type="text" class="form-control text-center"
                            placeholder="Ex: 2004">
                        <div v-if="v$.v_year.$error">
                            <p v-for="error of v$.v_year.$errors" :key="error.$uid" class="text-danger">
                                {{ error.$message }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row mb-4 mx-4">
                    <div class="col">
                        <label for="AjoutFilm" class="form-label float-center">Duree</label>
                        <input id="login" v-model="v_length" type="text" class="form-control text-center"
                            placeholder="Duree (en minutes)">
                        <div v-if="v$.v_length.$error">
                            <p v-for="error of v$.v_length.$errors" :key="error.$uid" class="text-danger">
                                {{ error.$message }}
                            </p>
                        </div>
                    </div>
                    <div class="col">
                        <label for="AjoutFilm" class="form-label float-center">Description</label>
                        <input id="login" v-model="v_description" type="text" class="form-control text-center"
                            placeholder="Description">
                        <div v-if="v$.v_description.$error">
                            <p v-for="error of v$.v_description.$errors" :key="error.$uid" class="text-danger">
                                {{ error.$message }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row mb-4 mx-4">
                    <div class="col">
                        <label for="AjoutFilm" class="form-label float-center">Classification</label>
                        <select class="form-select" v-model="v_rating">
                            <option value="G">G</option>
                            <option value="NC-17">NC-17</option>
                            <option value="PG">PG</option>
                            <option value="PG-13">PG-13</option>
                            <option value="R">R</option>
                        </select>
                        <div v-if="v$.v_rating.$error">
                            <p v-for="error of v$.v_rating.$errors" :key="error.$uid" class="text-danger">
                                {{ error.$message }}
                            </p>
                        </div>
                    </div>
                    <div class="col">
                        <label for="AjoutFilm" class="form-label float-center">Language</label>
                        <select class="form-select" v-model="v_language">
                            <option value="1">Anglais</option>
                            <option value="2">Italien</option>
                            <option value="3">Japonais</option>
                            <option value="4">Mandarin</option>
                            <option value="5">Francais</option>
                            <option value="6">Allemand</option>
                        </select>
                        <div v-if="v$.v_language.$error">
                            <p v-for="error of v$.v_language.$errors" :key="error.$uid" class="text-danger">
                                {{ error.$message }}
                            </p>
                        </div>
                    </div>
                    <div class="">
                        <label for="AjoutFilm" class="form-label float-center">Acteurs(ctrl pour ajouter
                            plusieurs)</label>
                        <select class="form-select" v-model="v_acteur" multiple>
                            <option v-for="actor in Actors" :value="actor.id">{{ actor.first_name }} {{ actor.last_name
                            }}
                            </option>
                        </select>
                        <div v-if="v$.v_language.$error">
                            <p v-for="error of v$.v_language.$errors" :key="error.$uid" class="text-danger">
                                {{ error.$message }}
                            </p>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn w-75">Ajouter Film</button>
            </form>
            <form id="supprimerFilm" @submit.prevent="onSubmitSupprimer" action="https://vuejs.org/" method="post">
                <p id="erreur" class="text-danger"></p>
                <div class="row mb-4 mx-4">
                    <div class="col">
                        <h3 class="form-label float-center text-danger">Supprimer un film</h3>
                        <select class="form-select" v-model="v_idFilmASupprimer">
                            <option v-for="id in idFilmsList" v-bind:key="id.id" :value="id.id">{{ id.id }} - {{
                                    id.title
                            }}
                            </option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn w-75">Supprimer Film</button>
            </form>
        </div>
    </div>
</template>
<script>
import HeaderPage from '@/components/HeaderPage.vue';
import AdminMenuService from '@/services/AdminMenuService';
import useVuelidate from "@vuelidate/core";
import RechercheFilmsService from '@/services/RechercheFilmsService';
import {
    helpers,
    required,
    email,
    minLength,
    maxLength,
    sameAs,
    alpha,
    alphaNum,
    numeric
} from "@vuelidate/validators";
export default {
    components: {
        HeaderPage
    },
    setup() {
        return {
            v$: useVuelidate(),
        }
    },
    data() {
        return {
            isAdmin: false,
            v_titre: '',
            v_year: '',
            v_length: '',
            v_description: '',
            v_rating: '',
            v_language: '',
            v_filmId: '',
            v_acteur: [],
            idFilmsList: [],
            v_idFilmASupprimer: '',
            Actors: [],
        }
    },
    validations() {
        return {
            v_titre: {
                required: helpers.withMessage("Veuillez entrer un titre", required)
            },
            v_year: {
                required: helpers.withMessage("Veuillez entrer une date de sortie", required),
                maxLength: helpers.withMessage("Ce champ doit contenir seulement 4 chiffres", maxLength(4)),
                minLength: helpers.withMessage("Ce champ doit contenir seulement 4 chiffres", minLength(4)),
                numeric: helpers.withMessage("Ce champ doit être un nombre", numeric),
            },
            v_length: {
                required: helpers.withMessage("Veuillez entrer la duree en minute", required),
                numeric: helpers.withMessage("Ce champ doit être un nombre", numeric),
                maxLength: helpers.withMessage("Ce champ doit contenir seulement 3 chiffres", maxLength(3)),
            },
            v_description: {
                required: helpers.withMessage("Veuillez entrer une description", required),
                maxLength: helpers.withMessage("Le nom d'utilisateur doit contenir moin 101 caractères", maxLength(100)),
            },
            v_rating: {
                required: helpers.withMessage("Veuillez choisir une classification", required)
            },
            v_language: {
                required: helpers.withMessage("Veuillez choisir une langue", required)
            },
        };
    },
    created() {
        document.title = "Admin Menu";
        let admin = localStorage.getItem("role")
        if (admin == "1") {
            this.isAdmin = true
        } else {
            this.isAdmin = false;
            this.$router.push('/');
        }
        AdminMenuService.listActeurs().then(response => {
            console.log(response);
            this.Actors = response.data;
        }).catch(error => {
            console.log(error);
        });
        RechercheFilmsService.getFilmsNonePaginated().then(response => {
            this.idFilmsList = response.data;
            this.idFilmsList = this.idFilmsList.data;
        }).catch(error => {
            console.log(error);
            erreur.innerText = "Erreur lors de la suppression du film";
        })
    },
    watch: {
        v_acteur: function (newValue, oldValue) {
        }
    },
    methods: {
        async onSubmitAjout() {
            const result = await this.v$.$validate();
            if (result) {
                let erreur = document.getElementById("erreurAjout");
                erreur.innerText = "";
                let film = {
                    titre: this.v_titre,
                    year: this.v_year,
                    length: this.v_length,
                    description: this.v_description,
                    rating: this.v_rating,
                    language: this.v_language,
                    actors: this.v_acteur
                }
                AdminMenuService.ajouterFilm(film).then(response => {
                    console.log(response);
                    erreur.innerText = "Film ajoute";
                }).catch(error => {
                    console.log(error);
                    erreur.innerText = "Erreur lors de l'ajout du film";
                })
            }
        },
        onSubmitSupprimer() {
            let erreur = document.getElementById("erreur");
            erreur.innerText = "";
            if (this.v_idFilmASupprimer == "") {
                erreur.innerText = "Veuillez entrez le ID du film a supprimer";
            } else {
                AdminMenuService.supprimerFilm(this.v_idFilmASupprimer).then(response => {
                    console.log(response);
                    erreur.innerText = "Film supprime";
                }).catch(error => {
                    console.log(error);
                    erreur.innerText = "Erreur lors de la suppression du film";
                })
            }
        },
        getFilms() {
            RechercheFilmsService.getFilmsNonePaginated().then(response => {
                this.idFilmsList = response.data;
                this.idFilmsList = this.idFilmsList.data;
            }).catch(error => {
                console.log(error);
                erreur.innerText = "Erreur lors de la suppression du film";
            })
        }
    }
}
</script>
<style lang="scss" scoped>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
/* Firefox */
input[type=number] {
    -moz-appearance: textfield;
}
.form-label {
    width: auto;
}
#AjoutFilm {
    margin: auto;
    width: 50%;
    border: 0.5px solid rgb(220, 220, 220);
    box-shadow: 5px 5px 24px 15px rgba(0, 0, 0, 0.31);
}
#supprimerFilm {
    margin: auto;
    width: 33%;
    border: 0.5px solid rgb(225, 225, 225);
    box-shadow: 5px 5px 24px 15px rgba(0, 0, 0, 0.31);
    padding: 20px;
    margin-top: 20px;
    margin-bottom: 20px;
}
::-webkit-input-placeholder {
    text-align: center;
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
option {
    text-align: center;
}
</style>