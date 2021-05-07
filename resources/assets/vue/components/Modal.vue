<template>
    <div class="modal-wrapper">
        <div class="container card">
            <page-header :title="title">
                <button class="button button__transparent button__small d-block d-block--right" @click="$emit('close')">
                    <span class="material-icons">close</span>
                </button>
            </page-header>

            <div class="separator"></div>

            <slot />
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator'
import PageHeader from './PageHeader.vue'

@Component({
    components: {
        'page-header': PageHeader
    }
})
export default class Modal extends Vue {
    @Prop({ required: false, type: String })
    public title!: string
}
</script>
