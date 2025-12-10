<script setup>
import { ref, onMounted } from 'vue'; 
import { useStore } from 'vuex';

import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

const store = useStore();
const loading = ref(false);
const data = ref({email: null});
const newsLettersErrors = ref({});
const newsLettersMessage = ref(null);

const saveNewsletter = async () => { 
    loading.value = true;

    newsLettersErrors.value = [];
    newsLettersMessage.value = null;

  await store.dispatch('newsletter/newsletterRequest', {email: data.value.email});

  const status = store.getters['newsletter/getNewsletterStatus'];
  const message = store.getters['newsletter/getNewsletterMessage'];
  const errors = store.getters['newsletter/getNewsletterErrors'];

  if (status === 'success') {

    loading.value = false
   
    Swal.fire({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        },
        icon: 'success',
        title: message
    })


    data.value.email = null

  } else {

    loading.value = false
    
    if (status === 'error') {

        newsLettersErrors.value = errors;
        newsLettersMessage.value = message;

        Swal.fire({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            },
            icon: 'error',
            title: message
        })

    }else{
        Swal.fire({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            },
            icon: 'error',
            title: message
        })
        newsLettersMessage.value = message;
        newsLettersErrors.value = [];
    }
  }
  loading.value = false;
}; 

const verifyCaptchaModalClose =  () => { 
    saveNewsletter();   
    $('#verifyCaptchaModal').modal('hide');
};
const verifyCaptchaModalOpen =  () => {    
    $('#verifyCaptchaModal').modal('show');
};
</script>
<template>
    <form class="input-group mt-2" >
        <input v-if="newsLettersErrors.email" required type="email" name="email" v-model="data.email" class="form-control is-invalid" placeholder="Entrez votre email " />
        <input v-else required type="email" name="email" v-model="data.email" class="form-control " placeholder="Entrez votre email " />
        <button type="submit"  class="btn btn-primary m-0" @click.prevent="verifyCaptchaModalOpen"  v-if="!loading"><i class="bi bi-send"></i></button>
        <button type="button"  class="btn btn-primary m-0" v-else>
            <i  style="color: #fff" class="fa fa-spinner fa-1x fa-spin fa-fw"></i>
            <span class="sr-only">Loading...</span>
        </button>
    </form>
    <div v-if="newsLettersErrors.email">
        <div v-for="errorEmail in newsLettersErrors.email" :key="errorEmail" class="form-text mt-2 text-white">
            {{ errorEmail }}
        </div>
    </div>
    <div v-else class="form-text mt-2 text-white">
        En vous abonnant, vous acceptez notre politique de confidentialité.
    </div>
    <div class="modal fade" id="verifyCaptchaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Verification CAPTCHA</h5>
                    <button type="button" class="btn-close" @click="verifyCaptchaModalClose" data-bs-dismiss="modal" aria-label="Close"></button>
                </div> 
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    
                </div>
                <div style="margin-top: -15px">
                    <p class="text-center"><span style="cursor: pointer;" @click="verifyCaptchaModalClose">Annuler</span></p>
                </div>
            </div>
        </div>
    </div>
</template>
