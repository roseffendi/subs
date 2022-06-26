<script setup>
import Spinner from "../components/Spinner.vue";
import FieldFormModal from "../components/FieldFormModal.vue";
</script>

<script>
import axios from "axios";

export default {
  data() {
    return {
      isLoading: true,
      createModalShouldShown: false,
      updateModalShouldShown: false,
      isSubmitting: false,
      fields: [],
      fieldToUpdate: {}
    };
  },
  async mounted() {
    try {
      await this.fetchData();
    } catch (err) {
      console.log(err);
    }
  },
  methods: {
    async fetchData() {
      try {
        this.isLoading = true;
        const response = await axios.get("http://localhost/api/v1/fields");
        const data = response.data;

        this.fields = data;
        this.isLoading = false;
      } catch (err) {
        console.log(err)
      }
    },
    showCreateModal() {
      this.createModalShouldShown = true;
    },
    hideCreateModal() {
      this.createModalShouldShown = false;
    },
    showUpdateModal(id) {
      this.fieldToUpdate = this.fields.find(field => field.id === id);

      this.updateModalShouldShown = true;
    },
    hideUpdateModal() {
      this.updateModalShouldShown = false;
    },
    handleSubmit(data) {
      if(data.mode == 'create') {
        this.handleCreate(data.field);
      }else{
        this.handleUpdate(data.field);
      }
    },
    async removeField(id) {
      try {
        await axios.delete(`http://localhost/api/v1/fields/${id}`);
        await this.fetchData();
      } catch(err) {
        console.log(err);
      }
    },
    async handleCreate(field) {
      try {
        this.isSubmitting = true;

        await axios.post("http://localhost/api/v1/fields", {
          title: field.title,
          type: field.type,
        });

        await this.fetchData();

        this.hideCreateModal();
      } catch(err) {
        console.log(err);
      } finally {
        this.isSubmitting = false;
      }
    },
    async handleUpdate(field) {
      try {
        this.isSubmitting = true;
        
        await axios.put(`http://localhost/api/v1/fields/${field.id}`, {
          title: field.title
        });

        await this.fetchData();

        this.hideUpdateModal();
      } catch(err) {
        console.log(err);
      } finally {
        this.isSubmitting = false;
      }
    }
  },
};
</script>

<template>
  <div class="relative">
    <div class="flex justify-end mb-5">
      <button
        class="
          bg-blue-500
          hover:bg-blue-700
          text-white text-sm
          py-1
          px-2
          rounded
        "
        @click="showCreateModal"
      >
        Create New Field
      </button>
    </div>
    <Spinner v-if="isLoading" />
    <table
      v-else
      class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
    >
      <thead
        class="
          text-xs text-gray-700
          uppercase
          bg-gray-50
          dark:bg-gray-700 dark:text-gray-400
        "
      >
        <tr>
          <th scope="col" class="px-6 py-3">Title</th>
          <th scope="col" class="px-6 py-3">Type</th>
          <th scope="col" class="px-6 py-3">
            <span class="sr-only">Edit</span>
            <span class="sr-only">Remove</span>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="fields.length === 0">
          <td
            scope="row"
            colspan="2"
            class="
              px-6
              py-4
              font-medium
              text-gray-900
              dark:text-white
              whitespace-nowrap
            "
          >
            No Data Available
          </td>
        </tr>
        <tr
          v-for="field in fields"
          :key="field.id"
          class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
        >
          <td
            scope="row"
            class="
              px-6
              py-4
              font-medium
              text-gray-900
              dark:text-white
              whitespace-nowrap
            "
          >
            {{ field.title }}
          </td>
          <td class="px-6 py-4 capitalize">{{ field.type }}</td>
          <td class="px-6 py-4 text-right">
            <a
              @click="() => showUpdateModal(field.id)"
              href="#"
              class="
                font-medium
                text-blue-600
                dark:text-blue-500
                hover:underline
              "
              >Edit</a
            >
            <a
              href="#"
              @click="() => removeField(field.id)"
              class="
                ml-5
                font-medium
                text-blue-600
                dark:text-blue-500
                hover:underline
              "
              >Remove</a
            >
          </td>
        </tr>
      </tbody>
    </table>
    <FieldFormModal
      :shown="createModalShouldShown"
      :isSubmitting="isSubmitting"
      mode="create"
      @close="hideCreateModal"
      @submitted="handleSubmit"
    />
    <FieldFormModal
      :shown="updateModalShouldShown"
      :isSubmitting="isSubmitting"
      :initialField="fieldToUpdate"
      mode="update"
      @close="hideUpdateModal"
      @submitted="handleSubmit"
    />
  </div>
</template>
