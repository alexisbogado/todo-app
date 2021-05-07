<template>
    <div>
        <button @click.prevent="openEditBoardModal" class="button button__transparent" :class="{ 'button__small': isSmall }" >
            <div class="material-icons" :class="{ 'text-small': isSmall }">edit</div>
        </button>

        <button @click.prevent="openDeleteBoardModal" class="button button__transparent" :class="{ 'button__small': isSmall }">
            <div class="material-icons" :class="{ 'text-small': isSmall }">delete</div>
        </button>
    </div>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator'
import { namespace } from 'vuex-class'

const Modals = namespace('Modals')
const Board = namespace('Board')

@Component
export default class BoardActions extends Vue {
    @Prop({ required: true, type: Number })
    public boardId!: number

    @Prop({ required: false, type: Boolean, default: true })
    public isSmall!: boolean

    @Modals.Action
    public showDeleteModal!: () => void

    @Modals.Action
    public showEditModal!: () => void

    @Board.Action
    public activateBoard!: (id: number) => void
    
    public openDeleteBoardModal(): void {
        this.showDeleteModal()
        
        if (this.$route.name !== 'board') {
            this.activateBoard(this.boardId)
        }
    }

    public openEditBoardModal(): void {
        this.showEditModal()
        
        if (this.$route.name !== 'board') {
            this.activateBoard(this.boardId)
        }
    }
}
</script>
