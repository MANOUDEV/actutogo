import { createRouter, createWebHistory } from 'vue-router';

//Importation du composant du tableau de bord des publicateurs d'articles

import DashboardPub from '../components/backoffice/publicateur/pages/DashboardComponent.vue'; 

//Importation du composant du gestion des tags des publicateurs d'articles

import TagsPub from '../components/backoffice/publicateur/pages/tags/TagsComponent.vue';
  
 
const routes =[

  //Routes pour les fonctionnalités de l'interface de l'publicateurs

  //Tableau de bord

  {path:'/pub/dashboard',component: DashboardPub, name: 'pub.dashboard' ,meta: { requiredAuth: true }},

  //Gestion des tags
  
  {path:'/pub/tags',component: TagsPub, name: 'pub.tags' ,meta: { requiredAuth: true }},

];
 
export const routeConfig = createRouter({
  history: createWebHistory(),
  routes: routes
});
