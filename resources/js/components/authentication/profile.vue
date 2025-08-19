<script setup>
import { ref, computed, onMounted } from 'vue';
import { useStore } from 'vuex';
import Swal from 'sweetalert2';

import {VueTelInput} from 'vue-tel-input';

const store = useStore();
const dataReady = ref(0)
const meRoleName = ref(null)
const meProfileUpdateData = ref({})
const meProfileUpdateMessage = ref(null)
const meProfileUpdateErrors= ref([])
const mePasswordUpdateData = ref({})
const mePasswordUpdateMessage = ref(null)
const mePasswordUpdateErrors= ref([])
const old_password = ref(null)
const new_password = ref(null)
const new_password_confirm = ref(null)
const showOPsw = ref(false)
const showNPsw = ref(false)
const showOPswC = ref(false) 
const meProfileData = ref({
    nom: null,
    prenoms: null,
    email: null,
    authorName: null,
    username: null,
    telephone: null,
    address: null,
    description: null
})
const meProfileErrors = ref([])
const meProfileMessage = ref(null) 
const meProfileUserName = ref(null)
const meProfileRoleName = ref(null)
const authSectionModal =  ref('LIST')
const username = ref(null)
const password = ref(null)
const password_confirm = ref(null)
const loadingLogin = ref(false)
const errorLogin = ref(false)
const errorsLogin = ref([])
const loadingConnect = ref(false)
const remember_me = ref(false)
const authSectionStepModal = ref(1)
const loadingForgotPasswordFirst = ref(false)
const loadingResendOTP = ref(false)
const errorForgotPasswordFirst = ref(false)
const errorsForgotPasswordFirst = ref([])
const loadingForgotPasswordTwo = ref(false)
const errorForgotPasswordTwo = ref(false)
const errorsForgotPasswordTwo = ref([])
const loadingForgotPasswordThree = ref(false)
const errorForgotPasswordThree = ref(false)
const errorsForgotPasswordThree = ref([])
const step = ref(1)
const nom = ref(null)
const prenoms = ref(null)
const authorName = ref(null)
const email = ref(null)
const telephone = ref(null)
const address = ref(null)
const description = ref("Nous tenons à rappeler aux visiteurs du site que sans partenariat avec togoactualite.com, la reprise des articles même partielle est strictement interdite.Tout contrevenant s'expose à de graves poursuites.")
const slug = ref(null)
const loadingUpdate = ref(false)
const loadingUpdatePassword = ref(false)
const showPsw = ref(false)
const showPswC = ref(false)
const loadingShow = ref(true)
const empty = ref(2)

    const authFormModalMeProfileUpdateClose =() =>{
        errorForgotPasswordFirst.value = null
        errorsForgotPasswordFirst.value = []
        errorForgotPasswordTwo.value = null
        errorsForgotPasswordTwo.value = []
        errorForgotPasswordThree.value = null
        errorsForgotPasswordThree.value = []
        errorLogin.value = false
        errorsLogin.value = []
        meProfileUpdateMessage.value =  null
        meProfileUpdateErrors.value = []
        $('#authFormModalMeProfileUpdate').modal('hide');
    }

    const showPassword = () =>{
        var x = document.getElementById("psw-input");
        if (x.type === "password") {
            x.type = "text";
            showPsw.value = true
        } else {
            x.type = "password";
            showPsw.value = false
        }
    }

    const showPasswordC = () =>{
        var x = document.getElementById("psw-input_c");
        if (x.type === "password") {
            x.type = "text";
            showPswC.value = true
        } else {
            x.type = "password";
            showPswC.value = false
        }
    }

    const showOPassword = () =>{
        var x = document.getElementById("psw-input_o");
        if (x.type === "password") {
            x.type = "text";
            showOPsw.value = true
        } else {
            x.type = "password";
            showOPsw.value = false
        }
    }

    const showNPassword = () =>{
        var x = document.getElementById("psw-input_n");
        if (x.type === "password") {
            x.type = "text";
            showNPsw.value = true
        } else {
            x.type = "password";
            showNPsw.value = false
        }
    }

    const showOPasswordC = () =>{
        var x = document.getElementById("psw-input_c_o");
        if (x.type === "password") {
            x.type = "text";
            showOPswC.value = true
        } else {
            x.type = "password";
            showOPswC.value = false
        }
    }

    const authFormModalMeProfileUpdateShow = (action_auth="SHOW") =>{

        if(localStorage.getItem('remember_me') == "true" && localStorage.getItem('username') && localStorage.getItem('password')){

            username.value = localStorage.getItem('username')

            password.value = localStorage.getItem('password')

            remember_me.value = localStorage.getItem('remember_me')
        }

        errorForgotPasswordFirst.value = null
        errorsForgotPasswordFirst.value = []
        errorForgotPasswordTwo.value = null
        errorsForgotPasswordTwo.value = []
        errorForgotPasswordThree.value = null
        errorsForgotPasswordThree.value = []
        errorLogin.value = false
        errorsLogin.value = []

        loadingUpdate.value = false

        if(authSectionModal == "SHOW"){

            loadingShow.value = false

        }else if(authSectionModal == "UPDATE"){

            loadingUpdate.value = false

        }

        $('#authFormModalMeProfileUpdate').modal('show');
    }

    const cancel = () => {
        old_password.value = null
        new_password.value = null
        new_password_confirm.value = null
        mePasswordUpdateMessage.value = null
        mePasswordUpdateErrors.value = []
    }

    const PreviousForgotPasswordStep = () =>{
        step.value  = 1
        errorForgotPasswordFirst.value  = null
        errorsForgotPasswordFirst.value  = []
        errorForgotPasswordTwo.value  = null
        errorsForgotPasswordTwo.value  = []
        errorForgotPasswordThree.value  = null
        errorsForgotPasswordThree.value  = []
        errorLogin.value  = false
        errorsLogin.value  = []
        meProfileUpdateMessage.value  =  null
        meProfileUpdateErrors.value  = []
    }

    const PreviousHForgotPasswordStep = () =>{
        step.value =  2
        errorForgotPasswordFirst.value =  null
        errorsForgotPasswordFirst.value =  []
        errorForgotPasswordTwo.value =  null
        errorsForgotPasswordTwo.value =  []
        errorForgotPasswordThree.value =  null
        errorsForgotPasswordThree.value =  []
        errorLogin.value =  false
        errorsLogin.value =  []
        meProfileUpdateMessage.value =   null
        meProfileUpdateErrors.value =  []
    }

    const changeAuthSectionStepModalToForgotPassword = () =>{
        authSectionStepModal.value = 2
        errorForgotPasswordFirst.value = null
        errorsForgotPasswordFirst.value = []
        errorForgotPasswordTwo.value = null
        errorsForgotPasswordTwo.value = []
        errorForgotPasswordThree.value = null
        errorsForgotPasswordThree.value = []
        errorLogin.value = false
        errorsLogin.value = []
        meProfileUpdateMessage.value =  null
        meProfileUpdateErrors.value = []
    }

   const changeAuthSectionStepModalToLogin = () => {
        authSectionStepModal.value = 1
        errorForgotPasswordFirst.value = null
        errorsForgotPasswordFirst.value = []
        errorForgotPasswordTwo.value = null
        errorsForgotPasswordTwo.value = []
        errorForgotPasswordThree.value = null
        errorsForgotPasswordThree.value = []
        errorLogin.value = false
        errorsLogin.value = []
        meProfileUpdateMessage.value =  null
        meProfileUpdateErrors.value = []
    }

    const meProfile = async () => { 

        loadingShow.value =  true

        authSectionModal.value =  'SHOW'

        await store.dispatch('meProfile/getMeProfile');
        const gettersMeProfileData =  store.getters['meProfile/getMeProfileData']; 
        const gettersMeProfileStatus =  store.getters['meProfile/getMeProfileStatus'];

        if( gettersMeProfileStatus ==="success"){

            meProfileData.value =  gettersMeProfileData 

            meProfileUpdateMessage.value =   null

            meProfileUpdateErrors.value =  [] 

            empty.value =  0

            dataReady.value =  1

            loadingShow.value =  false

        }else{

            empty.value =  1

            dataReady.value =  1

            meProfileMessage.value =  gettersMeProfileMessage

            loadingShow.value =  false

        }
    }

