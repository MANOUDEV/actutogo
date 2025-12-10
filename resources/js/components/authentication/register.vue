<script setup>
import { ref, computed, onMounted } from 'vue';
import { useStore } from 'vuex';
import Swal from 'sweetalert2';

const store = useStore();
const loadingVerifyEmail = ref(false); 
const loadingNewPass = ref(false);
const loadingNewInfo = ref(false);
const loadingResendOTP = ref(false); 
const loadingVerifyOtp = ref(false);  
const email = ref(null);
const otp = ref(null);
const password = ref(null);
const nom = ref(null);
const prenoms = ref(null);
const username = ref(null);
const password_confirm = ref(null);
const loginError = ref(false);
const loading = ref(false);
const errorFirst = ref(false);
const errorTwo = ref(false);
const errorThree = ref(false);
const errorFour = ref(false);
const errorsFirst = ref([]); 
const errorsTwo = ref([]); 
const errorsThree = ref([]); 
const errorsFour = ref([]); 
const dataReady = ref(0); 
const step = ref(1); 
const meProfileRoleName = ref(null);
const remember_me = ref(false); 
const showPsw = ref(false); 
const showPswC = ref(false);


// Fonction pour afficher/masquer le mot de passe
const showPassword = () => {
  const x = document.getElementById('psw-input');
  if (x.type === 'password') {
    x.type = 'text';
    showPsw.value = true;
  } else {
    x.type = 'password';
    showPsw.value = false;
  }
};

const showPasswordC = () => {
  const x = document.getElementById('psw-input_c');
  if (x.type === 'password') {
    x.type = 'text';
    showPswC.value = true;
  } else {
    x.type = 'password';
    showPswC.value = false;
  }
};


const PreviousStep= () =>{
    errorFirst.value = null
    errorsFirst.value = []
    errorTwo.value = null
    errorsTwo.value = []
    errorThree.value = null
    errorsThree.value = []
    errorFour.value = null
    errorsFour.value = []
    step.value = 1
};

const PreviousHStep= () =>{
    errorFirst.value = null
    errorsFirst.value = []
    errorTwo.value = null
    errorsTwo.value = []
    errorThree.value = null
    errorsThree.value = []
    errorFour.value = null
    errorsFour.value = []
    step.value = 2
};


const PreviousHHStep= () =>{
    errorFirst.value = null
    errorsFirst.value = []
    errorTwo.value = null
    errorsTwo.value = []
    errorThree.value = null
    errorsThree.value = []
    errorFour.value = null
    errorsFour.value = []
    step.value = 3
};
const submitVerifyEmail = async () => {
    loadingVerifyEmail.value = true
    errorFirst.value = null
    errorsFirst.value = []
    
    // Action de connexion
    await store.dispatch('register/sendOtpRegister',{email: email.value});

    const getterSendOtpRegisterStatus = store.getters['register/getSendOtpRegisterStatus'];
    const getterSendOtpRegisterMessage = store.getters['register/getSendOtpRegisterMessage'];
    const getterSendOtpRegisterErrors = store.getters['register/getSendOtpRegisterErrors'];

    if(getterSendOtpRegisterStatus === 'success'){

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
            title: getterSendOtpRegisterMessage
        })

        errorFirst.value = null
        errorsFirst.value = []

        loadingVerifyEmail.value = false

        step.value = 2

    }else if(getterSendOtpRegisterStatus === 'failed'){

        errorFirst.value = getterSendOtpRegisterMessage

        errorsFirst.value = []

        loadingVerifyEmail.value = false

    }else if(getterSendOtpRegisterStatus === 'error'){

        errorFirst.value = getterSendOtpRegisterMessage

        errorsFirst.value = getterSendOtpRegisterErrors

        loadingVerifyEmail.value = false
    }

    loadingVerifyEmail.value = false
};

