import axios from "axios";
const state = () => ({
    infosTogoactauliteStatus:null,
    infosTogoactauliteMessage:null,
    infosTogoactauliteData:[],
});
const getters = {

    getInfosTogoactauliteStatus(state){
        return state.infosTogoactauliteStatus;
    },

    getInfosTogoactauliteMessage(state){
        return state.infosTogoactauliteMessage;
    },

    getInfosTogoactauliteData(state){
        return state.infosTogoactauliteData;
    },
   
}

const actions = {
    async togoactualiteDataRequest({ commit }) {
        const response = await axios.get("/api/frontoffice/header/togoactualite").catch((err) => { console.log(err);});
        if (response && (response.data.data.status == 200)) {
            commit("setInfosTogoactauliteStatus", "success");
            commit("setInfosTogoactauliteMessage", response.data.message);
            commit("setInfosTogoactauliteData", response.data.data.togoactualiteData);
        }else if(response.data.data.status == 401) {
            commit("setInfosTogoactauliteStatus", "empty");
            commit("setInfosTogoactauliteMessage", response.data.message);
        }else if(response.data.data.status == 422){
            commit("setInfosTogoactauliteStatus", "error");
            commit("setInfosTogoactauliteMessage", response.data.message);
        }
    },

}

const mutations = {
    setInfosTogoactauliteStatus(state, value){
        state.infosTogoactauliteStatus = value;
    },

    setInfosTogoactauliteMessage(state, value){
        state.infosTogoactauliteMessage = value;
    },

    setInfosTogoactauliteData(state, value){
        state.infosTogoactauliteData = value;
    },
   
}

export default{
    namespaced:true,
    state,
    getters,
    actions,
    mutations
}
