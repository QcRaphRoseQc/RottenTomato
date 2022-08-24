<template>
    <div>
        <div class="d-flex flex-column justify-content-center w-50 mx-auto">
            <form @submit.prevent="onSubmit" action="https://vuejs.org/" method="post">
                <p id="erreur"></p>
                <div class="row mb-4 mx-4 text-center">
                    <div class="col">
                        <label for="inputEmail4" class="form-label">Nom Utilisateur</label>
                        <input v-model="login" type="text" class="form-control text-center"
                            placeholder="Nom Utilisateur">
                    </div>
                    <div class="col">
                        <label for="inputEmail4" class="form-label">Mot de passe</label>
                        <input id="password" v-model="password" type="password" class="form-control text-center"
                            placeholder="Mot de passe" aria-label="First name">
                    </div>
                </div>
                <button type="submit" class="btn w-75">Connexion</button>
            </form>
        </div>
    </div>
</template>
<script>
import HeaderPage from '@/components/HeaderPage.vue';
import ConnectionService from '@/services/ConnectionService.js';
document.title = "Connection";
export default {
    name: "ConnexionPage",
    components: {
        HeaderPage
    },
    data() {
        return {
            email: "",
            password: "",
            isConnected: false,
            token: "",
            login: "",
            urlToGo: "",
        }
    },
    created() {
        document.title = "Connection";
        this.token = localStorage.getItem("token");
        if (this.token != "" && this.token != null && this.token != undefined) {
            this.$router.push('/');
        }
    },
    methods: {
        onSubmit() {
            ConnectionService.login(this.login, this.password).then(response => {
                if (response.status == 200) {
                    this.urlToGo = localStorage.getItem("userUrlBeforeClick");
                    this.$router.push(this.urlToGo);
                    setTimeout(() => {
                        window.location.reload();
                    }, 50);
                }
                console.log(response);
                let sessionUser = {
                    token: response.data.data.token,
                    id: response.data.data.id,
                    role_id: response.data.data.role_id,
                }
                this.login = this.login.toUpperCase();
                localStorage.setItem("login", this.login);
                localStorage.setItem("password", this.password);
                localStorage.setItem("token", sessionUser.token);
                localStorage.setItem("id", sessionUser.id);
                localStorage.setItem("role", sessionUser.role_id);
            }).catch(error => {
                if (error.response.status == 401) {
                    document.getElementById("erreur").innerHTML = "Email ou mot de passe incorrect";
                }
            });
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
</style>