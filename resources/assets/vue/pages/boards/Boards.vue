<template>
    <div class="container mx-auto" v-if="isLoaded">
        <page-header :title="getBoardValue('title')" :add-separation="false">
            <board-actions :board-id="getBoardValue('id')" :is-small="false" v-if="isOwner" />

            <button @click.prevent="leave" class="button button__transparent" v-else-if="!isOwner && getBoardValue('is_member')" :disabled="isLeaving">Leave</button>
        </page-header>
        
        <p class="text-small">{{ getBoardValue('description') }}</p>

        <div class="separator"></div>

        <kanban-board v-if="getBoardValue('is_member')" />
        <join-board v-else />

        <board-actions-modals />
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { namespace } from 'vuex-class'
import Board from '../../../ts/models/Board'
import User from '../../../ts/models/User'
import Task from '../../../ts/models/Task'
import TaskStatus from '../../../ts/models/TaskStatus'
import KanbanBoard from '../../components/boards/KanbanBoard.vue'
import JoinBoard from '../../components/boards/JoinBoard.vue'
import PageHeader from '../../components/PageHeader.vue'
import BoardActions from '../../components/boards/BoardActions.vue'
import BoardActionsModals from '../../components/modals/boards/BoardActionsModals.vue'
import BoardService from '../../../ts/services/BoardService'

const Navigation = namespace('Navigation')
const User = namespace('User')
const Board = namespace('Board')
const Task = namespace('Task')

@Component({
    components: {
        'page-header': PageHeader,
        'kanban-board': KanbanBoard,
        'join-board': JoinBoard,
        'board-actions': BoardActions,
        'board-actions-modals': BoardActionsModals,
    }
})
export default class Boards extends Vue {
    @User.Getter
    public user!: User

    @Navigation.Action
    public showNavigationBar!: (payload: any) => void

    @Board.Getter
    public board!: Board

    @Task.Getter
    public taskStatuses!: TaskStatus[]

    @Board.Action
    public loadBoard!: (id: number) => Promise<any>

    @Task.Action
    public loadTasks!: (payload: Task[]) => void

    @Task.Action
    public loadStatuses!: () => Promise<any>

    public isLoaded: boolean = false
    public isLeaving: boolean = false

    get isOwner(): boolean {
        return this.board?.owner.id === this.user.id
    }

    get currentBoard(): Board {
        return this.board
    }

    public getBoardValue(key: string): string | null {
        return this.currentBoard?.[key]
    }

    public leave(): void {
        this.isLeaving = true

        const boardId = parseInt(this.$route.params.id)
        BoardService.leave(boardId)
            .then(() => this.loadBoard(boardId))
            .finally(() => {
                this.isLeaving = false
            })
    }

    created(): void {
        this.loadBoard(parseInt(this.$route.params.id))
            .then(async ({ contents }) => {
                if (!this.taskStatuses.length) {
                    await this.loadStatuses()
                }

                this.loadTasks(contents.board.tasks)
            })
            .finally(() => {
                this.showNavigationBar({
                    action: () => this.$router.back(),
                    title: `${this.board?.member_count || 0} Members`
                })

                this.isLoaded = true
            })
    }
}
</script>
