<script setup>
    
import { ref, computed, onMounted } from "vue"
import { useStore } from "vuex" 
import Swal from 'sweetalert2';
import StoreAlertInfosAnnonceComponent from './store_publications/alertInfosAnnoncesComponent.vue'
import StorePublicitesComponent from './store_publications/publicitesComponent.vue'
import StoreArticlesComponent from './store_publications/articlesComponent.vue'
import StoreVideosComponent from './store_publications/videosComponent.vue'
import StoreEventsComponent from './store_publications/eventsComponent.vue'
import { useRoute } from "vue-router"; // ✅ import

const store = useStore();
const route = useRoute(); 

// composants importés utilisables directement dans <template>
const dataReady = ref(0)
const meRoleName = ref(null)
 
const publicationCreateBySlugTypeShowData = ref(null);
const publicationCreateBySlugTypeShowMessage = ref(null);
const categories = ref([]);
const tags = ref([]);

const meProfileUserName = ref(null)
const meProfileRoleName = ref(null)
const authSectionModal = ref("LIST")

const username = ref(null)
const email = ref(null)
const password = ref(null)
const password_confirm = ref(null)

const loadingLogin = ref(false)
const errorLogin = ref(false)
const errorsLogin = ref([])

const loadingConnect = ref(false)
const remember_me = ref(false)
const authSectionStepModal = ref(1)

const loadingForgotPasswordFirst = ref(false)
const errorForgotPasswordFirstMessage = ref(false)
const errorsForgotPasswordFirstErrors = ref([])

const loadingForgotPasswordTwo = ref(false)
const errorForgotPasswordTwoMessage = ref(false)
const errorsForgotPasswordTwoErrors = ref([])

const loadingForgotPasswordThree = ref(false)
const errorForgotPasswordThreeMessage = ref(false)
const errorsForgotPasswordThreeErrors = ref([])

const step = ref(1)
const name = ref(null)
const slug = ref(null)

const loadingResendOTP = ref(false)
const loading = ref(true)
const empty = ref(2)

const otp = ref(null); 

const loadingCreate = ref(false)
const loadingUpdate = ref(false)
const loadingDelete = ref(false)

const showPsw = ref(false)
const showPswC = ref(false)


const showPassword = () => {
  const x = document.getElementById("psw-input")
  if (x && x.type === "password") {
    x.type = "text"
    showPsw.value = true
  } else if (x) {
    x.type = "password"
    showPsw.value = false
  }
}

const showPasswordC = () => {
  const x = document.getElementById("psw-input_c")
  if (x && x.type === "password") {
    x.type = "text"
    showPswC.value = true
  } else if (x) {
    x.type = "password"
    showPswC.value = false
  }
}

const authFormModalpublicationCreateBySlugTypeShowClose = () => {
  errorForgotPasswordFirstMessage.value = false
  errorsForgotPasswordFirstErrors.value = []
  errorForgotPasswordTwoMessage.value = false
  errorsForgotPasswordTwoErrors.value = []
  errorForgotPasswordThreeMessage.value = false
  errorsForgotPasswordThreeErrors.value = []
  errorLogin.value = false
  errorsLogin.value = []

  // si tu utilises jQuery pour tes modals :
  $('#authFormModalpublicationCreateBySlugTypeShow').modal('hide')
}


const authModalClick = (action_auth = "LIST", nameParam = null, slugParam = null) => {
    if (
        localStorage.getItem("remember_me") === "true" &&
        localStorage.getItem("username") &&
        localStorage.getItem("password")
    ) {
        username.value = localStorage.getItem("username");
        password.value = localStorage.getItem("password");
        remember_me.value = localStorage.getItem("remember_me");
    }

    name.value = nameParam;
    slug.value = slugParam;
    authSectionModal.value = action_auth;

    // ouverture du modal
    $("#authFormModalpublicationCreateBySlugTypeShow").modal("show");
};


const PreviousForgotPasswordStep = () => {
  step.value = 1
  errorForgotPasswordFirstMessage.value = null
  errorsForgotPasswordFirstErrors.value = []
  errorForgotPasswordTwoMessage.value = null
  errorsForgotPasswordTwoErrors.value = []
  errorForgotPasswordThreeMessage.value = null
  errorsForgotPasswordThreeErrors.value = []
  errorLogin.value = false
  errorsLogin.value = []
}

const PreviousHForgotPasswordStep = () => {
  step.value = 2
  errorForgotPasswordFirstMessage.value = null
  errorsForgotPasswordFirstErrors.value = []
  errorForgotPasswordTwoMessage.value = null
  errorsForgotPasswordTwoErrors.value = []
  errorForgotPasswordThreeMessage.value = null
  errorsForgotPasswordThreeErrors.value = []
  errorLogin.value = false
  errorsLogin.value = []
}

const changeAuthSectionStepModalToForgotPassword = () => {
  errorForgotPasswordFirstMessage.value = null
  errorsForgotPasswordFirstErrors.value = []
  errorForgotPasswordTwoMessage.value = null
  errorsForgotPasswordTwoErrors.value = []
  errorForgotPasswordThreeMessage.value = null
  errorsForgotPasswordThreeErrors.value = []
  errorLogin.value = false
  errorsLogin.value = []
  authSectionStepModal.value = 2
}

