<template>
    <div class="card d-flex d-flex--wrap align-items-center">
        <button @click="action" class="button button__transparent button__small" v-if="action">
            <span class="material-icons">arrow_back</span>
        </button>

        <strong class="d-block" :class="[ action ? 'mx-auto': 'd-block--left' ]">{{ title }}</strong>

        <router-link :to="{ name: 'logout' }" class="button button__transparent button__small" v-if="isLoggedIn">Logout</router-link>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { namespace } from 'vuex-class'

const User = namespace('User')
const Navigation = namespace('Navigation')

@Component
export default class NavigationBar extends Vue {
    @Navigation.Getter
    public action!: Object

    @Navigation.Getter
    public title!: string | null

    @User.Getter
    public isLoggedIn!: boolean
}
</script>
