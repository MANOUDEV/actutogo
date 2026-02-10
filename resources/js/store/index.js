import { createStore } from "vuex";

//Importation des modules gérant les données contenu dans l'entête de page
 
import economieModule from './modules/frontoffice/header/economie' 

import internationalModule from './modules/frontoffice/header/international'

import politiqueModule from './modules/frontoffice/header/politique'

//Importation des modules gérant les données contenu dans le pied de page

import newsletterModule from './modules/frontoffice/footer/newsletter' 

import categoryPopularsModule from './modules/frontoffice/footer/categoryPopulars'

import tagsPopularsModule from './modules/frontoffice/footer/tagsPopulars'
 
const store = createStore({
    modules:{

        //Déclaration des modules gérant les données contenu dans l'entête de page
        economie: economieModule, 
        international: internationalModule,
        politique: politiqueModule,

        //Déclaration des modules gérant les données contenu dans le pied de page
        
        newsletter: newsletterModule, 
        categoryPopulars: categoryPopularsModule,
        tagsPopulars: tagsPopularsModule
    }
  });

  export default store;
