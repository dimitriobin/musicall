import Vue from "vue";
import Router from "vue-router";
import admin from './admin';
import course from './course';
import search from './search';
import forum from './forum';
import publication from './publication';
import user from './user';
import userPublication from './user-publication';
import gallery from './gallery';
import message from './message';
import userAnnounce from './user-announce';

Vue.use(Router);

export default new Router({
  mode: 'history',
  routes: [
    {
      path: "/",
      name: 'home',
      component: () => import("../views/Home"),
      meta: {isAuthRequired: false}
    },
    {
      path: "/contact",
      name: 'contact',
      component: () => import("../views/contact/Contact"),
      meta: {isAuthRequired: false}
    },
    ...publication,
    ...userPublication,
    ...gallery,
    ...user,
    ...admin,
    ...course,
    ...search,
    ...forum,
    ...message,
    ...userAnnounce,
  ]
});