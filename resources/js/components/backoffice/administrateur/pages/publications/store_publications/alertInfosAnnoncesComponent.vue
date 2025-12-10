<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue'; 

const content = ref('');
const status = ref('publier_maintenant'); // statut par défaut

const props = defineProps({
  publicationCreate: {
    type: Object,
    default: null
  }
})

// Dates et heures
const startDate = ref('');
const startTime = ref('');
const endDate = ref('');
const endTime = ref('');

// Activation/désactivation des champs
const isStartDisabled = ref(true);
const isEndDisabled = ref(false);

const sites = [
  { name: 'Togoactu', logo: '/assets/images/logo-togoactu.png', description: 'Partagez cette publication sur Togoactu.' },
  { name: 'News228', logo: '/assets/images/logo-news228.png', description: 'Partagez cette publication sur News228.' }
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

  if (newStatus === 'publier_maintenant') {
    updateStartNow();
    intervalId = setInterval(updateStartNow, 1000); // mise à jour chaque seconde
    isStartDisabled.value = true;
    isEndDisabled.value = false;
    endDate.value = '';
    endTime.value = '';
  } else if (newStatus === 'definir_date') {
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

// Sauvegarde
const saveForm = () => {
  console.log({
    content: content.value,
    status: status.value,
    startDate: startDate.value,
    startTime: startTime.value,
    endDate: endDate.value,
    endTime: endTime.value,
    selectedSites: selectedSites.value
  });
  alert('Formulaire enregistré ! (voir console)');
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
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Contenu de <span v-if="props.publicationCreate.typePublication.id === 2">l'alerte info</span> <span v-else-if="props.publicationCreate.typePublication.id === 3">l'annonce</span></label>
                <QuillEditor theme="snow" />
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label">Statut</label>
                <select v-model="status" class="form-select">
                    <option value="publier_maintenant">Publier à l’instant</option>
                    <option value="brouillon">Mettre en brouillon</option>
                    <option value="attente_validation">Mettre en attente de validation</option>
                    <option value="definir_date">Définir une date de publication</option>
                </select>
            </div>
        </div>
        <div class="col-md-9">
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
    <button class="btn btn-primary" @click="saveForm">Enregistrer</button>
  </div>
</template>
