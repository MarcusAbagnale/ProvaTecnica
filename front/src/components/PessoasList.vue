<template>
  <v-container>
    <v-btn @click="openCreateDialog">Cadastrar Pessoa</v-btn>

    <!-- Campo de Pesquisa -->
    <v-text-field v-model="search" label="Pesquisar" append-icon="mdi-magnify" class="mb-4"></v-text-field>

    <v-data-table :items="filteredPessoas" :headers="headers" item-key="id">
      <template v-slot:item.actions="{ item }">
        <v-btn @click="openEditDialog(item)" class="mr-2">Editar</v-btn>
        <v-btn @click="deletePessoa(item.id)" color="red" class="mr-2">Deletar</v-btn>
        <v-btn @click="openAddressModal(item)" class="mr-2">Ver Endereços</v-btn>
      </template>
    </v-data-table>

    <v-dialog v-model="createDialog" max-width="600px">
      <v-card>
        <v-card-title>
          <span class="headline">Cadastrar Pessoa</span>
        </v-card-title>
        <v-card-text>
          <v-text-field v-model="newPessoa.nome" label="Nome" :error-messages="errors.nome"></v-text-field>
          <v-text-field v-model="newPessoa.nomeSocial" label="Nome Social" :error-messages="errors.nomeSocial"></v-text-field>
          <v-text-field v-model="newPessoa.cpf" label="CPF" :error-messages="errors.cpf"></v-text-field>
          <v-text-field v-model="newPessoa.nomeMae" label="Nome da Mãe" :error-messages="errors.nomeMae"></v-text-field>
          <v-text-field v-model="newPessoa.nomePai" label="Nome do Pai" :error-messages="errors.nomePai"></v-text-field>
          <v-text-field v-model="newPessoa.telefone" label="Telefone" :error-messages="errors.telefone"></v-text-field>
          <v-text-field v-model="newPessoa.email" label="Email" :error-messages="errors.email"></v-text-field>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn text @click="createDialog = false">Cancelar</v-btn>
          <v-btn color="primary" @click="createPessoa">Criar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="editDialog" max-width="600px">
      <v-card>
        <v-card-title>
          <span class="headline">Editar Cadastro de Pessoa</span>
        </v-card-title>
        <v-card-text>
          <v-text-field v-model="editedPessoa.nome" label="Nome" :error-messages="errors.nome"></v-text-field>
          <v-text-field v-model="editedPessoa.nomeSocial" label="Nome Social" :error-messages="errors.nomeSocial"></v-text-field>
          <v-text-field v-model="editedPessoa.cpf" label="CPF" :error-messages="errors.cpf"></v-text-field>
          <v-text-field v-model="editedPessoa.nomeMae" label="Nome da Mãe" :error-messages="errors.nomeMae"></v-text-field>
          <v-text-field v-model="editedPessoa.nomePai" label="Nome do Pai" :error-messages="errors.nomePai"></v-text-field>
          <v-text-field v-model="editedPessoa.telefone" label="Telefone" :error-messages="errors.telefone"></v-text-field>
          <v-text-field v-model="editedPessoa.email" label="Email" :error-messages="errors.email"></v-text-field>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn text @click="editDialog = false">Cancelar</v-btn>
          <v-btn color="primary" @click="updatePessoa">Salvar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Modal de Endereços -->
    <EnderecosModal ref="enderecosModal" :pessoa="selectedPessoa" />
  </v-container>
</template>

<script>
import axios from "axios";
import EnderecosModal from "./EnderecosModal.vue";

export default {
  components: {
    EnderecosModal,
  },
  data() {
    return {
      pessoas: [],
      search: '',
      headers: [
        { title: "Nome", value: "nome", sortable: true },
        { title: "Nome Social", value: "nomeSocial", sortable: true },
        { title: "CPF", value: "cpf", sortable: true },
        { title: "Nome da Mãe", value: "nomeMae", sortable: true },
        { title: "Nome do Pai", value: "nomePai", sortable: true },
        { title: "Telefone", value: "telefone", sortable: true },
        { title: "Email", value: "email", sortable: true },
        { title: "Ações", value: "actions", sortable: true },
      ],
      createDialog: false,
      editDialog: false,
      newPessoa: {
        nome: "",
        nomeSocial: "",
        cpf: "",
        nomeMae: "",
        nomePai: "",
        telefone: "",
        email: "",
      },
      editedPessoa: {},
      selectedPessoa: null,
      errors: {} // Adicionado para mensagens de erro
    };
  },
  computed: {
    filteredPessoas() {
      if (this.search) {
        const searchLower = this.search.toLowerCase();
        return this.pessoas.filter(pessoa =>
          Object.values(pessoa).some(val =>
            String(val).toLowerCase().includes(searchLower)
          )
        );
      }
      return this.pessoas;
    }
  },
  methods: {
  async fetchPessoas() {
    const token = localStorage.getItem("authToken");
    try {
      const response = await axios.get(
        `${import.meta.env.VITE_API_BASE_URL}/api/pessoas`,
        {
          headers: { Authorization: `Bearer ${token}` },
        }
      );
      this.pessoas = response.data;
    } catch (error) {
      console.error("Erro ao buscar pessoas:", error);
    }
  },
  async createPessoa() {
    const token = localStorage.getItem("authToken");
    try {
      console.log('Criando pessoa com dados:', this.newPessoa); // Verificação dos dados
      await axios.post(
        `${import.meta.env.VITE_API_BASE_URL}/api/pessoas`,
        this.newPessoa,
        {
          headers: { Authorization: `Bearer ${token}` },
        }
      );
      this.fetchPessoas();
      this.createDialog = false;
      this.newPessoa = { nome: "", nomeSocial: "", cpf: "", nomeMae: "", nomePai: "", telefone: "", email: "" };
      this.errors = {}; 
    } catch (error) {
      console.error("Erro ao criar pessoa:", error);

      console.log(error.response);

      if (error.response && error.response.status === 422) {
        this.errors = error.response.data.errors;
      }
    }
  },
  async updatePessoa() {
    const token = localStorage.getItem("authToken");
    try {
      console.log('Atualizando pessoa com dados:', this.editedPessoa); // Verificação dos dados
      await axios.put(
        `${import.meta.env.VITE_API_BASE_URL}/api/pessoas/${this.editedPessoa.id}`,
        this.editedPessoa,
        {
          headers: { Authorization: `Bearer ${token}` },
        }
      );
      this.fetchPessoas();
      this.editDialog = false;
      this.errors = {}; 
    } catch (error) {
      console.error("Erro ao editar pessoa:", error);
      if (error.response && error.response.status === 422) {
        this.errors = error.response.data.errors; 
      }
    }
  },
  async deletePessoa(pessoaId) {
    const token = localStorage.getItem("authToken");
    try {
      await axios.delete(
        `${import.meta.env.VITE_API_BASE_URL}/api/pessoas/${pessoaId}`,
        {
          headers: { Authorization: `Bearer ${token}` },
        }
      );
      this.fetchPessoas();
    } catch (error) {
      console.error("Erro ao deletar pessoa:", error);
    }
  },
  openCreateDialog() {
    this.createDialog = true;
  },
  openEditDialog(pessoa) {
    this.editedPessoa = { ...pessoa };
    this.editDialog = true;
    this.errors = {}; 
  },
  openAddressModal(pessoa) {
    this.selectedPessoa = pessoa;
    this.$refs.enderecosModal.open();
  },
},

  mounted() {
    this.fetchPessoas();
  },
};
</script>

<style>
.error {
  color: red;
  font-size: 0.9em;
}
</style>
