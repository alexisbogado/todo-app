<template>
    <div class="card card__light d-flex align-items-top">
        <form @submit.prevent="tryCreate" class="full-width form" autocomplete="off">
            <form-group type="textarea" id="description" label="" :disabled="isSubmitting" :error-message="getError('description')" @change="value => description = value" />

            <button class="button button__small" :disabled="isSubmitting">Create Task</button>
        </form>

        <button class="button button__transparent button__small d-block d-block--right" @click="$emit('close')" :disabled="isSubmitting">
            <span class="material-icons text-small">close</span>
        </button>
    </div>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator'
import { namespace } from 'vuex-class'
import Task from '../../../ts/models/Task'
import FormGroup from '../FormGroup.vue'
import CustomObject from '../../../ts/utils/CustomObject'
import Board from '../../../ts/models/Board'

const Board = namespace('Board')
const Task = namespace('Task')

@Component({
    components: {
        'form-group': FormGroup
    }
})
export default class TaskColumn extends Vue {
    @Prop({ required: true, type: Number })
    public statusId!: number

    @Board.Getter
    public board!: Board

    @Task.Getter
    public tasks!: Task[]

    @Task.Action
    public create!: (payload: any) => Promise<any>

    public description: string | null = null
    public isSubmitting: boolean = false
    public errors: CustomObject | null = null

    public getError(input: string): string | null {
        return this.errors?.[input] || null
    }

    public tryCreate(): void {
        this.isSubmitting = true

        this.create({
            board_id: this.board.id,
            status_id: this.statusId,
            description: this.description,
            order: ((this.tasks.length || 1) - 1)
        })
        .then(() => this.$emit('close'))
        .catch(({ data }) => this.errors = data.contents?.errors)
        .finally(() => this.isSubmitting = false)
    }
}
</script>
