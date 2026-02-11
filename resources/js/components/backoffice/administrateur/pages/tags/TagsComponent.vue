<script setup>
import { ref, computed, onMounted } from "vue"
import { useStore } from "vuex"
import { Bootstrap5Pagination, TailwindPagination } from "../../../../../libraries/pagination/lib"
import moment from "moment"
import Swal from 'sweetalert2';

const store = useStore()


// composants importés utilisables directement dans <template>
const dataReady = ref(0)
const meRoleName = ref(null)

const tagsListData = ref({})
const tagsListMessage = ref(null)

const tagsStoreData = ref({})
const tagsStoreMessage = ref(null)
const tagsStoreErrors = ref([])

const tagsUpdateData = ref({})
const tagsUpdateMessage = ref(null)
const tagsUpdateErrors = ref([])

const tagsDeleteData = ref({})
const tagsDeleteMessage = ref(null)

const searchByDateListData = ref({});
const searchByDateListMessage = ref(null);

const style = ref("bootstrap5")
const limit = ref(1)
const keepLength = ref(false)
const showDisabled = ref(false)
const size = ref("default")
const align = ref("left")
const search = ref("") 

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

const emptySearchByDate = ref(0);
const searchH = ref("ALL");
const user_id = ref(0)


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

const authFormModalTagsCreateClose = () => {
  errorForgotPasswordFirstMessage.value = false
  errorsForgotPasswordFirstErrors.value = []
  errorForgotPasswordTwoMessage.value = false
  errorsForgotPasswordTwoErrors.value = []
  errorForgotPasswordThreeMessage.value = false
  errorsForgotPasswordThreeErrors.value = []
  errorLogin.value = false
  errorsLogin.value = []

  // si tu utilises jQuery pour tes modals :
  $('#authFormModalTagsCreate').modal('hide')
}


