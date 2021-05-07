<template>
    <div class="card card__light card__separation cursor-pointer" :class="{ 'card__hover': !editing }">
        <page-header size="sm" :add-separation="false" :title="task.description" v-if="!editing">
            <button @click.prevent="editing = true" class="button button__transparent button__small" :disabled="isSubmitting">
                <div class="material-icons text-small">edit</div>
            </button>

            <button @click.prevent="tryDelete" class="button button__transparent button__small" :disabled="isSubmitting">
                <div class="material-icons text-small">delete</div>
            </button>
        </page-header>
        <edit-task :value="task.description" :task-id="task.id" @close="editing = false" v-else />
        
        <i class="text-small">@{{ task.user.name }}</i>
    </div>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator'
import { namespace } from 'vuex-class'
import Board from '../../../ts/models/Board'
import Task from '../../../ts/models/Task'
import PageHeader from '../PageHeader.vue'
import EditTask from './EditTask.vue'

const Board = namespace('Board')
const Task = namespace('Task')

@Component({
    components: {
        'page-header': PageHeader,
        'edit-task': EditTask
    }
})
export default class TaskCard extends Vue {
    @Prop({ required: true, type: Object })
    public task!: Task

    @Board.Getter
    public board!: Board

    @Task.Action
    public delete!: (payload: any) => Promise<any>

    public description: string | null = null
    public editing: boolean = false
    public isSubmitting: boolean = false

    public tryDelete(): void {
        this.isSubmitting = true

        this.delete({
            board_id: this.board.id,
            task_id: this.task.id
        })
        .finally(() => this.isSubmitting = false)
    }
}
</script>
