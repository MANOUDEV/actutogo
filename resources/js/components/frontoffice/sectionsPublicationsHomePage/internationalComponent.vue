<script setup>
import { ref, onMounted } from 'vue';
import moment from 'moment';
import { useStore } from 'vuex';
import { Splide, SplideSlide } from '@splidejs/vue-splide';
import '@splidejs/vue-splide/css';

import Swal from 'sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css';

const store = useStore();
const loading = ref(true);
const internationalFenetreSurLAfriqueSportsData = ref({});
const optionsOneInternationalFenetreSurLAfriqueSports = ref({ type : 'loop',
    autoplay: true,
    interval: 3000,
    perPage:2,
    pagination: false,
    breakpoints: {
        640: {
            perPage: 1,
            pagination:false
        },

    }});
const optionsOneFenetreSurLAfrique = ref({type : 'loop',
    autoplay: true,
    interval: 3000,
    breakpoints: {
        640: {
            perPage: 1,
            pagination:false
        },

    }});
const optionsOneSports= ref( {
    type : 'loop',
    autoplay: true,
    interval: 3000,
    perPage:4,
    pagination: false,
    breakpoints: {
        640: {
            perPage: 1,
            pagination:false
        },

    },
});
const internationalFenetreSurLAfriqueSportsMessage = ref(null);
const empty = ref(2);


const loadingN = ref(false);
const newsletterData = ref({email: null});
const newsletterErrors = ref({}); 
const newsletterMessage = ref(null)

const saveNewsletter = async () => { 
    loadingN.value = true;
    newsletterMessage.value = null;
    newsletterErrors.value = [];

    await store.dispatch('newsletter/newsletterRequest', {email: newsletterData.value.email});
 
    const status = store.getters['newsletter/getNewsletterStatus'];
    const message = store.getters['newsletter/getNewsletterMessage'];
    const errors = store.getters['newsletter/getNewsletterErrors'];
 
    if (status === 'success') {

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: 'success',
            title: message
        })

        newsletterData.value.email = null

    }else{
        if (status === 'error') {

            newsletterErrors.value = errors;
            newsletterErrors.value.email = message;
            
        }else{
  
            newsletterMessage.value = message;
        }

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: 'error',
            title: message
        })
    }

    
    loadingN.value = false;
}; 

const getResults = async () => {
  loading.value = true;
  await store.dispatch('sectionsPublicationsHomePage/internationalFenetreSurLAfriqueSportsDataRequest', {});

  const status = store.getters['sectionsPublicationsHomePage/getInfosInternationalFenetreSurLAfriqueSportsStatus'];
  const message = store.getters['sectionsPublicationsHomePage/getInfosInternationalFenetreSurLAfriqueSportsMessage'];
  const data = store.getters['sectionsPublicationsHomePage/getInfosInternationalFenetreSurLAfriqueSportsData'];

  if (status === 'success') {
    internationalFenetreSurLAfriqueSportsData.value = data;
    empty.value = 0;
  } else {
    internationalFenetreSurLAfriqueSportsMessage.value = message;
    empty.value = status === 'empty' ? 1 : 3;
  }
  loading.value = false;
};

const author = (slug) => {
  window.location = `/authors/${slug}`;
};

const article = (slug) => {
  window.location = `/${slug}`;
};

const getImage = (slug) => slug;

