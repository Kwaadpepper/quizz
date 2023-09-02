<template>
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-12">
          <!-- @include('bo.breadcrumb.breadcrumb-body') -->
        </div>
      </div>
      <div class="row">
        <div class="col">
          <span class="h3">{{ __('models.classes.customers') }}&nbsp;{{ __('Liste') }}</span>
        </div>
      </div>
    </div>
    <div class="card-body">
      <!-- @include('bo.modules.searchBar') -->
      <div class="row justify-content-center">
        <div class="col-12 col-md-6 p-0 p-md-3">
          <!-- {{ $customers->withQueryString()->onEachSide(1)->links() }} -->
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="table-responsive">
            <table class="table fix-action">
              <thead>
                <tr>
                  <th>className</th>
                  <th>name</th>
                  <th>action</th>
                </tr>
              </thead>
              <tbody>
                <template v-if="teams.data.length">
                  <tr
                    v-for="(team, teamIndex) of teams.data"
                    :key="teamIndex"
                  >
                    <td><b>{{ team.class }}</b></td>
                    <td><b>{{ team.name }}</b></td>
                    <td>
                      <div class="btn-group">
                        <a
                          class="btn btn-sm btn-warning"
                          href=""
                          title=""
                        >
                          <FontAwesomeIcon icon="eye" />
                        </a>
                      </div>
                    </td>
                  </tr>
                </template>
                <tr v-else>
                  <td
                    colspan="3"
                    class="text-center"
                  >
                    <span class="h3">{{
                      __('Aucun résultat pour les :models', { 'models': __('models.classes.teams') })
                    }}</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-12 col-md-6 p-0 p-md-3">
          <!-- {{ $customers->withQueryString()->onEachSide(1)->links() }} -->
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { defineComponent } from "vue";
import trans from "./../../../../modules/trans";
import { ActionTypes } from "./../../../../vuex/action-types";
import { ErrorType, getError } from "./../../../../vuex/errors";
import { createPaginator } from "./../../../../vuex/modelPaginator";
import { MutationTypes } from "./../../../../vuex/mutation-types";

export default defineComponent({
  name: "TeamsIndex",
  mixins: [trans],
  components: {
    FontAwesomeIcon
  },
  beforeMount() {
    this.$store.dispatch(ActionTypes.LOAD_RESOURCE, { resource: "teams", page: 1 })
      .then((response) => {
        if (typeof response === "boolean") {
          this.$store.commit(MutationTypes.APP_SET_ERROR, getError(
            ErrorType.SERVER_ERROR, this.__("Impossible de récupérer les équipes"))
          );
          return;
        }
        this.teams = response as Paginator<Team>;
      });

  },
  mounted() {

  },
  data(): {
    name: string;
    teams: Paginator<Team> ;
  } {
    return {
      name: "",
      teams: createPaginator()
    };
  },
  methods: {
    test() { }
  },
  computed: {},
});
</script>
