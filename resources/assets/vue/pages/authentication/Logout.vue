<template>
    <div class="container mx-auto">
        <page-header title="Sign out" />

        <div class="card">
            Signing out...
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { namespace } from 'vuex-class'
import PageHeader from '../../components/PageHeader.vue'

const Navigation = namespace('Navigation')
const User = namespace('User')

@Component({
    components: {
        'page-header': PageHeader,
    }
})
export default class Logout extends Vue {
    @Navigation.Action
    public hideNavigationBar!: () => void

    @User.Action
    public logout!: () => Promise<any>

    created(): void {
        this.hideNavigationBar()
        this.logout()
            .finally(() => this.$router.push({ name: 'login' }))
    }
}
</script>
