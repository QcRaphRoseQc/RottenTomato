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
const RESOURCE_NAME = 'films/search';
export default {
    search(p_keyword) {
        return apiClient.post(RESOURCE_NAME, {
            keywords: p_keyword
        }
        );
    },
    searchLastAddedFilms() {
        return apiClient.get('films' + '/last');
    },
    ajoutCritics(p_comment, p_id, p_rating) {
        return apiClient.post('critics', {
            film_id: p_id,
            comment: p_comment,
            score: p_rating
        }
        );
    },
    getCritics(p_filmId) {
        return apiClient.get('critics/last/' + p_filmId);
    },
    putCritics(p_id, p_comment, p_rating) {
        return apiClient.put('critics/' + p_id, {
            comment: p_comment,
            score: p_rating
        }
        );
    },
    getFilmsNonePaginated() {
        return apiClient.get('films');
    }
}