import { createRouter, createWebHistory } from "vue-router";
import SubscribersView from "../views/SubscribersView.vue";
import CreateSubscriberView from "../views/CreateSubscriberView.vue";
import EditSubscriberView from "../views/EditSubscriberView.vue";
import FieldsView from "../views/FieldsView.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "subscribers",
      component: SubscribersView,
    },
    {
      path: "/create-subscriber",
      name: "create-subscriber",
      component: CreateSubscriberView,
    },
    {
      path: "/subscribers/:id",
      name: "edit-subscriber",
      component: EditSubscriberView,
    },
    {
      path: "/fields",
      name: "fields",
      component: FieldsView,
    },
  ],
});

export default router;
