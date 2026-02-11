import jwtInterceptor  from '../../../../../../shared/jwtInterceptor';
import axios from 'axios'
const state = () => ({ 

    infosPublicationStorePublicitesStatus:null,
    infosPublicationStorePublicitesMessage:null,
    infosPublicationStorePublicitesData:[],
    infosPublicationStorePublicitesErrors:[],
    
 
});
const getters = { 

    getInfosPublicationStorePublicitesStatus(state){
        return state.infosPublicationStorePublicitesStatus;
    },

    getInfosPublicationStorePublicitesMessage(state){
        return state.infosPublicationStorePublicitesMessage;
    },

    getInfosPublicationStorePublicitesData(state){
        return state.infosPublicationStorePublicitesData;
    },  
    
    getInfosPublicationStorePublicitesErrors(state){
        return state.infosPublicationStorePublicitesErrors;
    },  

}

const actions = { 
    
    async publicationStorePublicitesDataRequest({ commit }, payload) { 
        const response = await jwtInterceptor.post(`backoffice/admin/publications/create/publicites/store_publicites`, payload, {
            headers: {
                'Content-Type': 'multipart/form-data', // Obligatoire pour les fichiers
            },
        }).catch((err) => { console.log(err);});
        if (response && (response.data.data.status == 200)) {
            commit("setInfosPublicationStorePublicitesStatus", "success");
            commit("setInfosPublicationStorePublicitesMessage", response.data.message);
            commit("setInfosPublicationStorePublicitesData", response.data.data);
        }else if(response.data.data.status == 401) {
            commit("setInfosPublicationStorePublicitesStatus", "empty");
            commit("setInfosPublicationStorePublicitesErrors", response.data.data.errors);
            commit("setInfosPublicationStorePublicitesMessage", response.data.message);
        }else if(response.data.data.status == 422){
            commit("setInfosPublicationStorePublicitesStatus", "error");
            commit("setInfosPublicationStorePublicitesMessage", response.data.message);
        }
    },

}

const mutations = { 

    setInfosPublicationStorePublicitesStatus(state, value){
        state.infosPublicationStorePublicitesStatus = value;
    },

    setInfosPublicationStorePublicitesMessage(state, value){
        state.infosPublicationStorePublicitesMessage = value;
    },

    setInfosPublicationStorePublicitesData(state, value){
        state.infosPublicationStorePublicitesData = value;
    },

    setInfosPublicationStorePublicitesErrors(state, value){
        state.infosPublicationStorePublicitesErrors = value;
    },

}

export default{
    namespaced:true,
    state,
    getters,
    actions,
    mutations
}
