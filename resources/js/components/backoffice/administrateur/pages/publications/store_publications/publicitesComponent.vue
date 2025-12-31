<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue'; 
import { useStore } from "vuex"
import { Bootstrap5Pagination, TailwindPagination } from "../../../../../../libraries/pagination/lib"
import {VueTelInput} from 'vue-tel-input';
import 'vue-tel-input/vue-tel-input.css';
import { useRoute } from "vue-router"; // ✅ import

import Swal from 'sweetalert2';

const store = useStore();
const route = useRoute(); 

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


const fileTypes = [
  { id: 1, title: 'Ajouter des fichiers',  icon: 'bi bi-cloud-arrow-up'},
  { id: 2, title: 'Sélectioner des fichiers', icon: 'bi bi-archive' },
  { id: 3, title: 'Ajouter des URL FILES', icon: 'bi bi-link-45deg' },
  { id: 4, title: 'Ajouter des codes Iframes', icon: 'bi bi-youtube' },
];


const currentType = ref(null);
const fileModal = ref(null);

const filesMessage= ref(null);
const statut = ref('')
const filesData = ref({ });
const emptyFiles = ref(0);
const statusFiles = ref(3);
const loadingFiles = ref(true);
const style = ref("bootstrap5")
const limit = ref(1)
const keepLength = ref(false)
const showDisabled = ref(false)
const size = ref("default")
const align = ref("left")
const search = ref("")


// composants importés utilisables directement dans <template>
const dataReady = ref(0)
const meRoleName = ref(null)

const publicationStoreInfoPublicitesData = ref({})
const publicationStoreInfoPublicitesMessage = ref(null)
const publicationStoreInfoPublicitesErrors = ref([])

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
const link_publicites = ref(null);
const entete_site = ref(false);
const dans_les_contenus = ref(false);
const entre_publications = ref(true);
const toutes_les_pages = ref(true);
const telephone = ref(null);

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

