import axios from 'axios'
const apiClient = axios.create({
    baseURL: 'https://vuetp3.herokuapp.com/api/',
    withCredentials: false,
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
const RESOURCE_NAME = 'users';
export default {
    //login , password , email , last_name , first_name , role_id
    register(login, email, password, last_name, first_name) {
        let isConnected = localStorage.getItem('isConnected');
        return apiClient.post('users/register', {
            login: login,
            email: email,
            password: password,
            last_name: last_name,
            first_name: first_name,
            role_id: 2
        });
    }
}