const authModalClick = (action_auth = "LIST", modalName = null, modalSlug = null) => {
  if (
    localStorage.getItem("remember_me") === "true" &&
    localStorage.getItem("username") &&
    localStorage.getItem("password")
  ) {
    username.value = localStorage.getItem("username")
    password.value = localStorage.getItem("password")
    remember_me.value = localStorage.getItem("remember_me") === "true"
  }

  name.value = modalName
  slug.value = modalSlug
  authSectionModal.value = action_auth

  if (authSectionModal.value === "CREATE") {
    loadingCreate.value = false
  } else if (authSectionModal.value === "UPDATE") {
    loadingUpdate.value = false
  } else if (authSectionModal.value === "DELETE") {
    loadingDelete.value = false
  }

  // ouverture modal bootstrap
  $('#authFormModalTagsCreate').modal("show")
}

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
    if (authSectionModal.value === 'LIST') {
        $('#authFormModalTagsCreate').modal('hide');
        getResults();
    } else if (authSectionModal.value === 'CREATE_AUTH') {
        authSectionModal.value = 'CREATE';
       tagsCreate();
    } else if (authSectionModal.value === 'UPDATE_AUTH') {
        authSectionModal.value = 'UPDATE';
       tagsUpdate();
    } else if (authSectionModal.value === 'DELETE_AUTH') {
        authSectionModal.value = 'DELETE';
       tagsDelete();
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


const  getResultsSearchByDate = async() =>{
 

    await store.dispatch('tagsAdmin/searchByDateListDataRequest', {});

    const status = store.getters['tagsAdmin/getInfosSearchByDateListStatus'];
    const message = store.getters['tagsAdmin/getInfosSearchByDateListMessage'];
    const data = store.getters['tagsAdmin/getInfosSearchByDateListData'];

    if (status === 'success') {

        searchByDateListData.value = data
        
        emptySearchByDate.value = 0
     
    } else if (status === 'empty') {

        emptySearchByDate.value = 1
    
    } else if (status === 'failed') {

        emptySearchByDate.value = 1
    
    } else {

        emptySearchByDate.value = 1
    
    }
 

}

const  handleSelection = async(event, page=1) =>{

   searchH.value = event.target.value
     authSectionModal.value = 'LIST';

    await store.dispatch('tagsAdmin/tagsListHDataRequest', {
        page, 
        searchH: searchH.value
    });

    const status = store.getters['tagsAdmin/getInfosTagsListStatus'];
    const message = store.getters['tagsAdmin/getInfosTagsListMessage'];
    const data = store.getters['tagsAdmin/getInfosTagsListData'];

    if (status === 'success') {
    tagsListData.value = data;
    empty.value = 0;
    dataReady.value = 1;
    loading.value = false; 
    getResultsSearchByDate( )
    } else if (status === 'empty') {
    tagsListData.value = data;
    tagsListMessage.value = message;
    empty.value = 1;
    dataReady.value = 1; 
    
    loading.value = false;
    } else if (status === 'failed') {
    dataReady.value = 3;
    loading.value = false;
    } else {
    tagsListMessage.value = message;
    empty.value = 3;
    dataReady.value = 4;
    loading.value = false;
    }

    loading.value = false;

}


const  handleSelectionUsers = async(event, page=1) =>{

   user_id.value = event.target.value
     authSectionModal.value = 'LIST';

    await store.dispatch('tagsAdmin/tagsListHHDataRequest', {
        page, 
        user_id: user_id.value
    });

    const status = store.getters['tagsAdmin/getInfosTagsListStatus'];
    const message = store.getters['tagsAdmin/getInfosTagsListMessage'];
    const data = store.getters['tagsAdmin/getInfosTagsListData'];

    if (status === 'success') {
    tagsListData.value = data;
    empty.value = 0;
    dataReady.value = 1;
    loading.value = false; 
    getResultsSearchByDate( )
    } else if (status === 'empty') {
    tagsListData.value = data;
    tagsListMessage.value = message;
    empty.value = 1;
    dataReady.value = 1; 
    
    loading.value = false;
    } else if (status === 'failed') {
    dataReady.value = 3;
    loading.value = false;
    } else {
    tagsListMessage.value = message;
    empty.value = 3;
    dataReady.value = 4;
    loading.value = false;
    }

    loading.value = false;

}
// Actions principales
const getResults = async (page = 1) => {
    authSectionModal.value = 'LIST';

    await store.dispatch('tagsAdmin/tagsListDataRequest', {
    page,
    search: search.value
    });

    const status = store.getters['tagsAdmin/getInfosTagsListStatus'];
    const message = store.getters['tagsAdmin/getInfosTagsListMessage'];
    const data = store.getters['tagsAdmin/getInfosTagsListData'];

    if (status === 'success') {
    tagsListData.value = data;
    empty.value = 0;
    dataReady.value = 1;
    loading.value = false;
    getResultsSearchByDate()
    } else if (status === 'empty') {
    tagsListData.value = data;
    tagsListMessage.value = message;
    empty.value = 1;
    dataReady.value = 1;
    
    loading.value = false;
    } else if (status === 'failed') {
    dataReady.value = 3;
    
    loading.value = false;
    } else {
    tagsListMessage.value = message;
    empty.value = 3;
    dataReady.value = 4;
    
    loading.value = false;
    }

    loading.value = false;
};

const tagsCreate = async () => {
    loadingCreate.value = true;
    authSectionModal.value = 'CREATE';

    await store.dispatch('tagsAdmin/tagsStoreDataRequest', { name: name.value });

    const status = store.getters['tagsAdmin/getInfosTagsStoreStatus'];
    const message = store.getters['tagsAdmin/getInfosTagsStoreMessage'];
    const data = store.getters['tagsAdmin/getInfosTagsStoreData'];
    const errors = store.getters['tagsAdmin/getInfosTagsStoreErrors'];

    if (status === 'success') {
    tagsStoreData.value = data;
    showToast('success',message);
    loadingCreate.value = false;
    name.value = null;
    slug.value = null;
    $('#authFormModalTagsCreate').modal('hide');
    getResults();
    } else if (status === 'empty') {
    tagsStoreMessage.value = message;
    tagsStoreErrors.value = errors;
    loadingCreate.value = false;
    } else if (status === 'error') {
    tagsStoreMessage.value = message;
    tagsStoreErrors.value = [];
    loadingCreate.value = false;
    }
};

const tagsUpdate = async () => {
    loadingUpdate.value = true;
    authSectionModal.value = 'UPDATE';

    await store.dispatch('tagsAdmin/tagsUpdateDataRequest', {
    name: name.value,
    slug: slug.value
    });

    const status = store.getters['tagsAdmin/getInfosTagsUpdateStatus'];
    const message = store.getters['tagsAdmin/getInfosTagsUpdateMessage'];
    const data = store.getters['tagsAdmin/getInfosTagsUpdateData'];
    const errors = store.getters['tagsAdmin/getInfosTagsUpdateErrors'];

    if (status === 'success') {
    tagsUpdateData.value = data;
    showToast('success',message);
    loadingUpdate.value = false;
    name.value = null;
    slug.value = null;
    $('#authFormModalTagsCreate').modal('hide');
    getResults();
    } else if (status === 'empty') {
    tagsUpdateMessage.value = message;
    tagsUpdateErrors.value = errors;
    loadingUpdate.value = false;
    } else if (status === 'error') {
    tagsUpdateMessage.value = message;
    tagsUpdateErrors.value = [];
    loadingUpdate.value = false;
    }
};

const tagsDelete = async () => {
    loadingDelete.value = true;
    authSectionModal.value = 'DELETE';

    await store.dispatch('tagsAdmin/tagsDeleteDataRequest', { slug: slug.value });

    const status = store.getters['tagsAdmin/getInfosTagsDeleteStatus'];
    const message = store.getters['tagsAdmin/getInfosTagsDeleteMessage'];
    const data = store.getters['tagsAdmin/getInfosTagsDeleteData'];
    const errors = store.getters['tagsAdmin/getInfosTagsDeleteErrors'];

    if (status === 'success') {
        tagsDeleteData.value = data;
        showToast('success',message);
        loadingDelete.value = false;
        name.value = null;
        slug.value = null;
        $('#authFormModalTagsCreate').modal('hide');
        getResults();
    } else if (status === 'empty') {
        tagsDeleteMessage.value = message;
        tagsDeleteErrors.value = errors;
        loadingDelete.value = false;
    } else if (status === 'error') {
        tagsDeleteMessage.value = message;
        loadingDelete.value = false;
    }
};

// --- CREATE ---
const create = async () => {
  loadingCreate.value = true;
  authSectionModal.value = 'CREATE';

  const accessToken = localStorage.getItem('access_token');
  const nbRsp = localStorage.getItem('nbRsp');

  if (accessToken && nbRsp) {
    await store.dispatch('roleSecurity/getMeRole');

    const roleStatus = store.getters['roleSecurity/getRoleStatus'];
    const meRole = store.getters['roleSecurity/getMeRoleName'];

    if (roleStatus === 'success') {
      meRoleName.value = meRole;

      if (meRoleName.value === nbRsp && nbRsp === '&nbtsd!?') {
        await tagsCreate();
      } else {
        loadingCreate.value = true;
      }
    } else if (roleStatus === 'failed') {
      authSectionModal.value = 'CREATE_AUTH';
    }
  } else {
    authSectionModal.value = 'CREATE_AUTH';
  }
};

// --- UPDATE ---
const update = async () => {
  loadingUpdate.value = true;
  authSectionModal.value = 'UPDATE';

  const accessToken = localStorage.getItem('access_token');
  const nbRsp = localStorage.getItem('nbRsp');

  if (accessToken && nbRsp) {
    await store.dispatch('roleSecurity/getMeRole');

    const roleStatus = store.getters['roleSecurity/getRoleStatus'];
    const meRole = store.getters['roleSecurity/getMeRoleName'];

    if (roleStatus === 'success') {
      meRoleName.value = meRole;

      if (meRoleName.value === nbRsp && nbRsp === '&nbtsd!?') {
        await tagsUpdate();
      } else {
        loadingUpdate.value = true;
      }
    } else if (roleStatus === 'failed') {
      authSectionModal.value = 'UPDATE_AUTH';
    }
  } else {
    authSectionModal.value = 'UPDATE_AUTH';
  }
};

// --- DESTROY ---
const destroy = async () => {
  loadingDelete.value = true;
  authSectionModal.value = 'DELETE';

  const accessToken = localStorage.getItem('access_token');
  const nbRsp = localStorage.getItem('nbRsp');

  if (accessToken && nbRsp) {
    await store.dispatch('roleSecurity/getMeRole');

    const roleStatus = store.getters['roleSecurity/getRoleStatus'];
    const meRole = store.getters['roleSecurity/getMeRoleName'];

    if (roleStatus === 'success') {
      meRoleName.value = meRole;

      if (meRoleName.value === nbRsp && nbRsp === '&nbtsd!?') {
        await tagsDelete();
      } else {
        loadingDelete.value = true;
      }
    } else if (roleStatus === 'failed') {
      authSectionModal.value = 'DELETE_AUTH';
    }
  } else {
    authSectionModal.value = 'DELETE_AUTH';
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

                <!-- Author list title START -->
                <div class="row g-4 pb-4">
                    <div class="col-12">
                        <!-- Title -->
                        <div class="d-sm-flex justify-content-sm-between align-items-center">
                            <h1 class="mb-2 mb-sm-0 h2">Mes Tags <span class="badge bg-primary bg-opacity-10 text-primary"> {{ tagsListData.tagsCount  }} </span></h1>
                            <span style="cursor: pointer" @click="authModalClick('CREATE')" class="btn btn-sm btn-primary mb-0"><i class="fas fa-plus me-2"></i>Ajouter un tag</span>
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-12">
                        <!-- Card START -->
                        <div class="card border">
                            <!-- Card header START -->
                            <div class="card-header border-bottom p-3">
                                <!-- Search and select START -->
                                <div class="row g-3 align-items-center justify-content-between">
                                    <!-- Search bar -->
                                    <div class="col-md-10 col-sm-10 col-xs-10">
                                         <div class="row" v-if="emptySearchByDate == 0">
                                            <div class="col-md-6">
                                                <form class="rounded position-relative" method="GET" @submit.prevent="getResults">
                                                    <input class="form-control bg-transparent" v-model="search" @input="show" name="search" type="search" placeholder="Rechercher un tag ..." aria-label="Search">
                                                    <button class="btn bg-transparent border-0 px-2 py-0 position-absolute top-50 end-0 translate-middle-y" type="submit"><i class="fas fa-search fs-6 "></i></button>
                                                </form>
                                            </div>
                                            <div class="col-md-3">
                                                <form class="rounded position-relative" method="GET" >
                                                    <select class="form-select" @change="handleSelection($event)" v-model="searchH" name="searchH" id="searchH">
                                                        <option value="ALL" >Tous les tags</option>
                                                        <option v-for="(option, index) in searchByDateListData.infosMonthYearTags" :key="index" :value="option.date_name">
                                                        {{ option.date_name }}
                                                        </option>
                                                    </select>
                                                </form>
                                            </div>
                                            <div class="col-md-3">
                                                <form class="rounded position-relative" method="GET" >
                                                    <select class="form-select" @change="handleSelectionUsers($event)" v-model="user_id" name="user_id" id="user_id">
                                                        <option value="0" >Tous les utilisateurs</option>
                                                        <option v-for="(option, index) in searchByDateListData.users" :key="index" :value="option.id">
                                                        {{ option.nom_complet }}
                                                        </option>
                                                    </select>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="row" v-else>
                                            <div class="col-md-12">
                                                <form class="rounded position-relative" method="GET" @submit.prevent="getResults">
                                                    <input class="form-control bg-transparent" v-model="search" @input="show" name="search" type="search" placeholder="Rechercher un tag ..." aria-label="Search">
                                                    <button class="btn bg-transparent border-0 px-2 py-0 position-absolute top-50 end-0 translate-middle-y" type="submit"><i class="fas fa-search fs-6 "></i></button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Tab buttons -->
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <!-- Tabs START -->
                                        <ul class="list-inline mb-0 nav nav-pills nav-pill-dark-soft border-0 justify-content-end" id="pills-tab" role="tablist">

                                             <!-- Grid tab -->
                                            <li class="nav-item">
                                                <a href="#nav-grid-tab" class="nav-link mb-0 me-2 active" data-bs-toggle="tab">
                                                    <i class="fas fa-fw fa-th-large"></i>
                                                </a>
                                            </li>

                                            <!-- List tab -->
                                            <li class="nav-item">
                                                <a href="#nav-list-tab" class="nav-link mb-0" data-bs-toggle="tab">
                                                    <i class="fas fa-fw fa-list-ul"></i>
                                                </a>
                                            </li>

                                        </ul>
                                        <!-- Tabs end -->
                                    </div>
                                </div>
                                <!-- Search and select END -->
                            </div>
                            <!-- Card header END -->
                            <div class="card-body p-3 pb-0" v-if="empty == 0">
                                <div class="tab-content py-0 my-0">

                                    <!-- Tabs content item START -->
                                    <div class="tab-pane fade" id="nav-list-tab">
                                        <!-- Table START -->
                                        <div class="table-responsive border-0">
                                            <table class="table align-middle p-4 mb-0 table-hover">
                                                <!-- Table head -->
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th scope="col" class="border-0 rounded-start">Tags</th>
                                                        <th scope="col" class="border-0">Publications</th>
                                                        <th scope="col" class="border-0">Ajouté le</th>
                                                        <th scope="col" class="border-0">Actions</th>
                                                    </tr>
                                                </thead>

                                                <!-- Table body START -->
                                                <tbody class="border-top-0">
                                                    <!-- Table row -->
                                                    <tr v-for="result in tagsListData.tags.data" :key="result.id">

                                                        <!-- Table data -->
                                                        <th> <router-link to="#"> {{ result.name }} </router-link> </th>
                                                        <!-- Table data -->
                                                        <td>
                                                            <span v-if="result.count_publications === 0" class="badge bg-danger bg-opacity-10 text-danger mb-2">Aucune publication</span>
                                                            <span v-else-if="result.count_publications == 1" class="badge bg-primary bg-opacity-10 text-primary mb-2">1 publication</span>
                                                            <span v-else class="badge bg-primary bg-opacity-10 text-primary mb-2"> {{ result.count_publications }} publications</span>
                                                        </td>
                                                        <!-- Table data -->
                                                        <td>{{ moment(result.date_publish).format("DD/MM/YYYY") }}</td>
                                                        <!-- Table data -->

                                                        <!-- Table data -->
                                                        <td>
                                                            <div class="d-flex gap-2">
                                                                <router-link to="#"> 
                                                                    
                                                                    <span style="cursor: pointer; font-size: 30px"  class="btn btn-success btn-round mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Message" aria-label="Message">
                                                                        <i class="bi bi-eye"></i>
                                                                    </span>
                                                                </router-link>
                                                                <span style="cursor: pointer" @click="authModalClick('UPDATE',result.name, result.slug )" class="btn btn-primary btn-round mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Message" aria-label="Message">
                                                                <i class="bi bi-pencil-fill"></i>
                                                                </span>
                                                                <span @click="authModalClick('DELETE',result.name, result.slug )" class="btn btn-danger btn-round mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Block" aria-label="Block">
                                                                    <i class="fas fa-trash"></i>
                                                                </span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                     <!-- Tabs content item START -->
                                    <div class="tab-pane fade show active" id="nav-grid-tab">
                                        <div class="row g-4">
                                            <div class="col-md-6 col-xl-4" v-for="result in tagsListData.tags.data" :key="result.id">
                                                <!-- tags item START -->
                                                <div class="card border h-100">
                                                    <!-- Card header -->
                                                    <div class="card-header border-bottom p-3">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="mb-0 ms-3"> {{ result.name }} </h5>
                                                        </div>
                                                    </div>

                                                    <!-- Card body START -->
                                                    <div class="card-body p-3">
                                                        <p> ajouté le {{ moment(result.date_publish).format("DD/MM/YYYY") }} </p>

                                                        <!-- Followers and Post -->
                                                        <div class="d-sm-flex justify-content-sm-between mt-3">
                                                            <div class="d-flex text-start align-items-center mt-3">
                                                                <div class="icon-md bg-success text-light rounded-circle flex-shrink-0">
                                                                    <i class="bi bi-file-earmark-text-fill fa-fw"></i>
                                                                </div>
                                                                <div class="ms-2" v-if="result.count_publications === 0">
                                                                    <h5 class="mb-0">Aucune</h5>
                                                                    <h6 class="mb-0 fw-light">publication</h6>
                                                                </div>

                                                                <div class="ms-2" v-else-if="result.count_publications === 1">
                                                                    <h5 class="mb-0">1</h5>
                                                                    <h6 class="mb-0 fw-light">publication</h6>
                                                                </div>

                                                                <div class="ms-2" v-else>
                                                                    <h5 class="mb-0">{{ result.count_publications }}</h5>
                                                                    <h6 class="mb-0 fw-light">publications</h6>
                                                                </div>
                                                            </div>

                                                            <!-- Total post -->
                                                            <div class="d-flex text-start align-items-center mt-3">
                                                                <div style="cursor: pointer" @click="authModalClick('UPDATE',result.name, result.slug )" class="icon-md bg-primary text-white-force rounded-circle flex-shrink-0">
                                                                    <i class="bi bi-pencil-fill"></i>
                                                                </div>
                                                                &nbsp;
                                                                <div style="cursor: pointer" @click="authModalClick('DELETE',result.name, result.slug )" class="icon-md bg-danger text-white-force rounded-circle flex-shrink-0">
                                                                    <i class="bi bi-trash"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Card body END -->

                                                    <!-- Card footer -->
                                                    <div class="card-footer border-top text-center p-3">
                                                        <router-link to="#" class="btn btn-primary-soft w-100 mb-0">Ajouter une publication</router-link>
                                                    </div>
                                                </div>
                                                <!-- tags item END -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-3 pb-0" v-else-if="empty == 1">
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                      <div style="position: relative; height: 250px;">
                                          <img :src="`/assets/images/empty.png`" style="width: 100px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);" alt="empty">
                                      </div>
                                      <h5 style="text-align: center; margin-top: -50px"> {{ tagsListMessage  }} </h5>
                                    </div>
                                    <div class="col-md-3"></div>
                                </div>
                            </div>

                            <div class="card-footer p-3" v-if="empty == 0">
                                <!-- Pagination START -->
                                <div class="d-sm-flex justify-content-sm-between align-items-sm-center">

                                    <Bootstrap5Pagination
                                        class="mb-0"
                                        :data="tagsListData.tags"
                                        :limit="limit"
                                        :keep-length="keepLength"
                                        :show-disabled="showDisabled"
                                        :size="size"
                                        :align="align"
                                        @pagination-change-page="getResults"
                                    />

                                </div>
                            </div>
                        </div>
                    </div>
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
    <!-- Modal Form -->
    <div class="modal fade" id="authFormModalTagsCreate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" v-if="authSectionModal == 'CREATE'">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un tag</h5>
                    <button type="button" class="btn-close" @click="authFormModalTagsCreateClose" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div v-if="tagsStoreMessage">
                            <div class="alert alert-danger"  role="alert">
                                {{ tagsStoreMessage }}
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3" v-if="tagsStoreErrors.name">
                            <label class="form-label" for="exampleInputEmailtagsCreate">Tag</label>
                            <input type="text" v-model="name" name="name" class="form-control is-invalid" id="exampleInputEmailtagsCreate" placeholder="Entrez le tag">
                            <div v-for="errorname in tagsStoreErrors.name" :key="errorname" class="invalid-feedback">
                                {{ errorname }}
                            </div>
                        </div>
                        <div class="mb-3" v-else>
                            <label class="form-label" for="exampleInputEmailLoginInvalid">Tag</label>
                            <input type="text" v-model="name" name="name" class="form-control" id="exampleInputEmailtagsCreate" placeholder="Entrez le tag">
                        </div>
                    </div>
                    <div class="modal-footer" >
                        <div  v-if="!loadingCreate" style="margin-top: -5px; margin-bottom: -10px">
                            <button type="submit" @click.prevent="create" class="btn btn-primary">Ajouter</button>
                            &nbsp;
                            <span><span class="btn btn-danger" @click="authFormModalTagsCreateClose">Fermez</span></span>
                        </div>
                        <div style="margin-top: -5px; margin-bottom: -10px" v-else>
                            <button type="button" disabled class="btn btn-primary   mx-auto w-100">
                                <i  style="color: #fff" class="fa fa-spinner fa-spin fa-1x fa-fw"></i>
                                <span class="sr-only">Loading...</span> Ajout en cours ...
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-content" v-if="authSectionModal == 'UPDATE'">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier un tag</h5>
                    <button type="button" class="btn-close" @click="authFormModalTagsCreateClose" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div v-if="tagsUpdateMessage">
                            <div class="alert alert-danger"  role="alert">
                                {{ tagsUpdateMessage }}
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3" v-if="tagsUpdateErrors.name">
                            <label class="form-label" for="exampleInputEmailtagsCreate">Tag</label>
                            <input type="text" v-model="name" name="name" class="form-control is-invalid" id="exampleInputEmailtagsCreate" placeholder="Entrez le tag">
                            <div v-for="errorname in tagsUpdateErrors.name" :key="errorname" class="invalid-feedback">
                                {{ errorname }}
                            </div>
                        </div>
                        <div class="mb-3" v-else>
                            <label class="form-label" for="exampleInputEmailLoginInvalid">Tag</label>
                            <input type="text" v-model="name" name="name" class="form-control" id="exampleInputEmailtagsCreate" placeholder="Entrez le tag">
                        </div>
                    </div>
                    <div class="modal-footer" >
                        <div  v-if="!loadingUpdate" style="margin-top: -5px; margin-bottom: -10px">
                            <button type="submit" @click.prevent="update" class="btn btn-primary">Modifier</button>
                            &nbsp;
                            <span><span class="btn btn-danger" @click="authFormModalTagsCreateClose">Fermez</span></span>
                        </div>
                        <div style="margin-top: -5px; margin-bottom: -10px" v-else>
                            <button type="button" disabled class="btn btn-primary   mx-auto w-100">
                                <i  style="color: #fff" class="fa fa-spinner fa-spin fa-1x fa-fw"></i>
                                <span class="sr-only">Loading...</span> Modification en cours ...
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-content" v-if="authSectionModal == 'DELETE'">

                <div class="modal-body">
                    <div class="d-flex justify-content-center">
                        <i class="bi bi-info-circle fa-fw me-2" style="font-size: 80px; color: blue"></i>
                    </div>
                    <div class="d-flex justify-content-center">
                        <h6>ATTENTION</h6>
                    </div>
                    <div class="d-flex justify-content-center">
                        <p class="text-center">Toutes données relatives à ce Tag seront aussi supprimés.</p>
                    </div>
                    <div class="d-flex justify-content-center" v-if="!loadingDelete" style="margin-top: -5px; margin-bottom: -10px">
                        <span style="cursor: pointer" @click.prevent="destroy" class="btn btn-danger">Supprimez</span>
                        &nbsp;
                        <span><span class="btn btn-success" @click="authFormModalTagsCreateClose">Annuler</span></span>
                    </div>
                    <div class="d-flex justify-content-center" style="margin-top: -5px; margin-bottom: -10px" v-else>
                        <button type="button" disabled class="btn btn-primary   mx-auto w-100">
                            <i  style="color: #fff" class="fa fa-spinner fa-spin fa-1x fa-fw"></i>
                            <span class="sr-only">Loading...</span> Suppression en cours ...
                        </button>
                    </div>
                </div>

            </div>
                <!-- Login Form -->
            <div class="modal-content" v-else-if="authSectionModal == 'DELETE_AUTH' || authSectionModal == 'UPDATE_AUTH' || authSectionModal == 'CREATE_AUTH' || authSectionModal == 'LIST'">
                <div class="modal-header">
                    <h5 class="modal-title" v-if="authSectionStepModal == 1">Se connecter</h5>
                    <h5 class="modal-title" v-else-if="authSectionStepModal == 2">Modifier le mot de passe</h5>
                    <h5 class="modal-title" v-else-if="authSectionStepModal == 3">S'inscrire</h5>
                    <button type="button" class="btn-close" @click="authFormModalTagsCreateClose" data-bs-dismiss="modal" aria-label="Close"></button>
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