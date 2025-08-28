import axios from "axios";
import jwtInterceptor from '../../../../shared/jwtInterceptor'
const state = () => ({
    infosCheckArticleStateStatus:null,
    infosCheckArticleStateMessage:null,
    infosCheckArticleStateData:[],

    infosCheckArticleLikesStatus:null,
    infosCheckArticleLikesMessage:null,
    infosCheckArticleLikesData:[],

    infosCheckArticleCommentsListStatus:null,
    infosCheckArticleCommentsListMessage:null,
    infosCheckArticleCommentsListData:[],

    infosCheckArticleCommentsAddStatus:null,
    infosCheckArticleCommentsAddMessage:null,
    infosCheckArticleCommentsAddData:[],
    infosCheckArticleCommentsAddErrors:[],

    infosCheckArticleCommentsAddAuthStatus:null,
    infosCheckArticleCommentsAddAuthMessage:null,
    infosCheckArticleCommentsAddAuthData:[],
    infosCheckArticleCommentsAddAuthErrors:[],
});
const getters = {

    getInfosCheckArticleStateStatus(state){
        return state.infosCheckArticleStateStatus;
    },

    getInfosCheckArticleStateMessage(state){
        return state.infosCheckArticleStateMessage;
    },

    getInfosCheckArticleStateData(state){
        return state.infosCheckArticleStateData;
    },

    getInfosCheckArticleLikesStatus(state){
        return state.infosCheckArticleLikesStatus;
    },

    getInfosCheckArticleLikesMessage(state){
        return state.infosCheckArticleLikesMessage;
    },

    getInfosCheckArticleLikesData(state){
        return state.infosCheckArticleLikesData;
    },

    getInfosCheckArticleCommentsListStatus(state){
        return state.infosCheckArticleCommentsListStatus;
    },

    getInfosCheckArticleCommentsListMessage(state){
        return state.infosCheckArticleCommentsListMessage;
    },

    getInfosCheckArticleCommentsListData(state){
        return state.infosCheckArticleCommentsListData;
    },

    getInfosCheckArticleCommentsAddStatus(state){
        return state.infosCheckArticleCommentsAddStatus;
    },

    getInfosCheckArticleCommentsAddMessage(state){
        return state.infosCheckArticleCommentsAddMessage;
    },

    getInfosCheckArticleCommentsAddData(state){
        return state.infosCheckArticleCommentsAddData;
    },

    getInfosCheckArticleCommentsAddErrors(state){
        return state.infosCheckArticleCommentsAddErrors;
    },

    getInfosCheckArticleCommentsAddAuthStatus(state){
        return state.infosCheckArticleCommentsAddAuthStatus;
    },

    getInfosCheckArticleCommentsAddAuthMessage(state){
        return state.infosCheckArticleCommentsAddAuthMessage;
    },

    getInfosCheckArticleCommentsAddAuthData(state){
        return state.infosCheckArticleCommentsAddAuthData;
    },

    getInfosCheckArticleCommentsAddAuthErrors(state){
        return state.infosCheckArticleCommentsAddAuthErrors;
    },

}

