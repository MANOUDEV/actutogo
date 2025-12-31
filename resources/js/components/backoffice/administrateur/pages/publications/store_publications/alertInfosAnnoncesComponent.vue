<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue'; 
import { useStore } from "vuex"
import Swal from 'sweetalert2';

const store = useStore()

const title = ref('');
const status = ref(1);
const props = defineProps({
  publicationCreate: {
    type: Object,
    default: null
  }
})

const authSectionModal = ref("CREATE")

const authSectionStepModal = ref(1)

// composants importés utilisables directement dans <template>
const dataReady = ref(0)
const meRoleName = ref(null)

const publicationStoreInfoAlertAnnonceData = ref({})
const publicationStoreInfoAlertAnnonceMessage = ref(null)
const publicationStoreInfoAlertAnnonceErrors = ref([])

const username = ref(null)
const email = ref(null)
const password = ref(null)
const password_confirm = ref(null)

const loadingLogin = ref(false)
const errorLogin = ref(false)
const errorsLogin = ref([])

const loadingConnect = ref(false)
const remember_me = ref(false)
 
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

// Dates et heures
const startDate = ref('');
const startTime = ref('');
const endDate = ref('');
const endTime = ref('');

// Activation/désactivation des champs
const isStartDisabled = ref(true);
const isEndDisabled = ref(false);

const sites = [
  { name: 'News 228', logo: '/assets/images/logo-news228.png', description: 'Partagez cette publication sur News228.' },
  { name: 'Togoactu', logo: '/assets/images/logo-togoactu.png', description: 'Partagez cette publication sur Togoactu.' }
]
const selectedSites = ref([]);

// Intervalle pour live clock
let intervalId = null;

// Mettre à jour la date et l’heure de début
const updateStartNow = () => {
  const now = new Date();
  startDate.value = now.toISOString().slice(0, 10);
  startTime.value = now.toTimeString().slice(0, 5);
};

// Gestion du changement de statut
const handleStatusChange = (newStatus) => {
  clearInterval(intervalId);

  if (newStatus == 1) {
    updateStartNow();
    intervalId = setInterval(updateStartNow, 1000); // mise à jour chaque seconde
    isStartDisabled.value = true;
    isEndDisabled.value = false;
    endDate.value = '';
    endTime.value = '';
  } else if (newStatus == 2) {
    isStartDisabled.value = false;
    isEndDisabled.value = false;
    startDate.value = '';
    startTime.value = '';
    endDate.value = '';
    endTime.value = '';
  } else {
    isStartDisabled.value = true;
    isEndDisabled.value = true;
    startDate.value = '';
    startTime.value = '';
    endDate.value = '';
    endTime.value = '';
  }
};

// Initialisation au montage
onMounted(() => {
  handleStatusChange(status.value);
  
});

// Nettoyage
onUnmounted(() => {
  clearInterval(intervalId);
});

// Toggle site
const toggleSite = (siteName) => {
  if (selectedSites.value.includes(siteName)) {
    selectedSites.value = selectedSites.value.filter(s => s !== siteName);
  } else {
    selectedSites.value.push(siteName);
  }
};

// Bouton Tout sélectionner / Tout désélectionner
const toggleAllSites = () => {
  if (selectedSites.value.length === sites.length) {
    selectedSites.value = [] // tout désélectionner
  } else {
    selectedSites.value = sites.map(s => s.name) // tout sélectionner
  }
}


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

const authFormModalAlertInfosAnnoncesCreateClose = () => {
  errorForgotPasswordFirstMessage.value = false
  errorsForgotPasswordFirstErrors.value = []
  errorForgotPasswordTwoMessage.value = false
  errorsForgotPasswordTwoErrors.value = []
  errorForgotPasswordThreeMessage.value = false
  errorsForgotPasswordThreeErrors.value = []
  errorLogin.value = false
  errorsLogin.value = []

  // si tu utilises jQuery pour tes modals :
  $('#authFormModalAlertInfosAnnoncesCreate').modal('hide')
}


