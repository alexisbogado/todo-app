<template>
    <div class="form__group">
        <label :for="id">{{ label }}</label>

        <textarea :placeholder="label" :id="id" class="form__input full-width" :class="{ 'form__input--invalid': errorMessage }" :disabled="disabled" v-model="value" v-if="isTextArea" />
        <input :type="type" :placeholder="label" :id="id" class="form__input full-width" :class="{ 'form__input--invalid': errorMessage }" :disabled="disabled" v-model="value" v-else />

        <div class="form__input--invalid__message" v-if="errorMessage">{{ errorMessage }}</div>
    </div>
</template>

<script lang="ts">
import { Component, Vue, Prop, Watch } from 'vue-property-decorator'

@Component
export default class FormGroup extends Vue {
    @Prop({ required: true, type: String })
    public id!: string

    @Prop({ required: true, type: String })
    public type!: string

    @Prop({ required: true, type: String })
    public label!: string

    @Prop({ required: true, type: Boolean})
    public disabled!: boolean

    @Prop({ required: false, type: String })
    public errorMessage!: string

    @Prop({ required: false, type: String })
    public initialValue!: string

    get isTextArea(): boolean {
        return this.type === 'textarea'
    }

    public value: string | null = null

    @Watch('value')
    onPropertyChanged(newValue: string) {
        this.$emit('change', newValue)
    }

    created(): void {
        if (this.initialValue) {
            this.value = this.initialValue
        }
    }
}
</script>
