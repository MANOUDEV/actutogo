import axios from "axios";
const state = () => ({
    infosInternationalStatus:null,
    infosInternationalMessage:null,
    infosInternationalData:[],

    infosMondeStatus:null,
    infosMondeMessage:null,
    infosMondeData:[],

    infosAfriqueStatus:null,
    infosAfriqueMessage:null,
    infosAfriqueData:[],
});
const getters = {

    getInfosInternationalStatus(state){
        return state.infosInternationalStatus;
    },

    getInfosInternationalMessage(state){
        return state.infosInternationalMessage;
    },

    getInfosInternationalData(state){
        return state.infosInternationalData;
    },

    getInfosMondeStatus(state){
        return state.infosMondeStatus;
    },

    getInfosMondeMessage(state){
        return state.infosMondeMessage;
    },

    getInfosMondeData(state){
        return state.infosMondeData;
    },

    getInfosAfriqueStatus(state){
        return state.infosAfriqueStatus;
    },

    getInfosAfriqueMessage(state){
        return state.infosAfriqueMessage;
    },

    getInfosAfriqueData(state){
        return state.infosAfriqueData;
    },
   
}

const actions = {
    async internationalDataRequest({ commit }) {
        const response = await axios.get("/api/frontoffice/header/international").catch((err) => { console.log(err);});
        if (response && (response.data.data.status == 200)) {
            commit("setInfosInternationalStatus", "success");
            commit("setInfosInternationalMessage", response.data.message);
            commit("setInfosInternationalData", response.data.data.internationalData);
        }else if(response.data.data.status == 401) {
            commit("setInfosInternationalStatus", "empty");
            commit("setInfosInternationalMessage", response.data.message);
        }else if(response.data.data.status == 422){
            commit("setInfosInternationalStatus", "error");
            commit("setInfosInternationalMessage", response.data.message);
        }
    },

    async mondeDataRequest({ commit }) {
        const response = await axios.get("/api/frontoffice/header/monde").catch((err) => { console.log(err);});
        if (response && (response.data.data.status == 200)) {
            commit("setInfosMondeStatus", "success");
            commit("setInfosMondeMessage", response.data.message);
            commit("setInfosMondeData", response.data.data.mondeData);
        }else if(response.data.data.status == 401) {
            commit("setInfosMondeStatus", "empty");
            commit("setInfosMondeMessage", response.data.message);
        }else if(response.data.data.status == 422){
            commit("setInfosMondeStatus", "error");
            commit("setInfosMondeMessage", response.data.message);
        }
    },

    async afriqueDataRequest({ commit }) {
        const response = await axios.get("/api/frontoffice/header/afrique").catch((err) => { console.log(err);});
        if (response && (response.data.data.status == 200)) {
            commit("setInfosAfriqueStatus", "success");
            commit("setInfosAfriqueMessage", response.data.message);
            commit("setInfosAfriqueData", response.data.data.afriqueData);
        }else if(response.data.data.status == 401) {
            commit("setInfosAfriqueStatus", "empty");
            commit("setInfosAfriqueMessage", response.data.message);
        }else if(response.data.data.status == 422){
            commit("setInfosAfriqueStatus", "error");
            commit("setInfosAfriqueMessage", response.data.message);
        }
    },

}

const mutations = {
    setInfosInternationalStatus(state, value){
        state.infosInternationalStatus = value;
    },

    setInfosInternationalMessage(state, value){
        state.infosInternationalMessage = value;
    },

    setInfosInternationalData(state, value){
        state.infosInternationalData = value;
    },

    setInfosMondeStatus(state, value){
        state.infosMondeStatus = value;
    },

    setInfosMondeMessage(state, value){
        state.infosMondeMessage = value;
    },

    setInfosMondeData(state, value){
        state.infosMondeData = value;
    },

    setInfosAfriqueStatus(state, value){
        state.infosAfriqueStatus = value;
    },

    setInfosAfriqueMessage(state, value){
        state.infosAfriqueMessage = value;
    },

    setInfosAfriqueData(state, value){
        state.infosAfriqueData = value;
    },
   
}

export default{
    namespaced:true,
    state,
    getters,
    actions,
    mutations
}
