<script>
import axios from "axios";

export default {
  data() {
    return {
      isSubmitting: false,
      subscriber: {
        name: "",
        email: "",
        state: "unconfirmed",
        fields: [],
      },
    };
  },
  async mounted() {
    await this.fetchFields();

    console.log(this.subscriber);
  },
  methods: {
    async fetchFields() {
      try {
        const response = await axios.get("http://localhost/api/v1/fields");
        const data = response.data;

        this.subscriber.fields = data;
      } catch (err) {
        console.log(err);
      }
    },
    async handleSubmit() {
      try {
        this.isSubmitting = true;
        const data = {...this.subscriber};

        data.fields = data.fields.filter(field => field.value !== undefined);

        await axios.post("http://localhost/api/v1/subscribers", data);

        this.$router.push('/');
      } catch (err) {
        console.log(err)
      } finally {
        this.isSubmitting = false;
      }
    }
  },
};
</script>

<template>
  <div class="py-6 px-6 lg:px-8">
    <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">
      Create New Subscriber
    </h3>
    <form class="space-y-6" @submit.prevent="handleSubmit">
      <div>
        <label
          for="name"
          class="
            block
            mb-2
            text-sm
            font-medium
            text-gray-900
            dark:text-gray-300
          "
          >Title</label
        >
        <input
          type="text"
          name="name"
          v-model="subscriber.name"
          id="name"
          class="
            bg-gray-50
            border border-gray-300
            text-gray-900 text-sm
            rounded-lg
            focus:ring-blue-500 focus:border-blue-500
            block
            w-full
            p-2.5
            dark:bg-gray-600
            dark:border-gray-500
            dark:placeholder-gray-400
            dark:text-white
          "
          placeholder="Subscriber's name"
          required
        />
      </div>
      <div>
        <label
          for="email"
          class="
            block
            mb-2
            text-sm
            font-medium
            text-gray-900
            dark:text-gray-300
          "
          >Email</label
        >
        <input
          type="text"
          name="email"
          v-model="subscriber.email"
          id="email"
          class="
            bg-gray-50
            border border-gray-300
            text-gray-900 text-sm
            rounded-lg
            focus:ring-blue-500 focus:border-blue-500
            block
            w-full
            p-2.5
            dark:bg-gray-600
            dark:border-gray-500
            dark:placeholder-gray-400
            dark:text-white
          "
          placeholder="Subscriber's email"
          required
        />
      </div>
      <div>
        <label
          for="state"
          class="
            block
            mb-2
            text-sm
            font-medium
            text-gray-900
            dark:text-gray-300
          "
          >State
        </label>
        <select
          v-model="subscriber.state"
          class="
            bg-gray-50
            border border-gray-300
            text-gray-900 text-sm
            rounded-lg
            focus:ring-blue-500 focus:border-blue-500
            block
            w-full
            p-2.5
            dark:bg-gray-700
            dark:border-gray-600
            dark:placeholder-gray-400
            dark:text-white
            dark:focus:ring-blue-500
            dark:focus:border-blue-500
          "
        >
          <option value="active">Active</option>
          <option value="unsubscribed">Unsubscribed</option>
          <option value="junk">Junk</option>
          <option value="bounced">Bounced</option>
          <option value="unconfirmed">Unconfirmed</option>
        </select>
      </div>
      <div v-for="i in subscriber.fields.length" :key="i" :class="{
        'flex': subscriber.fields[i - 1].type == 'boolean',
        'items-center': subscriber.fields[i - 1].type == 'boolean'
      }">
        <label
          :for="subscriber.fields[i - 1].title"
          class="
            block
            mb-2
            text-sm
            font-medium
            text-gray-900
            dark:text-gray-300
          "
          >{{ subscriber.fields[i - 1].title }}
        </label>
        <input
          v-if="subscriber.fields[i - 1].type == 'string'"
          class="
            bg-gray-50
            border border-gray-300
            text-gray-900 text-sm
            rounded-lg
            focus:ring-blue-500 focus:border-blue-500
            block
            w-full
            p-2.5
            dark:bg-gray-700
            dark:border-gray-600
            dark:placeholder-gray-400
            dark:text-white
            dark:focus:ring-blue-500
            dark:focus:border-blue-500
          "
          type="text"
          v-model="subscriber.fields[i - 1].value"
        />
        <input
          v-else-if="subscriber.fields[i - 1].type == 'date'"
          class="
            bg-gray-50
            border border-gray-300
            text-gray-900 text-sm
            rounded-lg
            focus:ring-blue-500 focus:border-blue-500
            block
            w-full
            p-2.5
            dark:bg-gray-700
            dark:border-gray-600
            dark:placeholder-gray-400
            dark:text-white
            dark:focus:ring-blue-500
            dark:focus:border-blue-500
          "
          type="date"
          v-model="subscriber.fields[i - 1].value"
        />
        <input
          v-else-if="subscriber.fields[i - 1].type == 'number'"
          class="
            bg-gray-50
            border border-gray-300
            text-gray-900 text-sm
            rounded-lg
            focus:ring-blue-500 focus:border-blue-500
            block
            w-full
            p-2.5
            dark:bg-gray-700
            dark:border-gray-600
            dark:placeholder-gray-400
            dark:text-white
            dark:focus:ring-blue-500
            dark:focus:border-blue-500
          "
          type="number"
          v-model="subscriber.fields[i - 1].value"
        />
        <input
          v-else-if="subscriber.fields[i - 1].type == 'boolean'"
          class="
            w-4
            h-4
            ml-4
            mb-2
            text-blue-600
            bg-gray-100
            rounded
            border-gray-300
            focus:ring-blue-500
            dark:focus:ring-blue-600 dark:ring-offset-gray-800
            focus:ring-2
            dark:bg-gray-700 dark:border-gray-600
          "
          type="checkbox"
          v-model="subscriber.fields[i - 1].value"
        />
      </div>
      <button
        type="submit"
        :disabled="isSubmitting"
        class="
          w-full
          text-white
          bg-blue-700
          hover:bg-blue-800
          focus:ring-4 focus:outline-none focus:ring-blue-300
          font-medium
          rounded-lg
          text-sm
          px-5
          py-2.5
          text-center
          dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800
        "
      >
        Submit
      </button>
    </form>
  </div>
</template>