const changeAuthSectionStepModalToLogin = () => {
  errorForgotPasswordFirstMessage.value = null
  errorsForgotPasswordFirstErrors.value = []
  errorForgotPasswordTwoMessage.value = null
  errorsForgotPasswordTwoErrors.value = []
  errorForgotPasswordThreeMessage.value = null
  errorsForgotPasswordThreeErrors.value = []
  errorLogin.value = false
  errorsLogin.value = []
  authSectionStepModal.value = 1
}

// Fonction pour afficher les toasts
const showToast = (icon, title) => {
    const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    },
    });
    Toast.fire({ icon, title });
};


 // Vérifier l'email et envoyer OTP
const submitVerifyForgotPasswordEmail = async () => {
    loadingForgotPasswordFirst.value = true;
    errorForgotPasswordFirstMessage.value = null;
    errorsForgotPasswordFirstErrors.value = [];

    await store.dispatch('forgot_password/sendOtpForgotPassword', { email: email.value });

    const status = store.getters['forgot_password/getSendOtpForgotPasswordStatus'];
    const message = store.getters['forgot_password/getSendOtpForgotPasswordMessage'];
    const errors = store.getters['forgot_password/getSendOtpForgotPasswordErrors'];

    if (status === 'success') {
    showToast('success', message);
    step.value = 2;
    } else if (status === 'failed') {
    errorForgotPasswordFirstMessage.value = message;
    } else if (status === 'error') {
    errorForgotPasswordFirstMessage.value = message;
    errorsForgotPasswordFirstErrors.value = errors;
    }

    loadingForgotPasswordFirst.value = false;
};

// Renvoyer OTP
const submitResendOtp = async () => {
    loadingResendOTP.value = true;
    errorForgotPasswordFirstMessage.value = null;
    errorsForgotPasswordFirstErrors.value = [];

    await store.dispatch('forgot_password/sendOtpForgotPassword', { email: email.value });

    const status = store.getters['forgot_password/getSendOtpForgotPasswordStatus'];
    const message = store.getters['forgot_password/getSendOtpForgotPasswordMessage'];
    const errors = store.getters['forgot_password/getSendOtpForgotPasswordErrors'];

    if (status === 'success') {
    showToast('success', message);
    step.value = 2;
    } else if (status === 'failed') {
    errorForgotPasswordFirstMessage.value = message;
    } else if (status === 'error') {
    errorForgotPasswordFirstMessage.value = message;
    errorsForgotPasswordFirstErrors.value = errors;
    }

    loadingResendOTP.value = false;
};

// Vérifier l'OTP
const submitVerifyForgotPasswordOtp = async () => {
    loadingForgotPasswordTwo.value = true;
     errorForgotPasswordTwoMessage.value = null;
    errorsForgotPasswordTwoErrors.value = [];

    await store.dispatch('forgot_password/checkOtpForgotPassword', { email: email.value, otp: otp.value });

    const status = store.getters['forgot_password/getCheckOtpForgotPasswordStatus'];
    const message = store.getters['forgot_password/getCheckOtpForgotPasswordMessage'];
    const errors = store.getters['forgot_password/getCheckOtpForgotPasswordErrors'];

    if (status === 'success') {
    showToast('success', message);
    step.value = 3;
   } else if (status === 'failed') {
    errorForgotPasswordTwoMessage.value = message;
    } else if (status === 'error') {
    errorForgotPasswordTwoMessage.value = message;
    errorsForgotPasswordTwoErrors.value = errors;
    }

    loadingForgotPasswordTwo.value = false;
};

// Définir le nouveau mot de passe
const submitForgotPasswordNewPass = async () => {
    loadingForgotPasswordThree.value = true;
    errorForgotPasswordThreeMessage.value = null;
    errorsForgotPasswordThreeErrors.value = [];

    await store.dispatch('forgot_password/newPassOtpForgotPassword', {
    email: email.value,
    otp: otp.value,
    password: password.value,
    password_confirm: password_confirm.value,
    });

    const status = store.getters['forgot_password/getNewPassOtpForgotPasswordStatus'];
    const message = store.getters['forgot_password/getNewPassOtpForgotPasswordMessage'];
    const errors = store.getters['forgot_password/getNewPassOtpForgotPasswordErrors'];

    if (status === 'success') {
    showToast('success', message);
    step.value = 1;
   } else if (status === 'failed') {
    errorForgotPasswordThreeMessage.value = message;
    } else if (status === 'error') {
    errorForgotPasswordThreeMessage.value = message;
    errorsForgotPasswordThreeErrors.value = errors;
    }

    loadingForgotPasswordThree.value = false;
};
const submitLogin = async () => {
    loadingLogin.value = true;
    errorLogin.value = false;
    errorsLogin.value = [];

    await store.dispatch('login/login', {
    username: username.value,
    password: password.value,
    remember_me: remember_me.value
    });

    const loginStatus = store.getters['login/getLoginStatus'];
    const loginMessage = store.getters['login/getLoginMessage'];
    const loginErrors = store.getters['login/getLoginErrors'];

    const showToast = (title) => {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
        Toast.fire({ icon: 'success', title });
    };

    if (loginStatus === 'success admin') {
    if (remember_me.value) {
        localStorage.setItem('username', username.value);
        localStorage.setItem('password', password.value);
        localStorage.setItem('remember_me', true);
    } else {
        localStorage.setItem('remember_me', false);
    }

    showToast(loginMessage);

    // Actions selon authSectionModal
    if(authSectionModal.value == 'LIST'){

        $('#authFormModalpublicationCreateBySlugTypeShow').modal('hide');

        getResults()

    }

    } else if (loginStatus === 'success pub') {
    if (remember_me.value) {
        localStorage.setItem('username', username.value);
        localStorage.setItem('password', password.value);
        localStorage.setItem('remember_me', true);
    } else {
        localStorage.setItem('remember_me', false);
    }
    loadingLogin.value = false;
    window.location = '/pub/dashboard';

    } else if (loginStatus === 'success visitor') {
    if (remember_me.value) {
        localStorage.setItem('username', username.value);
        localStorage.setItem('password', password.value);
        localStorage.setItem('remember_me', true);
    } else {
        localStorage.setItem('remember_me', false);
    }
    loadingLogin.value = false;
    window.location = '/';

    } else if (loginStatus === 'failed') {
    errorLogin.value = loginMessage;
    errorsLogin.value = [];
    loadingLogin.value = false;

    } else if (loginStatus === 'error') {
    errorLogin.value = loginMessage;
    errorsLogin.value = loginErrors;
    loadingLogin.value = false;
    }

    loadingLogin.value = false;
};


