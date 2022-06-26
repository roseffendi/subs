<script setup>
import Spinner from "../components/Spinner.vue";
</script>

<script>
import axios from "axios";
import { RouterLink } from "vue-router";

export default {
  data() {
    return {
      isLoading: true,
      fields: [],
      subscribers: []
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
    getFieldValue(field, subscriber) {
      const subscriberField = subscriber.fields.find(subscriberField => subscriberField.id === field.id);

      return subscriberField ? subscriberField.pivot.value : '';
    },
    async fetchData() {
      try {
        this.isLoading = true;
        const fieldsResponse = await axios.get("http://localhost/api/v1/fields");
        const fieldData = fieldsResponse.data;

        this.fields = fieldData;

        const response = await axios.get("http://localhost/api/v1/subscribers");
        const data = response.data;

        this.subscribers = data;
        this.isLoading = false;
      } catch (err) {
        console.log(err)
      }
    },
    async removeSubscriber(id) {
      try {
        await axios.delete(`http://localhost/api/v1/subscribers/${id}`);
        await this.fetchData();
      } catch(err) {
        console.log(err);
      }
    },
  },
};
</script>

<template>
  <div class="relative">
    <div class="flex justify-end mb-5">
      <RouterLink
        class="
          bg-blue-500
          hover:bg-blue-700
          text-white text-sm
          py-1
          px-2
          rounded
        "
        to="/create-subscriber"
      >
        Create New Subscriber
      </RouterLink>
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
          <th scope="col" class="px-6 py-3">Name</th>
          <th scope="col" class="px-6 py-3">Email</th>
          <th scope="col" class="px-6 py-3">State</th>
          <th v-for="field in fields" :key="field.id" scope="col" class="px-6 py-3">{{ field.title }}</th>
          <th scope="col" class="px-6 py-3">
            <span class="sr-only">Edit</span>
            <span class="sr-only">Remove</span>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="subscribers.length === 0">
          <td
            scope="row"
            :colspan="3 + fields.length"
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
          v-for="subscriber in subscribers"
          :key="subscriber.id"
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
            {{ subscriber.name }}
          </td>
          <td class="px-6 py-4">{{ subscriber.email }}</td>
          <td class="px-6 py-4 capitalize">{{ subscriber.state }}</td>
          <td v-for="field in fields" :key="field.id" class="px-6 py-4 capitalize">
            {{ getFieldValue(field, subscriber) }}
          </td>
          <td class="px-6 py-4 text-right">
            <RouterLink
              :to="`/subscribers/${subscriber.id}`"
              class="
                font-medium
                text-blue-600
                dark:text-blue-500
                hover:underline
              "
              >Edit</RouterLink
            >
            <a
              href="#"
              @click="() => removeSubscriber(subscriber.id)"
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
  </div>
</template>
