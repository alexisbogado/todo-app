<template>
    <div class="container mx-auto" v-if="isLoaded">
        <my-boards />

        <div class="separator"></div>

        <recommended-boards />
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { namespace } from 'vuex-class'
import User from '../../../ts/models/User'
import MyBoards from '../../components/boards/MyBoards.vue'
import RecommendedBoards from '../../components/boards/RecommendedBoards.vue'

const Navigation = namespace('Navigation')
const User = namespace('User')
const Board = namespace('Board')

@Component({
    components: {
        'my-boards': MyBoards,
        'recommended-boards': RecommendedBoards,
    }
})
export default class AllBoards extends Vue {
    @Navigation.Action
    public showNavigationBar!: (payload: any) => void

    @User.Getter
    public user!: User

    @Board.Action
    public desactivateBoard!: () => void

    @Board.Action
    public loadBoards!: () => Promise<any>

    public isLoaded: boolean = false

    created(): void {
        this.desactivateBoard()
        this.loadBoards()
            .finally(() => this.isLoaded = true)

        this.showNavigationBar({
            action: null,
            title: `Welcome @${this.user.name}`
        })
    }
}
</script>
