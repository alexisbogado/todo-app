<template>
    <form @submit.prevent="tryEdit" class="form d-flex align-items-top" autocomplete="off">
        <form-group type="textarea" id="description" class="full-width d-block d-block--left" label="" :disabled="isSubmitting" :error-message="getError('description')" :initial-value="value" @change="value => description = value" />

        <button type="submit" class="button button__transparent button__small" :disabled="isSubmitting">
            <span class="material-icons text-small">save</span>
        </button>

        <button class="button button__transparent button__small" @click="$emit('close')" :disabled="isSubmitting">
            <span class="material-icons text-small">close</span>
        </button>
    </form>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator'
import { namespace } from 'vuex-class'
import Task from '../../../ts/models/Task'
import FormGroup from '../FormGroup.vue'
import CustomObject from '../../../ts/utils/CustomObject'

const Task = namespace('Task')

@Component({
    components: {
        'form-group': FormGroup
    }
})
export default class EditTask extends Vue {
    @Prop({ required: true, type: String })
    public value!: string

    @Prop({ required: true, type: Number })
    public taskId!: number

    @Task.Action
    public edit!: (payload: any) => Promise<any>

    public description: string | null = null
    public isSubmitting: boolean = false
    public errors: CustomObject | null = null

    public getError(input: string): string | null {
        return this.errors?.[input] || null
    }

    public tryEdit(): void {
        this.isSubmitting = true

        this.edit({
            board_id: parseInt(this.$route.params.id),
            task_id: this.taskId,
            contents: {
                description: this.description
            }
        })
        .then(() => this.$emit('close'))
        .catch(({ data }) => this.errors = data.contents?.errors)
        .finally(() => this.isSubmitting = false)
    }
}
</script>
