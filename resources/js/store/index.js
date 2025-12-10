import { createStore } from "vuex";

//Importation des modules gérant les données contenu dans l'entête de page

import togoactualiteModule from './modules/frontoffice/header/togoactualite'

import rubriquesModule from './modules/frontoffice/header/rubriques'

import economieModule from './modules/frontoffice/header/economie'

import diasporaModule from './modules/frontoffice/header/diaspora'

import internationalModule from './modules/frontoffice/header/international'

import sportsModule from './modules/frontoffice/header/sports'

//Importation des modules gérant les données contenu dans le pied de page

import newsletterModule from './modules/frontoffice/footer/newsletter'

import articlesPopularsModule from './modules/frontoffice/footer/articlesPopulars'

import categoryPopularsModule from './modules/frontoffice/footer/categoryPopulars'

import tagsPopularsModule from './modules/frontoffice/footer/tagsPopulars'

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

import authorsAdminModule from './modules/backoffice/admin/authors'

import typePublicationAdminModule from './modules/backoffice/admin/typePublication'

import tagsAdminModule from './modules/backoffice/admin/tags'

import newsLetterAdminModule from './modules/backoffice/admin/newsLetter'

import  publicationAdminModule from './modules/backoffice/admin/publications/publication'

//Importation des modules gérant les données de la partie des publicateurs

import tagsPubModule from './modules/backoffice/publicator/tags'

import authorsPubModule from './modules/backoffice/publicator/authors'
  
const store = createStore({
    modules:{

        //Déclaration des modules gérant les données contenu dans l'entête de page

        togoactualite: togoactualiteModule,
        rubriques: rubriquesModule,
        economie: economieModule,
        diaspora: diasporaModule,
        international: internationalModule,
        sports: sportsModule,

        //Déclaration des modules gérant les données contenu dans le pied de page

        newsletter: newsletterModule,
        articlesPopulars: articlesPopularsModule,
        categoryPopulars: categoryPopularsModule,
        tagsPopulars: tagsPopularsModule,

        //Déclaration des modules gérant les données du système d'authenitification
        
        login: loginModule,
        logout:logoutModule,
        meProfile: meProfileModule,
        forgot_password: forgot_passwordModule,
        register: registerModule,

        //Déclaration des modules gérant les données du système de la securité su site

        roleSecurity: roleModule,


        //Déclaration du module gérant les données de création de matricule pour visiteurs
                        
        visitorsActions: visitorsActionsModule, 

        //Déclaration du module gérant les données d'envoi des messages

        contact: contactModule,

        //Déclaration du module gérant les données sur un article

        article: articleModule,

        //Déclaration des modules gérant les données de la partie administrative
                
        typePublicationAdmin: typePublicationAdminModule,
        publicationAdmin: publicationAdminModule,
        categoryAdmin: categoryAdminModule,
        authorsAdmin: authorsAdminModule,
        tagsAdmin: tagsAdminModule,
        newsLetterAdmin: newsLetterAdminModule,

        //Déclaration des modules gérant les données de la partie des publicateurs
        authorsPub: authorsPubModule,
        tagsPub: tagsPubModule,

    }
  });

  export default store;
