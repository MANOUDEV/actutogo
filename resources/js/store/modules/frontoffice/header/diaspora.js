import axios from "axios";
const state = () => ({
    infosDiasporaStatus:null,
    infosDiasporaMessage:null,
    infosDiasporaData:[],

    infosFenetreSurLAfriqueStatus:null,
    infosFenetreSurLAfriqueMessage:null,
    infosFenetreSurLAfriqueData:[],
});
const getters = {

    getInfosDiasporaStatus(state){
        return state.infosDiasporaStatus;
    },

    getInfosDiasporaMessage(state){
        return state.infosDiasporaMessage;
    },

    getInfosDiasporaData(state){
        return state.infosDiasporaData;
    },

    getInfosFenetreSurLAfriqueStatus(state){
        return state.infosFenetreSurLAfriqueStatus;
    },

    getInfosFenetreSurLAfriqueMessage(state){
        return state.infosFenetreSurLAfriqueMessage;
    },

    getInfosFenetreSurLAfriqueData(state){
        return state.infosFenetreSurLAfriqueData;
    },
   
}

const actions = {
    async diasporaDataRequest({ commit }) {
        const response = await axios.get("/api/frontoffice/header/diaspora").catch((err) => { console.log(err);});
        if (response && (response.data.data.status == 200)) {
            commit("setInfosDiasporaStatus", "success");
            commit("setInfosDiasporaMessage", response.data.message);
            commit("setInfosDiasporaData", response.data.data.diasporaData);
        }else if(response.data.data.status == 401) {
            commit("setInfosDiasporaStatus", "empty");
            commit("setInfosDiasporaMessage", response.data.message);
        }else if(response.data.data.status == 422){
            commit("setInfosDiasporaStatus", "error");
            commit("setInfosDiasporaMessage", response.data.message);
        }
    },

    async fenetreSurLAfriqueDataRequest({ commit }) {
        const response = await axios.get("/api/frontoffice/header/fenetreSurLAfrique").catch((err) => { console.log(err);});
        if (response && (response.data.data.status == 200)) {
            commit("setInfosFenetreSurLAfriqueStatus", "success");
            commit("setInfosFenetreSurLAfriqueMessage", response.data.message);
            commit("setInfosFenetreSurLAfriqueData", response.data.data.fenetreSurLAfriqueData);
        }else if(response.data.data.status == 401) {
            commit("setInfosFenetreSurLAfriqueStatus", "empty");
            commit("setInfosFenetreSurLAfriqueMessage", response.data.message);
        }else if(response.data.data.status == 422){
            commit("setInfosFenetreSurLAfriqueStatus", "error");
            commit("setInfosFenetreSurLAfriqueMessage", response.data.message);
        }
    },

}

const mutations = {
    setInfosDiasporaStatus(state, value){
        state.infosDiasporaStatus = value;
    },

    setInfosDiasporaMessage(state, value){
        state.infosDiasporaMessage = value;
    },

    setInfosDiasporaData(state, value){
        state.infosDiasporaData = value;
    },

    setInfosFenetreSurLAfriqueStatus(state, value){
        state.infosFenetreSurLAfriqueStatus = value;
    },

    setInfosFenetreSurLAfriqueMessage(state, value){
        state.infosFenetreSurLAfriqueMessage = value;
    },

    setInfosFenetreSurLAfriqueData(state, value){
        state.infosFenetreSurLAfriqueData = value;
    },
   
}

export default{
    namespaced:true,
    state,
    getters,
    actions,
    mutations
}
