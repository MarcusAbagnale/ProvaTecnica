<template>
  <v-dialog v-model="dialog" max-width="600px">
    <v-card>
      <v-card-title>
        <span class="headline">Criar Produto</span>
      </v-card-title>
      <v-card-text>
        <v-form ref="form">
          <v-text-field v-model="name" label="Nome" required></v-text-field>
          <v-text-field v-model="description" label="Descrição" required></v-text-field>
          <v-text-field v-model="price" label="Preço" required></v-text-field>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="close">Cancelar</v-btn>
        <v-btn color="blue darken-1" text @click="createProduct">Salvar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    authToken: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      dialog: false,
      name: '',
      description: '',
      price: '',
    };
  },
  methods: {
    open() {
      this.dialog = true;
    },
    close() {
      this.dialog = false;
    },
    createProduct() {
      axios.post('http://localhost:8000/api/products', {
        name: this.name,
        description: this.description,
        price: this.price,
      }, {
        headers: {
          'Authorization': `Bearer ${this.authToken}`,
        },
      })
      .then(() => {
        this.$emit('product-created');
        this.close();
      })
      .catch(error => {
        console.error(error);
      });
    },
  },
};
</script>
