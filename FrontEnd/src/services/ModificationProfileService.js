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
let userId = localStorage.getItem('id');
const RESOURCE_NAME = 'users';
export default {
    updateUserInfo(p_email, p_last_name, p_first_name) {
        let userProfile = apiClient.get(RESOURCE_NAME + '/' + userId);
        return userProfile.then(function (response) {
            if (p_email === '') {
                p_email = response.data.user.email;
            }
            if (p_last_name === '') {
                p_last_name = response.data.user.last_name;
            }
            if (p_first_name === '') {
                p_first_name = response.data.user.first_name;
            }
            return apiClient.put(RESOURCE_NAME, {
                email: p_email,
                last_name: p_last_name,
                first_name: p_first_name
            });
        }
        ).catch(function (error) {
            console.log(error);
        }
        );
    },
    updatePasswordInfo(p_NewPassword, p_current_password, p_confirm_password) {
        return apiClient.put(RESOURCE_NAME + '/password', {
            old_password: p_current_password,
            new_password: p_NewPassword,
            confirm_password: p_confirm_password
        }
        ).catch(function (error) {
            console.log(error);
        }
        );
    }
}