const show = async () =>{

    loadingShow.value =   true

    authSectionModal.value =   'SHOW'

    if(localStorage.getItem('access_token') && localStorage.getItem('nbRsp')){

        await store.dispatch('roleSecurity/getMeRole', {});

        const gettersRoleStatus =  store.getters['roleSecurity/getRoleStatus'];
        const gettersMeRoleName =  store.getters['roleSecurity/getMeRoleName'];

        if(gettersRoleStatus === 'success'){

            meRoleName.value =   gettersMeRoleName 

            if((meRoleName.value == localStorage.getItem('nbRsp')) && (localStorage.getItem('nbRsp') === '&nbtsd!?')){

                
                meProfile()

            }else{

                dataReady.value =  2

            }

        }else if(gettersRoleStatus === 'failed'){

            dataReady.value =   3;
        }

    }else{

        dataReady.value =   4;

    }

}
const update = async () =>{

    loadingUpdate.value = true

    authSectionModal.value = 'UPDATE'

    if(localStorage.getItem('access_token') && localStorage.getItem('nbRsp')){

        await store.dispatch('roleSecurity/getMeRole', {});

        const gettersRoleStatus =  store.getters['roleSecurity/getRoleStatus'];
        const gettersMeRoleName =  store.getters['roleSecurity/getMeRoleName'];

        if(gettersRoleStatus === 'success'){

            meRoleName.value = gettersMeRoleName

            if((meRoleName.value == localStorage.getItem('nbRsp')) && (localStorage.getItem('nbRsp') === '&nbtsd!?')){

                meProfileUpdate() 

            }else{

                authFormModalMeProfileUpdateShow('UPDATE')

            }

        }else if(gettersRoleStatus === 'failed'){

            authFormModalMeProfileUpdateShow('UPDATE')
        }

    }else{

        authFormModalMeProfileUpdateShow('UPDATE')

    }

}

const meProfileUpdate = async () =>{

    loadingUpdate.value  = true

    authSectionModal.value = 'UPDATE'

    meProfileUpdateMessage.value  =  null
    meProfileUpdateErrors.value  = []

    await store.dispatch('meProfile/updateMeProfile', { nom : meProfileData.value.nom, prenoms : meProfileData.value.prenoms, authorName : meProfileData.value.authorName, username : meProfileData.value.username,email : meProfileData.value.email, address : meProfileData.value.address, telephone : meProfileData.value.telephone, description : meProfileData.value.description});
    
    const getterInfosMeProfileUpdateStatus =  store.getters['meProfile/getMeProfileUpdateStatus'];
    const getterInfosMeProfileUpdateData =  store.getters['meProfile/getMeProfileUpdateData'];
    const getterInfosMeProfileUpdateErrors =  store.getters['meProfile/getMeProfileUpdateErrors'];
    const getterInfosMeProfileUpdateMessage =  store.getters['meProfile/getMeProfileUpdateMessage'];

    if( getterInfosMeProfileUpdateStatus ==="success"){

        meProfileUpdateData.value = getterInfosMeProfileUpdateData

        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: getterInfosMeProfileUpdateMessage,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        loadingUpdate.value = false

        meProfileUpdateMessage.value =  null
        meProfileUpdateErrors.value = []

    }else if( getterInfosMeProfileUpdateStatus ==="empty"){

        meProfileUpdateMessage.value = getterInfosMeProfileUpdateMessage

        meProfileUpdateErrors.value = getterInfosMeProfileUpdateErrors

        loadingUpdate.value = false


    }else if( getterInfosMeProfileUpdateStatus ==="error"){

        meProfileUpdateMessage.value = getterInfosMeProfileUpdateMessage

        meProfileUpdateErrors.value = []

        loadingUpdate.value = false

    }
}

