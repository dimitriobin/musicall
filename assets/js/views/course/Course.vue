<template>
  <b-loading v-if="isLoading" active/>
  <div v-else>

    <breadcrumb
        :root="{to: {name: 'home'}, label: 'Home'}"
        :current="{label: 'Cours'}"
    />

    <h1 class="subtitle is-3">
      Cours
      <b-tooltip label="Ajouter un cours vidéo YouTube" type="is-dark" class="is-pulled-right">
        <b-button v-if="isAuthenticated" class="youtube-btn" rounded
                  icon-left="youtube" icon-pack="fab"
                  @click="openAddVideoModal">
          Ajouter
        </b-button>
      </b-tooltip>
    </h1>
    <div class="columns mt-5 course-categories">
      <router-link
          tag="div"
          :to="{name:'course_by_category', params: {slug: category.slug}}"
          v-for="category in courseCategories"
          :key="category.order"
          class="column has-text-centered mb-3 is-clickable has-text-dark">
        <b-image :src="`/build/images/cours/${category.slug}.png`" responsive/>
        <span class="is-block mt-3 is-uppercase">{{ category.title }}</span>
      </router-link>
    </div>

  </div>
</template>

<script>
import {mapGetters} from 'vuex';
import Breadcrumb from "../../components/global/Breadcrumb";
import AddVideoForm from "../user/Publication/add/video/AddVideoForm";

export default {
  components: {AddVideoForm, Breadcrumb},
  computed: {
    ...mapGetters('security', ['isAuthenticated']),
    ...mapGetters('publicationCategory', ['isLoading', 'courseCategories'])
  },
  metaInfo() {
    return {
      title: 'Liste des catégories de cours - MusicAll',
    }
  },
  mounted() {
    this.$store.dispatch('publicationCategory/getCategories');
  },
  methods: {
    openAddVideoModal() {
      this.$buefy.modal.open({
        parent: this,
        component: AddVideoForm,
        props: {displayCategories: true, categories: this.courseCategories, isCoursesContext: true},
        hasModalCard: true,
        trapFocus: true
      })
    }
  }
}
</script>
