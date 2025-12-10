import axios from "axios";
const state = () => ({
    infosSportsStatus:null,
    infosSportsMessage:null,
    infosSportsData:[],

    infosCanStatus:null,
    infosCanMessage:null,
    infosCanData:[],

    infosTogoStatus:null,
    infosTogoMessage:null,
    infosTogoData:[],
});
const getters = {

    getInfosSportsStatus(state){
        return state.infosSportsStatus;
    },

    getInfosSportsMessage(state){
        return state.infosSportsMessage;
    },

    getInfosSportsData(state){
        return state.infosSportsData;
    },

    getInfosTogoStatus(state){
        return state.infosTogoStatus;
    },

    getInfosTogoMessage(state){
        return state.infosTogoMessage;
    },

    getInfosTogoData(state){
        return state.infosTogoData;
    },

    getInfosCanStatus(state){
        return state.infosCanStatus;
    },

    getInfosCanMessage(state){
        return state.infosCanMessage;
    },

    getInfosCanData(state){
        return state.infosCanData;
    },
}

const actions = {
    async sportsDataRequest({ commit }) {
        const response = await axios.get("/api/frontoffice/header/sports").catch((err) => { console.log(err);});
        if (response && (response.data.data.status == 200)) {
            commit("setInfosSportsStatus", "success");
            commit("setInfosSportsMessage", response.data.message);
            commit("setInfosSportsData", response.data.data.sportsData);
        }else if(response.data.data.status == 401) {
            commit("setInfosSportsStatus", "empty");
            commit("setInfosSportsMessage", response.data.message);
        }else if(response.data.data.status == 422){
            commit("setInfosSportsStatus", "error");
            commit("setInfosSportsMessage", response.data.message);
        }
    },

    async canDataRequest({ commit }) {
        const response = await axios.get("/api/frontoffice/header/can").catch((err) => { console.log(err);});
        if (response && (response.data.data.status == 200)) {
            commit("setInfosCanStatus", "success");
            commit("setInfosCanMessage", response.data.message);
            commit("setInfosCanData", response.data.data.canData);
        }else if(response.data.data.status == 401) {
            commit("setInfosCanStatus", "empty");
            commit("setInfosCanMessage", response.data.message);
        }else if(response.data.data.status == 422){
            commit("setInfosCanStatus", "error");
            commit("setInfosCanMessage", response.data.message);
        }
    },

    async togoDataRequest({ commit }) {
        const response = await axios.get("/api/frontoffice/header/togo").catch((err) => { console.log(err);});
        if (response && (response.data.data.status == 200)) {
            commit("setInfosTogoStatus", "success");
            commit("setInfosTogoMessage", response.data.message);
            commit("setInfosTogoData", response.data.data.togoData);
        }else if(response.data.data.status == 401) {
            commit("setInfosTogoStatus", "empty");
            commit("setInfosTogoMessage", response.data.message);
        }else if(response.data.data.status == 422){
            commit("setInfosTogoStatus", "error");
            commit("setInfosTogoMessage", response.data.message);
        }
    },

}

const mutations = {
    setInfosSportsStatus(state, value){
        state.infosSportsStatus = value;
    },

    setInfosSportsMessage(state, value){
        state.infosSportsMessage = value;
    },

    setInfosSportsData(state, value){
        state.infosSportsData = value;
    },

    setInfosTogoStatus(state, value){
        state.infosTogoStatus = value;
    },

    setInfosTogoMessage(state, value){
        state.infosTogoMessage = value;
    },

    setInfosTogoData(state, value){
        state.infosTogoData = value;
    },

    setInfosCanStatus(state, value){
        state.infosCanStatus = value;
    },

    setInfosCanMessage(state, value){
        state.infosCanMessage = value;
    },

    setInfosCanData(state, value){
        state.infosCanData = value;
    },

}

export default{
    namespaced:true,
    state,
    getters,
    actions,
    mutations
}