const updatePassword = async () =>{

    loadingUpdatePassword.value = true

    authSectionModal.value = 'UPDATEPASSWORD'

    if(localStorage.getItem('access_token') && localStorage.getItem('nbRsp')){

        await store.dispatch('roleSecurity/getMeRole', {});

        const gettersRoleStatus =  store.getters['roleSecurity/getRoleStatus'];
        const gettersMeRoleName =  store.getters['roleSecurity/getMeRoleName'];

        if(gettersRoleStatus === 'success'){

            meRoleName.value = gettersMeRoleName

            if((meRoleName.value == localStorage.getItem('nbRsp')) && (localStorage.getItem('nbRsp') === '&nbtsd!?')){

                mePasswordUpdate()

            }else{

                authFormModalMeProfileUpdateShow('UPDATEPASSWORD')

            }

        }else if(gettersRoleStatus === 'failed'){

            authFormModalMeProfileUpdateShow('UPDATEPASSWORD')
        }

    }else{

        authFormModalMeProfileUpdateShow('UPDATEPASSWORD')

    }

}

const mePasswordUpdate = async () =>{

    loadingUpdatePassword.value = true

    authSectionModal.value = 'UPDATEPASSWORD'

    mePasswordUpdateMessage.value =  null
    mePasswordUpdateErrors.value = []

    await store.dispatch('meProfile/updateMePassword', { password : old_password.value, new_password : new_password.value, new_password_confirm : new_password_confirm.value });
    const gettersInfosMePasswordUpdateStatus =  store.getters['meProfile/getMePasswordUpdateStatus'];
    const gettersInfosMePasswordUpdateData =  store.getters['meProfile/getMePasswordUpdateData'];
    const gettersInfosMePasswordUpdateErrors =  store.getters['meProfile/getMePasswordUpdateErrors'];
    const gettersInfosMePasswordUpdateMessage =  store.getters['meProfile/getMePasswordUpdateMessage'];
    
    if( gettersInfosMePasswordUpdateStatus ==="success"){

        mePasswordUpdateData.value = gettersInfosMePasswordUpdateData

       Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: gettersInfosMePasswordUpdateMessage,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
      });

        loadingUpdate.value = false

        mePasswordUpdateMessage.value =  null
        mePasswordUpdateErrors.value = []

        logout()

    }else if( gettersInfosMePasswordUpdateStatus ==="empty"){

        mePasswordUpdateMessage.value = gettersInfosMePasswordUpdateMessage

        mePasswordUpdateErrors.value = gettersInfosMePasswordUpdateErrors

        loadingUpdatePassword.value = false


    }else if( gettersInfosMePasswordUpdateStatus ==="error"){

        mePasswordUpdateMessage.value = gettersInfosMePasswordUpdateMessage

        mePasswordUpdateErrors.value = []

        loadingUpdatePassword.value = false

    }
}    
const logout = async () => {
  await store.dispatch('logout/getLogoutApi');
  const getterLogoutStatus =  store.getters['logout/getLogoutStatus'];
  const getterLogoutMessage =  store.getters['logout/getLogoutMessage'];
  if (getterLogoutStatus === 'success') {
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'success',
      title: getterLogoutMessage,
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true
    });
    const  clearToken = {
      access_token: null,
      expires_in: null
    }

    store.getters["login/getAuthData"].access_token = clearToken.access_token
    store.getters["login/getAuthData"].expires_in = clearToken.expires_in

    window.location = '/auth/login' 
  }
};

const submitVerifyForgotPasswordEmail = async () => {
    loadingForgotPasswordFirst.value = true
    errorForgotPasswordFirst.value = null
    errorsForgotPasswordFirst.value = [] 
    await store.dispatch('forgot_password/sendOtpForgotPassword', {email: email.value});
    const gettersSendOtpForgotPasswordStatus =  store.getters['forgot_password/getSendOtpForgotPasswordStatus'];
    const gettersSendOtpForgotPasswordErrors =  store.getters['forgot_password/getSendOtpForgotPasswordErrors'];
    const gettersSendOtpForgotPasswordMessage =  store.getters['forgot_password/getSendOtpForgotPasswordMessage'];

    if(gettersSendOtpForgotPasswordStatus === 'success'){

        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: gettersSendOtpForgotPasswordMessage,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        errorForgotPasswordFirst.value = null
        errorsForgotPasswordFirst.value = []

        loadingForgotPasswordFirst.value = false

        step = 2

    }else if(gettersSendOtpForgotPasswordStatus === 'failed'){

        errorsForgotPasswordFirst.value = gettersSendOtpForgotPasswordMessage

        errorsForgotPasswordFirst.value = []

        loadingForgotPasswordFirst.value = false

    }else if(gettersSendOtpForgotPasswordStatus === 'error'){

        errorForgotPasswordFirst.value = gettersSendOtpForgotPasswordMessage

        errorsForgotPasswordFirst.value = gettersSendOtpForgotPasswordErrors

        loadingForgotPasswordFirst.value = false
    }

    loadingForgotPasswordFirst = false
}
const submitResendOtp = async () =>{
    loadingResendOTP.value = true
    errorForgotPasswordFirst.value = null
    errorsForgotPasswordFirst.value = [] 
    await store.dispatch('forgot_password/sendOtpForgotPassword', {email: email.value});
    const gettersSendOtpForgotPasswordStatus =  store.getters['forgot_password/getSendOtpForgotPasswordStatus'];
    const gettersSendOtpForgotPasswordErrors =  store.getters['forgot_password/getSendOtpForgotPasswordErrors'];
    const gettersSendOtpForgotPasswordMessage =  store.getters['forgot_password/getSendOtpForgotPasswordMessage'];

    if(gettersSendOtpForgotPasswordStatus === 'success'){

        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: gettersSendOtpForgotPasswordMessage,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        errorForgotPasswordFirst.value = null
        errorsForgotPasswordFirst.value = []

        loadingResendOTP.value = false

        step.value = 2

    }else if(gettersSendOtpForgotPasswordStatus === 'failed'){

        errorForgotPasswordFirst.value = gettersSendOtpForgotPasswordMessage

        errorsForgotPasswordFirst.value = []

        loadingResendOTP.value = false

    }else if(gettersSendOtpForgotPasswordStatus === 'error'){

        errorForgotPasswordFirst.value = gettersSendOtpForgotPasswordMessage

        errorsForgotPasswordFirst.value = gettersSendOtpForgotPasswordErrors

        loadingResendOTP.value = false
    }

    loadingResendOTP.value = false
}

