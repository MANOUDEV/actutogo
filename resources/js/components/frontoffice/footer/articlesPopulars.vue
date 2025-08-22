<script setup>
import { ref, onMounted } from 'vue';
import moment from 'moment';
import { useStore } from 'vuex';

const store = useStore();
const loading = ref(true);
const publicationsPopularsData = ref({});
const publicationsPopularsMessage = ref(null);
const empty = ref(2);

const getResults = async () => {
  loading.value = true;
  await store.dispatch('articlesPopulars/articlesPopularsDataRequest', {});

  const status = store.getters['articlesPopulars/getInfosArticlesPopularsStatus'];
  const message = store.getters['articlesPopulars/getInfosArticlesPopularsMessage'];
  const data = store.getters['articlesPopulars/getInfosArticlesPopularsData'];

  if (status === 'success') {
    publicationsPopularsData.value = data;
    empty.value = 0;
  } else {
    publicationsPopularsMessage.value = message;
    empty.value = status === 'empty' ? 1 : 3;
  }
  loading.value = false;
};

const article = (slug) => {
  window.location = `/${slug}`;
};

const author = (slug) => {
  window.location = `/authors/${slug}`;
};

onMounted(() => {
  getResults();
});
</script>
<template>
    <div class="d-flex justify-content-center" v-if="loading">
        <div class="spinner-border text-light"  role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <div v-else class="mb-4 position-relative" v-for="info in publicationsPopularsData" :key="info.id">
        <div><span @click="category(info.category_slug)" style="cursor: pointer"  class="badge text-bg-danger mb-2"><i class="fas fa-circle me-2 small fw-bold"></i> {{ info.category_name }} </span></div>
        <span @click="article(info.slug)" style="cursor: pointer" class="btn-link text-white fw-normal" v-html="info.title"></span>
        <ul class="nav nav-divider align-items-center small mt-2 text-muted">
            <li class="nav-item position-relative">
                <div class="nav-link">par <span @click="author(info.author_slug)" style="cursor: pointer" class="stretched-link text-reset btn-link"> {{ info.author_name }} </span>
                </div>
            </li>
            <li class="nav-item">{{ moment(info.date_publish).format("DD/MM/YYYY") }}</li>
        </ul>
    </div>
    <!-- Item -->

</template>
