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
 
const app = createApp({})
 
app.component('EconomieHeader', EconomieHeader)
 
app.component('InternationalHeader', InternationalHeader)

app.component('PolitiqueHeader', PolitiqueHeader)

app.component('NewsletterFooter', NewsletterFooter)
 
app.use(VueSweetalert2).use(store).mount('#app')
  