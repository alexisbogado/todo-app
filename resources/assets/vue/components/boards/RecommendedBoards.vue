<template>
    <div>
        <page-header title="Recommended Boards" />

        <div class="d-grid" v-if="recommendedBoards.length">
            <board-card v-for="board in recommendedBoards" :key="board.id" :board="board" />
        </div>
        <p class="text-center" v-else>
            We didn't find any recommended boards for you at this moment, try again later!
        </p>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { namespace } from 'vuex-class'
import Board from '../../../ts/models/Board'
import PageHeader from '../PageHeader.vue'
import BoardCard from './BoardCard.vue'

const Board = namespace('Board')

@Component({
    components: {
        'page-header': PageHeader,
        'board-card': BoardCard
    }
})
export default class RecommendedBoards extends Vue {
    @Board.Getter
    public recommendedBoards!: Board[]
}
</script>
