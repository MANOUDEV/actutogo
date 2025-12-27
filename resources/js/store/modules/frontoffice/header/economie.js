import axios from "axios";
const state = () => ({
    infosEconomieStatus:null,
    infosEconomieMessage:null,
    infosEconomieData:[],
});
const getters = {

    getInfosEconomieStatus(state){
        return state.infosEconomieStatus;
    },

    getInfosEconomieMessage(state){
        return state.infosEconomieMessage;
    },

    getInfosEconomieData(state){
        return state.infosEconomieData;
    },
   
}

const actions = {
    async economieDataRequest({ commit }) {
        const response = await axios.get("/api/frontoffice/header/economie").catch((err) => { console.log(err);});
        if (response && (response.data.data.status == 200)) {
            commit("setInfosEconomieStatus", "success");
            commit("setInfosEconomieMessage", response.data.message);
            commit("setInfosEconomieData", response.data.data);
        }else if(response.data.data.status == 401) {
            commit("setInfosEconomieStatus", "empty");
            commit("setInfosEconomieMessage", response.data.message);
        }else if(response.data.data.status == 422){
            commit("setInfosEconomieStatus", "error");
            commit("setInfosEconomieMessage", response.data.message);
        }
    },

}

const mutations = {
    setInfosEconomieStatus(state, value){
        state.infosEconomieStatus = value;
    },

    setInfosEconomieMessage(state, value){
        state.infosEconomieMessage = value;
    },

    setInfosEconomieData(state, value){
        state.infosEconomieData = value;
    },
   
}

export default{
    namespaced:true,
    state,
    getters,
    actions,
    mutations
}
