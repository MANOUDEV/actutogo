<script setup>
import { ref, computed, onMounted } from 'vue';
import { useStore } from 'vuex'; 

const store = useStore();
const loading = ref(1); 
const checkLikes = ref(false);
const checkViews = ref(false);
const states = ref({});
const message = ref(null);
const empty = ref(2);

const url = window.location.href;
const segments = window.location.pathname.split("/"); 
const dynamicData = segments[segments.length - 1];
const createVisitorMatricule = async () =>{
    loading.value = true    
     
    await store.dispatch('visitorsActions/createVisitorsDataRequest');
    const getterInfosCreateVisitorsStatus =  store.getters['visitorsActions/getInfosCreateVisitorsStatus'];
    const getterInfosCreateVisitorsData =  store.getters['visitorsActions/getInfosCreateVisitorsData'];
    const getterInfosCreateVisitorsMessage =  store.getters['visitorsActions/getInfosCreateVisitorsMessage']; 
    if(getterInfosCreateVisitorsStatus ==="success"){

        localStorage.setItem('mtr', getterInfosCreateVisitorsData.visitor_matricule_create.matricule_reference)
 
        empty.value = 0

        loading.value = false

        initialState();

    }else if( getterInfosCreateVisitorsStatus ==="empty"){

    empty.value = 1

    loading.value = false

    }else{

        empty.value = 3 

    }


}
 
const getResults = async () =>{

    await store.dispatch('visitorsActions/checkVisitorsDataRequest');

    const getterInfosCheckVisitorsStatus =  store.getters['visitorsActions/getInfosCheckVisitorsStatus']; 

    if(getterInfosCheckVisitorsStatus ==="empty"){

        createVisitorMatricule() 

    }else if(getterInfosCheckVisitorsStatus ==="success"){

        initialState();

    }

};

const initialState = async () => { 
    loading.value = 1
    await store.dispatch('article/checkArticleStateDataRequest', {slug: dynamicData, mtr: localStorage.getItem('mtr')});
    const getterInfosCheckArticleStateStatus =  store.getters['article/getInfosCheckArticleStateStatus'];
    const getterInfosCheckArticleStateData =  store.getters['article/getInfosCheckArticleStateData'];
    const getterInfosCheckArticleStateMessage =  store.getters['article/getInfosCheckArticleStateMessage'];

    if(getterInfosCheckArticleStateStatus === 200){

        states.value = getterInfosCheckArticleStateData.article

        loading.value = 0

    }else if(getterInfosCheckArticleStateStatus === 201){

        states.value = getterInfosCheckArticleStateData.article

        loading.value = 0

        checkViews.value = true

    }else if(getterInfosCheckArticleStateStatus === 202){

        states.value = getterInfosCheckArticleStateData.article

        loading.value = 0

        checkViews.value = true

        checkLikes.value = true

    }else if(getterInfosCheckArticleStateStatus === 422){

        message.value = getterInfosCheckArticleStateMessage

        loading.value = 2

    }

};
const actionLikes = async () => {

    await store.dispatch('article/checkArticleLikesDataRequest', {slug: dynamicData, mtr: localStorage.getItem('mtr')});
    const getterInfosCheckArticleLikesStatus =  store.getters['article/getInfosCheckArticleLikesStatus'];
    const getterInfosCheckArticleLikesData =  store.getters['article/getInfosCheckArticleLikesData'];
    const getterInfosCheckArticleLikesMessage =  store.getters['article/getInfosCheckArticleLikesMessage'];

     if(getterInfosCheckArticleLikesStatus === 201){

        states.value.likes_count += 1

        checkLikes.value = true

        
    }else if(getterInfosCheckArticleLikesStatus === 202){

        states.value.likes_count -= 1

        checkLikes.value = false

    }else if(getterInfosCheckArticleLikesStatus === 422){

    }
}

onMounted(() => {
  getResults();
});
</script>
<template>
   <ul class="list-inline mb-2 mt-2" v-if="loading == 1">
        <li class="list-inline-item">
            <button class="btn btn-outline-light btn-sm ">
                <i  style="color: green; font-size: 20px;" class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span>
                Chargement des données ...
            </button>
        </li>
    </ul>
    <ul class="list-inline mb-2 mt-2" v-if="loading == 2">
        <li class="list-inline-item">
            <button class="btn btn-danger btn-sm">
                <i class="fa fa-exclamation-triangle fa-1x fa-fw"></i>
                Impossible de charger les données.
            </button>
        </li>
    </ul>
    <ul class="list-inline mb-0 mt-2" v-else-if="loading==0">
        <li class="list-inline-item">
            <span style="cursor: pointer;" class="btn btn-outline-light btn-sm"><i class="far fa-comment-alt me-1"></i> {{ states.comment_count }} commentaire<span v-if="states.comment_count< 2"></span><span v-else>s</span> </span>
        </li>
        <li class="list-inline-item">
            <span  v-if="!checkViews" class="btn btn-outline-light btn-sm"><i class="far fa-eye me-1"></i> {{ states.views_count }} vue<span v-if="states.views_count< 2"></span><span v-else>s</span></span>
            <span  v-else class="btn btn-primary btn-sm"><i class="far fa-eye me-1"></i> {{ states.views_count }} vue<span v-if="states.views_count< 2"></span><span v-else>s</span></span>
        </li>
        <li class="list-inline-item">
            <span style="cursor: pointer;" v-if="!checkLikes" @click="actionLikes" class="btn btn-outline-light btn-sm"><i class="fas fa-heart me-1 text-danger"></i>  {{ states.likes_count }} j'aime</span>
            <span style="cursor: pointer;" v-else @click="actionLikes" class="btn btn-danger btn-sm"><i class="fas fa-heart me-1"></i>  {{ states.likes_count }} j'aime</span>
        </li>

        <li class="list-inline-item">
            <span class="btn btn-outline-light btn-sm"><i class="fas fa-share-alt me-2"></i> 49 partages</span>
        </li>
    </ul>
</template> 