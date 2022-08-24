import axios from 'axios'
const apiClient = axios.create({
    baseURL: 'https://vuetp3.herokuapp.com/api/',
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + localStorage.getItem('token')
    }
});
apiClient.interceptors.response.use(
    function (response) {
        return response;
    },
    function (error) {
        console.error('API Error, ' + error);
        return Promise.reject(error);
    }
);
const RESOURCE_NAME = 'films';
export default {
    ajouterFilm(p_film) {
        return apiClient.post(RESOURCE_NAME, {
            title: p_film.titre,
            release_year: p_film.year,
            length: p_film.length,
            description: p_film.description,
            rating: p_film.rating,
            language_id: p_film.language,
            special_features: "test",
            image: "test",
            actors: p_film.actors,
        }
        );
    },
    supprimerFilm(p_filmId) {
        return apiClient.delete(RESOURCE_NAME + '/' + p_filmId);
    },
    listActeurs() {
        return apiClient.get(RESOURCE_NAME + '/actors');
    }
}