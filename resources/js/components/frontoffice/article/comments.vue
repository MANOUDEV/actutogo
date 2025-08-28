<script setup>
import { ref, computed, onMounted } from 'vue';
import { useStore } from 'vuex';
import moment from 'moment';
import Swal from 'sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css';  

const store = useStore();
const loading = ref(1);  
const loadingC = ref(false); 
const comments = ref({});
const message = ref(null);
const empty = ref(2);
const dataReady = ref(0);
const email = ref(null); 
const nom_complet = ref(null);
const content = ref(null); 
const error = ref(null);
const errors = ref({}); 
const authState = ref(false);
const showCommentFormState = ref(true);

const url = window.location.href;
const segments = window.location.pathname.split("/"); 
const dynamicData = segments[segments.length - 1];

const show = async () => {
  if (localStorage.getItem('access_token') && localStorage.getItem('nbRsp')) {
        await store.dispatch('meProfile/getMeProfile');
        
        const gettersMeProfileStatus =  store.getters['meProfile/getMeProfileStatus'];  

            if(gettersMeProfileStatus === 'success'){

                dataReady.value = 1

                authState.value = 1


            }else if(gettersMeProfileStatus === 'failed'){

                dataReady.value = 2;

                authState.value = 2

            }

    }else{

        dataReady.value = 2;
        authState.value = 2

    }
};
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

        initialComments();

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

        initialComments();

    }

    show();

};

const initialComments = async () => {  
    await store.dispatch('article/checkArticleCommentsDataRequest', {slug: dynamicData, mtr: localStorage.getItem('mtr')});
    const getterInfosCheckArticleCommentsListStatus =  store.getters['article/getInfosCheckArticleCommentsListStatus'];
    const getterInfosCheckArticleCommentsListData =  store.getters['article/getInfosCheckArticleCommentsListData'];
    const getterInfosCheckArticleCommentsListMessage =  store.getters['article/getInfosCheckArticleCommentsListMessage'];

    if(getterInfosCheckArticleCommentsListStatus === 200){

        comments.value = getterInfosCheckArticleCommentsListData
        loading.value = 0

    }else if(getterInfosCheckArticleCommentsListStatus === 201){

        comments.value = getterInfosCheckArticleCommentsListData
        loading.value = 0

    }else if(getterInfosCheckArticleCommentsListStatus === 202){

        comments.value = getterInfosCheckArticleCommentsListData
        loading.value = 0
 

    }else if(getterInfosCheckArticleCommentsListStatus === 422){

        message.value = getterInfosCheckArticleCommentsListMessage

        loading.value = 2

    }

};

const submitCommentAuth = async () =>{
    
    loadingC.value = true

    error.value = false

    errors.value = []

    await store.dispatch('article/createArticleCommentsAuthDataRequest', {content: content.value, slug: dynamicData, mtr: localStorage.getItem('mtr')});

    const gettersInfosCheckArticleCommentsAddAuthStatus =  store.getters['article/getInfosCheckArticleCommentsAddAuthStatus']; 
    const gettersInfosCheckArticleCommentsAddAuthMessage =  store.getters['article/getInfosCheckArticleCommentsAddAuthMessage'];  
    const gettersInfosCheckArticleCommentsAddAuthErrors =  store.getters['article/getInfosCheckArticleCommentsAddAuthErrors']; 

    console.log(gettersInfosCheckArticleCommentsAddAuthStatus)

    if(gettersInfosCheckArticleCommentsAddAuthStatus === 'success'){

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: 'success',
            title: gettersInfosCheckArticleCommentsAddAuthMessage
        })

        loadingC.value = false
        
        content.value = null

        error.value = false

        errors.value = []

        getResults();

    }else if(gettersInfosCheckArticleCommentsAddAuthStatus === 'failed'){

        error.value = gettersInfosCheckArticleCommentsAddAuthMessage

        errors.value = []

        loadingC.value = false

    }else if(gettersInfosCheckArticleCommentsAddAuthStatus === 'error'){


        error.value = gettersInfosCheckArticleCommentsAddAuthMessage

        errors.value = gettersInfosCheckArticleCommentsAddAuthErrors

        loadingC.value = false
    }

}