const submitVerifyForgotPasswordOtp = async () =>{
    loadingForgotPasswordTwo.value = true
    errorForgotPasswordTwo.value = null
    errorsForgotPasswordTwo.value = []
    await store.dispatch('forgot_password/checkOtpForgotPassword', {email: email.value, otp: otp.value});
    const gettersCheckOtpForgotPasswordStatus =  store.getters['forgot_password/getCheckOtpForgotPasswordStatus'];
    const gettersCheckOtpForgotPasswordErrors =  store.getters['forgot_password/getCheckOtpForgotPasswordErrors'];
    const gettersCheckOtpForgotPasswordMessage =  store.getters['forgot_password/getCheckOtpForgotPasswordMessage'];

    if(gettersCheckOtpForgotPasswordStatus === 'success'){

        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: gettersCheckOtpForgotPasswordMessage,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        errorForgotPasswordTwo.value = null
        errorsForgotPasswordTwo.value = []

        loadingForgotPasswordTwo.value = false

        step.value = 3

    }else if(gettersCheckOtpForgotPasswordStatus === 'failed'){

        errorForgotPasswordTwo.value = gettersCheckOtpForgotPasswordMessage

        errorsForgotPasswordTwo.value = []

        loadingForgotPasswordTwo.value = false

    }else if(gettersCheckOtpForgotPasswordStatus === 'error'){

        errorForgotPasswordTwo.value = gettersCheckOtpForgotPasswordMessage

        errorsForgotPasswordTwo.value = gettersCheckOtpForgotPasswordErrors

        loadingForgotPasswordTwo.value = false
    }
    loadingForgotPasswordTwo.value = false
}

const submitForgotPasswordNewPass = async()=>{
    loadingForgotPasswordThree.value = true
    errorForgotPasswordThree.value = null
    errorsForgotPasswordThree.value = [] 

    await store.dispatch('forgot_password/newPassOtpForgotPassword', {email:email.value, otp :otp.value, password :password.value ,password_confirm :password_confirm.value});
    const gettersNewPassOtpForgotPasswordStatus =  store.getters['forgot_password/getNewPassOtpForgotPasswordStatus'];
    const gettersNewPassOtpForgotPasswordErrors =  store.getters['forgot_password/getNewPassOtpForgotPasswordErrors'];
    const gettersNewPassOtpForgotPasswordMessage =  store.getters['forgot_password/getNewPassOtpForgotPasswordMessage'];

    if(gettersNewPassOtpForgotPasswordStatus === 'success'){

        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: gettersNewPassOtpForgotPasswordMessage,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        errorForgotPasswordThree.value = null
        errorsForgotPasswordThree.value = []

        authSectionStepModal.value = 1

    }else if(gettersNewPassOtpForgotPasswordStatus === 'failed'){

        errorForgotPasswordThree.value = gettersNewPassOtpForgotPasswordMessage

        errorsForgotPasswordThree.value = []

        loadingForgotPasswordThree.value = false

    }else if(gettersNewPassOtpForgotPasswordStatus === 'error'){

        errorForgotPasswordThree.value = gettersNewPassOtpForgotPasswordMessage

        errorsForgotPasswordThree.value = gettersNewPassOtpForgotPasswordErrors

        loadingForgotPasswordThree.value = false
    }
    loadingForgotPasswordThree.value = false

}