const actions = {

    async checkArticleStateDataRequest({ commit }, payload) {
        const response = await axios.get(`/api/article/${payload.slug}/article_states/${localStorage.getItem('mtr')}`, payload).catch((err) => { console.log(err);});
       if(response.data.data.status == 200) {
            commit("setInfosCheckArticleStateStatus", 200);
            commit("setInfosCheckArticleStateData", response.data.data);
            commit("setInfosCheckArticleStateMessage", response.data.message);
        }else if(response.data.data.status == 201) {
            commit("setInfosCheckArticleStateStatus", 201);
            commit("setInfosCheckArticleStateData", response.data.data);
            commit("setInfosCheckArticleStateMessage", response.data.message);
        }else if(response.data.data.status == 202) {
            commit("setInfosCheckArticleStateStatus", 202);
            commit("setInfosCheckArticleStateData", response.data.data);
            commit("setInfosCheckArticleStateMessage", response.data.message);
        }else if(response.data.data.status == 422) {
            commit("setInfosCheckArticleStateStatus", 422);
            commit("setInfosCheckArticleStateMessage", response.data.message);
        }

    },

    async checkArticleLikesDataRequest({ commit }, payload) {
        const response = await axios.get(`/api/article/${payload.slug}/article_likes/${localStorage.getItem('mtr')}/check_likes`, payload).catch((err) => { console.log(err);});
       if(response.data.data.status == 200) {
            commit("setInfosCheckArticleLikesStatus", 200);
            commit("setInfosCheckArticleLikesData", response.data.data);
            commit("setInfosCheckArticleLikesMessage", response.data.message);
        }else if(response.data.data.status == 201) {
            commit("setInfosCheckArticleLikesStatus", 201);
            commit("setInfosCheckArticleLikesData", response.data.data);
            commit("setInfosCheckArticleLikesMessage", response.data.message);
        }else if(response.data.data.status == 202) {
            commit("setInfosCheckArticleLikesStatus", 202);
            commit("setInfosCheckArticleLikesData", response.data.data);
            commit("setInfosCheckArticleLikesMessage", response.data.message);
        }else if(response.data.data.status == 422) {
            commit("setInfosCheckArticleLikesStatus", 422);
            commit("setInfosCheckArticleLikesMessage", response.data.message);
        }

    },

    async checkArticleCommentsDataRequest({ commit }, payload) {
        const response = await axios.get(`/api/article/${payload.slug}/article_comments/${localStorage.getItem('mtr')}/check_comments`, payload).catch((err) => { console.log(err);});
       if(response.data.data.status == 200) {
            commit("setInfosCheckArticleCommentsListStatus", 200);
            commit("setInfosCheckArticleCommentsListData", response.data.data);
            commit("setInfosCheckArticleCommentsListMessage", response.data.message);
        }else if(response.data.data.status == 422) {
            commit("setInfosCheckArticleCommentsListStatus", 422);
            commit("setInfosCheckArticleCommentsListMessage", response.data.message);
        }

    },

    async createArticleCommentsDataRequest({ commit }, payload) {
        const response = await axios.post(`/api/article/${payload.slug}/article_comments/${localStorage.getItem('mtr')}/create_comments`, payload).catch((err) => { console.log(err);});
        if(response.data.data.status == 200) {
            commit("setInfosCheckArticleCommentsAddStatus", 'success');
            commit("setInfosCheckArticleCommentsAddData", response.data.data);
            commit("setInfosCheckArticleCommentsAddMessage", response.data.message);
        }else if(response.data.data.status == 422) {
            commit("setInfosCheckArticleCommentsAddStatus", 'failed');
            commit("setInfosCheckArticleCommentsAddMessage", response.data.message);
        }else if(response.data.data.status == 401) {
            commit("setInfosCheckArticleCommentsAddStatus", 'error');
             commit("setInfosCheckArticleCommentsAddErrors", response.data.data.errors);
            commit("setInfosCheckArticleCommentsAddMessage", response.data.message);
        }

    },

    async createArticleCommentsAuthDataRequest({ commit }, payload) {
        const response = await jwtInterceptor.post(`article/${payload.slug}/article_comments/${localStorage.getItem('mtr')}/create_auth_comments`, payload).catch((err) => { console.log(err);});
        if(response.data.data.status == 200) {
            commit("setInfosCheckArticleCommentsAddAuthStatus", 'success');
            commit("setInfosCheckArticleCommentsAddAuthData", response.data.data);
            commit("setInfosCheckArticleCommentsAddAuthMessage", response.data.message);
        }else if(response.data.data.status == 422) {
            commit("setInfosCheckArticleCommentsAddAuthStatus", 'failed');
            commit("setInfosCheckArticleCommentsAddAuthMessage", response.data.message);
        }else if(response.data.data.status == 401) {
            commit("setInfosCheckArticleCommentsAddAuthStatus", 'error');
             commit("setInfosCheckArticleCommentsAddAuthErrors", response.data.data.errors);
            commit("setInfosCheckArticleCommentsAddAuthMessage", response.data.message);
        }

    },

}

const mutations = {
    setInfosCheckArticleStateStatus(state, value){
        state.infosCheckArticleStateStatus = value;
    },

    setInfosCheckArticleStateMessage(state, value){
        state.infosCheckArticleStateMessage = value;
    },

    setInfosCheckArticleStateData(state, value){
        state.infosCheckArticleStateData = value;
    },

    setInfosCheckArticleLikesStatus(state, value){
        state.infosCheckArticleLikesStatus = value;
    },

    setInfosCheckArticleLikesMessage(state, value){
        state.infosCheckArticleLikesMessage = value;
    },

    setInfosCheckArticleLikesData(state, value){
        state.infosCheckArticleLikesData = value;
    },


    setInfosCheckArticleCommentsListStatus(state, value){
        state.infosCheckArticleCommentsListStatus = value;
    },

    setInfosCheckArticleCommentsListMessage(state, value){
        state.infosCheckArticleCommentsListMessage = value;
    },

    setInfosCheckArticleCommentsListData(state, value){
        state.infosCheckArticleCommentsListData = value;
    },

    setInfosCheckArticleCommentsAddStatus(state, value){
        state.infosCheckArticleCommentsAddStatus = value;
    },

    setInfosCheckArticleCommentsAddMessage(state, value){
        state.infosCheckArticleCommentsAddMessage = value;
    },

    setInfosCheckArticleCommentsAddData(state, value){
        state.infosCheckArticleCommentsAddData = value;
    },

    setInfosCheckArticleCommentsAddErrors(state, value){
        state.infosCheckArticleCommentsAddErrors = value;
    },

    setInfosCheckArticleCommentsAddAuthStatus(state, value){
        state.infosCheckArticleCommentsAddAuthStatus = value;
    },

    setInfosCheckArticleCommentsAddAuthMessage(state, value){
        state.infosCheckArticleCommentsAddAuthMessage = value;
    },

    setInfosCheckArticleCommentsAddAuthData(state, value){
        state.infosCheckArticleCommentsAddAuthData = value;
    },

    setInfosCheckArticleCommentsAddAuthErrors(state, value){
        state.infosCheckArticleCommentsAddAuthErrors = value;
    },
}

export default{
    namespaced:true,
    state,
    getters,
    actions,
    mutations
}
