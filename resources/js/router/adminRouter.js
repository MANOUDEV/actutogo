import { createRouter, createWebHistory } from 'vue-router';

//Importation du composant du tableau de bord de l'administrateur

import DashboardAdmin from '../components/backoffice/administrateur/pages/DashboardComponent.vue';
 
//Importation du composant de gestion des publications de l'administrateur

import PublicationCreateAdmin from '../components/backoffice/administrateur/pages/publications/TypesPublicationsComponent.vue';
 
import PublicationCreateBySlugType  from '../components/backoffice/administrateur/pages/publications/TypesPublicationsSlugComponent.vue';
 
//Importation du composant du gestion des catégories de l'administrateur

import CategoryAdmin from '../components/backoffice/administrateur/pages/categories/CategoryComponent.vue';
 
//Importation du composant du gestion des tags de l'administrateur

import TagsAdmin from '../components/backoffice/administrateur/pages/tags/TagsComponent.vue';
  
//Importation du composant du gestion des newsletter de l'administrateur

import NewsLettersAdmin from '../components/backoffice/administrateur/pages/newsletters/NewsLettersComponent.vue';
 
const routes =[

  //Routes pour les fonctionnalités de l'interface de l'administrateur

  //Tableau de bord

  {path:'/admin/dashboard',component: DashboardAdmin, name: 'admin.dashboard' ,meta: { requiredAuth: true }},

  //Gestion des publications

  {path:'/admin/publications/create',component: PublicationCreateAdmin, name: 'admin.publications.create' ,meta: { requiredAuth: true }},
  
  {path:'/admin/publications/create/:slug/types_publications',component: PublicationCreateBySlugType , name: 'admin.publications.create.types_publications' ,meta: { requiredAuth: true }},
 
  //Gestion des categories

  {path:'/admin/categories',component: CategoryAdmin, name: 'admin.categories' ,meta: { requiredAuth: true }},

  //Gestion des tags

  {path:'/admin/tags',component: TagsAdmin, name: 'admin.tags' ,meta: { requiredAuth: true }},

   //Gestion des newsletters

  {path:'/admin/newsletters',component: NewsLettersAdmin, name: 'admin.newsletters' ,meta: { requiredAuth: true }},

];

export const routeConfig = createRouter({
  history: createWebHistory(),
  routes: routes
});
