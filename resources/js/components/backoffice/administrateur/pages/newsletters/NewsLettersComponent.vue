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
const status = ref(null)

const newsLetterListData = ref({})
const newsLetterListMessage = ref(null)

const newsLetterSearchData = ref({})
const newsLetterSearchMessage = ref(null)

const newsLetterStoreData = ref({})
const newsLetterStoreMessage = ref(null)
const newsLetterStoreErrors = ref([])

const newsLetterUpdateData = ref({})
const newsLetterUpdateMessage = ref(null)
const newsLetterUpdateErrors = ref([])

const newsLetterDeleteData = ref({})
const newsLetterDeleteMessage = ref(null)

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
const loadingBlock = ref(false)
const loadingActivate = ref(false)

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

const authFormModalNewsLetterCreateClose = () => {
  errorForgotPasswordFirstMessage.value = false
  errorsForgotPasswordFirstErrors.value = []
  errorForgotPasswordTwoMessage.value = false
  errorsForgotPasswordTwoErrors.value = []
  errorForgotPasswordThreeMessage.value = false
  errorsForgotPasswordThreeErrors.value = []
  errorLogin.value = false
  errorsLogin.value = []

  // si tu utilises jQuery pour tes modals :
  $('#authFormModalNewsLetterCreate').modal('hide')
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
  $('#authFormModalNewsLetterCreate').modal("show")
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
        $('#authFormModalNewsLetterCreate').modal('hide');
        getResults();
    } else if (authSectionModal.value === 'CREATE_AUTH') {
        authSectionModal.value = 'CREATE';
       newsLetterCreate();
    } else if (authSectionModal.value === 'UPDATE_AUTH') {
        authSectionModal.value = 'UPDATE';
       newsLetterUpdate();
    } else if (authSectionModal.value === 'DELETE_AUTH') {
        authSectionModal.value = 'DELETE';
       newsLetterDelete();
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

// Actions principales
const getResults = async (page = 1) => {
    authSectionModal.value = 'LIST';

    await store.dispatch('newsLetterAdmin/newsLetterListDataRequest', {
    page,
    search: search.value
    });

    const status = store.getters['newsLetterAdmin/getInfosNewsLetterListStatus'];
    const message = store.getters['newsLetterAdmin/getInfosNewsLetterListMessage'];
    const data = store.getters['newsLetterAdmin/getInfosNewsLetterListData'];

    if (status === 'success') {
    newsLetterListData.value = data;
    empty.value = 0;
    dataReady.value = 1;
    loading.value = false;
    } else if (status === 'empty') {
    newsLetterListData.value = data;
    newsLetterListMessage.value = message;
    empty.value = 1;
    dataReady.value = 1;
    
    loading.value = false;
    } else if (status === 'failed') {
    dataReady.value = 3;
    
    loading.value = false;
    } else {
    newsLetterListMessage.value = message;
    empty.value = 3;
    dataReady.value = 4;
    
    loading.value = false;
    }

    loading.value = false;
};


// Actions principales
const handleSelectionSearchByStatus = async (event,page = 1) => {

    status.value = event.target.value

    authSectionModal.value = 'LIST';

    await store.dispatch('newsLetterAdmin/newsLetterSearchDataRequest', {
    page,
    status: status.value
    });

    const status = store.getters['newsLetterAdmin/getInfosNewsLetterSearchStatus'];
    const message = store.getters['newsLetterAdmin/getInfosNewsLetterSearchMessage'];
    const data = store.getters['newsLetterAdmin/getInfosNewsLetterSearchData'];

    if (status === 'success') {
    newsLetterListData.value = data;
    empty.value = 0;
    dataReady.value = 1;
    loading.value = false;
    } else if (status === 'empty') {
    newsLetterListData.value = data;
    newsLetterListMessage.value = message;
    empty.value = 1;
    dataReady.value = 1;
    
    loading.value = false;
    } else if (status === 'failed') {
    dataReady.value = 3;
    
    loading.value = false;
    } else {
    newsLetterListMessage.value = message;
    empty.value = 3;
    dataReady.value = 4;
    
    loading.value = false;
    }

    loading.value = false;
};

const newsLetterCreate = async () => {
    loadingCreate.value = true;
    authSectionModal.value = 'CREATE';

    await store.dispatch('newsLetterAdmin/newsLetterStoreDataRequest', { name: name.value });

    const status = store.getters['newsLetterAdmin/getInfosNewsLetterStoreStatus'];
    const message = store.getters['newsLetterAdmin/getInfosNewsLetterStoreMessage'];
    const data = store.getters['newsLetterAdmin/getInfosNewsLetterStoreData'];
    const errors = store.getters['newsLetterAdmin/getInfosNewsLetterStoreErrors'];

    if (status === 'success') {
    newsLetterStoreData.value = data;
    showToast('success',message);
    loadingCreate.value = false;
    name.value = null;
    slug.value = null;
    $('#authFormModalNewsLetterCreate').modal('hide');
    getResults();
    } else if (status === 'empty') {
    newsLetterStoreMessage.value = message;
    newsLetterStoreErrors.value = errors;
    loadingCreate.value = false;
    } else if (status === 'error') {
    newsLetterStoreMessage.value = message;
    newsLetterStoreErrors.value = [];
    loadingCreate.value = false;
    }
};

const newsLetterUpdate = async () => {
    loadingUpdate.value = true;
    authSectionModal.value = 'UPDATE';

    await store.dispatch('newsLetterAdmin/newsLetterUpdateDataRequest', {
    name: name.value,
    slug: slug.value
    });

    const status = store.getters['newsLetterAdmin/getInfosNewsLetterUpdateStatus'];
    const message = store.getters['newsLetterAdmin/getInfosNewsLetterUpdateMessage'];
    const data = store.getters['newsLetterAdmin/getInfosNewsLetterUpdateData'];
    const errors = store.getters['newsLetterAdmin/getInfosNewsLetterUpdateErrors'];

    if (status === 'success') {
    newsLetterUpdateData.value = data;
    showToast('success',message);
    loadingUpdate.value = false;
    name.value = null;
    slug.value = null;
    $('#authFormModalNewsLetterCreate').modal('hide');
    getResults();
    } else if (status === 'empty') {
    newsLetterUpdateMessage.value = message;
    newsLetterUpdateErrors.value = errors;
    loadingUpdate.value = false;
    } else if (status === 'error') {
    newsLetterUpdateMessage.value = message;
    newsLetterUpdateErrors.value = [];
    loadingUpdate.value = false;
    }
};

const newsLetterDelete = async () => {
    loadingDelete.value = true;
    authSectionModal.value = 'DELETE';

    await store.dispatch('newsLetterAdmin/newsLetterDeleteDataRequest', { slug: slug.value });

    const status = store.getters['newsLetterAdmin/getInfosNewsLetterDeleteStatus'];
    const message = store.getters['newsLetterAdmin/getInfosNewsLetterDeleteMessage'];
    const data = store.getters['newsLetterAdmin/getInfosNewsLetterDeleteData'];
    const errors = store.getters['newsLetterAdmin/getInfosNewsLetterDeleteErrors'];

    if (status === 'success') {
        newsLetterDeleteData.value = data;
        showToast('success',message);
        loadingDelete.value = false;
        name.value = null;
        slug.value = null;
        $('#authFormModalNewsLetterCreate').modal('hide');
        getResults();
    } else if (status === 'empty') {
        newsLetterDeleteMessage.value = message;
        newsLetterDeleteErrors.value = errors;
        loadingDelete.value = false;
    } else if (status === 'error') {
        newsLetterDeleteMessage.value = message;
        loadingDelete.value = false;
    }
};


const newsLetterBlock = async () => {
    loadingBlock.value = true;
    authSectionModal.value = 'BLOCK';

    await store.dispatch('newsLetterAdmin/newsLetterBlockDataRequest', { slug: slug.value });

    const status = store.getters['newsLetterAdmin/getInfosNewsLetterBlockStatus'];
    const message = store.getters['newsLetterAdmin/getInfosNewsLetterBlockMessage'];
    const data = store.getters['newsLetterAdmin/getInfosNewsLetterBlockData'];
    const errors = store.getters['newsLetterAdmin/getInfosNewsLetterBlockErrors'];

    if (status === 'success') {
        newsLetterBlockData.value = data;
        showToast('success',message);
        loadingBlock.value = false;
        name.value = null;
        slug.value = null;
        $('#authFormModalNewsLetterCreate').modal('hide');
        getResults();
    } else if (status === 'empty') {
        newsLetterBlockMessage.value = message;
        newsLetterBlockErrors.value = errors;
        loadingBlock.value = false;
    } else if (status === 'error') {
        newsLetterBlockMessage.value = message;
        loadingBlock.value = false;
    }
};



const newsLetterActivate = async () => {
    loadingActivate.value = true;
    authSectionModal.value = 'ACTIVATE';

    await store.dispatch('newsLetterAdmin/newsLetterActivateDataRequest', { slug: slug.value });

    const status = store.getters['newsLetterAdmin/getInfosNewsLetterActivateStatus'];
    const message = store.getters['newsLetterAdmin/getInfosNewsLetterActivateMessage'];
    const data = store.getters['newsLetterAdmin/getInfosNewsLetterActivateData'];
    const errors = store.getters['newsLetterAdmin/getInfosNewsLetterActivateErrors'];

    if (status === 'success') {
        newsLetterActivateData.value = data;
        showToast('success',message);
        loadingActivate.value = false;
        name.value = null;
        slug.value = null;
        $('#authFormModalNewsLetterCreate').modal('hide');
        getResults();
    } else if (status === 'empty') {
        newsLetterActivateMessage.value = message;
        newsLetterActivateErrors.value = errors;
        loadingActivate.value = false;
    } else if (status === 'error') {
        newsLetterActivateMessage.value = message;
        loadingActivate.value = false;
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
        await newsLetterCreate();
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
        await newsLetterUpdate();
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
        await newsLetterDelete();
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


// --- block ---
const block = async () => {
  loadingBlock.value = true;
  authSectionModal.value = 'BLOCK';

  const accessToken = localStorage.getItem('access_token');
  const nbRsp = localStorage.getItem('nbRsp');

  if (accessToken && nbRsp) {
    await store.dispatch('roleSecurity/getMeRole');

    const roleStatus = store.getters['roleSecurity/getRoleStatus'];
    const meRole = store.getters['roleSecurity/getMeRoleName'];

    if (roleStatus === 'success') {
      meRoleName.value = meRole;

      if (meRoleName.value === nbRsp && nbRsp === '&nbtsd!?') {
        await newsLetterBlock();
      } else {
        loadingBlock.value = true;
      }
    } else if (roleStatus === 'failed') {
      authSectionModal.value = 'BLOCK_AUTH';
    }
  } else {
    authSectionModal.value = 'BLOCK_AUTH';
  }
};


// --- block ---
const activate = async () => {
  loadingActivate.value = true;
  authSectionModal.value = 'ACTIVATE';

  const accessToken = localStorage.getItem('access_token');
  const nbRsp = localStorage.getItem('nbRsp');

  if (accessToken && nbRsp) {
    await store.dispatch('roleSecurity/getMeRole');

    const roleStatus = store.getters['roleSecurity/getRoleStatus'];
    const meRole = store.getters['roleSecurity/getMeRoleName'];

    if (roleStatus === 'success') {
      meRoleName.value = meRole;

      if (meRoleName.value === nbRsp && nbRsp === '&nbtsd!?') {
        await newsLetterActivate();
      } else {
        loadingActivate.value = true;
      }
    } else if (roleStatus === 'failed') {
      authSectionModal.value = 'ACTIVATE_AUTH';
    }
  } else {
    authSectionModal.value = 'ACTIVATE_AUTH';
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

        <!-- Author list title START -->
            <div class="row g-4 pb-4">
                <div class="col-12">
                    <!-- Title -->
                    <div class="d-sm-flex justify-content-sm-between align-items-center">
                        <h1 class="mb-2 mb-sm-0 h2">Tableau de bord</h1>			
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
   
</template> 