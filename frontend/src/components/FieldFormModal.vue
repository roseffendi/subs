<script setup>
import Modal from "./Modal.vue";
</script>

<script>
export default {
  props: {
    shown: Boolean,
    isSubmitting: Boolean,
    mode: String,
    initialField: Object,
  },
  data() {
    console.log(this.initialField);

    return {
      field: { ...this.initialField, ...{ title: "", type: "string" } },
    };
  },
  methods: {
    handleSubmit() {
      this.$emit("submitted", {
        field: this.field,
        mode: this.mode,
      });
    },
  },
  watch: {
    initialField(newVal, oldVal) {
      this.field = {...newVal};
    },
  },
};
</script>

<template>
  <Modal :shown="shown" v-on:close="$emit('close')">
    <div class="py-6 px-6 lg:px-8">
      <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">
        Create New Field
      </h3>
      <form class="space-y-6" @submit.prevent="handleSubmit">
        <div>
          <label
            for="title"
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
            type="title"
            name="title"
            v-model="field.title"
            id="title"
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
            placeholder="Field title"
            required
          />
        </div>
        <div>
          <label
            for="type"
            class="
              block
              mb-2
              text-sm
              font-medium
              text-gray-900
              dark:text-gray-300
            "
            >Type
          </label>
          <select
            v-model="field.type"
            :disabled="mode == 'update'"
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
            <option value="string">String</option>
            <option value="date">Date</option>
            <option value="number">Number</option>
            <option value="boolean">Boolean</option>
          </select>
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
  </Modal>
</template>