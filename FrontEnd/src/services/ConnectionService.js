import axios from 'axios'
const apiClient = axios.create({
    baseURL: 'https://vuetp3.herokuapp.com/api/',
    withCredentials: false,
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
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
const RESOURCE_NAME = 'users/login';
export default {
    login(username, password) {
        return apiClient.post(RESOURCE_NAME, {
            login: username,
            password: password
        });
    }
}