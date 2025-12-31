<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue';
import { useStore } from 'vuex';
import { useRoute } from 'vue-router';
import VueMultiselect from 'vue-multiselect';
import { Bootstrap5Pagination } from '../../../../../../libraries/pagination/lib';
import 'vue-multiselect/dist/vue-multiselect.min.css'; 

import { defineProps } from 'vue';

const props = defineProps({
  publicationCreate: {
    type: Object,
    default: () => ({ authors: [], categories: [], tags: [] })
  }
});

const store = useStore();
const route = useRoute();

// 🔹 Publication fields
const title = ref('');
const content = ref('');
const source = ref('');
const author = ref(null);
const category = ref([]);
const tag = ref([]);
const status = ref('1');
const startDate = ref('');
const startTime = ref('');
const endDate = ref('');
const endTime = ref('');
const selectedSites = ref([]);

// 🔹 Files
const allFiles = ref([]);
const coverImage = ref(null);
const fileInput = ref(null);

// 🔹 Pagination / files DB
const filesData = ref({ files: { data: [], meta: {} } });
const filesMessage = ref('');
const emptyFiles = ref(0);
const loadingFiles = ref(true);
const statut = ref('3'); // 3 = tous les fichiers
const statusFiles = ref('3'); // select filter files
const limit = ref(1);
const keepLength = ref(false);
const showDisabled = ref(false);
const size = ref('default');
const align = ref('left');
const search = ref('');

// 🔹 Modal & type
const currentType = ref(null);
const fileModal = ref(null);
const fileTypes = [
  { id: 1, title: 'Ajouter des fichiers', icon: 'bi bi-cloud-arrow-up' },
  { id: 2, title: 'Sélectioner des fichiers', icon: 'bi bi-archive' },
  { id: 3, title: 'Ajouter des liens vidéos', icon: 'bi bi-link-45deg' },
  { id: 4, title: 'Ajouter des codes Iframes', icon: 'bi bi-youtube' },
];

// 🔹 Sites partenaires
const sites = [
  { name: 'Togoactu', logo: '/assets/images/logo-togoactu.png', description: 'Partagez cette publication sur Togoactu.' },
  { name: 'News228', logo: '/assets/images/logo-news228.png', description: 'Partagez cette publication sur News228.' }
];

// 🔹 Drag & drop helpers
const isDuplicate = (file) => allFiles.value.some(f => f.file === file || f.url === URL.createObjectURL(file));
const addFile = (file) => {
  if (isDuplicate(file)) { alert("Ce fichier est déjà ajouté !"); return; }
  const type = file.type.startsWith("video") ? "video" : file.type.startsWith("image") ? "image" : "other";
  allFiles.value.push({ file, url: URL.createObjectURL(file), type, name: file.name, caption: '', isCover: false });
};
const handleDrop = (e) => { e.preventDefault(); for (let file of e.dataTransfer.files) addFile(file); };
const handleFiles = (e) => { for (let file of e.target.files) addFile(file); };
const triggerFileInput = () => fileInput.value?.click();
const removeFile = (index) => {
  if (allFiles.value[index].isCover) coverImage.value = null;
  allFiles.value.splice(index, 1);
};
const toggleCover = (file) => {
  if(file.isCover) { file.isCover = false; coverImage.value = null; }
  else { allFiles.value.forEach(f => { if(f.type === 'image') f.isCover = false; }); file.isCover = true; coverImage.value = file; }
};

// 🔹 Ajouter fichier depuis DB
const addFromDb = (file) => {
  let type = file.type_file_id === 2 ? 'video' : file.type_file_id === 3 ? 'iframe' : 'image';
  if(allFiles.value.some(f => f.url === file.file_url)) { alert("Ce fichier est déjà sélectionné !"); return; }
  allFiles.value.push({ file: null, url: file.file_url, type, name: file.file_name, caption: file.caption||'', isCover: false });
};