const submitResendOtp = async () => {
    loadingResendOTP.value = true
    errorFirst.value = null
    errorsFirst.value = []
    
    // Action de connexion
    await store.dispatch('register/sendOtpRegister',{email: email.value});

    const getterSendOtpRegisterStatus = store.getters['register/getSendOtpRegisterStatus'];
    const getterSendOtpRegisterMessage = store.getters['register/getSendOtpRegisterMessage'];
    const getterSendOtpRegisterErrors = store.getters['register/getSendOtpRegisterErrors'];

    if(getterSendOtpRegisterStatus === 'success'){

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
            title: getterSendOtpRegisterMessage
        })

        errorFirst.value = null
        errorsFirst.value = []

        loadingResendOTP.value = false

        step.value = 2

    }else if(getterSendOtpRegisterStatus === 'failed'){

        errorFirst.value = getterSendOtpRegisterMessage

        errorsFirst.value = []

        loadingResendOTP.value = false

    }else if(getterSendOtpRegisterStatus === 'error'){

        errorFirst.value = getterSendOtpRegisterMessage

        errorsFirst.value = getterSendOtpRegisterErrors

        loadingResendOTP.value = false
    }

    loadingResendOTP.value = false
}

const submitVerifyOtp = async () => {
    loadingVerifyOtp.value = true
    errorTwo.value = null
    errorsTwo.value = []
    
    // Action de connexion
    await store.dispatch('register/checkOtpRegister',{email: email.value, otp: otp.value});

    const getterCheckOtpRegisterStatus = store.getters['register/getCheckOtpRegisterStatus'];
    const getterCheckOtpRegisterMessage = store.getters['register/getCheckOtpRegisterMessage'];
    const getterCheckOtpRegisterErrors = store.getters['register/getCheckOtpRegisterErrors'];

    if(getterCheckOtpRegisterStatus === 'success'){

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
            title: getterCheckOtpRegisterMessage
        })

        errorTwo.value = null
        errorsTwo.value = []

        loadingVerifyOtp.value = false

        step.value = 4

    }else if(getterCheckOtpRegisterStatus === 'failed'){

        errorTwo.value = getterCheckOtpRegisterMessage

        errorsTwo.value = []

        loadingVerifyOtp.value = false

    }else if(getterCheckOtpRegisterStatus === 'error'){

        errorTwo.value = getterCheckOtpRegisterMessage

        errorsTwo.value = getterCheckOtpRegisterErrors

        loadingVerifyOtp.value = false
    }

    loadingVerifyOtp.value = false
};

const submitNewInfo = async () => {
    loadingNewInfo.value = true
    errorFour.value = null
    errorsFour.value = []
    
    // Action de connexion
    await store.dispatch('register/newInfoOtpRegister',{email: email.value, otp: otp.value, nom: nom.value, prenoms :prenoms.value, username: username.value});

    const getterNewInfoOtpRegisterStatus = store.getters['register/getNewInfoOtpRegisterStatus'];
    const getterNewInfoOtpRegisterMessage = store.getters['register/getNewInfoOtpRegisterMessage'];
    const getterNewInfoOtpRegisterErrors = store.getters['register/getNewInfoOtpRegisterErrors'];

    if(getterNewInfoOtpRegisterStatus === 'success'){

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
            title: getterNewInfoOtpRegisterMessage
        })

        errorFour.value = null
        errorsFour.value = []

        step.value =  3

    }else if(getterNewInfoOtpRegisterStatus === 'failed'){

        errorFour.value = getterNewInfoOtpRegisterMessage

        errorsFour.value = []

        loadingNewInfo.value = false

    }else if(getterNewInfoOtpRegisterStatus === 'error'){

        errorFour.value = getterNewInfoOtpRegisterMessage

        errorsFour.value = getterNewInfoOtpRegisterErrors

        loadingNewInfo.value = false
    }

    loadingNewInfo.value = false
};

const submitNewPass = async () => {
    loadingNewPass.value = true
    errorThree.value = null
    errorsThree.value = []
    
    // Action de connexion
    await store.dispatch('register/newPassOtpRegister',{email: email.value, otp: otp.value, nom: nom.value, prenoms :prenoms.value, username: username.value ,password: password.value, password_confirm: password_confirm.value});

    const getterNewPassOtpRegisterStatus = store.getters['register/getNewPassOtpRegisterStatus'];
    const getterNewPassOtpRegisterMessage = store.getters['register/getNewPassOtpRegisterMessage'];
    const getterNewPassOtpRegisterErrors = store.getters['register/getNewPassOtpRegisterErrors'];

    if(getterNewPassOtpRegisterStatus === 'success'){

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
            title: getterNewPassOtpRegisterMessage
        })

        errorThree.value = null
        errorsThree.value = []

        if(remember_me.value){

            localStorage.setItem('password', password.value)

            localStorage.setItem('remember_me', true)

            window.location="/auth/login"


        }else{

            localStorage.setItem('remember_me', false)

            window.location="/auth/login"
        }

    }else if(getterNewPassOtpRegisterStatus === 'failed'){

        errorThree.value = getterNewPassOtpRegisterMessage

        errorsThree.value = []

        loadingNewPass.value = false

    }else if(getterNewPassOtpRegisterStatus === 'error'){

        errorThree.value = getterNewPassOtpRegisterMessage

        errorsThree.value = getterNewPassOtpRegisterErrors

        loadingNewPass.value = false
    }

    loadingNewPass.value = false
};