const submitLogin = async () => {
  loadingLogin.value = true;
  errorLogin.value = false;
  errorsLogin.value = [];
  
  // Action de connexion
  await store.dispatch('login/login',{
    username: username.value,
    password: password.value,
    remember_me: remember_me.value,
  });

  const getterLoginStatus = store.getters['login/getLoginStatus'];
  const getterLoginMessage = store.getters['login/getLoginMessage'];
  const getterLoginErrors = store.getters['login/getLoginErrors'];

  if (getterLoginStatus === 'success admin') {
        errorsLogin.value = []

        errorLogin.value = false

        if(remember_me.value){

            localStorage.setItem('username', username.value )

            localStorage.setItem('password', password.value)

            localStorage.setItem('remember_me', true)

            loadingLogin.value = false

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

            Toast.fire({
                icon: 'success',
                title: getterLoginMessage,
            });

            if(authSectionModal.value== 'SHOW'){

                authFormModalMeProfileUpdateClose()

                loadingShow.value = true

                meProfile()

            }else if(authSectionModal.value== 'UPDATE'){


                authFormModalMeProfileUpdateClose()

                loadingUpdate.value = true

                meProfileUpdate()

            }else if(authSectionModal.value== 'UPDATEPASSWORD'){

                authFormModalMeProfileUpdateClose()

                loadingUpdatePassword.value = true

                mePasswordUpdate()
            }

        }else{

            localStorage.setItem('remember_me', false)

            loadingLogin.value = false

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

            Toast.fire({
                icon: 'success',
                title: getterLoginMessage,
            });

            if(authSectionModal== 'SHOW'){

                authFormModalMeProfileUpdateClose()

                loadingShow.value = true

                meProfile()

            }else if(authSectionModal.value== 'UPDATE'){

                authFormModalMeProfileUpdateClose()

                loadingUpdate.value = true

                meProfileUpdate()

            }else if(authSectionModal.value== 'UPDATEPASSWORD'){

                authFormModalMeProfileUpdateClose()

                loadingUpdatePassword.value = true

                mePasswordUpdate()
            }

        }

    }else if(getterLoginStatus === 'success pub'){

        if(remember_me.value){

            localStorage.setItem('username', this.username )

            localStorage.setItem('password', this.password)

            localStorage.setItem('remember_me', true)

            loadingLogin.value = false

            window.location = '/pub/dashboard'


        }else{

            localStorage.setItem('remember_me', false)

            loadingLogin.value = false

            window.location = '/pub/dashboard'
        }


    }else if(getterLoginStatus === 'success visitor'){

        if(remember_me.value){

            localStorage.setItem('username', username.value )

            localStorage.setItem('password', password.value)

            localStorage.setItem('remember_me', true)

            loadingLogin.value = false

            window.location = '/'


        }else{

            localStorage.setItem('remember_me', false)

            loadingLogin.value = false

            window.location = '/'
        }

    
    
    } else if (getterLoginStatus === 'failed') {
        errorLogin.value = getterLoginMessage;
        errorsLogin.value = []; 
        loadingLogin.value = false;
    }else if (getterLoginStatus === 'error') {
        errorLogin.value = getterLoginMessage;
        errorsLogin.value = getterLoginErrors;
        loadingLogin.value = false; 
    } 
}

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
                            <h3 class="mb-2 mb-sm-0 ">Modifier un profil  </h3> 
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    <!-- Left sidebar START -->
                    <div class="col-lg-12 col-xxl-8">
                        <!-- Profile START -->
                        <div class="card border mb-4">
                            <div class="card-header border-bottom p-3">
                                <h5 class="card-header-title mb-0">Modifier un auteur</h5>
                            </div>
                            <div class="card-body" v-if="empty == 0">

                                <div v-if="meProfileUpdateMessage">
                                    <div class="alert alert-danger"  role="alert">
                                        {{ meProfileUpdateMessage }}
                                    </div>
                                </div>
                                <form>
                                  <!-- Full name -->
                                  <div class="mb-3"  v-if="meProfileUpdateErrors.nom || meProfileUpdateErrors.prenoms">
                                    <label class="form-label">Nom Complet <sup class="text-danger">*</sup></label>
                                    <div class="input-group">
                                      <input type="text" class="form-control is-invalid" v-model="meProfileData.nom" name="nom" placeholder="Entrez votre nom">
                                      <input type="text" class="form-control is-invalid" v-model="meProfileData.prenoms"  name="prenoms" placeholder="Entrez votre prénom">
                                    </div>
                                    <div v-for="errornom in meProfileUpdateErrors.nom" :key="errornom" class="text-danger">
                                        {{ errornom }}
                                    </div>
                                    <div v-for="errorprenoms in meProfileUpdateErrors.prenoms" :key="errorprenoms" class="text-danger">
                                        {{ errorprenoms }}
                                    </div>
                                  </div>
                                  <div class="mb-3" v-else>
                                    <label class="form-label">Nom Complet <sup class="text-danger">*</sup></label>
                                    <div class="input-group">
                                      <input type="text" class="form-control" v-model="meProfileData.nom" name="nom" placeholder="Entrez votre nom">
                                      <input type="text" class="form-control" v-model="meProfileData.prenoms"  name="prenoms" placeholder="Entrez votre prénom">
                                    </div>
                                  </div>
                                  <!-- Username -->
                                  <div class="row">
                                    <div class="col-lg-6">
                                      <div class="mb-3" v-if="meProfileUpdateErrors.authorName">
                                        <label class="form-label">Nom d'auteur</label>
                                        <div class="input-group">
                                          <span class="input-group-text">togoactualite.com/authors/</span>
                                          <input type="text" class="form-control is-invalid" v-model="meProfileData.authorName" name="authorName" placeholder="Entrez votre nom d'auteur">
                                        </div>
                                        <div v-for="errorname in meProfileUpdateErrors.authorName" :key="errorname" class="text-danger">
                                            {{ errorname }}
                                        </div>
                                      </div>
                                      <div class="mb-3" v-else>
                                        <label class="form-label">Nom d'auteur</label>
                                        <div class="input-group">
                                          <span class="input-group-text">togoactualite.com/authors/</span>
                                          <input type="text" class="form-control" name="authorName" v-model="meProfileData.authorName" placeholder="Entrez votre nom d'auteur">
                                        </div>
                                        <div class="form-text">Par defaut le nom d'auteur sera son prénom.</div>
                                      </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3" v-if="meProfileUpdateErrors.username">
                                            <label class="form-label">Nom d'utilisateur</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control is-invalid" name="username" v-model="meProfileData.username" placeholder="Entrez votre nom d'utilisateur">
                                            </div>
                                            <div v-for="errorusername in meProfileUpdateErrors.username" :key="errorusername" class="text-danger">
                                                {{ errorusername }}
                                            </div>
                                        </div>
                                        <div class="mb-3" v-else>
                                            <label class="form-label">Nom d'utilisateur</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="username" v-model="meProfileData.username" placeholder="Entrez votre nom d'utilisateur">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3" v-if="meProfileUpdateErrors.email">
                                            <label class="form-label">Email <sup class="text-danger">*</sup></label>
                                            <div class="input-group">
                                                <input type="email" class="form-control is-invalid" name="email" v-model="meProfileData.email" placeholder="Entrez votre email">
                                            </div>
                                            <div v-for="erroremail in meProfileUpdateErrors.email" :key="erroremail" class="text-danger">
                                                {{ erroremail }}
                                            </div>
                                        </div>
                                        <div class="mb-3" v-else>
                                            <label class="form-label">Email <sup class="text-danger">*</sup></label>
                                            <div class="input-group">
                                                <input type="email" class="form-control" name="email" v-model="meProfileData.email" placeholder="Entrez votre email">
                                            </div>
                                        </div>
                                    </div>

                                  </div>
                                  <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3" v-if="meProfileUpdateErrors.telephone">
                                            <label class="form-label">Télephone</label>
                                            <div class="input-group">
                                                <vue-tel-input  class="form-control is-invalid" mode="international" v-model="meProfileData.telephone"></vue-tel-input>
                                            </div>
                                            <div v-for="errortelephone in meProfileUpdateErrors.telephone" :key="errortelephone" class="text-danger">
                                                {{ errortelephone }}
                                            </div>
                                        </div>
                                        <div class="mb-3" v-else>
                                            <label class="form-label">Télephone</label>
                                            <div class="input-group">
                                                <vue-tel-input  class="form-control " mode="international"  v-model="meProfileData.telephone"></vue-tel-input>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3" v-if="meProfileUpdateErrors.address">
                                            <label class="form-label">Adresse</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-lg is-invalid" name="address" v-model="meProfileData.address" placeholder="Entrez votre Adresse">
                                            </div>
                                            <div v-for="erroraddress in meProfileUpdateErrors.address" :key="erroraddress" class="text-danger">
                                                {{ erroraddress }}
                                            </div>
                                        </div>
                                        <div class="mb-3" v-else>
                                            <label class="form-label">Adresse</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-lg" name="address" v-model="meProfileData.address" placeholder="Entrez votre Adresse">
                                            </div>
                                        </div>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-lg-12">
                                      <div class="mb-3" v-if="meProfileUpdateErrors.description">
                                        <label class="form-label">Description</label>
                                        <textarea v-model="meProfileData.description" class="form-control is-invalid" placeholder='Redigez le contenu de votre description!' rows="3" ></textarea>
                                        <div v-for="errordescription in meProfileUpdateErrors.description" :key="errordescription" class="text-danger">
                                            {{ errordescription }}
                                        </div>
                                      </div>
                                      <div class="mb-3" v-else>
                                        <label class="form-label">Description</label>
                                        <textarea v-model="meProfileData.description" class="form-control" placeholder='Redigez le contenu de votre description!' rows="3" ></textarea>
                                        <div class="form-text">Une brève description pour le profil de l'auteur.</div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="d-flex justify-content-end mt-4" v-if="!loadingUpdate"> 
                                    <button type="submit" @click="update" class="btn btn-primary">Modifier</button>
                                  </div>
                                  <div class="d-flex justify-content-end mt-4" v-else>
                                    <button type="button" disabled class="btn btn-success border-0 me-2">
                                        <i  style="color: #fff" class="fa fa-spinner fa-spin fa-1x fa-fw"></i>
                                        <span class="sr-only">Loading...</span>Modification...</button>
                                  </div>
                                </form>

                            </div>
                            <div class="card-body p-3 pb-0" v-else-if="empty == 1">
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                      <div style="position: relative; height: 250px;">
                                          <img :src="`/assets/images/empty.png`" style="width: 100px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);" alt="empty">
                                      </div>
                                      <h5 style="text-align: center; margin-top: -50px"> {{ meProfileMessage  }} </h5>
                                    </div>
                                    <div class="col-md-3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-xxl-8"><!-- Update password START -->
                        <div class="card border">
                          <div class="card-header border-bottom p-3">
                            <h5 class="card-header-title mb-0">Modifier votre mot de passe</h5>
                          </div>
                          <div class="card-body">
                            <div v-if="mePasswordUpdateMessage">
                                <div class="alert alert-danger"  role="alert">
                                    {{ mePasswordUpdateMessage }}
                                </div>
                            </div>
                            <form>
                              <!-- Full name -->
                              <div class="mb-3"  v-if="mePasswordUpdateErrors.password"> 
                                <label class="form-label"  for="exampleInputPasswordLoginInvalid">Mot de passe actuel</label>
                                <div class="input-group">
                                    <input v-model="old_password" name="password" class="form-control is-invalid fakepassword" type="password" id="psw-input_o" placeholder="*********">
                                    <span class="input-group-text p-0" @click="showOPassword" style="cursor: pointer">
                                        <i v-if="showOPsw == false" class="fakepasswordicon far fa-eye cursor-pointer p-2 w-40px"></i>
                                        <i v-else class="fakepasswordicon far fa-eye-slash cursor-pointer p-2 w-40px"></i>
                                    </span>
                                </div>
                                <div class="rounded mt-1" id="psw-strength"></div>
                              </div>
                              <div class="mb-3" v-else>
                                <label class="form-label"  for="exampleInputPasswordLoginInvalid">Mot de passe actuel</label>
                                <div class="input-group">
                                    <input v-model="old_password" name="password" class="form-control fakepassword" type="password" id="psw-input_o" placeholder="*********">
                                    <span class="input-group-text p-0" @click="showOPassword" style="cursor: pointer">
                                        <i v-if="showOPsw == false" class="fakepasswordicon far fa-eye cursor-pointer p-2 w-40px"></i>
                                        <i v-else class="fakepasswordicon far fa-eye-slash cursor-pointer p-2 w-40px"></i>
                                    </span>
                                </div>
                                <div class="rounded mt-1" id="psw-strength"></div>
                              </div>
                              <div class="mb-3"  v-if="mePasswordUpdateErrors.new_password"> 
                                <label class="form-label"  for="exampleInputPasswordLoginInvalid">Votre nouveau mot de passe</label>
                                <div class="input-group">
                                    <input v-model="new_password" name="new_password" class="form-control is-invalid fakepassword" type="password" id="psw-input_n" placeholder="*********">
                                    <span class="input-group-text p-0" @click="showNPassword" style="cursor: pointer">
                                        <i v-if="showNPsw == false" class="fakepasswordicon far fa-eye cursor-pointer p-2 w-40px"></i>
                                        <i v-else class="fakepasswordicon far fa-eye-slash cursor-pointer p-2 w-40px"></i>
                                    </span>
                                </div>
                                <div class="rounded mt-1" id="psw-strength"></div>
                              </div>
                              <div class="mb-3" v-else>
                                <label class="form-label"  for="exampleInputPasswordLoginInvalid">Votre nouveau mot de passe</label>
                                <div class="input-group">
                                    <input v-model="new_password" name="new_password" class="form-control fakepassword" type="password" id="psw-input_n" placeholder="*********">
                                    <span class="input-group-text p-0" @click="showNPassword" style="cursor: pointer">
                                        <i v-if="showNPsw == false" class="fakepasswordicon far fa-eye cursor-pointer p-2 w-40px"></i>
                                        <i v-else class="fakepasswordicon far fa-eye-slash cursor-pointer p-2 w-40px"></i>
                                    </span>
                                </div>
                                <div class="rounded mt-1" id="psw-strength"></div>
                              </div>
                              <div class="mb-3"  v-if="mePasswordUpdateErrors.new_password_confirm"> 
                                <label class="form-label"  for="exampleInputPasswordLoginInvalid">Confirmez votre mot de passe</label>
                                <div class="input-group">
                                    <input v-model="new_password_confirm" name="new_password_confirm" class="form-control is-invalid fakepassword" type="password" id="psw-input_c_o" placeholder="*********">
                                    <span class="input-group-text p-0" @click="showOPasswordC" style="cursor: pointer">
                                        <i v-if="showOPswC == false" class="fakepasswordicon far fa-eye cursor-pointer p-2 w-40px"></i>
                                        <i v-else class="fakepasswordicon far fa-eye-slash cursor-pointer p-2 w-40px"></i>
                                    </span>
                                </div>
                                <div class="rounded mt-1" id="psw-strength"></div>
                              </div>
                              <div class="mb-3" v-else>
                                <label class="form-label"  for="exampleInputPasswordLoginInvalid">Confirmez votre mot de passe</label>
                                <div class="input-group">
                                    <input v-model="new_password_confirm" name="new_password_confirm" class="form-control fakepassword" type="password" id="psw-input_c_o" placeholder="*********">
                                    <span class="input-group-text p-0" @click="showOPasswordC" style="cursor: pointer">
                                        <i v-if="showOPswC == false" class="fakepasswordicon far fa-eye cursor-pointer p-2 w-40px"></i>
                                        <i v-else class="fakepasswordicon far fa-eye-slash cursor-pointer p-2 w-40px"></i>
                                    </span>
                                </div>
                                <div class="rounded mt-1" id="psw-strength"></div>
                              </div>
                              <div class="d-flex justify-content-end mt-4" v-if="!loadingUpdatePassword">
                                <button type="button" @click="cancel" class="btn btn-danger border-0 me-2">Annuler</button>
                                <button type="submit" @click="updatePassword" class="btn btn-primary">Modifier</button>
                              </div>
                              <div class="d-flex justify-content-end mt-4" v-else>
                                <button type="button" disabled class="btn btn-success border-0 me-2">
                                    <i  style="color: #fff" class="fa fa-spinner fa-spin fa-1x fa-fw"></i>
                                    <span class="sr-only">Loading...</span>Modification...</button>
                              </div>
                            </form>
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
                                <span style="cursor: pointer"  @click="authFormModalMeProfileUpdateShow('SHOW')" class="btn btn-danger-soft mt-3"><i class="fas fa-long-arrow-alt-left me-3"></i>Se connecter</span>

                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
     <!-- Modal Form -->
     <div class="modal fade" id="authFormModalMeProfileUpdate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">


            <!-- Login Form -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-if="authSectionStepModal == 1">Se connecter</h5>
                    <h5 class="modal-title" v-else-if="authSectionStepModal == 2">Modifier le mot de passe</h5>
                    <h5 class="modal-title" v-else-if="authSectionStepModal == 3">S'inscrire</h5>
                    <button type="button" class="btn-close" @click="authFormModalMeProfileUpdateClose" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                <label class="form-label" for="exampleInputPasswordLogin">Mot de passe</label>
                                <div class="input-group">
                                    <input v-model="password" name="password" class="form-control fakepassword is-invalid" type="password" id="psw-input" placeholder="*********">
                                    <span class="input-group-text p-0" @click="showPassword" style="cursor: pointer">
                                        <i v-if="showPsw == false" class="fakepasswordicon far fa-eye cursor-pointer p-2 w-40px"></i>
                                        <i v-else class="fakepasswordicon far fa-eye-slash cursor-pointer p-2 w-40px"></i>
                                    </span>
                                </div>
                                <div class="rounded mt-1" id="psw-strength"></div>
                                <div  v-for="errorPassword in errorsLogin.password" :key="errorPassword" class="invalid-feedback">
                                    {{ errorPassword }}
                                </div>
                            </div>
                            <div class="mb-3" v-else>
                                <label class="form-label"  for="exampleInputPasswordLoginInvalid">Mot de passe</label>
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
                            <div v-if="errorForgotPasswordFirst" class="mb-3">
                                <div class="alert alert-danger"  role="alert">
                                    {{ errorForgotPasswordFirst }}
                                </div>
                            </div>
                            <form >
                                <div class="mb-3" v-if="errorsForgotPasswordFirst.email">
                                    <input type="email" v-model="email" name="email" class="form-control is-invalid" id="exampleInputEmail1" placeholder="Veuillez entrer votre email">
                                    <div v-for="errorForgotPasswordemail in errorsForgotPasswordFirst.email" :key="errorForgotPasswordemail" class="invalid-feedback">
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
                            <div v-if="errorForgotPasswordTwo" class="mb-3">

                                <div class="alert alert-danger"  role="alert">
                                    {{ errorForgotPasswordTwo }}
                                </div>
                            </div>
                            <form>
                                <div class="mb-3" v-if="errorsForgotPasswordTwo.otp">

                                    <input type="text" v-model="otp" name="otp" class="form-control is-invalid" id="exampleInputOtp1" placeholder="Veuillez entrer votre code de vérification">
                                    <div v-for="errorForgotPasswordOtp in errorsForgotPasswordTwo.otp" :key="errorForgotPasswordOtp" class="invalid-feedback">
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
                            <div v-if="errorForgotPasswordThree" class="mb-3">
                                <div class="alert alert-danger"  role="alert">
                                    {{ errorForgotPasswordThree }}
                                </div>
                            </div>
                            <form>
                                <div class="mb-3" v-if="errorsForgotPasswordThree.password">
                                    <label class="form-label" for="exampleInputpassword1">Votre nouveau mot de passe</label>
                                    <div class="input-group">
                                        <input v-model="password" name="password" class="form-control fakepassword is-invalid" type="password" id="psw-input" placeholder="Veuillez entrer votre nouveau mot de passe .">
                                        <span class="input-group-text p-0" @click="showPassword" style="cursor: pointer">
                                            <i v-if="showPsw == false" class="fakepasswordicon far fa-eye cursor-pointer p-2 w-40px"></i>
                                            <i v-else class="fakepasswordicon far fa-eye-slash cursor-pointer p-2 w-40px"></i>
                                        </span>
                                    </div>
                                    <div class="rounded mt-1" id="psw-strength"></div>
                                    <div v-for="errorForgotPasswordpassword in errorsForgotPasswordThree.password" :key="errorForgotPasswordpassword" class="invalid-feedback">
                                        {{ errorForgotPasswordpassword }}
                                    </div>
                                </div>
                                <div class="mb-3" v-else>
                                    <label class="form-label" for="exampleInputpassword1">Votre nouveau mot de passe</label>
                                    <div class="input-group">
                                        <input v-model="password" name="password" class="form-control fakepassword" type="password" id="psw-input" placeholder="Veuillez entrer votre nouveau mot de passe .">
                                        <span class="input-group-text p-0" @click="showPassword" style="cursor: pointer">
                                            <i v-if="showPsw == false" class="fakepasswordicon far fa-eye cursor-pointer p-2 w-40px"></i>
                                            <i v-else class="fakepasswordicon far fa-eye-slash cursor-pointer p-2 w-40px"></i>
                                        </span>
                                    </div>
                                    <div class="rounded mt-1" id="psw-strength"></div>
                                </div>

                                <div class="mb-3" v-if="errorsForgotPasswordThree.password_confirm">
                                    <label class="form-label" for="exampleInputpassword1">Confirmer le mot de passe</label>
                                    <div class="input-group">
                                        <input type="password" v-model="password_confirm" class="form-control fakepassword is-invalid"  name="password_confirm"  id="psw-input_c" placeholder="Veuillez confirmer le mot de passe">
                                        <span class="input-group-text p-0" @click="showPasswordC" style="cursor: pointer">
                                            <i v-if="showPswC == false" class="fakepasswordicon far fa-eye cursor-pointer p-2 w-40px"></i>
                                            <i v-else class="fakepasswordicon far fa-eye-slash cursor-pointer p-2 w-40px"></i>
                                        </span>
                                    </div>
                                    <div class="rounded mt-1" id="psw-strength"></div>
                                    <div v-for="errorForgotPasswordpassword_confirm in errorsForgotPasswordThree.password_confirm" :key="errorForgotPasswordpassword_confirm" class="invalid-feedback">
                                        {{ errorForgotPasswordpassword_confirm }}
                                    </div>
                                </div>
                                <div class="mb-3" v-else>
                                    <label class="form-label" for="exampleInputpassword1">Confirmer le mot de passe</label>
                                    <div class="input-group">
                                        <input type="password" v-model="password_confirm" class="form-control fakepassword "  name="password_confirm"  id="psw-input_c" placeholder="Veuillez confirmer le mot de passe">
                                        <span class="input-group-text p-0" @click="showPasswordC" style="cursor: pointer">
                                            <i v-if="showPswC == false" class="fakepasswordicon far fa-eye cursor-pointer p-2 w-40px"></i>
                                            <i v-else class="fakepasswordicon far fa-eye-slash cursor-pointer p-2 w-40px"></i>
                                        </span>
                                    </div>
                                    <div class="rounded mt-1" id="psw-strength"></div>
                                </div>
                                <div class="row" v-if="loadingForgotPasswordThree">
                                    <div class="col-md-12">
                                        <button type="button" disabled class="btn btn-success  mx-auto w-100"> <i  style="color: #fff" class="fa fa-spinner fa-spin fa-1x fa-fw"></i>
                                            <span class="sr-only">Loading...</span>Modification
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