const authModalClick = (action_auth = "CREATE_AUTH", modalName = null, modalSlug = null) => {
  if (
    localStorage.getItem("remember_me") === "true" &&
    localStorage.getItem("username") &&
    localStorage.getItem("password")
  ) {
    username.value = localStorage.getItem("username")
    password.value = localStorage.getItem("password")
    remember_me.value = localStorage.getItem("remember_me") === "true"
  }

  authSectionModal.value = action_auth

  if (authSectionModal.value === "CREATE_AUTH") {
    loadingCreate.value = false
  }  

  // ouverture modal bootstrap
  $('#authFormModalAlertInfosAnnoncesCreate').modal("show")
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

    if (authSectionModal.value === 'CREATE_AUTH') {
        authSectionModal.value = 'CREATE';
        authFormModalAlertInfosAnnoncesCreateClose();
        AlertInfosAnnoncesCreate();
       
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

  const getStartDateTime = () => {
  if (!startDate.value || !startTime.value) return null;

  const localDateTime = `${startDate.value}T${startTime.value}:00`;
  return new Date(localDateTime).toISOString();
};

const getEndDateTime = () => {
  if (!endDate.value || !endTime.value) return null;

  const localDateTime = `${endDate.value}T${endTime.value}:00`;
  return new Date(localDateTime).toISOString();
};

const AlertInfosAnnoncesCreate = async () => {
    loadingCreate.value = true;
    authSectionModal.value = 'CREATE';
    publicationStoreInfoAlertAnnonceMessage.value = null;
    publicationStoreInfoAlertAnnonceErrors.value = [];

    await store.dispatch('alertInfosAnnonceAdmin/publicationStoreInfoAlertAnnonceDataRequest', { slug : props.publicationCreate.typePublication.slug, title: title.value, status: status.value, date_publish: getStartDateTime(), date_publish_end: getEndDateTime() });

    const statut = store.getters['alertInfosAnnonceAdmin/getInfosPublicationStoreInfoAlertAnnonceStatus'];
    const message = store.getters['alertInfosAnnonceAdmin/getInfosPublicationStoreInfoAlertAnnonceMessage'];
    const data = store.getters['alertInfosAnnonceAdmin/getInfosPublicationStoreInfoAlertAnnonceData'];
    const errors = store.getters['alertInfosAnnonceAdmin/getInfosPublicationStoreInfoAlertAnnonceErrors'];

    if (statut === 'success') {
      publicationStoreInfoAlertAnnonceData.value = data;
      showToast('success',message);
      loadingCreate.value = false;
    } else if (statut === 'empty') {
      publicationStoreInfoAlertAnnonceMessage.value = message;
      publicationStoreInfoAlertAnnonceErrors.value = errors;
      loadingCreate.value = false;
    } else if (statut === 'error') {
      publicationStoreInfoAlertAnnonceMessage.value = message;
      publicationStoreInfoAlertAnnonceErrors.value = [];
      loadingCreate.value = false;
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
        await AlertInfosAnnoncesCreate();
      } else {
        loadingCreate.value = true;
      }
    } else if (roleStatus === 'failed') {
      authSectionModal.value = 'CREATE_AUTH';
      authModalClick(authSectionModal.value)
    }
  } else {
    authSectionModal.value = 'CREATE_AUTH';
    authModalClick(authSectionModal.value)
  }
};


// Watcher statut
watch(status, handleStatusChange);
</script>

<style>
/* Mettre en évidence la carte sélectionnée */
.card.border-primary {
  border-width: 2px !important;
}
</style>

