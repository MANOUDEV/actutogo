import axios from "axios";
const state = () => ({
    infosCreateVisitorsStatus:null,
    infosCreateVisitorsMessage:null,
    infosCreateVisitorsData:[],

    infosCheckVisitorsStatus:null,
    infosCheckVisitorsMessage:null,
    infosCheckVisitorsData:[],
});
const getters = {

    getInfosCreateVisitorsStatus(state){
        return state.infosCreateVisitorsStatus;
    },

    getInfosCreateVisitorsMessage(state){
        return state.infosCreateVisitorsMessage;
    },

    getInfosCreateVisitorsData(state){
        return state.infosCreateVisitorsData;
    },

    getInfosCheckVisitorsStatus(state){
        return state.infosCheckVisitorsStatus;
    },

    getInfosCheckVisitorsMessage(state){
        return state.infosCheckVisitorsMessage;
    },

    getInfosCheckVisitorsData(state){
        return state.infosCheckVisitorsData;
    },

}

const actions = {
    async createVisitorsDataRequest({ commit }) {
        const response = await axios.post("/api/home/visitor/create").catch((err) => { console.log(err);});
        if (response && (response.data.data.status == 200)) {
            commit("setInfosCreateVisitorsStatus", "success");
            commit("setInfosCreateVisitorsMessage", response.data.message);
            commit("setInfosCreateVisitorsData", response.data.data);
        }else if(response.data.data.status == 401) {
            commit("setInfosCreateVisitorsStatus", "empty");
            commit("setInfosCreateVisitorsMessage", response.data.message);
        }else if(response.data.data.status == 422){
            commit("setInfosCreateVisitorsStatus", "error");
            commit("setInfosCreateVisitorsMessage", response.data.message);
        }
    },

    async checkVisitorsDataRequest({ commit }, payload) {
        const response = await axios.get(`/api/home/visitor/${localStorage.getItem('mtr')}/check`).catch((err) => { console.log(err);});
       if(response.data.data.status == 401) {
            commit("setInfosCheckVisitorsStatus", "empty");
            commit("setInfosCheckVisitorsMessage", response.data.message);
        }if(response.data.data.status == 200) {
            commit("setInfosCheckVisitorsStatus", "success");
            commit("setInfosCheckVisitorsMessage", response.data.message);
        }
    },

}

const mutations = {
    setInfosCreateVisitorsStatus(state, value){
        state.infosCreateVisitorsStatus = value;
    },

    setInfosCreateVisitorsMessage(state, value){
        state.infosCreateVisitorsMessage = value;
    },

    setInfosCreateVisitorsData(state, value){
        state.infosCreateVisitorsData = value;
    },

    setInfosCheckVisitorsStatus(state, value){
        state.infosCheckVisitorsStatus = value;
    },

    setInfosCheckVisitorsMessage(state, value){
        state.infosCheckVisitorsMessage = value;
    },

    setInfosCheckVisitorsData(state, value){
        state.infosCheckVisitorsData = value;
    },
}

export default{
    namespaced:true,
    state,
    getters,
    actions,
    mutations
}
