<template>
    <div>
        <delete-board-modal v-if="isDeleteBoardModalVisible" @close-modal="closeDeleteBoardModal" />
        <edit-board-modal v-if="isEditBoardModalVisible" @close-modal="closeEditBoardModal" />
    </div>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator'
import { namespace } from 'vuex-class'
import DeleteBoardModal from './DeleteBoardModal.vue'
import EditBoardModal from './EditBoardModal.vue'

const Board = namespace('Board')
const Modals = namespace('Modals')

@Component({
    components: {
        'delete-board-modal': DeleteBoardModal,
        'edit-board-modal': EditBoardModal,
    }
})
export default class BoardActionsModals extends Vue {
    @Modals.Getter
    public isDeleteBoardModalVisible!: boolean
    
    @Modals.Getter
    public isEditBoardModalVisible!: boolean

    @Modals.Action
    public hideDeleteModal!: () => void

    @Modals.Action
    public hideEditModal!: () => void

    @Board.Action
    public desactivateBoard!: () => void

    public closeDeleteBoardModal(): void {
        this.hideDeleteModal()

        if (this.$route.name !== 'board') {
            this.desactivateBoard()
        }
    }
    
    public closeEditBoardModal(): void {
        this.hideEditModal()

        if (this.$route.name !== 'board') {
            this.desactivateBoard()
        }
    }
}
</script>
