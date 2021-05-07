<template>
    <div class="container mx-auto">
        <page-header title="Error 404" />
        
        <div class="card full-width">
            <p>Oops! The page that you have requested is not available</p>
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { namespace } from 'vuex-class'
import PageHeader from '../components/PageHeader.vue'

const Navigation = namespace('Navigation')

@Component({
    components: {
        'page-header': PageHeader
    }
})
export default class Error extends Vue {
    @Navigation.Action
    public showNavigationBar!: (payload: any) => void

    created(): void {
        this.showNavigationBar({
            action: () => this.$router.back(),
            title: ''
        })
    }
}
</script>
