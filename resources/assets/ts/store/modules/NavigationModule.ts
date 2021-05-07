import { Module, VuexModule, Mutation, Action } from 'vuex-module-decorators'
import NavigationBar from '../interfaces/NavigationBar'

@Module({
    namespaced: true
})
export default class NavigationModule extends VuexModule {
    public isRouteLoading: boolean = false
    public isElementVisible: boolean = false
    public backAction: Object | null = null
    public text: string | null = null

    get isViewLoading(): boolean {
        return this.isRouteLoading
    }
    
    get isVisible(): boolean {
        return this.isElementVisible
    }

    get action(): Object | null {
        return this.backAction
    }

    get title(): string | null {
        return this.text
    }

    @Mutation
    public setLoaderVisibility(isVisible: boolean) {
        this.isRouteLoading = isVisible
    }

    @Mutation
    public show(payload: NavigationBar): void {
        this.isElementVisible = true
        this.text = payload.title
        this.backAction = payload.action
    }

    @Mutation
    public hide(): void {
        this.isElementVisible = false
        this.backAction = null
        this.text = null
    }

    @Mutation
    public changeTitle(title: string): void {
        this.text = title
    }

    @Action
    public showLoader(): void {
        this.context.commit('setLoaderVisibility', true)
    }

    @Action
    public hideLoader(): void {
        this.context.commit('setLoaderVisibility', false)
    }

    @Action
    public showNavigationBar(payload: NavigationBar): void {
        this.context.commit('show', payload)
    }

    @Action
    public hideNavigationBar(): void {
        this.context.commit('hide')
    }

    @Action
    public setTitle(title: string): void {
        this.context.commit('changeTitle', title)
    }
}
