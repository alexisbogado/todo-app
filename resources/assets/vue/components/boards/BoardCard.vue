<template>
    <router-link :to="{ name: 'board', params: { id: board.id } }" class="card card__hover">        
        <page-header size="md" :title="board.title">
            <slot v-if="hasActions" />
        </page-header>

        <div class="separator"></div>

        <p class="text-small">{{ board.description }}</p>
    </router-link>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator'
import Board from '../../../ts/models/Board'
import PageHeader from '../PageHeader.vue'

@Component({
    components: {
        'page-header': PageHeader
    }
})
export default class BoardCard extends Vue {
    @Prop({ required: true, type: Object })
    public board!: Board

    get hasActions(): boolean {
        return this.$slots?.default?.[0] ? true : false
    }
}
</script>
