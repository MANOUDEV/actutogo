import { createStore } from "vuex";

//Importation des modules gérant les données contenu dans l'entête de page
 
import economieModule from './modules/frontoffice/header/economie' 

import internationalModule from './modules/frontoffice/header/international'

import politiqueModule from './modules/frontoffice/header/politique'



import transferModule from './modules/frontoffice/includes/transferdb' 


//Importation des modules gérant les données contenu dans le pied de page

import newsletterModule from './modules/frontoffice/footer/newsletter' 

import categoryPopularsModule from './modules/frontoffice/footer/categoryPopulars'

import tagsPopularsModule from './modules/frontoffice/footer/tagsPopulars'

//Importation du module gérant les données des publications sur la page d'accueil

import sectionsPublicationsHomePageModule from './modules/frontoffice/sectionsPublicationsHomePage/sectionsPublicationsHomePage'
  
//Importation du module gérant les données de création de matricule pour visiteurs

import visitorsActionsModule from './modules/frontoffice/visitors_actions/visitors_actions'
 
//Importation du module gérant les données d'envoi de messages

import contactModule from "./modules/frontoffice/includes/contact"
 
//Importation du module gérant les données sur article

import articleModule from './modules/frontoffice/article/article'


//Le fonctionnalités pour l'authentification

import loginModule from './modules/auth/login';

import meProfileModule from './modules/auth/meProfile';

import logoutModule from './modules/auth/logout';

import registerModule from './modules/auth/register';

import forgot_passwordModule from './modules/auth/forgot_password';

//Sécurité pour les pages d'administration

import roleModule from './modules/backoffice/role'

//Importation des modules gérant les données de la partie administrative

import categoryAdminModule from './modules/backoffice/admin/category'
 
import typePublicationAdminModule from './modules/backoffice/admin/typePublication'

import tagsAdminModule from './modules/backoffice/admin/tags'

import newsLetterAdminModule from './modules/backoffice/admin/newsLetter'

import  publicationAdminModule from './modules/backoffice/admin/publications/publication'

import  alertInfosAnnonceAdminModule from './modules/backoffice/admin/publications/create/alertInfosAnnonce'

import  articlesAdminModule from './modules/backoffice/admin/publications/create/articles'

import  publicitesAdminModule from './modules/backoffice/admin/publications/create/publicites'

//Importation des modules gérant les données de la partie des publicateurs

import tagsPubModule from './modules/backoffice/publicator/tags'

 
 
const store = createStore({
    modules:{

        //Déclaration des modules gérant les données contenu dans l'entête de page
        economie: economieModule, 
        international: internationalModule,
        politique: politiqueModule,

        //Déclaration des modules gérant les données contenu dans le pied de page
        
        newsletter: newsletterModule, 
        categoryPopulars: categoryPopularsModule,
        tagsPopulars: tagsPopularsModule,
        
        transfer: transferModule,

        //Déclaration du module gérant les données des publications sur la page d'accueil
                                
                sectionsPublicationsHomePage: sectionsPublicationsHomePageModule,
                
                //Déclaration du module gérant les données de création de matricule pour visiteurs
                
                visitorsActions: visitorsActionsModule, 
        
                //Déclaration du module gérant les données d'envoi des messages
        
                contact: contactModule,
        
                //Déclaration du module gérant les données sur un article
        
                article: articleModule,     

        //Déclaration des modules gérant les données du système d'authenitification
                        
        login: loginModule,
        logout:logoutModule,
        meProfile: meProfileModule,
        forgot_password: forgot_passwordModule,
        register: registerModule,

        //Déclaration des modules gérant les données du système de la securité su site

        roleSecurity: roleModule,
            
        //Déclaration des modules gérant les données de la partie administrative
                        
        typePublicationAdmin: typePublicationAdminModule,
        publicationAdmin: publicationAdminModule,
        alertInfosAnnonceAdmin: alertInfosAnnonceAdminModule,
        articlesAdmin: articlesAdminModule,
        publicitesAdmin: publicitesAdminModule,
        categoryAdmin: categoryAdminModule, 
        tagsAdmin: tagsAdminModule,
        newsLetterAdmin: newsLetterAdminModule,

        //Déclaration des modules gérant les données de la partie des publicateurs 
        tagsPub: tagsPubModule,
    }
  });

  export default store;
