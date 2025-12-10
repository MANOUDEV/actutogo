import axios from "axios";
const state = () => ({
    infosArticlesPopularsStatus:null,
    infosArticlesPopularsMessage:null,
    infosArticlesPopularsData:[],
});
const getters = {

    getInfosArticlesPopularsStatus(state){
        return state.infosArticlesPopularsStatus;
    },

    getInfosArticlesPopularsMessage(state){
        return state.infosArticlesPopularsMessage;
    },

    getInfosArticlesPopularsData(state){
        return state.infosArticlesPopularsData;
    },

}

const actions = {
    async articlesPopularsDataRequest({ commit }) {
        const response = await axios.get("/api/frontoffice/footer/articles_populars").catch((err) => { console.log(err);});
        if (response && (response.data.data.status == 200)) {
            commit("setInfosArticlesPopularsStatus", "success");
            commit("setInfosArticlesPopularsMessage", response.data.message);
            commit("setInfosArticlesPopularsData", response.data.data.publicationsPopularsData);
        }else if(response.data.data.status == 401) {
            commit("setInfosArticlesPopularsStatus", "empty");
            commit("setInfosArticlesPopularsMessage", response.data.message);
        }else if(response.data.data.status == 422){
            commit("setInfosArticlesPopularsStatus", "error");
            commit("setInfosArticlesPopularsMessage", response.data.message);
        }
    },

}

const mutations = {
    setInfosArticlesPopularsStatus(state, value){
        state.infosArticlesPopularsStatus = value;
    },

    setInfosArticlesPopularsMessage(state, value){
        state.infosArticlesPopularsMessage = value;
    },

    setInfosArticlesPopularsData(state, value){
        state.infosArticlesPopularsData = value;
    },

}

export default{
    namespaced:true,
    state,
    getters,
    actions,
    mutations
}
