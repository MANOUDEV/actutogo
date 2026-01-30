import axios from "axios";
const state = () => ({
    infosTransferStatus:null,
    infosTransferMessage:null,
    infosTransferData:[],
});
const getters = {

    getInfosTransferStatus(state){
        return state.infosTransferStatus;
    },

    getInfosTransferMessage(state){
        return state.infosTransferMessage;
    },

    getInfosTransferData(state){
        return state.infosTransferData;
    },

}

const actions = {
    async TransferDataRequest({ commit }) {
        const response = await axios.get("/api/frontoffice/header/transfer").catch((err) => { console.log(err);});
        if (response && (response.data.data.status == 200)) {
            commit("setInfosTransferStatus", "success");
            commit("setInfosTransferMessage", response.data.message);
            commit("setInfosTransferData", response.data.data.TransferData);
        }else if(response.data.data.status == 401) {
            commit("setInfosTransferStatus", "empty");
            commit("setInfosTransferMessage", response.data.message);
        }else if(response.data.data.status == 422){
            commit("setInfosTransferStatus", "error");
            commit("setInfosTransferMessage", response.data.message);
        }
    },

}

const mutations = {
    setInfosTransferStatus(state, value){
        state.infosTransferStatus = value;
    },

    setInfosTransferMessage(state, value){
        state.infosTransferMessage = value;
    },

    setInfosTransferData(state, value){
        state.infosTransferData = value;
    },
}

export default{
    namespaced:true,
    state,
    getters,
    actions,
    mutations
}
