import { createRouter, createWebHistory } from 'vue-router';

//Importation du composant du tableau de bord de l'administrateur

import DashboardAdmin from '../components/backoffice/administrateur/pages/DashboardComponent.vue';

//Importation des composants gérant les publications

import TypesPublicationsAdmin from '../components/backoffice/administrateur/pages/publications/typesPublicationsComponent.vue';

import TypesPublicationsSlugAdmin from '../components/backoffice/administrateur/pages/publications/typePublicationSlugComponent.vue';

import PublicationsAdmin from '../components/backoffice/administrateur/pages/publications/publicationsComponent.vue';

import BrouillonsAdmin from '../components/backoffice/administrateur/pages/publications/brouillonsComponent.vue';

//Importation du composant des fichiers de l'administrateur

import FilesAdmin from '../components/backoffice/administrateur/pages/files/filesComponent.vue';

//Importation du composant des auteurs de l'administrateur

import AuthorsAdmin from '../components/backoffice/administrateur/pages/authors/authorsComponent.vue';

//Importation du composant des categories de l'administrateur

import CategoryAdmin from '../components/backoffice/administrateur/pages/category/categoryComponent.vue';

//Importation du composant des tags de l'administrateur

import TagsAdmin from '../components/backoffice/administrateur/pages/tags/tagsComponent.vue';



const routes =[

  //Routes pour les fonctionnalités de l'interface de l'administrateur

  //Tableau de bord

  {path:'/admin/dashboard',component: DashboardAdmin, name: 'admin.dashboard' ,meta: { requiredAuth: true }},
  
  //Publications

  {path:'/admin/publications/create',component: TypesPublicationsAdmin, name: 'admin.publications.create.types_publications' ,meta: { requiredAuth: true }},
  
  {path:'/admin/publications/:slug/create',component: TypesPublicationsSlugAdmin, name: 'admin.publications.create.types_publications.slug' ,meta: { requiredAuth: true }},
  
  {path:'/admin/publications',component: PublicationsAdmin, name: 'admin.publications' ,meta: { requiredAuth: true }},

  {path:'/admin/brouillons',component: BrouillonsAdmin, name: 'admin.brouillons' ,meta: { requiredAuth: true }},

  //Auteurs

  {path:'/admin/authors',component: AuthorsAdmin, name: 'admin.authors' ,meta: { requiredAuth: true }},

  //Fichiers

  {path:'/admin/files',component: FilesAdmin, name: 'admin.files' ,meta: { requiredAuth: true }},

  //Categories

  {path:'/admin/category',component: CategoryAdmin, name: 'admin.category' ,meta: { requiredAuth: true }},

  //Tags

  {path:'/admin/tags',component: TagsAdmin, name: 'admin.tags' ,meta: { requiredAuth: true }},


];

export const routeConfig = createRouter({
  history: createWebHistory(),
  routes: routes
});
