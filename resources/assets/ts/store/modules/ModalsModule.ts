import { Module, VuexModule, Mutation, Action } from 'vuex-module-decorators'

@Module({
    namespaced: true
})
export default class ModalsModule extends VuexModule {
    public isEditBoardModalElementVisible: boolean = false
    public isDeleteBoardModalElementVisible: boolean = false

    get isEditBoardModalVisible(): boolean {
        return this.isEditBoardModalElementVisible
    }

    get isDeleteBoardModalVisible(): boolean {
        return this.isDeleteBoardModalElementVisible
    }

    @Mutation
    public setEditModalVisibility(isVisible: boolean): void {
        this.isEditBoardModalElementVisible = isVisible
    }

    @Mutation
    public setDeleteModalVisibility(isVisible: boolean): void {
        this.isDeleteBoardModalElementVisible = isVisible
    }

    @Action
    public showEditModal(): void {
        this.context.commit('setEditModalVisibility', true)
    }

    @Action
    public hideEditModal(): void {
        this.context.commit('setEditModalVisibility', false)
    }

    @Action
    public showDeleteModal(): void {
        this.context.commit('setDeleteModalVisibility', true)
    }

    @Action
    public hideDeleteModal(): void {
        this.context.commit('setDeleteModalVisibility', false)
    }
}