const submitComment = async () =>{
    
    loadingC.value = true

    error.value = false

    errors.value = []

    await store.dispatch('article/createArticleCommentsDataRequest', {content: content.value, email: email.value, nom_complet: nom_complet.value, slug: dynamicData, mtr: localStorage.getItem('mtr') });

    const gettersInfosCheckArticleCommentsAddStatus =  store.getters['article/getInfosCheckArticleCommentsAddStatus']; 
    const gettersInfosCheckArticleCommentsAddMessage =  store.getters['article/getInfosCheckArticleCommentsAddMessage'];  
    const gettersInfosCheckArticleCommentsAddErrors =  store.getters['article/getInfosCheckArticleCommentsAddErrors']; 

    if(gettersInfosCheckArticleCommentsAddStatus === 'success'){

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: 'success',
            title: gettersInfosCheckArticleCommentsAddMessage
        })

        loadingC.value = false
        
        content.value = null

        email.value = null

        nom_complet.value = null

        error.value = false

        errors.value = []

        getResults();

    }else if(gettersInfosCheckArticleCommentsAddStatus === 'failed'){

        error.value = gettersInfosCheckArticleCommentsAddMessage

        errors.value = []

        loadingC.value = false

    }else if(gettersInfosCheckArticleCommentsAddStatus === 'error'){


        error.value = gettersInfosCheckArticleCommentsAddMessage

        errors.value = gettersInfosCheckArticleCommentsAddErrors

        loadingC.value = false
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
    <div v-if="loading == 0" class="mt-2">
        <!-- Review START -->
        <div class="container" >
            <!-- Title and Select -->
            <div class="d-flex justify-content-between align-items-center">
                <!-- Title -->
                <h5 class="mb-0">{{ comments.article.comment_count }} commentaire<span v-if="comments.comment_count < 2"></span> <span v-else>s</span></h5>
                	
            </div>
            
            <div v-if="showCommentFormState">
                <hr class="my-4"> <!-- Divider -->
                <div v-if="dataReady == 0">
                    <br><br><br> <br>
                    <div class="d-flex justify-content-center mt-3">
                        <div class="spinner-border text-success"  role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <br><br><br><br><br>
                </div>
                <div v-else-if="dataReady == 1">
                    <!-- Form START -->
                    <form class="contact-form" id="contact-form" name="contactform" method="POST">

                        <div v-if="error">
                            <br>
                            <div class="alert alert-danger"  role="alert">
                                {{ error }}
                            </div>
                        </div>
                        <!-- Main form -->
                        <div class="row">
                           
                            <div class="col-md-12">
                                <!-- Message -->
                                <div class="mb-3" v-if="errors.content">
                                    <textarea v-model="content" placeholder='Redigez votre commentaire' style=" min-height: 100px;" class="form-control is-invalid" contentType="html" ></textarea>
                                    <div id="exampleInputPassword1" v-for="errorContent in errors.content" :key="errorContent" class="invalid-feedback">
                                        {{ errorContent }}
                                    </div>
                                </div>
                                <div class="mb-3" v-else>
                                    <textarea v-model="content" placeholder='Redigez votre commentaire' style=" min-height: 100px;" class="form-control" contentType="html" ></textarea>
                                    
                                </div>
                            </div>
                        <!-- submit button -->
                            <div class="col-md-12 text-start">
                                <button @click.prevent="submitCommentAuth" v-if="!loadingC" class="btn btn-primary w-100"  >Envoyer le commentaire</button>
                                <button type="button" disabled v-else class="btn btn-primary w-100">
                                    <i  style="color: #fff" class="fa fa-spinner fa-spin fa-1x fa-fw"></i>
                                    <span class="sr-only">Loading...</span>  Envoie en cours ...
                                </button>
                            </div>
                        </div>
                    </form>
                    <!-- Form END -->
                </div>

                <div v-else-if="dataReady == 2">

                    <div v-if="error">
                        <br>
                        <div class="alert alert-danger"  role="alert">
                            {{ error }}
                        </div>
                    </div>
                    <!-- Form START -->
                    <form class="contact-form" >
                        <!-- Main form -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-md-12">
                                <!-- Nom Complet -->
                                <div class="mb-3" v-if="errors.nom_complet">
                                    <input  id="con-nom_complet" name="nom_complet" v-model="nom_complet" type="text" class="form-control is-invalid" placeholder="Votre nom complet">
                                    <div id="exampleInputPassword1" v-for="errornom_complet in errors.nom_complet" :key="errornom_complet" class="invalid-feedback">
                                        {{ errornom_complet }}
                                    </div>
                                </div>
                                    <div class="mb-3" v-else>
                                    <input  id="con-nom_complet" name="nom_complet" v-model="nom_complet" type="text" class="form-control" placeholder="Votre nom complet">
                                </div>
                            </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3" v-if="errors.email">
                                    <input  id="con-email" name="email" v-model="email" type="email" class="form-control is-invalid" placeholder="Votre email">
                                    <div id="exampleInputPassword1" v-for="errorEmail in errors.email" :key="errorEmail" class="invalid-feedback">
                                        {{ errorEmail }}
                                    </div>
                                </div>
                                <div class="mb-3" v-else>
                                    <input  id="con-email" name="email" v-model="email" type="email" class="form-control" placeholder="Votre email">
                                </div>
                            </div>
                           
                            <div class="col-md-12">
                                <!-- Message -->
                                <div class="mb-3" v-if="errors.content">
                                    <textarea v-model="content" placeholder='Redigez votre commentaire' style=" min-height: 100px;" class="form-control is-invalid" contentType="html" ></textarea>
                                    <div id="exampleInputPassword1" v-for="errorContent in errors.content" :key="errorContent" class="invalid-feedback">
                                        {{ errorContent }}
                                    </div>
                                </div>
                                <div class="mb-3" v-else>
                                    <textarea v-model="content" placeholder='Redigez votre commentaire' class="form-control" style=" min-height: 100px;" contentType="html" ></textarea>
                                    
                                </div>
                            </div>
                        <!-- submit button -->
                            <div class="col-md-12 text-start">
                                <button @click.prevent="submitComment" v-if="!loadingC" class="btn btn-primary w-100"  >Envoyer le commentaire</button>
                                <button type="button" disabled v-else class="btn btn-primary w-100">
                                    <i  style="color: #fff" class="fa fa-spinner fa-spin fa-1x fa-fw"></i>
                                    <span class="sr-only">Loading...</span>  Envoie en cours ...
                                </button>
                            </div>
                        </div>
                    </form>
                    <!-- Form END -->
                </div>
            </div>
            <hr class="my-4"> <!-- Divider -->
            <div v-for="result in comments.comments " :key="result.id">

                <!-- Review item -->
                <div class="d-flex" >
                    <div class="avatar avatar-md">
                        <div class="avatar avatar-md rounded-circle float-start me-3 bg-success">
                            <span class="text-light position-absolute top-50 start-50 translate-middle fw-bold">{{ result.nom_complet[0].toUpperCase()  }}{{ result.nom_complet[1].toUpperCase()  }}</span>
                        </div>
                    </div>
                    &nbsp; &nbsp; &nbsp; &nbsp; 
                    <div>
                        <div class="d-flex justify-content-between mb-2">
                            <div>
                                <h6 class="m-0">{{ result.nom_complet }}</h6>
                                <span class="me-3 small">{{ moment(result.date_publish).format('DD/MM/YYYY') }}</span>
                            </div>	
                            
                        </div>
                        <p v-html="result.content"></p>
                        
                    </div>
                </div>
                

                <hr class="my-4"> <!-- Divider -->
            </div>

        </div>
    </div>
</template>