import { createRouter, createWebHistory } from 'vue-router';

//Importation du composant du tableau de bord des publicateurs d'articles

import DashboardPub from '../components/backoffice/publicateur/pages/DashboardComponent.vue'; 
 
 //Importation du composant du tableau de bord de l'administrateur
 
 import ProfilePub from '../components/backoffice/publicateur/pages/profile/profileComponent.vue';
   
  
 const routes =[
 
   //Routes pour les fonctionnalités de l'interface de l'administrateur
 
   //Tableau de bord
 
   {path:'/pub/dashboard',component: DashboardPub, name: 'pub.dashboard' ,meta: { requiredAuth: true }},
 
   //Gestion du profile
 
   {path:'/pub/profile',component: ProfilePub, name: 'pub.profile' ,meta: { requiredAuth: true }},
   
 ];
export const routeConfig = createRouter({
  history: createWebHistory(),
  routes: routes
});