<template>
  <div class="container mt-4"> 
    <div class="row">
       <div v-if="publicationStoreInfoAlertAnnonceMessage">
            <div class="alert alert-danger"  role="alert">
                {{ publicationStoreInfoAlertAnnonceMessage }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Contenu de <span v-if="props.publicationCreate.typePublication.id === 2">l'alerte info</span> <span v-else-if="props.publicationCreate.typePublication.id === 3">l'annonce</span></label>
                 <QuillEditor theme="snow"  v-model:content="title" contentType="html" />
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label">Statut</label>
                <select v-model="status" class="form-select">
                    <option value="1">Publier à l’instant</option>
                    <option value="0">Mettre en brouillon</option>
                    <option value="3">Mettre en attente de validation</option>
                    <option value="2">Définir une date de publication</option>
                </select>
            </div>
        </div>
        <div class="col-md-9">
            <div class="mb-3 row" v-if="status !== 0 && status !== 3">
                <div class="col mb-3">
                    <label class="form-label">Date début</label>
                    <input type="date" class="form-control" v-model="startDate" :disabled="isStartDisabled" />
                </div>
                <div class="col mb-3">
                    <label class="form-label">Heure début</label>
                    <input type="time" class="form-control" v-model="startTime" :disabled="isStartDisabled" />
                </div>
                <div class="col mb-3">
                    <label class="form-label">Date fin</label>
                    <input type="date" class="form-control" v-model="endDate" :disabled="isEndDisabled" />
                </div>
                <div class="col mb-3">
                    <label class="form-label">Heure fin</label>
                    <input type="time" class="form-control" v-model="endTime" :disabled="isEndDisabled" />
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-3">
            <div class="row">
                <div class="col-md-3 mb-3 ">
                    <h5>Diffuser sur d’autres sites partenaires</h5>
                    <p>Choisissez les sites où vous voulez partager cette cette. Cliquez sur les logos pour sélectionner ou annuler la diffusion.</p>
                    <span style="cursor: pointer" class="btn btn-dark mb-0" @click="toggleAllSites">
                        {{ selectedSites.length === sites.length ? 'Tout désélectionner' : 'Tout sélectionner' }}     
                    </span>
                </div>
                <div class="col-md-9 mb-3">
                    <div class="row g-3 justify-content-center">
                        <div v-for="site in sites" :key="site.name" class="col-md-6 mb-3">
                            <div class="card border" @click="toggleSite(site.name)" 
                                :class="{ 'border-primary': selectedSites.includes(site.name) }" 
                                style="cursor:pointer;">
                            
                                <!-- Image -->
                                <div class="card-img-top avatar mb-2" style="width: 100%; height: 180px">
                                    <div class="avatar-img bg-light">
                                        <span class="text-light position-absolute top-50 start-50 translate-middle fw-bold small" > <img :src="site.logo" :alt="site.name" style="height: 85px; width: 100%; object-fit: cover;"> </span>
                                    </div>
                                </div>

                                <!-- Card body -->
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ site.name }}</h5>
                                    <p class="mb-3">{{ site.description }}</p>
                                </div>

                                <!-- Card footer -->
                                <div class="card-footer pb-3 d-grid">
                                    <button class="btn btn-sm btn-dark mb-0">
                                        {{ selectedSites.includes(site.name) ? 'Désélectionner' : 'Sélectionner' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
        </div>
    </div>
    <button class="btn btn-primary" v-if="!loadingCreate" @click="create">Enregistrer</button>
    <button class="btn btn-primary" v-else>Enregistrement en cours ..</button>
  </div>
     <div class="modal fade" id="authFormModalAlertInfosAnnoncesCreate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
           
                <!-- Login Form -->
            <div class="modal-content" v-if=" authSectionModal == 'CREATE_AUTH'">
                <div class="modal-header">
                    <h5 class="modal-title" v-if="authSectionStepModal == 1">Se connecter</h5>
                    <h5 class="modal-title" v-else-if="authSectionStepModal == 2">Modifier le mot de passe</h5>
                    <h5 class="modal-title" v-else-if="authSectionStepModal == 3">S'inscrire</h5>
                    <button type="button" class="btn-close" @click="authFormModalAlertInfosAnnoncesCreateClose" data-bs-dismiss="modal" aria-label="Close"></button>
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
