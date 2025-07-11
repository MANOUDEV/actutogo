import './bootstrap';

//Importation des librairies js

import { createApp } from 'vue'

import VueSweetalert2 from 'vue-sweetalert2';

import 'sweetalert2/dist/sweetalert2.min.css';

import store from './store/index';

//Importation, déclaration et chargement des composants inclus dans le header
 
import EconomieHeader from './components/frontoffice/header/economie.vue'
 
import InternationalHeader from './components/frontoffice/header/international.vue'

import PolitiqueHeader from './components/frontoffice/header/politique.vue'

//Importation, déclaration et chargement des composants inclus dans le footer

import NewsletterFooter from './components/frontoffice/footer/newsletter.vue' 

//Verifier les connexions pour configurer les menus

import in_second_menuF from './components/frontoffice/verify_auth/in_second_menu.vue'
 
import off_canvasF from './components/frontoffice/verify_auth/off_canvas.vue'

import in_first_menuF from './components/frontoffice/verify_auth/in_first_menu.vue'

//Importation, déclaration et chargement des composants des sections de publications inclus dans la page d'accueil
 
import aNePasManquerTogoComponent from './components/frontoffice/sectionsPublicationsHomePage/aNePasManquerTogoComponent.vue'

import internationalComponent from './components/frontoffice/sectionsPublicationsHomePage/internationalComponent.vue'

import opinionFaitsDiversComponent from './components/frontoffice/sectionsPublicationsHomePage/opinionFaitsDiversComponent.vue'

import societeComponent from './components/frontoffice/sectionsPublicationsHomePage/societeComponent.vue'
 
//Importation des composants pour l'authentification

import loginH from './components/authentication/login.vue'

//Importation du Composant de contact à afficher sur la page d'accueil

import contactF from './components/frontoffice/includes/contact.vue'
 
//Gestion de tous les composants administrateurs

import * as adminRouter from './router/adminRouter';

import administrateurA from './components/backoffice/administrateur/administrateur.vue'

import HeaderAdminComponent from './components/backoffice/administrateur/includes/HeaderAdminComponent.vue'

import FooterAdminComponent from './components/backoffice/administrateur/includes/FooterComponent.vue'

import authRequiredMessageAdminComponent from './components/backoffice/administrateur/errorMessage/sections/authRequiredMessage.vue'

import sessionExpiredMessageAdminComponent from './components/backoffice/administrateur/errorMessage/sections/sessionExpiredMessage.vue'

import accessUnAuthorizedAdminComponent from './components/backoffice/administrateur/errorMessage/sections/accessUnAuthorized.vue' 

//Gestion de tous les composants publicateurs d'articles

import * as pubRouter from './router/pubRouter';

import publicateurA from './components/backoffice/publicateur/publicateur.vue'

import HeaderPubComponent from './components/backoffice/publicateur/includes/HeaderPubComponent.vue'

import FooterPubComponent from './components/backoffice/publicateur/includes/FooterComponent.vue'

import authRequiredMessagePubComponent from './components/backoffice/publicateur/errorMessage/sections/authRequiredMessage.vue'

import sessionExpiredMessagePubComponent from './components/backoffice/publicateur/errorMessage/sections/sessionExpiredMessage.vue'

import accessUnAuthorizedPubComponent from './components/backoffice/publicateur/errorMessage/sections/accessUnAuthorized.vue'

const app = createApp({})
 
app.component('EconomieHeader', EconomieHeader)
 
app.component('InternationalHeader', InternationalHeader)

app.component('PolitiqueHeader', PolitiqueHeader)

app.component('NewsletterFooter', NewsletterFooter)

app.component('inSecondMenu', in_second_menuF)

app.component('offCanvas', off_canvasF)

app.component('inFirstMenu', in_first_menuF) 

app.component('aNePasManquerTogo', aNePasManquerTogoComponent)

app.component('international', internationalComponent)

app.component('societe', societeComponent)

app.component('opinionFaitsDivers', opinionFaitsDiversComponent) 

app.component('login', loginH)

app.component('contact', contactF)

app.use(VueSweetalert2).use(store).mount('#app')

const administrateur = createApp(administrateurA)
 
administrateur.component('HeaderAdmin', HeaderAdminComponent)

administrateur.component('FooterAdmin',  FooterAdminComponent)

administrateur.component('authRequiredMessageAdmin', authRequiredMessageAdminComponent)

administrateur.component('accessUnAuthorizedAdmin', accessUnAuthorizedAdminComponent)

administrateur.component('sessionExpiredMessageAdmin', sessionExpiredMessageAdminComponent)

administrateur.use(adminRouter.routeConfig).use(store).mount('#admin');

const publicateur = createApp(publicateurA)  

publicateur.component('HeaderPub', HeaderPubComponent)

publicateur.component('FooterPub',  FooterPubComponent)

publicateur.component('authRequiredMessagePub', authRequiredMessagePubComponent)

publicateur.component('accessUnAuthorizedPub', accessUnAuthorizedPubComponent)

publicateur.component('sessionExpiredMessagePub', sessionExpiredMessagePubComponent)

publicateur.use(pubRouter.routeConfig).use(store).mount('#pub');

//Importation du Composant de contact à afficher sur la page d'accueil

import ContactF from './components/frontoffice/home_page/contact.vue'

const contact = createApp(ContactF)

contact.use(VueSweetalert2);

contact.use(store);

contact.mount('#contact')

//Importation du Composant des données à afficher sur la page d'un article

import statesComponent from './components/frontoffice/article/states.vue'

import commentsComponent from './components/frontoffice/article/comments.vue'

const states = createApp(statesComponent)

const comments = createApp(commentsComponent)

states.use(VueSweetalert2);

states.use(store);

states.mount('#states')

comments.use(VueSweetalert2);

comments.use(store);

comments.mount('#comments')


//Importation du Composant de contact à afficher sur la page d'accueil

import ChangeProgramF from './components/frontoffice/home_page/changeProgram.vue'

const changeProgram = createApp(ChangeProgramF)

changeProgram.use(VueSweetalert2);

changeProgram.use(store);

changeProgram.mount('#changeProgram')

