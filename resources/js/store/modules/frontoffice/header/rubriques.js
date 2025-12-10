import axios from "axios";
const state = () => ({
    infosRubriquesStatus:null,
    infosRubriquesMessage:null,
    infosRubriquesData:[],

    infosChroniquesStatus:null,
    infosChroniquesMessage:null,
    infosChroniquesData:[],

    infosDiplomatieStatus:null,
    infosDiplomatieMessage:null,
    infosDiplomatieData:[],
});
const getters = {

    getInfosRubriquesStatus(state){
        return state.infosRubriquesStatus;
    },

    getInfosRubriquesMessage(state){
        return state.infosRubriquesMessage;
    },

    getInfosRubriquesData(state){
        return state.infosRubriquesData;
    },

    getInfosChroniquesStatus(state){
        return state.infosChroniquesStatus;
    },

    getInfosChroniquesMessage(state){
        return state.infosChroniquesMessage;
    },

    getInfosChroniquesData(state){
        return state.infosChroniquesData;
    },

    getInfosDiplomatieStatus(state){
        return state.infosDiplomatieStatus;
    },

    getInfosDiplomatieMessage(state){
        return state.infosDiplomatieMessage;
    },

    getInfosDiplomatieData(state){
        return state.infosDiplomatieData;
    },
   
}

const actions = {
    async rubriquesDataRequest({ commit }) {
        const response = await axios.get("/api/frontoffice/header/rubriques").catch((err) => { console.log(err);});
        if (response && (response.data.data.status == 200)) {
            commit("setInfosRubriquesStatus", "success");
            commit("setInfosRubriquesMessage", response.data.message);
            commit("setInfosRubriquesData", response.data.data.rubriquesData);
        }else if(response.data.data.status == 401) {
            commit("setInfosRubriquesStatus", "empty");
            commit("setInfosRubriquesMessage", response.data.message);
        }else if(response.data.data.status == 422){
            commit("setInfosRubriquesStatus", "error");
            commit("setInfosRubriquesMessage", response.data.message);
        }
    },

    async diplomatieDataRequest({ commit }) {
        const response = await axios.get("/api/frontoffice/header/diplomatie").catch((err) => { console.log(err);});
        if (response && (response.data.data.status == 200)) {
            commit("setInfosDiplomatieStatus", "success");
            commit("setInfosDiplomatieMessage", response.data.message);
            commit("setInfosDiplomatieData", response.data.data.diplomatieData);
        }else if(response.data.data.status == 401) {
            commit("setInfosDiplomatieStatus", "empty");
            commit("setInfosDiplomatieMessage", response.data.message);
        }else if(response.data.data.status == 422){
            commit("setInfosDiplomatieStatus", "error");
            commit("setInfosDiplomatieMessage", response.data.message);
        }
    },

    async chroniquesDataRequest({ commit }) {
        const response = await axios.get("/api/frontoffice/header/chroniques").catch((err) => { console.log(err);});
        if (response && (response.data.data.status == 200)) {
            commit("setInfosChroniquesStatus", "success");
            commit("setInfosChroniquesMessage", response.data.message);
            commit("setInfosChroniquesData", response.data.data.chroniquesData);
        }else if(response.data.data.status == 401) {
            commit("setInfosChroniquesStatus", "empty");
            commit("setInfosChroniquesMessage", response.data.message);
        }else if(response.data.data.status == 422){
            commit("setInfosChroniquesStatus", "error");
            commit("setInfosChroniquesMessage", response.data.message);
        }
    },

}

const mutations = {
    setInfosRubriquesStatus(state, value){
        state.infosRubriquesStatus = value;
    },

    setInfosRubriquesMessage(state, value){
        state.infosRubriquesMessage = value;
    },

    setInfosRubriquesData(state, value){
        state.infosRubriquesData = value;
    },

    setInfosDiplomatieStatus(state, value){
        state.infosDiplomatieStatus = value;
    },

    setInfosDiplomatieMessage(state, value){
        state.infosDiplomatieMessage = value;
    },

    setInfosDiplomatieData(state, value){
        state.infosDiplomatieData = value;
    },

    setInfosChroniquesStatus(state, value){
        state.infosChroniquesStatus = value;
    },

    setInfosChroniquesMessage(state, value){
        state.infosChroniquesMessage = value;
    },

    setInfosChroniquesData(state, value){
        state.infosChroniquesData = value;
    },
   
}

export default{
    namespaced:true,
    state,
    getters,
    actions,
    mutations
}
