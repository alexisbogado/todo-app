<template>
    <loader v-if="isViewLoading" />
    <div class="antialiased" v-else>
        <navigation-bar v-if="isVisible" />
        <router-view />
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { namespace } from 'vuex-class'
import Loader from './components/Loader.vue'
import NavigationBar from './components/NavigationBar.vue'

const Navigation = namespace('Navigation')

@Component({
    components: {
        'navigation-bar': NavigationBar,
        'loader': Loader
    }
})
export default class Layout extends Vue {
    @Navigation.Getter
    public isViewLoading!: boolean

    @Navigation.Getter
    public isVisible!: boolean
}
</script>
