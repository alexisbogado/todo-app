<template>
    <div>
        <page-header title="My Boards">
            <button @click="isCreateBoardModalVisible = true" class="button button__transparent">
                <span class="material-icons">add_circle</span>
            </button>
        </page-header>

        <div class="d-grid" v-if="myBoards.length">
            <board-card v-for="board in myBoards" :key="board.id" :board="board">
                <board-actions :board-id="board.id" v-if="board.owner.id === user.id" />
            </board-card>
        </div>
        <p class="text-center" v-else>
            You don't have any board yet, click on the button <span class="material-icons text-small">add_circle</span> to create your first board!
        </p>

        <create-board-modal v-if="isCreateBoardModalVisible" @close-modal="isCreateBoardModalVisible = false" />
        <board-actions-modals />
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { namespace } from 'vuex-class'
import Board from '../../../ts/models/Board'
import User from '../../../ts/models/User'
import PageHeader from '../PageHeader.vue'
import BoardCard from './BoardCard.vue'
import CreateBoardModal from '../modals/boards/CreateBoardModal.vue'
import BoardActions from './BoardActions.vue'
import BoardActionsModals from '../modals/boards/BoardActionsModals.vue'

const User = namespace('User')
const Board = namespace('Board')

@Component({
    components: {
        'page-header': PageHeader,
        'board-card': BoardCard,
        'create-board-modal': CreateBoardModal,
        'board-actions': BoardActions,
        'board-actions-modals': BoardActionsModals,
    }
})
export default class MyBoards extends Vue {
    @User.Getter
    public user!: User

    @Board.Getter
    public myBoards!: Board[]

    public isCreateBoardModalVisible: boolean = false
}
</script>
