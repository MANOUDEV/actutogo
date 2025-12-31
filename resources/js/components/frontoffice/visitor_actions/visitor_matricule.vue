<script setup>
import { ref, computed, onMounted } from 'vue';
import { useStore } from 'vuex'; 

const store = useStore();
const loading = ref(true); 
const empty = ref(2); 
const createVisitorMatricule = async () =>{
    loading.value = true    
    await store.dispatch('visitorsActions/createVisitorsDataRequest');
    const getterInfosCreateVisitorsStatus =  store.getters['visitorsActions/getInfosCreateVisitorsStatus'];
    const getterInfosCreateVisitorsData =  store.getters['visitorsActions/getInfosCreateVisitorsData'];
    const getterInfosCreateVisitorsMessage =  store.getters['visitorsActions/getInfosCreateVisitorsMessage']; 
    if(getterInfosCreateVisitorsStatus ==="success"){

        localStorage.setItem('mtr', getterInfosCreateVisitorsData.visitor_matricule_create.matricule_reference)
 
        empty.value = 0

        loading.value = false

    }else if( getterInfosCreateVisitorsStatus ==="empty"){

    empty.value = 1

    loading.value = false

    }else{

        empty.value = 3 

    }


}
const getResults = async () =>{

    if(localStorage.getItem('mtr') == null){

        createVisitorMatricule()

    }else{

        await store.dispatch('visitorsActions/checkVisitorsDataRequest');
        const getterInfosCheckVisitorsStatus =  store.getters['visitorsActions/getInfosCheckVisitorsStatus'];
        const getterInfosCheckVisitorsData =  store.getters['visitorsActions/getInfosCheckVisitorsData'];
        const getterInfosCheckVisitorsMessage =  store.getters['visitorsActions/getInfosCheckVisitorsMessage'];

        if(getterInfosCheckVisitorsStatus ==="empty"){

            createVisitorMatricule()

        }
        
    }
};
onMounted(() => {
  getResults();
});
</script>
<template>
   <div></div>
</template> 