const authFormModalPublicitesCreateClose = () => {
  errorForgotPasswordFirstMessage.value = false
  errorsForgotPasswordFirstErrors.value = []
  errorForgotPasswordTwoMessage.value = false
  errorsForgotPasswordTwoErrors.value = []
  errorForgotPasswordThreeMessage.value = false
  errorsForgotPasswordThreeErrors.value = []
  errorLogin.value = false
  errorsLogin.value = []

  // si tu utilises jQuery pour tes modals :
  $('#authFormModalPublicitesCreate').modal('hide')
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
  $('#authFormModalPublicitesCreate').modal("show")
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
        authFormModalPublicitesCreateClose();
        PublicitesCreate();
       
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

/* =========================
   MEDIA – STATES
========================= */
const uploadedFiles = ref([])
const existingFiles = ref([])
const videoLinks = ref([])
const iframeEmbeds = ref([])
const previewFiles = ref([])
const coverImage = ref(null)


const getFiles = async ( page=1) =>{

  await store.dispatch('publicationAdmin/publicationGetFilesDataRequest',{slug : route.params.slug, page : page, search: search.value });

    const status = store.getters['publicationAdmin/getInfosPublicationGetFilesStatus'];
    const message = store.getters['publicationAdmin/getInfosPublicationGetFilesMessage'];
    const data = store.getters['publicationAdmin/getInfosPublicationGetFilesData'];

  if( status === "success"){

      filesData.value = data

      empty.value = 0

      loadingFiles.value = false

  }else if( status ==="empty"){

    filesMessage.value = message

    empty.value = 1

    loadingFiles.value = false

  } else{

    filesMessage.value = message

    empty.value = 3

    loadingFiles.value = false

  }
};

const  handleSelectionSearchByStatusFiles = async ( event, page=1) =>{

  statut.value = event.target.value

  await store.dispatch('publicationAdmin/publicationSearchByTypeFilesDataRequest',{slug : route.params.slug, page : page, search: search.value , status: statut.value});

    const status = store.getters['publicationAdmin/getInfosPublicationSearchByTypeFilesStatus'];
    const message = store.getters['publicationAdmin/getInfosPublicationSearchByTypeFilesMessage'];
    const data = store.getters['publicationAdmin/getInfosPublicationSearchByTypeFilesData'];

  if( status === "success"){

      filesData.value = data

      empty.value = 0

      loadingFiles.value = false

  }else if( status ==="empty"){

    filesMessage.value = message

    empty.value = 1

    loadingFiles.value = false

  } else{

    filesMessage.value = message

    empty.value = 3

    loadingFiles.value = false

  }
};

const videoLink = ref('');
const iframeCode = ref('');

const openModal = (id) => {
  currentType.value = fileTypes.find(t => t.id === id);
  const modal = new bootstrap.Modal(fileModal.value);
  modal.show();
};

const closeModal = () => {
  const modal = bootstrap.Modal.getInstance(fileModal.value);
  modal.hide();
};


const fileInput = ref(null);
 
const getImage = (slug) => {
    return slug;
};

const triggerFileInput = () => {
  if (fileInput.value) {
    fileInput.value.click();
  }
};

const addFile = (file) => {
  const url = URL.createObjectURL(file);

  if (isDuplicate(url)) {
    alert("Ce fichier est déjà ajouté !");
    return;
  }

  const type = file.type.startsWith("video")
    ? "video"
    : file.type.startsWith("image")
    ? "image"
    : "other";

  previewFiles.value.push({
    url,
    type,
    name: file.name,
    caption: "",
    isCover: false,

    
  });

  uploadedFiles.value.push({'file' :file,  name: file.name,
    caption: "",
    isCover: false,})
};
const handleDrop = (e) => {
  const files = e.dataTransfer.files; // c'est un FileList
  for (let file of files) {
    addFile(file);
  }
};

const handleInput = (e) => {
  const files = e.target.files; // FileList aussi
  for (let file of files) {
    addFile(file);
  }
};

// Fonction utilitaire : vérifier doublons
const isDuplicate = (url) => {
  return previewFiles.value.some(f => f.url === url);
};

// Ajouter fichiers via drag & drop ou input
const handleFiles = (e) => {
   const files = e.target.files || e.dataTransfer.files;
  for (let file of files) {
    const url = URL.createObjectURL(file);
    if (isDuplicate(url)) {
      alert("Ce fichier est déjà ajouté !");
      continue;
    }
    const type = file.type.startsWith("video")
      ? "video"
      : file.type.startsWith("image")
      ? "image"
      : "other";

    previewFiles.value.push({
      url,
      type,
      name: file.name,
      caption: "",
      isCover: false,
    });
    uploadedFiles.value.push({'file' :file,  name: file.name,
    caption: "",
    isCover: false,})
  }
};

// Depuis la base de données
const addFromDb = (file) => {
  console.log(file)
  let type = "image";
  if (file.type_file_id == 2) type = "video";
  if (file.type_file_id == 3) type = "iframe";

  if (isDuplicate(file.file_url)) {
    alert("Ce fichier est déjà sélectionné !");
    return;
  }

  existingFiles.value.push({ url: file.file_url,
    type,
    name: file.file_name,
    caption: file.caption || "",
    isCover: false, })

  previewFiles.value.push({
    url: file.file_url,
    type,
    name: file.file_name,
    caption: file.caption || "",
    isCover: false,
  }) 

};
 

// Ajouter un lien vidéo (MP4 ou YouTube)
const addVideoLink = () => {
  if (!videoLink.value) return;

  const url = videoLink.value.trim();

  // MP4 direct
  if (url.toLowerCase().endsWith('.mp4')) {
    previewFiles.value.push({
      url,
      type: 'video',
      name: 'Vidéo',
      caption: '',
      isCover: false
    });
    videoLink.value = '';
    return;
  }

  // YouTube (watch, embed, youtu.be)
  const ytRegex = /(?:youtube\.com\/.*[?&]v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/;
  const match = url.match(ytRegex);

  if (match && match[1]) {
    const embedUrl = `https://www.youtube.com/embed/${match[1]}`;
    if (isDuplicate(embedUrl)) {
    alert("Cette vidéo est déjà ajoutée !");
    return;
  }
    previewFiles.value.push({
      url: embedUrl,
      type: 'iframe',
      name: 'YouTube vidéo',
      caption: '',
      isCover: false
    });
    videoLinks.value.push({
      url,
      type: 'video',
      name: 'Vidéo',
      caption: '',
      isCover: false
    });
    videoLink.value = '';
    return;
  }

  alert('Lien vidéo non supporté');
};
// Traiter iframe embed
const addIframe = () => {
  if (!iframeCode.value) return;

  const match = iframeCode.value.match(/src=["']([^"']+)["']/);
  if (!match) {
    alert("Code d'intégration non valide !");
    return;
  }

  const embedUrl = match[1];

  if (isDuplicate(embedUrl)) {
    alert("Cet iframe est déjà ajouté !");
    return;
  }

  previewFiles.value.push({
    url: embedUrl,
    type: "iframe",
    name: "Iframe intégré",
    caption: "",
    isCover: false,
  });

  
 iframeEmbeds.value.push({
    url: embedUrl,
    type: "iframe",
    name: "Iframe intégré",
    caption: "",
    isCover: false,
  });

  iframeCode.value = "";
};

const toggleCover = (file) => {
  previewFiles.value.forEach(f => f.isCover = false)
  file.isCover = true
  coverImage.value = file
}

const removeFile = (index) => {
  if (previewFiles.value[index].isCover) coverImage.value = null
  previewFiles.value.splice(index, 1)
}

const PublicitesCreate = async () => {
    loadingCreate.value = true;
    authSectionModal.value = 'CREATE';
    publicationStoreInfoPublicitesMessage.value = null;
    publicationStoreInfoPublicitesErrors.value = [];

      
    /* 3️⃣ Build FormData */
    const formData = new FormData()

    formData.append('slug', props.publicationCreate.typePublication.slug)
    formData.append('title', title.value)
    formData.append('telephone', telephone.value)
    formData.append('status', status.value)
    const startDateTime = getStartDateTime()
    const endDateTime = getEndDateTime()

    if (startDateTime) {
      formData.append('date_publish', startDateTime)
    }

    if (endDateTime) {
      formData.append('date_publish_end', endDateTime)
    }
    formData.append('link_publicites', link_publicites.value)

    formData.append('entete_site', entete_site.value)
    formData.append('entre_publications', entre_publications.value)
    formData.append('toutes_les_pages', toutes_les_pages.value)
    formData.append('dans_les_contenus', dans_les_contenus.value)

    selectedSites.value.forEach(s => formData.append('sites[]', s))
    uploadedFiles.value.forEach(f => formData.append('uploads[]', f))
    existingFiles.value.forEach(id => formData.append('existing_files[]', id))
    videoLinks.value.forEach(v => formData.append('videos[]', JSON.stringify(v)))
    iframeEmbeds.value.forEach(i => formData.append('iframes[]', i))

  if (coverImage.value) {
    formData.append('cover', coverImage.value.url)
  }


    await store.dispatch('publicitesAdmin/publicationStorePublicitesDataRequest', formData);

    const statut = store.getters['publicitesAdmin/getInfosPublicationStorePublicitesStatus'];
    const message = store.getters['publicitesAdmin/getInfosPublicationStorePublicitesMessage'];
    const data = store.getters['publicitesAdmin/getInfosPublicationStorePublicitesData'];
    const errors = store.getters['publicitesAdmin/getInfosPublicationStorePublicitesErrors'];

    if (statut === 'success') {
      publicationStoreInfoPublicitesData.value = data;
      showToast('success',message);
      loadingCreate.value = false;
    } else if (statut === 'empty') {
      publicationStoreInfoPublicitesMessage.value = message;
      publicationStoreInfoPublicitesErrors.value = errors;
      loadingCreate.value = false;
    } else if (statut === 'error') {
      publicationStoreInfoPublicitesMessage.value = message;
      publicationStoreInfoPublicitesErrors.value = [];
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
        await PublicitesCreate();
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

onMounted(() => {
  getFiles();
});
</script>

<style>
/* Mettre en évidence la carte sélectionnée */
.card.border-primary {
  border-width: 2px !important;
}

.drag-image{
  border: 1px dashed #0f0866;
  height: 300px;
  width: 100%;
  border-radius: 5px;
  font-weight:400;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
}
.drag-image.active{
  border: 2px solid blue;
}
.drag-image .icon{
  font-size: 30px;
  color: #000;
}
.drag-image h6{
  font-size: 20px;
  font-weight: 300;
  color: #000;
}
.drag-image span{
  font-size: 14px;
  font-weight: 300;
  color: #000;
  margin: 10px 0 15px 0;
}
.drag-image button{
  padding: 10px 25px;
  font-size: 14px; 
  font-weight: 300;
  border: none;
  outline: none;
  background:transparent;
  color: #000;
  border:1px solid #000;
  border-radius: 5px;
  cursor: pointer;
  transition:all 0.5s;
}

.drag-image button:hover{
    background-color: blue;
    color:#fff;
}

.drag-image img{
  height: 100%;
  width: 100%;
  object-fit: cover;
  border-radius: 5px;
}
</style>

<template>
  <div class="container mt-4"> 
    <div class="row">
       <div v-if="publicationStoreInfoPublicitesMessage">
            <div class="alert alert-danger"  role="alert">
                {{ publicationStoreInfoPublicitesMessage }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Contenu de la publicité </label>
                 <QuillEditor theme="snow"  v-model:content="title" contentType="html" />
            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Télephone</label>
                <div class="input-group">
                    <vue-tel-input  class="form-control " v-model="telephone" mode="international" ></vue-tel-input>
                </div>
            </div>
             <div class="mb-3">
                <label class="form-label" >Lien de la publication</label>
                <input type="text" class="form-control  " v-model="link_publicites" name="authorName" placeholder="Entrez le lien de la publicité">
            </div>
        </div>
        <div class="col-md-12 mb-3">
            <div>
                <label class="form-label">Ajouter des photos ou vidéos</label>
                <div class="d-flex flex-wrap gap-3">
                  <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12" v-for="type in fileTypes" :key="type.id">
                      <!-- Cards pour les 4 types -->
                      <div class="flex-fill" >
                        <div class="btn btn-outline-light w-100" @click="openModal(type.id)" style="cursor:pointer;">
                          <i :class="type.icon" style="font-size:2rem; color:#0d6efd;"></i>
                          <span>
                            <h6>{{ type.title }}</h6> 
                          </span> 
                        </div>
                      </div>
                    </div>
                  </div>
                  
                </div>

                <!-- Modal -->
                <div class="modal fade" id="fileModal" tabindex="-1" aria-hidden="true" ref="fileModal">
                <div class="modal-dialog modal-fullscreen">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ currentType?.title }}</h5>
                        <button type="button" class="btn-close" @click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                      <div class="row g-4">
                        <div class="col-lg-5">
                          <!-- Upload Drag & Drop -->
                          <div v-if="currentType?.id === 1">
                            
                            <div class="drag-image" @dragover.prevent @drop.prevent="handleDrop($event)">
                              <div class="icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                              </div>
                              <h6>Glisser-déposer des fichiers ici (Images & Vidéos)</h6>
                              <span>OU</span>

                              <!-- Bouton qui déclenche l'input file -->
                              <button type="button" @click="triggerFileInput">Browse File</button>

                              <!-- Input file caché -->
                              <input 
                                type="file" 
                                ref="fileInput" 
                                multiple 
                                accept="image/*,video/*" 
                                @change="handleFiles($event)" 
                                class="form-control mt-2" 
                                hidden
                              >
                            </div>
                          </div>

                          <!-- Sélection depuis la base -->
                          <div v-if="currentType?.id === 2">
                          <div class="row g-2">
                            <div v-if="loadingFiles == false">
                              <div class="col-lg-12 mb-3">
                                <div class="card border">
                                  <!-- Card header -->
                                  <div class="card-header border-bottom p-3">
                                    <div class="row g-3 align-items-center justify-content-between">
                                      <!-- Search bar -->
                                      <div class="col-md-12">
                                        <form class="rounded position-relative" @submit.prevent="getFiles">
                                          <input
                                            class="form-control bg-transparent"
                                            v-model="search"
                                            @input="getFiles"
                                            name="search"
                                            type="search"
                                            placeholder="Rechercher un fichier ..."
                                          >
                                          <button
                                            class="btn bg-transparent border-0 px-2 py-0 position-absolute top-50 end-0 translate-middle-y"
                                            type="submit"
                                          >
                                            <i class="fas fa-search fs-6"></i>
                                          </button>
                                        </form>
                                      </div>
                                      <!-- Tab buttons -->
                                      <div class="col-md-12">
                                        <select
                                          class="form-select"
                                          @change="handleSelectionSearchByStatusFiles($event)"
                                          v-model="statusFiles"
                                        >
                                          <option value="3">Tous les fichiers</option>
                                          <option value="1">Les images</option>
                                          <option value="2">Les vidéos</option>
                                        </select>
                                      </div>
                                    </div>
                                  </div>

                                  <!-- Files list -->
                                  <div
                                    class="card-body p-3"
                                    v-if="emptyFiles == 0"
                                    style="height: 301px; overflow-y: scroll;"
                                  >
                                    <div class="row g-3">
                                      <div
                                        class="col-12 col-sm-6 col-lg-4"
                                        v-for="result in filesData.files.data"
                                        :key="result.id"
                                      >
                                        <div class="card h-100 bg-light shadow-sm">
                                          <!-- Image -->
                                          <img
                                            v-if="result.type_file_id == 1"
                                            :src="getImage(result.file_url)"
                                            class="card-img-top"
                                            style="cursor:pointer; object-fit:cover; height:90px"
                                            @click="addFromDb(result)"


                                          >

                                          <!-- Vidéo -->
                                          <video
                                            v-else-if="result.type_file_id == 2"
                                            :src="getImage(result.file_url)"
                                            class="card-img-top"
                                            muted
                                            style="cursor:pointer; object-fit:cover; height:90px"
                                            @click="addFromDb(result)"

                                          ></video>

                                          <!-- Iframe (ex: YouTube) -->
                                          <div
                                            v-else-if="result.type_file_id == 3"
                                            class="ratio ratio-16x9"
                                            v-html="result.file_url"
                                            style="cursor:pointer"
                                            @click="addFromDb(result)"
                                          ></div>

                                          
                                        </div>
                                      </div>
                                    </div>
                                  </div>

                                  <!-- Empty -->
                                  <div class="card-body p-3 pb-0" v-else-if="emptyFiles == 1">
                                    <div class="text-center">
                                      <img
                                        src="https://togoactu.com/assets/images/empty.png"
                                        style="width:100px"
                                        alt="empty"
                                      >
                                      <h5 class="mt-3">{{ filesMessage }}</h5>
                                    </div>
                                  </div>

                                  <!-- Pagination -->
                                  <div class="card-footer p-3" v-if="emptyFiles == 0">
                                    <Bootstrap5Pagination
                                      class="mb-0"
                                      :data="filesData.files"
                                      :limit="limit"
                                      :keep-length="keepLength"
                                      :show-disabled="showDisabled"
                                      :size="size"
                                      :align="align"
                                      @pagination-change-page="getFiles"
                                    />
                                  </div>
                                </div>
                              </div>
                            </div>

                          </div>
                          
                          </div>

                          <!-- Formulaire liens vidéo -->
                          <div v-if="currentType?.id === 3">
                          <div class="mb-2">
                              <input type="text" v-model="videoLink" class="form-control" placeholder="Coller le lien vidéo">
                          </div>
                          <button class="btn btn-success mb-3" @click="addVideoLink">Ajouter vidéo</button>
                          </div>

                          <!-- Formulaire iframe YouTube -->
                          <div v-if="currentType?.id === 4">
                          <div class="mb-2">
                              <textarea v-model="iframeCode" class="form-control" placeholder="Coller le code iframe"></textarea>

                          </div>
                          <button class="btn btn-success mb-3" @click="addIframe">Ajouter iframe</button>
                          </div>
                        </div>
                        <div class="col-lg-7">
                          <div class="col-lg-12">
                              <div class="card border">
                                <!-- Card header -->
                                <div class="card-header border-bottom p-3">
                                  <div class="row g-3 align-items-center justify-content-between">
                                    <!-- Search bar -->
                                    <div class="col-md-12">
                                      <h5>Fichiers ajoutés</h5>
                                    </div>
                                  </div>
                                </div>
                                <div class="card-body border-bottom p-3" style="height: 391px; overflow-y: scroll;">
                                   <div class="row g-3">
                                      <div class="col-md-12" v-for="(file, index) in previewFiles" :key="index">
                                        <div class="bg-light position-relative overflow-hidden h-100">
                                          <div class="row">

                                            <!-- Aperçu -->
                                            <div class="col-md-4">
                                              <img v-if="file.type.includes('image')" :src="file.url" alt="Image" style="height: 225px; object-fit: cover">
                                              
                                              <video v-else-if="file.type === 'video' && file.url.endsWith('.mp4')" class="w-100" controls style="height: 225px; object-fit: cover">
                                                  <source :src="file.url" type="video/mp4" />
                                                </video>
                                                
                                                <div v-else-if="file.type === 'iframe'" class="ratio ratio-16x9" style="height: 225px; object-fit: cover">
                                                  <iframe :src="file.url" frameborder="0" allowfullscreen></iframe>
                                                </div>


                                              <!-- Iframe YouTube déjà ajouté -->
                                              <div v-else-if="file.type === 'iframe'" v-html="file.url" class="ratio ratio-16x9" style="height: 225px; object-fit: cover"></div>

                                              <!-- Badge couverture -->
                                              <span v-if="file.isCover" class="badge bg-success position-absolute top-0 start-0 m-2">
                                                Couverture
                                              </span>
                                            </div>
                                            <div class="col-md-8">
                                              <div class="p-4 p-sm-3">
                                                <input type="text" v-model="file.name" class="form-control mb-2" placeholder="Nom du fichier">
                                                <textarea v-model="file.caption" class="form-control mb-2" rows="2" placeholder="Légende"></textarea>
                                                <small class="text-dark d-block mt-2">{{ file.type.includes('video') ? 'Vidéo' : file.type.includes('image') ? 'Image' : 'Iframe' }}</small>
                                              </div>
                                               <!-- Card footer actions -->
                                              <div class="pb-3">
                                                <button class="btn btn-danger btn-sm" @click="removeFile(index)">Supprimer</button>
                                                &nbsp;
                                                <button 
                                                  v-if="file.type.includes('image')" 
                                                  class="btn btn-sm" 
                                                  :class="file.isCover ? 'btn-success' : 'btn-secondary'"
                                                  @click="toggleCover(file)"
                                                >
                                                  {{ file.isCover ? "Couverture définie" : "Définir couverture" }}
                                                </button>
                                              </div>
                                            </div>
                                           
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                         
                      </div>
                      <div class="modal-footer">
                          <button class="btn btn-secondary" @click="closeModal">Fermer</button>
                      </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 mb-3">
          <label class="form-label">Definir les emplacements pour la publicité.</label>
          <div class="row g-3 g-sm-4">
              <div class="col-sm-3">
                  <div class="bg-light rounded-2 p-3">
                      <div class="form-check form-check-inline mb-0">
                          <input class="form-check-input" type="checkbox" v-model="entete_site" name="entete_site" id="delivery1"  >
                          <label class="form-check-label h5 mb-0" for="delivery1">A l'entête du site</label>
                          <p class="mb-1 small">Afficher la publicité en l'entête du site</p>
                      </div>
                  </div>
              </div>
              <!-- Delivery option -->
              <div class="col-sm-3">
                  <div class="bg-light rounded-2 p-3">
                      <div class="form-check form-check-inline mb-0">
                          <input class="form-check-input" type="checkbox"  v-model="entre_publications" name="entre_publications" id="delivery2">
                          <label class="form-check-label h5 mb-0" for="delivery2">Entre les publications </label>
                          <p class="mb-1 small">Afficher la publicité entre les publications sur la page d'accueil</p>
                      </div>
                  </div>
              </div>
              <div class="col-sm-3">
                  <div class="bg-light rounded-2 p-3">
                      <div class="form-check form-check-inline mb-0">
                          <input class="form-check-input" type="checkbox" v-model="toutes_les_pages" name="toutes_les_pages" id="delivery3"  >
                          <label class="form-check-label h5 mb-0" for="delivery3">Sur toutes les pages</label>
                          <p class="mb-1 small">Afficher la publicité entre les publications sur toutes les pages</p>
                      </div>
                  </div>
              </div>
              <div class="col-sm-3">
                  <div class="bg-light rounded-2 p-3">
                      <div class="form-check form-check-inline mb-0">
                          <input class="form-check-input" type="checkbox" v-model="dans_les_contenus" name="dans_les_contenus" id="delivery4">
                          <label class="form-check-label h5 mb-0" for="delivery4">Dans les contenus</label>
                          <p class="mb-1 small">Afficher la publicité à l'intérieur des contenus des publications</p>
                      </div>
                  </div>
              </div>
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
     <div class="modal fade" id="authFormModalPublicitesCreate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
           
                <!-- Login Form -->
            <div class="modal-content" v-if=" authSectionModal == 'CREATE_AUTH'">
                <div class="modal-header">
                    <h5 class="modal-title" v-if="authSectionStepModal == 1">Se connecter</h5>
                    <h5 class="modal-title" v-else-if="authSectionStepModal == 2">Modifier le mot de passe</h5>
                    <h5 class="modal-title" v-else-if="authSectionStepModal == 3">S'inscrire</h5>
                    <button type="button" class="btn-close" @click="authFormModalPublicitesCreateClose" data-bs-dismiss="modal" aria-label="Close"></button>
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