const show = async () => {
  if (localStorage.getItem('access_token') && localStorage.getItem('nbRsp')) {
    await store.dispatch('meProfile/getMeProfile');
    
    const gettersMeProfileUserName =  store.getters['meProfile/getMeProfileUserName'];
    const gettersMeProfileRoleName =  store.getters['meProfile/getMeProfileRoleName'];
    const gettersMeProfileStatus =  store.getters['meProfile/getMeProfileStatus'];  

    if (gettersMeProfileStatus === 'success') {
      meProfileRoleName.value = gettersMeProfileRoleName;
      meProfileUserName.value = gettersMeProfileUserName;
      dataReady.value = 1;
    } else if (gettersMeProfileStatus === 'failed') {
      if (localStorage.getItem('remember_me') === 'true') {
        username.value = localStorage.getItem('username');
        password.value = localStorage.getItem('password');
        remember_me.value = localStorage.getItem('remember_me');
      }
      dataReady.value = 3;
    } else {
      dataReady.value = 3;
      if (localStorage.getItem('remember_me') === 'true') {
        username.value = localStorage.getItem('username');
        password.value = localStorage.getItem('password');
        remember_me.value = localStorage.getItem('remember_me');
      }
    }
  } else {
    dataReady.value = 2;
    if (localStorage.getItem('remember_me') === 'true') {
      username.value = localStorage.getItem('username');
      password.value = localStorage.getItem('password');
      remember_me.value = localStorage.getItem('remember_me');
    }
  }
};