onMounted(() => {
  getResults();
});
</script>
<template>
    <div v-if="loading">
    </div>
    <div v-else class="container">

        <div class="row mb-4">
            <!-- Main Post START -->
            <div class="col-lg-8 mb-2">
                <!-- Entertainment START -->
                <div class="col-md-12 ">
                    <div class="d-flex mb-2">
                        <div>
                            <img :src="`/assets/images/Icones_formes/Icones-TAAsset-87.png`" class="alaune_logo" alt="Icones-TAAsset-87.png">
                        </div>
                        <div>&nbsp; &nbsp; &nbsp;</div>
                        <div>
                            <h2 class="text-success m-0 alaune_title">EDITORIAL</h2>
                            <p>Suivez nos actualités sur l'éditorial.</p>
                        </div>
                    </div>
                    <!-- Card item START -->

                    <div class="card border rounded-3 up-hover p-4 mt-2 mb-2"  v-for="result in internationalFenetreSurLAfriqueSportsData.internationalTwo" :key="result.id">
                        <div class="row g-3">
                            <div class="col-sm-8">
                                <!-- Title -->
                                <h5 class="card-title">
                                    <span @click="article(result.slug)" style="cursor: pointer" class="btn-link text-reset stretched-link" v-html="result.title"></span>
                                </h5>

                                <div v-html="result.truncate_content_max">

                                </div>
                                <!-- Card info -->
                                <ul class="nav nav-divider align-items-center text-uppercase d-sm-inline-block" style="font-size: 12px">
                                    <li class="nav-item">
                                        <div class="nav-link">
                                            <div class="d-flex align-items-center position-relative">

                                                <span class="ms-3">  <span @click="author(result.author_slug)" style="cursor: pointer" class="stretched-link text-reset btn-link">{{ result.author_name }}</span></span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="nav-item">{{ moment(result.date_publish).format("DD/MM/YYYY") }}</li>

                                </ul>
                            </div>
                            <!-- Image -->
                            <div class="col-sm-4">
                                <img class="rounded-3" :src='getImage(result.image_cover_url)' style="height: 220px; width: 700px ;object-fit:cover" :alt='result.title'>
                            </div>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="border-bottom border-primary border-2 opacity-1 my-2"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Title -->
                            <Splide :options="optionsOneInternationalFenetreSurLAfriqueSports" aria-label="My Favorite Images">

                                <SplideSlide v-for="result in internationalFenetreSurLAfriqueSportsData.internationalFirst" :key="result.id">
                                    <div  style="margin: 5px">
                                        <div class="card card-overlay-bottom card-img-scale">
                                            <!-- Card Image -->
                                            <img class="card-img international_first_section_img_responsive" :src='getImage(result.image_cover_url)' :alt='result.title'>
                                            <!-- Card Image overlay -->
                                            <div class="card-img-overlay d-flex flex-column ">
                                                <div class="w-100 mt-auto">

                                                    <!-- Card title -->
                                                    <h5 class="text-white"><span @click="article(result.slug)" style="cursor: pointer" class="btn-link text-reset stretched-link" v-html="result.title"></span></h5>
                                                    <!-- Card info -->
                                                    <ul class="nav nav-divider align-items-center text-white-force text-uppercase d-sm-inline-block" style="font-size: 11px">
                                                        <li class="nav-item">
                                                            <div class="nav-link">
                                                                <div class="d-flex align-items-center position-relative">

                                                                    <span class="ms-3">  <span @click="author(result.author_slug)" style="cursor: pointer" class="stretched-link text-reset btn-link">{{ result.author_name }}</span></span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="nav-item">{{ moment(result.date_publish).format("DD/MM/YYYY") }}</li>

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </SplideSlide>
                            </Splide>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-2">
                <div class="row">

                    <!-- Main Post START -->
                    <div class="col-lg-12">
                        <div class="col-md-12 ">
                            <div class="d-flex mb-2">
                                <div>
                                    <img :src="`/assets/images/Icones_formes/Icones-TAAsset-79.png`" class="alaune_logo" alt="Icones-TAAsset-79.png">
                                </div>
                                <div>&nbsp; &nbsp; &nbsp;</div>
                                <div>
                                    <h4 class="text-success m-0 alaune_title">COMMUNIQUE</h4>
                                    <p>Suivez nos communiqués.</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-12">
                                <!-- Title -->
                                <Splide :options="optionsOneFenetreSurLAfrique" aria-label="My Favorite Images">

                                    <SplideSlide v-for="result in internationalFenetreSurLAfriqueSportsData.afrique" :key="result.id">
                                        <div  style="margin: 5px">
                                            <div class="card card-overlay-bottom card-img-scale">
                                                <!-- Card Image -->
                                                <img class="card-img international_first_section_img_responsive" :src='getImage(result.image_cover_url)' :alt='result.title'>
                                                <!-- Card Image overlay -->
                                                <div class="card-img-overlay d-flex flex-column ">
                                                    <div class="w-100 mt-auto">

                                                        <!-- Card title -->
                                                        <h6 class="text-white"><span @click="article(result.slug)" style="cursor: pointer" class="btn-link text-reset stretched-link" v-html="result.title"></span></h6>
                                                        <!-- Card info -->
                                                        <ul class="nav nav-divider align-items-center text-white-force text-uppercase d-sm-inline-block" style="font-size: 11px">
                                                            <li class="nav-item">
                                                                <div class="nav-link">
                                                                    <div class="d-flex align-items-center position-relative">

                                                                        <span class="ms-3">  <span @click="author(result.author_slug)" style="cursor: pointer" class="stretched-link text-reset btn-link">{{ result.author_name }}</span></span>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="nav-item">{{ moment(result.date_publish).format("DD/MM/YYYY") }}</li>

                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </SplideSlide>
                                </Splide>

                            </div>
                        </div>

                    </div>

                    
                    <!-- Newsletter START -->
                    <div class="bg-light p-4 mt-4 rounded-3 text-center">
                        <h4>Abonnez-vous à notre liste de diffusion!</h4>
                        <form>
                            <div class="mb-3">
                                <input v-if="newsletterErrors.email" required type="email" name="email" v-model="newsletterData.email"  class="form-control is-invalid" placeholder="Entrez votre email " />
                                <input v-else required type="email" name="email" v-model="newsletterData.email" class="form-control " placeholder="Entrez votre email " />
                            </div>
                            <button type="submit" class="btn btn-primary"  @click.prevent="saveNewsletter"  v-if="!loadingN">S'abonner</button>
                            <button type="button"  class="btn btn-primary m-0" v-else>
                                <i  style="color: #fff" class="fa fa-spinner fa-1x fa-spin fa-fw"></i>
                                <span class="sr-only">Loading...</span>
                            </button>
                            <div class="form-text">Nous ne spammons pas</div>
                        </form>
                    </div>
                    <!-- Newsletter END -->
                </div>
            </div>

            <div class="col-lg-12 mb-2">

                <div class="row">
                    <!-- Main Post START -->
                    <div class="col-lg-12 mt-4">
                        <div class="col-md-12 ">
                            <div class="d-flex mb-2">
                                <div>
                                    <img :src="`/assets/images/Icones_formes/Icones-TAAsset-87.png`" class="alaune_logo" alt="Icones-TAAsset-89.png">
                                </div>
                                <div>&nbsp; &nbsp; &nbsp;</div>
                                <div>
                                    <h2 class="text-success m-0 alaune_title">INTERVIEWS</h2>
                                    <p>Suivez les interviews.</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <!-- Title -->
                                <Splide :options="optionsOneSports" aria-label="My Favorite Images">

                                    <SplideSlide v-for="result in internationalFenetreSurLAfriqueSportsData.sports" :key="result.id">

                                        <div  style="margin: 5px">
                                            <div class="card card-overlay-bottom card-img-scale">
                                                <!-- Card Image -->
                                                <img class="card-img international_first_section_img_responsive" :src='getImage(result.image_cover_url)' :alt='result.title'>
                                                <!-- Card Image overlay -->
                                                <div class="card-img-overlay d-flex flex-column ">
                                                    <div class="w-100 mt-auto">

                                                        <!-- Card title -->
                                                        <h6 class="text-white"><span @click="article(result.slug)" style="cursor: pointer" class="btn-link text-reset stretched-link" v-html="result.title"></span></h6>
                                                        <!-- Card info -->
                                                        <ul class="nav nav-divider align-items-center text-white-force text-uppercase d-sm-inline-block" style="font-size: 11px">
                                                            <li class="nav-item">
                                                                <div class="nav-link">
                                                                    <div class="d-flex align-items-center position-relative">

                                                                        <span class="ms-3">  <span @click="author(result.author_slug)" style="cursor: pointer" class="stretched-link text-reset btn-link">{{ result.author_name }}</span></span>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="nav-item">{{ moment(result.date_publish).format("DD/MM/YYYY") }}</li>

                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </SplideSlide>
                                </Splide>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
