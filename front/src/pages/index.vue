<template>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <div class="text-center">
    <div class="text-body-2 font-weight-light mb-n1 mt-5">
      <h1>Cadastro de Pessoas</h1>
      <h2 v-if="user">Bem-vindo, {{ user.name }}!</h2>
    </div>
    <v-app>
      <PessoasList />
    </v-app>

  </div>

</template>
<script>

import PessoasList from '../components/PessoasList.vue';

import axios from 'axios';

export default {
  data() {
    return {
      email: 'test@example.com',
      password: 'password',
      user: null,
    };
  },
  methods: {
    async login() {
      try {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await axios.post(`${import.meta.env.VITE_API_BASE_URL}/api/login`, {
          email: this.email,
          password: this.password,
        }, {
          headers: {
            'X-CSRF-TOKEN': token,
          },
        });
        localStorage.setItem('authToken', response.data.token);
        this.user = response.data.user;
        console.log('Login bem-sucedido:', response.data);
      } catch (error) {
        console.error('Erro ao fazer login:', error.response ? error.response.data : error.message);
      }
    },
  },
  mounted() {
    this.login();
  },
  components: {
    PessoasList,
  }
};
</script>
