<template>
  <v-dialog v-model="dialog" max-width="80%">
    <v-card>
      <v-card-title>
        <span class="headline">{{ editing ? 'Editar Endereço' : 'Cadastrar Endereço' }}</span>
      </v-card-title>
      <v-card-text>
        <v-form ref="form">
          <v-row dense>
            <v-col cols="12" md="6" class="pa-2">
              <v-text-field v-model="currentEndereco.cep" label="CEP" dense @input="fetchCepData" :rules="[cepRule]" v-mask="'#####-###'"></v-text-field>
            </v-col>
            <v-col cols="12" md="6" class="pa-2">
              <v-text-field v-model="currentEndereco.logradouro" label="Logradouro" required dense :readonly></v-text-field>
            </v-col>
            <v-col cols="12" md="6" class="pa-2">
              <v-text-field v-model="currentEndereco.numero" label="Número" required dense></v-text-field>
            </v-col>
            <v-col cols="12" md="6" class="pa-2">
              <v-text-field v-model="currentEndereco.complemento" label="Complemento" dense></v-text-field>
            </v-col>
            <v-col cols="12" md="6" class="pa-2">
              <v-text-field v-model="currentEndereco.bairro" label="Bairro" dense :readonly></v-text-field>
            </v-col>
            <v-col cols="12" md="6" class="pa-2">
              <v-text-field v-model="currentEndereco.cidade" label="Cidade" required dense :readonly></v-text-field>
            </v-col>
            <v-col cols="12" md="6" class="pa-2">
              <v-text-field v-model="currentEndereco.estado" label="Estado" required dense :readonly></v-text-field>
            </v-col>            
            <v-col cols="12" md="6" class="pa-2">
              <v-select v-model="currentEndereco.tipo" :items="tipos" label="Tipo" required dense></v-select>
            </v-col>
          </v-row>
        </v-form>

        <v-data-table :items="enderecos" :headers="headers" item-value="id">
          <template v-slot:item.actions="{ item }">
            <v-btn @click="startEdit(item)" class="mr-2">Editar</v-btn>
            <v-btn @click="deleteEndereco(item.id)" color="red" class="mr-2">Deletar</v-btn>
          </template>
        </v-data-table>
        
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn text @click="dialog = false">Fechar</v-btn>
        <v-btn color="primary" @click="saveEndereco">{{ editing ? 'Salvar' : 'Criar' }}</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    pessoa: Object,
  },
  data() {
    return {
      dialog: false,
      enderecos: [],
      currentEndereco: {
        id: null,
        logradouro: '',
        numero: '',
        complemento: '',
        bairro: '',
        cidade: '',
        estado: '',
        cep: '',
        tipo: '',
      },
      cepFetched: false,
      tipos: ['Residencial', 'Comercial'], 
      headers: [
        { title: 'Logradouro', value: 'logradouro' },
        { title: 'Número', value: 'numero' },
        { title: 'Complemento', value: 'complemento' },
        { title: 'Bairro', value: 'bairro' },
        { title: 'Cidade', value: 'cidade' },
        { title: 'Estado', value: 'estado' },
        { title: 'CEP', value: 'cep' },
        { title: 'Tipo', value: 'tipo' },
        { title: 'Ações', value: 'actions', sortable: false },
      ],
      editing: false,
    };
  },
  watch: {
    pessoa: {
      handler(newPessoa) {
        if (newPessoa && newPessoa.id) {
          this.fetchEnderecos(newPessoa.id);
        }
      },
      immediate: true,
    },
  },
  methods: {
    async fetchEnderecos(pessoaId) {
      const token = localStorage.getItem('authToken');
      try {
        const response = await axios.get(`${import.meta.env.VITE_API_BASE_URL}/api/enderecos/pessoa/${pessoaId}`, {
          headers: { 'Authorization': `Bearer ${token}` },
        });
        this.enderecos = response.data;
      } catch (error) {
        console.error('Erro ao buscar endereços:', error);
      }
    },
    open() {
      this.dialog = true;
    },
    async saveEndereco() {
      const token = localStorage.getItem('authToken');
      try {
        if (this.editing) {
          await axios.put(`${import.meta.env.VITE_API_BASE_URL}/api/enderecos/${this.currentEndereco.id}`, this.currentEndereco, {
            headers: { 'Authorization': `Bearer ${token}` },
          });
        } else {
          await axios.post(`${import.meta.env.VITE_API_BASE_URL}/api/enderecos`, {
            ...this.currentEndereco,
            pessoa_id: this.pessoa.id,
          }, {
            headers: { 'Authorization': `Bearer ${token}` },
          });
        }
        this.fetchEnderecos(this.pessoa.id);
        this.dialog = false;
      } catch (error) {
        console.error('Erro ao salvar endereço:', error);
      }
    },
    startEdit(endereco) {
      this.currentEndereco = { ...endereco };
      this.editing = true;
      this.dialog = true;
    },
    async deleteEndereco(enderecoId) {
      const token = localStorage.getItem('authToken');
      try {
        await axios.delete(`${import.meta.env.VITE_API_BASE_URL}/api/enderecos/${enderecoId}`, {
          headers: { 'Authorization': `Bearer ${token}` },
        });
        this.fetchEnderecos(this.pessoa.id);
      } catch (error) {
        console.error('Erro ao deletar endereço:', error);
      }
    },
    async fetchCepData() {
      const cep = this.currentEndereco.cep.replace(/\D/g, '');
      if (cep.length === 8) {
        try {
          const response = await axios.get(`https://viacep.com.br/ws/${cep}/json/`);
          const data = response.data;
          if (!data.erro) {
            this.currentEndereco.logradouro = data.logradouro;
            this.currentEndereco.bairro = data.bairro;
            this.currentEndereco.cidade = data.localidade;
            this.currentEndereco.estado = data.uf;
            this.cepFetched = true;
          } else {
            this.cepFetched = false;
          }
        } catch (error) {
          console.error('Erro ao buscar dados do CEP:', error);
          this.cepFetched = false;
        }
      } else {
        this.cepFetched = false;
      }
    },
  },
  computed: {
    cepRule() {
      return v => !!v && v.length === 9 || 'CEP deve ser no formato 00000-000';
    },
  },
};
</script>
