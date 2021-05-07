<template>
    <div class="text-center">
        <strong>Oops! You are not joined to this board</strong>

        <div class="separator"></div>
        
        <p class="text-small text-center">
            To have access to this board content you need to be a member.<br />
            Click on the button below to join this board.
        </p>

        <div class="separator"></div>

        <button @click="tryJoin" :disabled="isSubmitting" class="button d-block mx-auto">Join Now!</button>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { namespace } from 'vuex-class'
import BoardService from '../../../ts/services/BoardService'

const Board = namespace('Board')

@Component
export default class JoinBoard extends Vue {
    @Board.Action
    public loadBoard!: (id: number) => Promise<any>

    public isSubmitting: boolean = false

    public tryJoin(): void {
        this.isSubmitting = true

        const boardId = parseInt(this.$route.params.id)
        BoardService.join(boardId)
            .then(() => this.loadBoard(boardId))
            .finally(() => {
                this.isSubmitting = false
            })
    }
}
</script>
