import { createRouter, createWebHistory } from 'vue-router';

//Importation du composant du tableau de bord des publicateurs d'articles

import DashboardPub from '../components/backoffice/publicateur/pages/DashboardComponent.vue'; 

//Importation du composant du gestion des tags des publicateurs d'articles

import TagsPub from '../components/backoffice/publicateur/pages/tags/TagsComponent.vue';
 
//Importation du composant du gestion des auteurs des publicateurs d'articles

import AuthorsPub from '../components/backoffice/publicateur/pages/authors/AuthorsComponent.vue';
 
 
const routes =[

  //Routes pour les fonctionnalités de l'interface de l'publicateurs

  //Tableau de bord

  {path:'/pub/dashboard',component: DashboardPub, name: 'pub.dashboard' ,meta: { requiredAuth: true }},

  //Gestion des tags
  
  {path:'/pub/tags',component: TagsPub, name: 'pub.tags' ,meta: { requiredAuth: true }},

  //Gestion des auteurs

  {path:'/pub/authors',component: AuthorsPub, name: 'pub.authors' ,meta: { requiredAuth: true }},
  

];
 
export const routeConfig = createRouter({
  history: createWebHistory(),
  routes: routes
});
