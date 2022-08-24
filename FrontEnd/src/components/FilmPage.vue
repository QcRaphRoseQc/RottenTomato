<template>
    <div class="container d-flex justify-content-center">
        <div id="body" class="col mt-3">
            <div id="Page" class="row">
                <link rel="stylesheet"
                    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
                <div id="details" class=" w-50 mx-auto">
                    <div id="image" class="">
                        <img src="../assets/popcorn.png" alt="popcorn" class="img-fluid">
                    </div>
                    <div class="row">
                        <h5>{{ Film.title }}</h5>
                    </div>
                    <div class="row">
                        <span>
                            <span class="fw-bold">Annee de parution:</span> {{ Film.release_year }}
                        </span>
                    </div>
                    <div class="row">
                        <span>
                            <span class="fw-bold">Rating: </span>
                            <star-rating style="display:inline-block" :read-only="true" :rating="parseInt(Film.score)"
                                :show-rating="false" :increment="0.5" :star-size="20"></star-rating>
                            ({{ nombreCritiques }})
                        </span>
                    </div>
                    <div class="row">
                        <span>
                            <span class="fw-bold"> Duree: </span>{{ Film.length }} Minutes </span>
                    </div>
                    <div class="row">
                        <span>
                            <span class="fw-bold">Actors: <p id="actors" class="font-weight-light"> {{ acteurs }}</p>
                            </span>
                        </span>
                    </div>
                    <div class="row">
                        <span>
                            <span id="description" class="fw-bold w-15">Synopsis: <br>
                            </span>{{ Film.description }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Commentaires :IDFilmChoisis="IDFilmChoisis" />
</template>
<script>
import Commentaires from '@/components/Commentaires.vue';
import RechercheFilmSeul from '@/services/RechercheFilmSeul.js';
import HeaderPage from '@/components/HeaderPage.vue'
import StarRating from 'vue-star-rating';
export default {
    name: "FilmPage",
    data() {
        return {
            Film: {
                type: Object,
                default: null
            },
            IDFilmChoisis: "",
            rating: 0,
            star: "",
            nombreCritiques: 0,
            Actors: [],
            acteurs: "",
        }
    },
    components: {
        RechercheFilmSeul,
        Commentaires,
        HeaderPage,
        StarRating
    },
    created() {
        document.title = "Film";
        this.IDFilmChoisis = this.$route.params.id;
        RechercheFilmSeul.search(this.IDFilmChoisis).then(response => {
            console.log(response);
            this.Film = response.data.data;
            this.rating = response.data.data.score;
            this.nombreCritiques = response.data.critics.length;
            this.Film.nombreCritiques = this.nombreCritiques;
        });
        RechercheFilmSeul.filmActors(this.IDFilmChoisis).then(response => {
            console.log(response);
            this.Actors = response.data;
            this.acteurs = this.Actors.join(", ");
        });
    }
}
</script>
<style lang="scss" scoped>
.bgHomeMade {
    border-radius: 38px;
}
.checked {
    color: orange;
}
img {
    width: 12%;
    height: 12%;
    padding: 10px;
}
#image {
    border-radius: 10px;
}
#details {
    width: 75%;
    border: 1px solid #ccc;
    margin: 5px;
    border-radius: 10px;
    box-shadow: 5px 5px 24px 15px rgba(0, 0, 0, 0.31);
}
span {
    font-size: 0.9em;
    margin-bottom: 10px;
}
div.container.d-flex.justify-content-center {
    padding: 0px;
}
#actors {
    font-size: 0.8em;
    margin-bottom: 10px;
    font-weight: lighter;
}
</style>