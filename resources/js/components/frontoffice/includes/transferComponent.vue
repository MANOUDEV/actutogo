<script setup>
import { ref, onMounted } from 'vue'; 
import { useStore } from 'vuex';

const store = useStore();
const loading = ref(true);
const transferData = ref({});
const transferMessage = ref(null);
const empty = ref(2);

const getResults = async () => {
  loading.value = true;
  await store.dispatch('transfer/TransferDataRequest', {});

  const status = store.getters['transfer/getInfosTransferStatus'];
  const message = store.getters['transfer/getInfosTransferMessage'];
  const data = store.getters['transfer/getInfosTransferData'];
  if (status === 'success') {
    transferData.value = data;
    empty.value = 0;
  } else {
    transferMessage.value = message;
    empty.value = status === 'empty' ? 1 : 3;
  }
  loading.value = false;
};
 
onMounted(() => {
  getResults();
});
</script>
<template>
    
</template>
