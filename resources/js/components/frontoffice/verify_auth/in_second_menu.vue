<script setup>
import { ref, computed, onMounted } from 'vue';
import { useStore } from 'vuex';
import Swal from 'sweetalert2';

const store = useStore();
const dataReady = ref(0);
const meProfileUserName = ref(null);
const meProfileRoleName = ref(null);
const meProfileEmail = ref(null);
const loadingConnect = ref(false);
const logoutCheck = ref(false);
const remember_me = ref(false);

const show = async () => {
  if (localStorage.getItem('access_token') && localStorage.getItem('nbRsp')) {
    await store.dispatch('meProfile/getMeProfile');
    
    const gettersMeProfileUserName =  store.getters['meProfile/getMeProfileUserName'];
    const gettersMeProfileRoleName =  store.getters['meProfile/getMeProfileRoleName'];
    const gettersMeProfileEmail =  store.getters['meProfile/getMeProfileEmail'];
    const gettersMeProfileStatus =  store.getters['meProfile/getMeProfileStatus'];  

    if (gettersMeProfileStatus === 'success') {
      meProfileRoleName.value = gettersMeProfileRoleName;
      meProfileUserName.value = gettersMeProfileUserName;
      meProfileEmail.value = gettersMeProfileEmail;
      dataReady.value = 1;
    } else {
      dataReady.value = 2;
    }
  } else {
    dataReady.value = 2;
  }
};


const loginClick = async () => {
  if (localStorage.getItem('remember_me') === 'true' && localStorage.getItem('username') && localStorage.getItem('password')) {
    loadingConnect.value = true;
    remember_me.value = localStorage.getItem('remember_me');

    await store.dispatch('login/login', {
      username: localStorage.getItem('username'),
      password: localStorage.getItem('password'),
      remember_me: localStorage.getItem('remember_me')
    });

    const getterLoginStatus =  store.getters['login/getLoginStatus'];
    const getterLoginMessage =  store.getters['login/getLoginMessage'];

    if (getterLoginStatus.includes('success')) {
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: getterLoginMessage,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
      });
      const redirectPath = getterLoginStatus.includes('admin') ? '/admin/dashboard' : getterLoginStatus.includes('pub') ? '/pub/dashboard' : '/';
      window.location = redirectPath;
    } else {
      window.location = '/auth/login';
    }
  } else {
    window.location = '/auth/login';
  }
};

const logout = async () => {
  logoutCheck.value = true;
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
    window.location = '/auth/login';
  }
};

onMounted(() => {
  show();
});
</script>

<template> 
  <div class="nav-item ms-2 ms-md-3" v-if="dataReady === 0 || dataReady === 2">
    <span class="avatar avatar-xs" v-if="!loadingConnect" @click="loginClick" style="cursor: pointer">
      <div class="btn btn-primary btn-round mb-0 ">
        <i class="bi bi-person" ></i>
      </div> 
    </span>
    <span class="avatar avatar-xs" v-else>
      <div class="btn btn-primary btn-round mb-0 ">
        <i  style="color: #fff" class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span> 
      </div> 
    </span>
  </div> 
  <div class="nav-item ms-2 ms-md-3 dropdown"  v-else-if="dataReady === 1">
    <a class="btn btn-primary btn-round mb-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
      {{ meProfileUserName[0].toUpperCase() }}
    </a> 
    <ul class="dropdown-menu dropdown-animation dropdown-menu-end shadow pt-3" aria-labelledby="profileDropdown">
      <li class="dropdown-item">
        <div class="d-flex align-items-center">
          <div class="avatar avatar-xs">
            <div class="avatar-img rounded-circle bg-primary">
              <span class="text-light position-absolute top-50 start-50 translate-middle fw-bold small">
                {{ meProfileUserName[0].toUpperCase() }}
              </span>
            </div>
          </div>
          &nbsp; &nbsp; &nbsp;
          <div>
            <a class="h6 mt-2 mt-sm-0" href="#">{{ meProfileUserName }}</a>
            <p class="small m-0">{{ meProfileEmail }}</p>
          </div>
        </div>
        <hr>
      </li>
      <li><a class="dropdown-item" href="/auth/profile"><i class="bi bi-person fa-fw me-2"></i>Editer mon profil</a></li>
      <li v-if="meProfileRoleName === 'Administrateur'"><a class="dropdown-item" href="/admin/dashboard"><i class="bi bi-house-door fa-fw me-2"></i>Tableau de bord</a></li>
      <li v-else-if="meProfileRoleName === 'Publicateur d\' articles'"><a class="dropdown-item" href="/pub/dashboard"><i class="bi bi-house-door fa-fw me-2"></i>Tableau de bord</a></li>
      <li>
        <span class="dropdown-item" v-if="!logoutCheck" style="cursor: pointer" @click="logout"><i class="bi bi-power fa-fw me-2"></i>Se déconnecter</span>
        <span class="dropdown-item" v-else><i class="bi bi-power fa-fw me-2"></i>Déconnexion en cours ...</span>
      </li>
    </ul>
  </div> 
</template>