// 🔹 Ajouter lien vidéo ou iframe
const videoLink = ref('');
const iframeCode = ref('');
const addVideoLink = () => {
  if(!videoLink.value) return;
  const url = videoLink.value.trim();
  if(url.toLowerCase().endsWith('.mp4')) { addFile(new File([], 'Vidéo.mp4')); videoLink.value=''; return; }
  const ytRegex = /(?:youtube\.com\/.*[?&]v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/;
  const match = url.match(ytRegex);
  if(match && match[1]) { allFiles.value.push({ file: null, url: `https://www.youtube.com/embed/${match[1]}`, type:'iframe', name:'YouTube vidéo', caption:'', isCover:false }); videoLink.value=''; return; }
  alert('Lien vidéo non supporté');
};
const addIframe = () => {
  if(!iframeCode.value) return;
  const match = iframeCode.value.match(/src=["']([^"']+)["']/);
  if(!match) { alert("Code d'intégration non valide !"); return; }
  if(allFiles.value.some(f => f.url === match[1])) { alert("Cet iframe est déjà ajouté !"); return; }
  allFiles.value.push({ file: null, url: match[1], type:'iframe', name:'Iframe intégré', caption:'', isCover:false });
  iframeCode.value='';
};

// 🔹 Pagination & fetch fichiers DB
const getFiles = async (page=1) => {
  loadingFiles.value = true;
  await store.dispatch('publicationAdmin/publicationGetFilesDataRequest', { slug: route.params.slug, page, search: search.value });
  const statusResp = store.getters['publicationAdmin/getInfosPublicationGetFilesStatus'];
  const dataResp = store.getters['publicationAdmin/getInfosPublicationGetFilesData'];
  const messageResp = store.getters['publicationAdmin/getInfosPublicationGetaFilesMessage'];
  if(statusResp === 'success'){ filesData.value = dataResp; emptyFiles.value=0; }
  else if(statusResp === 'empty'){ filesMessage.value = messageResp; emptyFiles.value=1; }
  else{ filesMessage.value = messageResp; emptyFiles.value=3; }
  loadingFiles.value=false;
};
const handleSelectionSearchByStatusFiles = async (e, page=1) => {
  statut.value = e.target.value;
  await store.dispatch('publicationAdmin/publicationSearchByTypeFilesDataRequest',{slug: route.params.slug, page, search: search.value, status: statut.value});
  const statusResp = store.getters['publicationAdmin/getInfosPublicationSearchByTypeFilesStatus'];
  const dataResp = store.getters['publicationAdmin/getInfosPublicationSearchByTypeFilesData'];
  const messageResp = store.getters['publicationAdmin/getInfosPublicationSearchByTypeFilesMessage'];
  if(statusResp === 'success'){ filesData.value = dataResp; emptyFiles.value=0; }
  else if(statusResp === 'empty'){ filesMessage.value = messageResp; emptyFiles.value=1; }
  else{ filesMessage.value = messageResp; emptyFiles.value=3; }
};

// 🔹 Modal
const openModal = (id) => { currentType.value = fileTypes.find(t=>t.id===id); new bootstrap.Modal(fileModal.value).show(); };
const closeModal = () => bootstrap.Modal.getInstance(fileModal.value)?.hide();

// 🔹 Sites partenaires
const toggleSite = (name) => selectedSites.value.includes(name) ? selectedSites.value = selectedSites.value.filter(s=>s!==name) : selectedSites.value.push(name);
const toggleAllSites = () => selectedSites.value.length===sites.length ? selectedSites.value=[] : selectedSites.value = sites.map(s=>s.name);

// 🔹 Dates / statut
const isStartDisabled = ref(true);
const isEndDisabled = ref(false);
const updateStartNow = () => {
  const now = new Date();
  startDate.value = now.toISOString().slice(0,10);
  startTime.value = now.toTimeString().slice(0,5);
};
const handleStatusChange = (newStatus) => {
  clearInterval(intervalId);
  if(newStatus==='1'){ updateStartNow(); intervalId=setInterval(updateStartNow,1000); isStartDisabled.value=true; isEndDisabled.value=false; endDate.value=''; endTime.value=''; }
  else if(newStatus==='definir_date'){ isStartDisabled.value=false; isEndDisabled.value=false; startDate.value=''; startTime.value=''; endDate.value=''; endTime.value=''; }
  else { isStartDisabled.value=true; isEndDisabled.value=true; startDate.value=''; startTime.value=''; endDate.value=''; endTime.value=''; }
};
let intervalId = null;
watch(status, handleStatusChange);
onMounted(()=>{ handleStatusChange(status.value); getFiles(); });
onUnmounted(()=>{ clearInterval(intervalId); });

// 🔹 Ajouter category / tag
const addCategory = (newTag) => category.value.push({ name: newTag });
const addTag = (newTag) => tag.value.push({ name: newTag });

// 🔹 Enregistrer publication
const storePublication = async () => {
  const formData = new FormData();
  allFiles.value.forEach(f => { if(f.file) formData.append("files[]", f.file); });
  formData.append("title", title.value);
  formData.append("content", content.value);
  formData.append("source", source.value);
  formData.append("author", JSON.stringify(author.value));
  formData.append("category", JSON.stringify(category.value));
  formData.append("tag", JSON.stringify(tag.value));
  formData.append("status", status.value);
  formData.append("date_publish", startDate.value);
  formData.append("startTime", startTime.value);
  formData.append("date_publish_end", endDate.value);
  formData.append("endTime", endTime.value);
  formData.append("selectedSites", JSON.stringify(selectedSites.value));

  try{
    await store.dispatch('publicationAdmin/publicationStoreDataRequest', formData);
    const statutResp = store.getters['publicationAdmin/getInfosPublicationStoreStatus'];
    const messageResp = store.getters['publicationAdmin/getInfosPublicationStoreMessage'];
    if(statutResp==='success'){ alert("Publication enregistrée !"); allFiles.value=[]; author.value=null; category.value=[]; tag.value=[]; }
    else alert("Données rejetées par le serveur en raison d'une incompatibilité openSSL");  
  } catch(e){ console.error(e); alert("Données rejetées par le serveur en raison d'une incompatibilité openSSL du serveur."); }
};

// 🔹 Helper getImage
const getImage = (url) => url;
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
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Titre de l'article</label>
                <QuillEditor theme="snow" v-model:content="title" contentType="html" />
            </div>
        </div>
        <div class="col-md-12">
             <div class="mb-3">
                <label class="form-label" >Source</label>
                <input type="text" class="form-control  " v-model="source" name="authorName" placeholder="Entrez le lien de la publicité">
            </div>
        </div>
        <div class="col-lg-6 mt-3">
            <div class="mb-3">
                <label class="form-label" >Ajouter un auteur</label>
                <VueMultiselect
                    v-model="author"
                    :options="publicationCreate.authors"
                    :close-on-select="true"
                    :clear-on-select="false"
                    placeholder="Choisir un auteur"
                    label="authorName"
                    track-by="authorName"
                />
            </div>
        </div>
        <div class="col-lg-6 mt-3">
            <div class="mb-3">
                <label class="form-label" >Ajouter des catégories</label>
                <VueMultiselect
                    v-model="category"
                    :options="publicationCreate.categories"
                    :multiple="true"
                    :taggable="true"
                    @tag="addCategory"
                    :close-on-select="true"
                    :clear-on-select="false"
                    placeholder="Choisir des catégories"
                    label="name"
                    track-by="name"
                />
            </div>
        </div>
       <div class="col-lg-12 mt-3"  >
            <div class="mb-3">
                <label class="form-label" >Contenu de la publication</label>
                <QuillEditor theme="snow" toolbar="full" v-model:content="content" contentType="html" />
            </div>
        </div>
        <div class="col-lg-12 mt-3">
            <div class="mb-3"> 
                <label class="form-label" >Ajouter des tags</label>
                <VueMultiselect
                    v-model="tag"
                    :options="publicationCreate.tags"
                    :multiple="true"
                    :taggable="true"
                    @tag="addTag"
                    :close-on-select="true"
                    :clear-on-select="false"
                    placeholder="Choisir des mots clés"
                    label="name"
                    track-by="name"
                />
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
                                      <div class="col-md-12" v-for="(file, index) in allFiles" :key="index">
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
         
         <div class="col-md-3 mt-2">
            <div class="mb-3">
                <label class="form-label">Statut</label>
                <select v-model="status" class="form-select">
                    <option value="1">Publier à l’instant</option>
                    <option value="brouillon">Mettre en brouillon</option>
                    <option value="attente_validation">Mettre en attente de validation</option>
                    <option value="definir_date">Définir une date de publication</option>
                </select>
            </div>
        </div>
        <div class="col-md-9 mt-2">
            <div class="mb-3 row" v-if="status !== 'brouillon' && status !== 'attente_validation'">
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
    <button class="btn btn-primary" @click="storePublication">Enregistrer</button>
  </div>
</template>
