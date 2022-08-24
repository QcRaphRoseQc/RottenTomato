import { createRouter, createWebHistory } from 'vue-router'
import Home from '../components/HomePage.vue'
import CreationCompte from '../components/CreationComptePage.vue'
import Connexion from '../components/ConnexionPage.vue'
import AdminMenu from '../components/AdminMenuPage.vue'
import ProfilPage from '../components/ProfilPage.vue'
import FilmList from '@/components/FilmList.vue';
import FilmPage from '@/components/FilmPage.vue';
const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home,
        meta: {
            title: 'Home'
        }
    },
    {
        path: '/CreationCompte',
        name: 'CreationCompte',
        component: CreationCompte
    },
    {
        path: '/Connexion',
        name: 'Connexion',
        component: Connexion
    },
    {
        path: '/AdminMenu',
        name: 'AdminMenu',
        component: AdminMenu
    },
    {
        path: '/Profil',
        name: 'Profil',
        component: ProfilPage
    },
    {

        path: '/RechercheFilms',
        name: 'RechercheFilms',
        component: FilmList
    },
    {
        path: '/FilmPage/:id',
        name: 'FilmPage',
        component: FilmPage
    },

]
const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes
})

export default router