import jwtInterceptor  from '../../../../../shared/jwtInterceptor';
const state = () => ({

    infosPublicationCreateBySlugTypeShowStatus:null,
    infosPublicationCreateBySlugTypeShowMessage:null,
    infosPublicationCreateBySlugTypeShowData:[],

    infosPublicationGetFilesStatus:null,
    infosPublicationGetFilesMessage:null,
    infosPublicationGetFilesData:[],
    infosPublicationGetFilesErrors:[],

    infosPublicationSearchByTypeFilesStatus:null,
    infosPublicationSearchByTypeFilesMessage:null,
    infosPublicationSearchByTypeFilesData:[],
    infosPublicationSearchByTypeFilesErrors:[],
 
});
const getters = {
 
    getInfosPublicationCreateBySlugTypeShowStatus(state){
        return state.infosPublicationCreateBySlugTypeShowStatus;
    },

    getInfosPublicationCreateBySlugTypeShowMessage(state){
        return state.infosPublicationCreateBySlugTypeShowMessage;
    },

    getInfosPublicationCreateBySlugTypeShowData(state){
        return state.infosPublicationCreateBySlugTypeShowData;
    },   

    
    getInfosPublicationGetFilesStatus(state){
        return state.infosPublicationGetFilesStatus;
    },

    getInfosPublicationGetFilesMessage(state){
        return state.infosPublicationGetFilesMessage;
    },

    getInfosPublicationGetFilesData(state){
        return state.infosPublicationGetFilesData;
    },  
    
    getInfosPublicationGetFilesErrors(state){
        return state.infosPublicationGetFilesErrors;
    },
    
    getInfosPublicationSearchByTypeFilesStatus(state){
        return state.infosPublicationSearchByTypeFilesStatus;
    },

    getInfosPublicationSearchByTypeFilesMessage(state){
        return state.infosPublicationSearchByTypeFilesMessage;
    },

    getInfosPublicationSearchByTypeFilesData(state){
        return state.infosPublicationSearchByTypeFilesData;
    },  
    
    getInfosPublicationSearchByTypeFilesErrors(state){
        return state.infosPublicationSearchByTypeFilesErrors;
    },  
 
}

const actions = {
    
    async publicationCreateBySlugTypeShowDataRequest({ commit }, payload) {
        const response = await jwtInterceptor.get(`backoffice/admin/publications/create/${payload.slug}/type_publications`).catch((err) => { console.log(err);});
        if (response && (response.data.data.status == 200)) {
            commit("setInfosPublicationCreateBySlugTypeShowStatus", "success");
            commit("setInfosPublicationCreateBySlugTypeShowMessage", response.data.message);
            commit("setInfosPublicationCreateBySlugTypeShowData", response.data.data);
        }else if(response.data.data.status == 401) {
            commit("setInfosPublicationCreateBySlugTypeShowStatus", "empty");
            commit("setInfosPublicationCreateBySlugTypeShowData", response.data.data);
            commit("setInfosPublicationCreateBySlugTypeShowMessage", response.data.message);
        }else if(response.data.data.status == 422){
            commit("setInfosPublicationCreateBySlugTypeShowStatus", "error");
            commit("setInfosPublicationCreateBySlugTypeShowMessage", response.data.message);
        }
    },
 
     async publicationGetFilesDataRequest({ commit }, payload) {
        const response = await axios.get(`/api/auth/backoffice/admin/publications/create/${payload.slug}/get_files?page=${payload.page}&search=${payload.search}`, payload).catch((err) => { console.log(err);});
        if (response && (response.data.data.status == 200)) {
            commit("setInfosPublicationGetFilesStatus", "success");
            commit("setInfosPublicationGetFilesMessage", response.data.message);
            commit("setInfosPublicationGetFilesData", response.data.data);
        }else if(response.data.data.status == 401) {
            commit("setInfosPublicationGetFilesStatus", "empty");
            commit("setInfosPublicationGetFilesErrors", response.data.data.errors);
            commit("setInfosPublicationGetFilesMessage", response.data.message);
        }else if(response.data.data.status == 422){
            commit("setInfosPublicationGetFilesStatus", "error");
            commit("setInfosPublicationGetFilesMessage", response.data.message);
        }
    },

    async publicationSearchByTypeFilesDataRequest({ commit }, payload) {
        const response = await axios.get(`/api/auth/backoffice/admin/publications/create/${payload.slug}/get_files_search/${payload.status}/search_by_type_files?page=${payload.page}&search=${payload.search}`, payload).catch((err) => { console.log(err);});
        if (response && (response.data.data.status == 200)) {
            commit("setInfosPublicationSearchByTypeFilesStatus", "success");
            commit("setInfosPublicationSearchByTypeFilesMessage", response.data.message);
            commit("setInfosPublicationSearchByTypeFilesData", response.data.data);
        }else if(response.data.data.status == 401) {
            commit("setInfosPublicationSearchByTypeFilesStatus", "empty");
            commit("setInfosPublicationSearchByTypeFilesErrors", response.data.data.errors);
            commit("setInfosPublicationSearchByTypeFilesMessage", response.data.message);
        }else if(response.data.data.status == 422){
            commit("setInfosPublicationSearchByTypeFilesStatus", "error");
            commit("setInfosPublicationSearchByTypeFilesMessage", response.data.message);
        }
    },
}

const mutations = {
     

    setInfosPublicationCreateBySlugTypeShowStatus(state, value){
        state.infosPublicationCreateBySlugTypeShowStatus = value;
    },

    setInfosPublicationCreateBySlugTypeShowMessage(state, value){
        state.infosPublicationCreateBySlugTypeShowMessage = value;
    },

    setInfosPublicationCreateBySlugTypeShowData(state, value){
        state.infosPublicationCreateBySlugTypeShowData = value;
    },

    
    setInfosPublicationGetFilesStatus(state, value){
        state.infosPublicationGetFilesStatus = value;
    },

    setInfosPublicationGetFilesMessage(state, value){
        state.infosPublicationGetFilesMessage = value;
    },

    setInfosPublicationGetFilesData(state, value){
        state.infosPublicationGetFilesData = value;
    },

    setInfosPublicationGetFilesErrors(state, value){
        state.infosPublicationGetFilesErrors = value;
    },

    setInfosPublicationSearchByTypeFilesStatus(state, value){
        state.infosPublicationSearchByTypeFilesStatus = value;
    },

    setInfosPublicationSearchByTypeFilesMessage(state, value){
        state.infosPublicationSearchByTypeFilesMessage = value;
    },

    setInfosPublicationSearchByTypeFilesData(state, value){
        state.infosPublicationSearchByTypeFilesData = value;
    },

    setInfosPublicationSearchByTypeFilesErrors(state, value){
        state.infosPublicationSearchByTypeFilesErrors = value;
    },
 
 
}

export default{
    namespaced:true,
    state,
    getters,
    actions,
    mutations
}