onMounted(() => {
  show();
});
</script>
<template>
    <!-- **************** MAIN CONTENT START **************** -->
    <main style="margin-top: -45px; margin-bottom: -35px;">
        <!-- =======================Inner intro START -->
        <div v-if="dataReady == 0">
            <br><br><br> <br><br><br>
            <div class="d-flex justify-content-center mt-3">
                <div class="spinner-border text-success"   role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <br><br><br><br><br><br>
        </div>
        <section v-if="dataReady == 2 || dataReady == 3">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-lg-8 col-xl-6 mx-auto">
                        <div class="p-4 p-sm-5 bg-primary bg-opacity-10 rounded">
                            <h2 v-if="step === 1">Email d'activation</h2>
                            <h2 v-else-if="step === 2">Code d' activation</h2>
                            <h2 v-else-if="step === 4">Complèter votre profil</h2>
                            <h2 v-else-if="step === 3">Ajouter un mot de passe</h2>
                            <!-- Form START -->

                            <form class="mt-4">
                                <div v-if="step === 1">
                                    <div v-if="errorFirst" class="mb-3">
                                        <br>
                                        <div class="alert alert-danger"  role="alert">
                                            {{ errorFirst }}
                                        </div>
                                    </div>
                                    <div class="mb-3" v-if="errorsFirst.email">
                                        <label class="form-label" for="exampleInputEmail1">Votre email</label>
                                        <input type="email" v-model="email" name="email" class="form-control is-invalid" id="exampleInputEmail1" placeholder="Veuillez entrer votre email">
                                        <div id="exampleInputEmail1" v-for="erroremail in errorsFirst.email" :key="erroremail" class="invalid-feedback">
                                            {{ erroremail }}
                                        </div>
                                    </div>
                                    <div class="mb-3" v-else>
                                        <label class="form-label" for="exampleInputEmail1">Votre email</label>
                                        <input type="email" v-model="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Veuillez entrer votre email">
                                    </div>
                                    <!-- Button -->
                                    <div class="row align-items-center">
                                        <div class="col-sm-4">
                                            <button type="button" disabled v-if="loadingVerifyEmail" class="btn btn-success"> <i  style="color: #fff" class="fa fa-spinner fa-spin fa-1x fa-fw"></i>
                                                <span class="sr-only">Loading...</span>  Vérification</button>
                                            <button type="submit"  @click.prevent="submitVerifyEmail" v-else class="btn btn-success">Vérifier</button>
                                        </div>
                                        <div class="col-sm-8 text-sm-end">
                                            <span><a href="/auth/login"><u>Mot de passe oublié</u></a></span>
                                        </div>

                                    </div>
                                </div>

                                <div v-else-if="step === 2">
                                    <div v-if="errorTwo" class="mb-3">
                                        <br>
                                        <div class="alert alert-danger"  role="alert">
                                            {{ errorTwo }}
                                        </div>
                                    </div>
                                    <div class="mb-3" v-if="errorsTwo.otp">
                                        <label class="form-label" for="exampleInputOtp1">Votre code de rénitialisation</label>
                                        <input type="text" v-model="otp" name="otp" class="form-control is-invalid" id="exampleInputOtp1" placeholder="Veuillez entrer votre code de vérification">
                                        <div id="exampleInputOtp1" v-for="errorOtp in errorsTwo.otp" :key="errorOtp" class="invalid-feedback">
                                            {{ errorOtp }}
                                        </div>
                                    </div>
                                    <div class="mb-3" v-else>
                                        <label class="form-label" for="exampleInputOtp1">Votre code de rénitialisation</label>
                                        <input type="text"  v-model="otp" name="otp" class="form-control" id="exampleInputOtp1" placeholder="Veuillez entrer votre code de vérification">
                                    </div>
                                    <div class="mb-3" v-if="loadingResendOTP">
                                        <span for="exampleInputOtp1"><span style="color: blue; cursor:pointer">Renvoie d'un nouveau code en cours ... </span></span>
                                    </div>
                                    <div class="mb-3" v-if="!loadingResendOTP">
                                        <span for="exampleInputOtp1" @click.prevent="submitResendOtp"><span style="color: blue; cursor:pointer">Renvoyer un nouveau code </span></span>
                                    </div>
                                    <!-- Button -->
                                    <div class="row align-items-center">
                                        <div class="col-sm-12 justify-content-center" v-if="loadingVerifyOtp">
                                            <button type="button" disabled  class="btn btn-success"> 
                                                <i  style="color: #fff" class="fa fa-spinner fa-spin fa-1x fa-fw"></i>
                                                <span class="sr-only">Loading...</span>  Validation
                                            </button>
                                        </div>
                                        <div class="col-sm-12 justify-content-center" v-else>
                                            <span><span class="btn btn-primary" @click="PreviousStep"><i class="bi bi-arrow-left-short"></i> Retour</span></span> &nbsp;
                                            <button type="submit"  @click.prevent="submitVerifyOtp" class="btn btn-success">Valider </button>
                                        </div>
                                    </div>
                                </div>

                                <div v-else-if="step === 4">
                                    <div v-if="errorFour" class="mb-3">
                                        <br>
                                        <div class="alert alert-danger"  role="alert">
                                            {{ errorFour }}
                                        </div>
                                    </div>
                                    <div class="mb-3" v-if="errorsFour.nom">
                                        <label class="form-label" for="">Votre nom</label>
                                        <input type="text" v-model="nom" name="nom" class="form-control is-invalid" id="" placeholder="Veuillez entrer votre nom">
                                        <div id="" v-for="errornom in errorsFour.nom" :key="errornom" class="invalid-feedback">
                                            {{ errornom }}
                                        </div>
                                    </div>
                                    <div class="mb-3" v-else>
                                        <label class="form-label" for="">Votre nom</label>
                                        <input type="text"  v-model="nom" name="nom" class="form-control" id="" placeholder="Veuillez entrer votre nom">
                                    </div>

                                    <div class="mb-3" v-if="errorsFour.prenoms">
                                        <label class="form-label" for="">votre prénom</label>
                                        <input type="text" v-model="prenoms" name="prenoms" class="form-control is-invalid" id="" placeholder="Veuillez entrer votre prénom">
                                        <div id="" v-for="errorprenoms in errorsFour.prenoms" :key="errorprenoms" class="invalid-feedback">
                                            {{ errorprenoms }}
                                        </div>
                                    </div>
                                    <div class="mb-3" v-else>
                                        <label class="form-label" for="">votre prénom</label>
                                        <input type="text"  v-model="prenoms" name="prenoms" class="form-control" id="" placeholder="Veuillez entrer votre prénom">
                                    </div>

                                    <div class="mb-3" v-if="errorsFour.username">
                                        <label class="form-label" for="">votre nom d'utilisateur</label>
                                        <input type="text" v-model="username" name="username" class="form-control is-invalid" id="" placeholder="Veuillez entrer votre nom d'utilisateur">
                                        <div id="" v-for="errorusername in errorsFour.username" :key="errorusername" class="invalid-feedback">
                                            {{ errorusername }}
                                        </div>
                                    </div>
                                    <div class="mb-3" v-else>
                                        <label class="form-label" for="">votre nom d'utilisateur</label>
                                        <input type="text"  v-model="username" name="username" class="form-control" id="" placeholder="Veuillez entrer votre nom d'utilisateur">
                                        <p id="" style="color: black">
                                            Par défaut votre nom d'utilisateur sera votre prénom
                                        </p>
                                    </div>

                                    <!-- Button -->
                                    <div class="row align-items-center">
                                        <div class="col-sm-12 justify-content-center" v-if="loadingNewInfo">
                                            <button type="button" disabled  class="btn btn-success"> 
                                                <i  style="color: #fff" class="fa fa-spinner fa-spin fa-1x fa-fw"></i>
                                                <span class="sr-only">Loading...</span>  Enregistrement
                                            </button>
                                        </div>
                                        <div class="col-sm-12 justify-content-center" v-else>
                                            <span><span class="btn btn-primary" @click="PreviousHStep"><i class="bi bi-arrow-left-short"></i> Retour</span></span> &nbsp;
                                            <button type="submit"  @click.prevent="submitNewInfo" class="btn btn-success">Enregistrer </button>
                                        </div>
                                    </div>
                                </div>

                                <div v-else-if="step === 3">
                                    <div v-if="errorThree" class="mb-3">
                                        <br>
                                        <div class="alert alert-danger"  role="alert">
                                            {{ errorThree }}
                                        </div>
                                    </div>
                                    <div class="mb-3" v-if="errorsThree.password">
                                        <label class="form-label" for="">Votre nouveau mot de passe</label>
                                        <div class="input-group">
                                            <input v-model="password" name="password" class="form-control fakepassword is-invalid" type="password" id="psw-input" placeholder="Veuillez entrer votre nouveau mot de passe .">
                                            <span class="input-group-text p-0" @click="showPassword" style="cursor: pointer">
                                                <i v-if="showPsw == false" class="fakepasswordicon far fa-eye cursor-pointer p-2 w-40px"></i>
                                                <i v-else class="fakepasswordicon far fa-eye-slash cursor-pointer p-2 w-40px"></i>
                                            </span>
                                        </div>
                                        <div class="rounded mt-1" id="psw-strength"></div>
                                        <div id="" v-for="errorpassword in errorsThree.password" :key="errorpassword" class="invalid-feedback">
                                            {{ errorpassword }}
                                        </div>
                                    </div>
                                    <div class="mb-3" v-else>
                                        <label class="form-label" for="">Votre nouveau mot de passe</label>
                                        <div class="input-group">
                                            <input v-model="password" name="password" class="form-control fakepassword" type="password" id="psw-input" placeholder="Veuillez entrer votre nouveau mot de passe .">
                                            <span class="input-group-text p-0" @click="showPassword" style="cursor: pointer">
                                                <i v-if="showPsw == false" class="fakepasswordicon far fa-eye cursor-pointer p-2 w-40px"></i>
                                                <i v-else class="fakepasswordicon far fa-eye-slash cursor-pointer p-2 w-40px"></i>
                                            </span>
                                        </div>
                                        <div class="rounded mt-1" id="psw-strength"></div>                                    </div>

                                    <div class="mb-3" v-if="errorsThree.password_confirm">
                                        <label class="form-label" for="">Confirmer le mot de passe</label>
                                        <div class="input-group">
                                            <input type="password" v-model="password_confirm" class="form-control fakepassword is-invalid"  name="password_confirm"  id="psw-input_c" placeholder="Veuillez confirmer le mot de passe">
                                            <span class="input-group-text p-0" @click="showPasswordC" style="cursor: pointer">
                                                <i v-if="showPswC == false" class="fakepasswordicon far fa-eye cursor-pointer p-2 w-40px"></i>
                                                <i v-else class="fakepasswordicon far fa-eye-slash cursor-pointer p-2 w-40px"></i>
                                            </span>
                                        </div>
                                        <div class="rounded mt-1" id="psw-strength"></div>
                                        <div id="" v-for="errorpassword_confirm in errorsThree.password_confirm" :key="errorpassword_confirm" class="invalid-feedback">
                                            {{ errorpassword_confirm }}
                                        </div>
                                    </div>
                                    <div class="mb-3" v-else>
                                        <label class="form-label" for="">Confirmer le mot de passe</label>
                                        <div class="input-group">
                                            <input type="password" v-model="password_confirm" class="form-control fakepassword"  name="password_confirm"  id="psw-input_c" placeholder="Veuillez confirmer le mot de passe">
                                            <span class="input-group-text p-0" @click="showPasswordC" style="cursor: pointer">
                                                <i v-if="showPswC == false" class="fakepasswordicon far fa-eye cursor-pointer p-2 w-40px"></i>
                                                <i v-else class="fakepasswordicon far fa-eye-slash cursor-pointer p-2 w-40px"></i>
                                            </span>
                                        </div>
                                        <div class="rounded mt-1" id="psw-strength"></div>
                                        <div id="" v-for="errorpassword_confirm in errorsThree.password_confirm" :key="errorpassword_confirm" class="invalid-feedback">
                                            {{ errorpassword_confirm }}
                                        </div>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" name="remember_me" v-model="remember_me" class="form-check-input" id="exampleCheck1">
                                        <label class="form-check-label" for="exampleCheck1">Se souvenir de moi</label>
                                    </div>

                                   <!-- Button -->
                                   <div class="row align-items-center">
                                    <div class="col-sm-12 justify-content-center" v-if="loadingNewPass">
                                        <button type="button" disabled  class="btn btn-success"> 
                                            <i  style="color: #fff" class="fa fa-spinner fa-spin fa-1x fa-fw"></i>
                                            <span class="sr-only">Loading...</span>  Enregistrement
                                        </button>
                                    </div>
                                    <div class="col-sm-12 justify-content-center" v-else>
                                        <span><span class="btn btn-primary" @click="PreviousHHStep"><i class="bi bi-arrow-left-short"></i> Retour</span></span> &nbsp;
                                        <button type="submit"  @click.prevent="submitNewPass" class="btn btn-success">Enregistrer </button>
                                    </div>
                                </div>
                                </div>


                                <div class="row mt-3 align-items-center">
                                    <div class="col-sm-12 text-center">
                                        <span>Si vous  disposez déjà d'un compte,&nbsp;<a href="/auth/login"><u>Se connecter</u></a></span>
                                    </div>
                                </div>

                            </form>
                            <hr>
                            <div class="mt-3 text-center">
                                <p>Se connecter par le canal des réseaux sociaux</p>
                                <ul class="list-unstyled d-sm-flex mt-3 justify-content-center">
                                    <li class="mx-2">
                                        <a href="#" class="btn bg-facebook d-inline-block"><i class="fab fa-facebook-f me-2"></i> &nbsp; Facebook</a>
                                    </li>
                                    <li class="mx-2">
                                        <a href="#" class="btn bg-google d-inline-block"><i class="fab fa-google me-2"></i> &nbsp; Google</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="overflow-hidden" v-else-if="dataReady == 1">
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
                        <h2 class=" text-primary">Vous êtes déjà connecté.</h2>
                        <p>Vous n'avez plus besoin de vous connecter encore.</p>
                        <a href="/admin/dashboard" v-if="meProfileRoleName === 'Administrateur'" class="btn btn-danger-soft mt-3">Tableau de bord.<i class="fas fa-long-arrow-alt-right me-3"></i></a>
                        <a href="/pub/dashboard" v-else-if="meProfileRoleName === 'Publicateur d\' articles'" class="btn btn-danger-soft mt-3">Tableau de bord.</a>
                        <a href="/" v-else-if="meProfileRoleName === 'Visiteur'" class="btn btn-danger-soft mt-3"><i class="fas fa-long-arrow-alt-left me-3"></i>Page d'accueil.</a>
                    </div>
                </div>
            </div>
        </section>
        <!-- =======================Inner intro END -->
    </main>
    <!-- **************** MAIN CONTENT END **************** -->
</template> 
