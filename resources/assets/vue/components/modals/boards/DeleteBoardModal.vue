<template>
    <modal title="Delete Board" @close="$emit('close-modal')">
        <p>Are you sure that you want to delete this board? This action cannot be undone</p>

        <div class="separator"></div>
        <button class="button d-block d-block--right" :disabled="isSubmitting" @click="tryDelete">Yes, delete</button>
        <div class="separator"></div>
    </modal>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { namespace } from 'vuex-class'
import FormGroup from '../../FormGroup.vue'
import Modal from '../../Modal.vue'

const Board = namespace('Board')

@Component({
    components: {
        'modal': Modal,
        'form-group': FormGroup
    }
})
export default class DeleteBoardModal extends Vue {
    @Board.Action
    public delete!: () => Promise<any>

    public isSubmitting: boolean = false

    public tryDelete(): void {
        this.isSubmitting = true

        this.delete()
            .finally(() => {
                this.isSubmitting = false
                this.$emit('close-modal')

                if (this.$route.name === 'board') {
                    this.$router.push({ name: 'boards' })
                }
            })
    }
}
</script>
