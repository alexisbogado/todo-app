<template>
    <modal title="Add New Board" @close="$emit('close-modal')">
        <form @submit.prevent="tryCreate" class="form" autocomplete="off">
            <form-group type="text" id="title" label="Title" :disabled="isSubmitting" :error-message="getError('title')" @change="value => title = value" />
            
            <form-group type="textarea" id="description" label="Description" :disabled="isSubmitting" :error-message="getError('description')" @change="value => description = value" />

            <button type="submit" class="button full-width" :disabled="isSubmitting">Create</button>
        </form>

        <div class="separator"></div>
    </modal>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { namespace } from 'vuex-class'
import CustomObject from '../../../../ts/utils/CustomObject'
import FormGroup from '../../FormGroup.vue'
import Modal from '../../Modal.vue'

const Board = namespace('Board')

@Component({
    components: {
        'modal': Modal,
        'form-group': FormGroup
    }
})
export default class CreateBoardModal extends Vue {
    @Board.Action
    public create!: (payload: any) => Promise<any>

    public title: string | null = null
    public description: string | null = null
    public isSubmitting: boolean = false
    public errors: CustomObject | null = null

    public getError(input: string): string | null {
        return this.errors?.[input] || null
    }

    public tryCreate(): void {
        this.isSubmitting = true

        this.create({
            title: this.title,
            description: this.description
        })
        .then(() => this.$emit('close-modal'))
        .catch(({ data }) => this.errors = data.contents?.errors)
        .finally(() => this.isSubmitting = false)
    }
}
</script>
