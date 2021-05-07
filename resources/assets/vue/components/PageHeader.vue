<template>
    <div>
        <div class="d-flex align-items-center">
            <strong v-if="size === 'sm'">{{ title }}</strong>
            <h3 v-else-if="size === 'md'">{{ title }}</h3>
            <h1 v-else>{{ title }}</h1>

            <div class="d-block d-block--right" v-if="hasActions">
                <slot />
            </div>
        </div>

        <div class="separator" v-if="addSeparation"></div>
    </div>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator'

@Component
export default class PageHeader extends Vue {
    @Prop({ required: true, type: String})
    public title!: string

    @Prop({ required: false, type: String })
    public size!: string

    @Prop({ required: false, type: Boolean, default: true })
    public addSeparation!: boolean

    get hasActions(): boolean {
        return this.$slots?.default?.[0] ? true : false
    }
}
</script>