const getResults = async (page = 1) => {

    loading.value = true;
    authSectionModal.value = "LIST";

    await store.dispatch("publicationAdmin/publicationCreateBySlugTypeShowDataRequest", {
        slug: route.params.slug
    });

    const status = store.getters["publicationAdmin/getInfosPublicationCreateBySlugTypeShowStatus"];

    if (status === "success") {
        publicationCreateBySlugTypeShowData.value = store.getters["publicationAdmin/getInfosPublicationCreateBySlugTypeShowData"];
        categories.value = publicationCreateBySlugTypeShowData.value.categories;
        tags.value = publicationCreateBySlugTypeShowData.value.tags;

        empty.value = 0;
        dataReady.value = 1;

    } else if (status === "empty") {
        publicationCreateBySlugTypeShowMessage.value = store.getters["publicationAdmin/getInfosPublicationCreateBySlugTypeShowMessage"];
        publicationCreateBySlugTypeShowData.value = store.getters["publicationAdmin/getInfosPublicationCreateBySlugTypeShowData"];

        empty.value = 1;
        dataReady.value = 1;

    } else if (status === "failed") {
        dataReady.value = 3;

    } else {
        publicationCreateBySlugTypeShowMessage.value = store.getters["publicationAdmin/getInfosPublicationCreateBySlugTypeShowMessage"];
        empty.value = 3;
        dataReady.value = 4;
    }
};

// Fonction show() adaptée
const show = async () => {
    if (localStorage.getItem('access_token') && localStorage.getItem('nbRsp')) {

    await store.dispatch('roleSecurity/getMeRole');

    authSectionModal.value = 'LIST';

    const roleStatus = store.getters['roleSecurity/getRoleStatus'];
    const roleName = store.getters['roleSecurity/getMeRoleName'];

    if (roleStatus === 'success') {
        meRoleName.value = roleName;

        if (meRoleName.value === localStorage.getItem('nbRsp') && localStorage.getItem('nbRsp') === '&nbtsd!?') {
        
            getResults();
        } else {
            dataReady.value = 2;
        }

    } else if (roleStatus === 'failed') {
        dataReady.value = 3;
    }

    } else {
    dataReady.value = 4;
    }
};

onMounted(() => {
  show();
});
</script>
<template>
    <!-- =======================Author list START -->
    <section class="py-4">
        <div class="container">
            <div  v-if="dataReady == 0" >
                <br/><br/><br/><br/><br/><br/><br/>
                <div class="d-flex justify-content-center">
                    <img :src="`/assets/images/logo.png`"  style="width: 150px;" alt="empty">
                </div>
                <div class="d-flex justify-content-center mt-3">
                    <div class="spinner-border text-success" style="color: #006633" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <br/><br/><br/><br/><br/><br/><br/>
            </div>
            <div v-else-if="dataReady== 1">
                <div v-if="empty == 0">
                    <!-- Author list title START -->
                    <div class="row pb-4 mb-2" v-if="publicationCreateBySlugTypeShowData">
                        <div class="col-12">

                            <div class="d-sm-flex justify-content-sm-between align-items-center">
                                <h1 class="mb-2 mb-sm-0 h2">
                                    <router-link  :to="{name: 'admin.publications.create'}"  >
                                        <div class="btn btn-primary-soft  btn-round mb-0 " style="font-size: 25px">
                                            <i  class="bi bi-arrow-left-short"></i>
                                        </div>
                                    </router-link>
                                    {{publicationCreateBySlugTypeShowData.typePublication.name}}
                                </h1>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4" v-if="publicationCreateBySlugTypeShowData.typePublication.id === 2 || publicationCreateBySlugTypeShowData.typePublication.id === 3 ">
                        <StoreAlertInfosAnnonceComponent  :publicationCreate="publicationCreateBySlugTypeShowData" />
                    </div>

                    <div class="row g-4" v-else-if="publicationCreateBySlugTypeShowData.typePublication.id === 5">
                        <StorePublicitesComponent  :publicationCreate="publicationCreateBySlugTypeShowData" />
                    </div>

                    <div class="row g-4" v-else-if="publicationCreateBySlugTypeShowData.typePublication.id === 1">
                        <StoreArticlesComponent  :publicationCreate="publicationCreateBySlugTypeShowData" />
                    </div>

                    <div class="row g-4" v-else-if="publicationCreateBySlugTypeShowData.typePublication.id === 4">
                        <StoreVideosComponent  :publicationCreate="publicationCreateBySlugTypeShowData" />
                    </div>

                    <div class="row g-4" v-else-if="publicationCreateBySlugTypeShowData.typePublication.id === 6">
                        <StoreEventsComponent  :publicationCreate="publicationCreateBySlugTypeShowData" />
                    </div>
                    
                    <div class="row g-4" v-else >

                        <section class="overflow-hidden">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-9 text-center mx-auto my-0 my-md-5 py-0 py-lg-5 position-relative z-index-9">
                                        <!-- SVG shape START -->
                                        <figure class="position-absolute top-50 start-50 translate-middle opacity-7 z-index-n9">
                                        <svg width="650" height="379" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"  viewBox="0 0 510 297">
                                            <g>
                                            <path class="fill-primary opacity-1" d="M121,147.4c0,6-4.8,10.8-10.8,10.8H47.6c-6,0-10.8-4.8-10.8-10.8v-11.5c0-6,4.8-10.8,10.8-10.8h62.6
                                            c6,0,10.8,4.8,10.8,10.8V147.4z"/>
                                            <path class="fill-primary opacity-1" d="M179.4,90.2c0,6-4.8,10.8-10.8,10.8h-62.6c-6,0-10.8-4.8-10.8-10.8V78.7c0-6,4.8-10.8,10.8-10.8h62.6
                                            c6,0,10.8,4.8,10.8,10.8V90.2z"/>
                                            <path class="fill-primary opacity-1" d="M459.1,26.3c0,6-4.8,10.8-10.8,10.8h-62.6c-6,0-10.8-4.8-10.8-10.8V14.8c0-6,4.8-10.8,10.8-10.8h62.6
                                            c6,0,10.8,4.8,10.8,10.8V26.3z"/>
                                            <path class="fill-primary opacity-1" d="M422.1,66.9c0,6-4.8,10.8-10.8,10.8h-62.6c-6,0-10.8-4.8-10.8-10.8V55.3c0-6,4.8-10.8,10.8-10.8h62.6
                                            c6,0,10.8,4.8,10.8,10.8V66.9z"/>
                                            <path class="fill-primary opacity-1" d="M275.8,282.6c0,5.9-4.8,10.8-10.8,10.8h-62.6c-6,0-10.8-4.8-10.8-10.8v-11.5c0-6,4.8-10.8,10.8-10.8h62.6
                                            c6,0,10.8,4.8,10.8,10.8V282.6z"/>
                                            <path class="fill-primary opacity-1" d="M87.7,42.9c0,5.9-4.8,10.8-10.8,10.8H14.3c-6,0-10.8-4.8-10.8-10.8V31.4c0-6,4.8-10.8,10.8-10.8h62.6
                                            c6,0,10.8,4.8,10.8,10.8V42.9z"/>
                                            <path class="fill-primary opacity-1" d="M505.9,123.4c0,6-4.8,10.8-10.8,10.8h-62.6c-6,0-10.8-4.8-10.8-10.8v-11.5c0-6,4.8-10.8,10.8-10.8h62.6
                                            c6,0,10.8,4.8,10.8,10.8V123.4z"/>
                                            <path class="fill-primary opacity-1" d="M482.5,204.9c0,5.9-4.8,10.8-10.8,10.8h-62.6c-6,0-10.8-4.8-10.8-10.8v-11.5c0-6,4.8-10.8,10.8-10.8h62.6
                                            c5.9,0,10.8,4.8,10.8,10.8V204.9z"/>
                                            <path class="fill-primary opacity-1" d="M408.3,258.8c0,5.9-4.8,10.8-10.8,10.8H335c-6,0-10.8-4.8-10.8-10.8v-11.5c0-6,4.8-10.8,10.8-10.8h62.6
                                            c6,0,10.8,4.8,10.8,10.8V258.8z"/>
                                            <path class="fill-primary opacity-1" d="M147,252.5c0,5.9-4.8,10.8-10.8,10.8H73.6c-6,0-10.8-4.8-10.8-10.8V241c0-5.9,4.8-10.8,10.8-10.8h62.6
                                            c6,0,10.8,4.8,10.8,10.8V252.5z"/>
                                            </g>
                                        </svg>
                                        </figure>
                                        <!-- SVG shape START -->
                                        <!-- Content -->
                                        <h1 class="display-1 text-primary">Indisponible</h1>
                                        <h5>Le formulaire pour ce type de publication n'est pas encore disponible pour le moment.Merci de bien vouloir patienter un moment.</h5>
                                        <router-link :to="{name: 'admin.publications.create' }"   class="btn btn-danger-soft mt-3"><i class="fas fa-long-arrow-alt-left me-3"></i>Voir les types de publications.</router-link>

                                    </div>
                                </div>
                            </div>
                        </section>

                    </div>

                </div>

                <div  v-else-if="empty == 1">
                    <section class="overflow-hidden">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-9 text-center mx-auto my-0 my-md-5 py-0 py-lg-5 position-relative z-index-9">
                                    <!-- SVG shape START -->
                                    <figure class="position-absolute top-50 start-50 translate-middle opacity-7 z-index-n9">
                                    <svg width="650" height="379" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"  viewBox="0 0 510 297">
                                        <g>
                                        <path class="fill-primary opacity-1" d="M121,147.4c0,6-4.8,10.8-10.8,10.8H47.6c-6,0-10.8-4.8-10.8-10.8v-11.5c0-6,4.8-10.8,10.8-10.8h62.6
                                        c6,0,10.8,4.8,10.8,10.8V147.4z"/>
                                        <path class="fill-primary opacity-1" d="M179.4,90.2c0,6-4.8,10.8-10.8,10.8h-62.6c-6,0-10.8-4.8-10.8-10.8V78.7c0-6,4.8-10.8,10.8-10.8h62.6
                                        c6,0,10.8,4.8,10.8,10.8V90.2z"/>
                                        <path class="fill-primary opacity-1" d="M459.1,26.3c0,6-4.8,10.8-10.8,10.8h-62.6c-6,0-10.8-4.8-10.8-10.8V14.8c0-6,4.8-10.8,10.8-10.8h62.6
                                        c6,0,10.8,4.8,10.8,10.8V26.3z"/>
                                        <path class="fill-primary opacity-1" d="M422.1,66.9c0,6-4.8,10.8-10.8,10.8h-62.6c-6,0-10.8-4.8-10.8-10.8V55.3c0-6,4.8-10.8,10.8-10.8h62.6
                                        c6,0,10.8,4.8,10.8,10.8V66.9z"/>
                                        <path class="fill-primary opacity-1" d="M275.8,282.6c0,5.9-4.8,10.8-10.8,10.8h-62.6c-6,0-10.8-4.8-10.8-10.8v-11.5c0-6,4.8-10.8,10.8-10.8h62.6
                                        c6,0,10.8,4.8,10.8,10.8V282.6z"/>
                                        <path class="fill-primary opacity-1" d="M87.7,42.9c0,5.9-4.8,10.8-10.8,10.8H14.3c-6,0-10.8-4.8-10.8-10.8V31.4c0-6,4.8-10.8,10.8-10.8h62.6
                                        c6,0,10.8,4.8,10.8,10.8V42.9z"/>
                                        <path class="fill-primary opacity-1" d="M505.9,123.4c0,6-4.8,10.8-10.8,10.8h-62.6c-6,0-10.8-4.8-10.8-10.8v-11.5c0-6,4.8-10.8,10.8-10.8h62.6
                                        c6,0,10.8,4.8,10.8,10.8V123.4z"/>
                                        <path class="fill-primary opacity-1" d="M482.5,204.9c0,5.9-4.8,10.8-10.8,10.8h-62.6c-6,0-10.8-4.8-10.8-10.8v-11.5c0-6,4.8-10.8,10.8-10.8h62.6
                                        c5.9,0,10.8,4.8,10.8,10.8V204.9z"/>
                                        <path class="fill-primary opacity-1" d="M408.3,258.8c0,5.9-4.8,10.8-10.8,10.8H335c-6,0-10.8-4.8-10.8-10.8v-11.5c0-6,4.8-10.8,10.8-10.8h62.6
                                        c6,0,10.8,4.8,10.8,10.8V258.8z"/>
                                        <path class="fill-primary opacity-1" d="M147,252.5c0,5.9-4.8,10.8-10.8,10.8H73.6c-6,0-10.8-4.8-10.8-10.8V241c0-5.9,4.8-10.8,10.8-10.8h62.6
                                        c6,0,10.8,4.8,10.8,10.8V252.5z"/>
                                        </g>
                                    </svg>
                                    </figure>
                                    <!-- SVG shape START -->
                                    <!-- Content -->
                                    <h1 class="display-1 text-primary">Erreur</h1>
                                    <h5>{{ publicationCreateBySlugTypeShowMessage  }}</h5>
                                    <router-link :to="{name: 'admin.publications.create' }"   class="btn btn-danger-soft mt-3"><i class="fas fa-long-arrow-alt-left me-3"></i>Voir les types de publications.</router-link>

                                </div>
                            </div>
                        </div>
                    </section>
                </div>

            </div>
            <div v-else-if="dataReady== 2"> <accessUnAuthorizedAdmin></accessUnAuthorizedAdmin> </div>
            <div v-else-if="dataReady== 3 || dataReady == 4">
                <section class="overflow-hidden">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-9 text-center mx-auto my-0 my-md-5 py-0 py-lg-5 position-relative z-index-9">
                                <!-- SVG shape START -->
                                <figure class="position-absolute top-50 start-50 translate-middle opacity-7 z-index-n9">
                                <svg width="650" height="379" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"  viewBox="0 0 510 297">
                                    <g>
                                    <path class="fill-primary opacity-1" d="M121,147.4c0,6-4.8,10.8-10.8,10.8H47.6c-6,0-10.8-4.8-10.8-10.8v-11.5c0-6,4.8-10.8,10.8-10.8h62.6
                                    c6,0,10.8,4.8,10.8,10.8V147.4z"/>
                                    <path class="fill-primary opacity-1" d="M179.4,90.2c0,6-4.8,10.8-10.8,10.8h-62.6c-6,0-10.8-4.8-10.8-10.8V78.7c0-6,4.8-10.8,10.8-10.8h62.6
                                    c6,0,10.8,4.8,10.8,10.8V90.2z"/>
                                    <path class="fill-primary opacity-1" d="M459.1,26.3c0,6-4.8,10.8-10.8,10.8h-62.6c-6,0-10.8-4.8-10.8-10.8V14.8c0-6,4.8-10.8,10.8-10.8h62.6
                                    c6,0,10.8,4.8,10.8,10.8V26.3z"/>
                                    <path class="fill-primary opacity-1" d="M422.1,66.9c0,6-4.8,10.8-10.8,10.8h-62.6c-6,0-10.8-4.8-10.8-10.8V55.3c0-6,4.8-10.8,10.8-10.8h62.6
                                    c6,0,10.8,4.8,10.8,10.8V66.9z"/>
                                    <path class="fill-primary opacity-1" d="M275.8,282.6c0,5.9-4.8,10.8-10.8,10.8h-62.6c-6,0-10.8-4.8-10.8-10.8v-11.5c0-6,4.8-10.8,10.8-10.8h62.6
                                    c6,0,10.8,4.8,10.8,10.8V282.6z"/>
                                    <path class="fill-primary opacity-1" d="M87.7,42.9c0,5.9-4.8,10.8-10.8,10.8H14.3c-6,0-10.8-4.8-10.8-10.8V31.4c0-6,4.8-10.8,10.8-10.8h62.6
                                    c6,0,10.8,4.8,10.8,10.8V42.9z"/>
                                    <path class="fill-primary opacity-1" d="M505.9,123.4c0,6-4.8,10.8-10.8,10.8h-62.6c-6,0-10.8-4.8-10.8-10.8v-11.5c0-6,4.8-10.8,10.8-10.8h62.6
                                    c6,0,10.8,4.8,10.8,10.8V123.4z"/>
                                    <path class="fill-primary opacity-1" d="M482.5,204.9c0,5.9-4.8,10.8-10.8,10.8h-62.6c-6,0-10.8-4.8-10.8-10.8v-11.5c0-6,4.8-10.8,10.8-10.8h62.6
                                    c5.9,0,10.8,4.8,10.8,10.8V204.9z"/>
                                    <path class="fill-primary opacity-1" d="M408.3,258.8c0,5.9-4.8,10.8-10.8,10.8H335c-6,0-10.8-4.8-10.8-10.8v-11.5c0-6,4.8-10.8,10.8-10.8h62.6
                                    c6,0,10.8,4.8,10.8,10.8V258.8z"/>
                                    <path class="fill-primary opacity-1" d="M147,252.5c0,5.9-4.8,10.8-10.8,10.8H73.6c-6,0-10.8-4.8-10.8-10.8V241c0-5.9,4.8-10.8,10.8-10.8h62.6
                                    c6,0,10.8,4.8,10.8,10.8V252.5z"/>
                                    </g>
                                </svg>
                                </figure>
                                <!-- SVG shape START -->
                                <!-- Content -->
                                <h1 class="display-1 text-primary">Session expiré! </h1>
                                <h5>Votre delai de connexion est expiré, connectez vous pour acceder à cette page.</h5>
                                <span style="cursor: pointer"  @click="authModalClick('LIST')" class="btn btn-danger-soft mt-3"><i class="fas fa-long-arrow-alt-left me-3"></i>Se connecter</span>

                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>


    <div class="modal fade" id="authFormModalpublicationCreateBySlugTypeShow" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">


            <!-- Login Form -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-if="authSectionStepModal == 1">Se connecter</h5>
                    <h5 class="modal-title" v-else-if="authSectionStepModal == 2">Modifier le mot de passe</h5>
                    <h5 class="modal-title" v-else-if="authSectionStepModal == 3">S'inscrire</h5>
                    <button type="button" class="btn-close" @click="authFormModalpublicationCreateBySlugTypeShowClose" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                 <div class="modal-body">
                    <div v-if="authSectionStepModal == 1" style="margin-bottom: -15px">
                        <div v-if="errorLogin">
                            <div class="alert alert-danger"  role="alert">
                                {{ errorLogin }}
                            </div>
                        </div>
                        <form>
                            <!-- Email -->
                            <div class="mb-3" v-if="errorsLogin.username">
                                <label class="form-label" for="exampleInputEmailLogin">Nom d'utilisateur ou email</label>
                                <input type="text" v-model="username" name="username" class="form-control is-invalid" id="exampleInputEmailLogin" placeholder="Votre nom d'utilisateur ou votre email">
                                <div v-for="errorUsername in errorsLogin.username" :key="errorUsername" class="invalid-feedback">
                                    {{ errorUsername }}
                                </div>
                            </div>
                            <div class="mb-3" v-else>
                                <label class="form-label" for="exampleInputEmailLoginInvalid">Nom d'utilisateur ou email</label>
                                <input type="text" v-model="username" name="username" class="form-control" id="exampleInputEmailLoginInvalid" placeholder="Votre nom d'utilisateur ou votre email">
                            </div>
                            <!-- Password -->
                            <div class="mb-3" v-if="errorsLogin.password">
                                <label class="form-label" for="exampleInputPassword1">Mot de passe</label>
                                <div class="input-group">
                                    <input v-model="password" name="password" class="form-control fakepassword is-invalid" type="password" id="psw-input" placeholder="*********">
                                    <span class="input-group-text p-0" @click="showPassword" style="cursor: pointer">
                                        <i v-if="showPsw == false" class="fakepasswordicon far fa-eye cursor-pointer p-2 w-40px"></i>
                                        <i v-else class="fakepasswordicon far fa-eye-slash cursor-pointer p-2 w-40px"></i>
                                    </span>
                                </div>
                                <div class="rounded mt-1" id="psw-strength"></div>
                                <div id="exampleInputPassword1" v-for="errorPassword in errorsLogin.password" :key="errorPassword" class="invalid-feedback">
                                    {{ errorPassword }}
                                </div>
                            </div>
                            <div class="mb-3" v-else>
                                <label class="form-label"  for="exampleInputPassword1">Mot de passe</label>
                                <div class="input-group">
                                    <input v-model="password" name="password" class="form-control fakepassword" type="password" id="psw-input" placeholder="*********">
                                    <span class="input-group-text p-0" @click="showPassword" style="cursor: pointer">
                                        <i v-if="showPsw == false" class="fakepasswordicon far fa-eye cursor-pointer p-2 w-40px"></i>
                                        <i v-else class="fakepasswordicon far fa-eye-slash cursor-pointer p-2 w-40px"></i>
                                    </span>
                                </div>
                                <div class="rounded mt-1" id="psw-strength"></div>
                            </div>
                            <!-- Checkbox -->
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="remember_me" v-model="remember_me" class="form-check-input" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">Se souvenir de moi</label>
                            </div>

                            <button type="button" disabled v-if="loadingLogin" class="btn btn-success mx-auto w-100"> <i  style="color: #fff" class="fa fa-spinner fa-spin fa-1x fa-fw"></i>
                                <span class="sr-only">Loading...</span>  Connexion</button>
                            <button type="submit"  @click.prevent="submitLogin" v-else class="btn btn-success mx-auto w-100">Se connecter</button>

                        </form>

                    </div>
                    <div  v-if="authSectionStepModal == 2" style="margin-bottom: -15px">
                        <h6 v-if="step === 1">Email de vérification</h6>
                        <h6 v-else-if="step === 2">Code de rénitialisation</h6>
                        <h6 v-else-if="step === 3">Nouveau mot de passe</h6>

                        <div v-if="step === 1">
                            <div v-if="errorForgotPasswordFirstMessage" class="mb-3">
                                <div class="alert alert-danger"  role="alert">
                                    {{ errorForgotPasswordFirstMessage }}
                                </div>
                            </div>
                            <form >
                                <div class="mb-3" v-if="errorsForgotPasswordFirstErrors.email">
                                    <input type="email" v-model="email" name="email" class="form-control is-invalid" id="exampleInputEmail1" placeholder="Veuillez entrer votre email">
                                    <div v-for="errorForgotPasswordemail in errorsForgotPasswordFirstErrors.email" :key="errorForgotPasswordemail" class="invalid-feedback">
                                        {{ errorForgotPasswordemail }}
                                    </div>
                                </div>
                                <div class="mb-3" v-else>
                                    <input type="email" v-model="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Veuillez entrer votre email">
                                </div>
                                <!-- Button -->
                                <button type="button" disabled v-if="loadingForgotPasswordFirst" class="btn btn-success  mx-auto w-100"> <i  style="color: #fff" class="fa fa-spinner fa-spin fa-1x fa-fw"></i>
                                    <span class="sr-only">Loading...</span>Vérification</button>
                                <button type="submit" @click.prevent="submitVerifyForgotPasswordEmail" v-else class="btn btn-success  mx-auto w-100">Vérifier</button>
                            </form>
                        </div>
                        <div v-if="step === 2">
                            <div v-if="errorForgotPasswordTwoMessage" class="mb-3">

                                <div class="alert alert-danger"  role="alert">
                                    {{ errorForgotPasswordTwoMessage }}
                                </div>
                            </div>
                            <form>
                                <div class="mb-3" v-if="errorsForgotPasswordTwoErrors.otp">

                                    <input type="text" v-model="otp" name="otp" class="form-control is-invalid" id="exampleInputOtp1" placeholder="Veuillez entrer votre code de vérification">
                                    <div v-for="errorForgotPasswordOtp in errorsForgotPasswordTwoErrors.otp" :key="errorForgotPasswordOtp" class="invalid-feedback">
                                        {{ errorForgotPasswordOtp }}
                                    </div>
                                </div>
                                <div class="mb-3" v-else>

                                    <input type="text"  v-model="otp" name="otp" class="form-control" id="exampleInputOtp1" placeholder="Veuillez entrer votre code de vérification">
                                </div>
                                <div class="mb-3" v-if="loadingResendOTP">
                                    <span for="exampleInputOtp1"><span style="color: blue; cursor:pointer">Renvoie d'un nouveau code en cours ... </span></span>
                                </div>
                                <div class="mb-3" v-if="!loadingResendOTP">
                                    <span for="exampleInputOtp1" @click.prevent="submitResendOtp"><span style="color: blue; cursor:pointer">Renvoyer un nouveau code </span></span>
                                </div>
                                <div class="row" v-if="loadingForgotPasswordTwo">
                                    <div class="col-md-12">
                                        <button type="button" disabled class="btn btn-success  mx-auto w-100"> <i  style="color: #fff" class="fa fa-spinner fa-spin fa-1x fa-fw"></i>
                                            <span class="sr-only">Loading...</span>Validation
                                        </button>
                                    </div>
                                </div>
                                <div class="row" v-else>
                                    <div class="col-md-6">
                                        <span><span class="btn btn-primary  mx-auto w-100" @click="PreviousForgotPasswordStep"><i class="bi bi-arrow-left-short"></i>Retour</span></span>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" @click.prevent="submitVerifyForgotPasswordOtp" class="btn btn-success  mx-auto w-100">Valider</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div v-if="step === 3">
                            <div v-if="errorForgotPasswordThreeMessage" class="mb-3">
                                <div class="alert alert-danger"  role="alert">
                                    {{ errorForgotPasswordThreeMessage }}
                                </div>
                            </div>
                            <form>
                                <div class="mb-3" v-if="errorsForgotPasswordThreeErrors.password">
                                    <label class="form-label" for="exampleInputpassword1">Votre nouveau mot de passe</label>
                                    <div class="input-group">
                                        <input v-model="password" name="password" class="form-control fakepassword is-invalid" type="password" id="psw-input" placeholder="Veuillez entrer votre nouveau mot de passe">
                                        <span class="input-group-text p-0" @click="showPassword" style="cursor: pointer">
                                            <i v-if="showPsw == false" class="fakepasswordicon far fa-eye cursor-pointer p-2 w-40px"></i>
                                            <i v-else class="fakepasswordicon far fa-eye-slash cursor-pointer p-2 w-40px"></i>
                                        </span>
                                    </div>
                                    <div class="rounded mt-1" id="psw-strength"></div>
                                    <div id="exampleInputpassword1" v-for="errorpassword in errorsForgotPasswordThreeErrors.password" :key="errorpassword" class="invalid-feedback">
                                        {{ errorpassword }}
                                    </div>
                                </div>
                                <div class="mb-3" v-else>
                                    <label class="form-label" for="exampleInputpassword1">Votre nouveau mot de passe</label>
                                    <div class="input-group">
                                        <input v-model="password" name="password" class="form-control fakepassword" type="password" id="psw-input" placeholder="Veuillez entrer votre nouveau mot de passe">
                                        <span class="input-group-text p-0" @click="showPassword" style="cursor: pointer">
                                            <i v-if="showPsw == false" class="fakepasswordicon far fa-eye cursor-pointer p-2 w-40px"></i>
                                            <i v-else class="fakepasswordicon far fa-eye-slash cursor-pointer p-2 w-40px"></i>
                                        </span>
                                    </div>
                                    <div class="rounded mt-1" id="psw-strength"></div>
                                </div>

                                <div class="mb-3" v-if="errorsForgotPasswordThreeErrors.password_confirm">
                                    <label class="form-label" for="exampleInputpassword1">Confirmer le mot de passe</label>
                                    <div class="input-group">
                                        <input type="password" v-model="password_confirm" class="form-control fakepassword is-invalid"  name="password_confirm"  id="psw-input_c" placeholder="Veuillez confirmer le mot de passe">
                                        <span class="input-group-text p-0" @click="showPasswordC" style="cursor: pointer">
                                            <i v-if="showPsw == false" class="fakepasswordicon far fa-eye cursor-pointer p-2 w-40px"></i>
                                            <i v-else class="fakepasswordicon far fa-eye-slash cursor-pointer p-2 w-40px"></i>
                                        </span>
                                    </div>
                                    <div class="rounded mt-1" id="psw-strength"></div>
                                    <div id="exampleInputpassword1" v-for="errorpassword_confirm in errorsForgotPasswordThreeErrors.password_confirm" :key="errorpassword_confirm" class="invalid-feedback">
                                        {{ errorpassword_confirm }}
                                    </div>
                                </div>
                                <div class="mb-3" v-else>
                                    <label class="form-label" for="exampleInputpassword1">Confirmer le mot de passe</label>
                                    <div class="input-group">
                                        <input type="password" v-model="password_confirm" class="form-control fakepassword"  name="password_confirm"  id="psw-input_c" placeholder="Veuillez confirmer le mot de passe">
                                        <span class="input-group-text p-0" @click="showPasswordC" style="cursor: pointer">
                                            <i v-if="showPsw == false" class="fakepasswordicon far fa-eye cursor-pointer p-2 w-40px"></i>
                                            <i v-else class="fakepasswordicon far fa-eye-slash cursor-pointer p-2 w-40px"></i>
                                        </span>
                                    </div>
                                    <div class="rounded mt-1" id="psw-strength"></div>
                                </div>
                                <div class="row" v-if="loadingForgotPasswordThree">
                                    <div class="col-md-12">
                                        <button type="button" disabled class="btn btn-success  mx-auto w-100"> <i  style="color: #fff" class="fa fa-spinner fa-spin fa-1x fa-fw"></i>
                                            <span class="sr-only">Loading...</span>Validation
                                        </button>
                                    </div>
                                </div>
                                <div class="row" v-else>
                                    <div class="col-md-6">
                                        <span><span class="btn btn-primary  mx-auto w-100" @click="PreviousHForgotPasswordStep"><i class="bi bi-arrow-left-short"></i>Retour</span></span>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" @click.prevent="submitForgotPasswordNewPass" class="btn btn-success  mx-auto w-100">Modifier</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">

                </div>
                <div v-if="authSectionStepModal == 2" style="margin-top: -15px">
                    <p class="text-center"><span @click="changeAuthSectionStepModalToLogin" style="cursor: pointer; color: blue"> Se connecter</span></p>
                </div>
                <div v-else-if="authSectionStepModal == 1" style="margin-top: -15px">
                    <p class="text-center"><span @click="changeAuthSectionStepModalToForgotPassword" style="cursor: pointer; color: blue"> Mot de passe oublié ?</span></p>
                </div>
            </div>
        </div>
    </div> 
</template>