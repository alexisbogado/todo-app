import { Module, VuexModule, Mutation, Action } from 'vuex-module-decorators'
import AuthData from '../interfaces/AuthData'
import User from '../../models/User'
import AuthenticationService from '../../services/AuthenticationService'

const storedAuthData: string | null = localStorage.getItem('authData') || null

@Module({
    namespaced: true
})
export default class UserModule extends VuexModule {
    public authData: AuthData | null = storedAuthData && JSON.parse(storedAuthData)

    get isLoggedIn(): boolean {
        return this.authData ? true : false
    }

    get token(): string | null {
        return this.authData?.token || null
    }

    get user(): User | null {
        return this.authData?.user || null
    }

    @Mutation
    public setAuthData(authData: AuthData | null = null): void {
        this.authData = authData
    }

    @Action({ rawError: true })
    public login(payload: any): Promise<any> {
        return AuthenticationService
            .login(payload.email, payload.password)
            .then(data => {
                this.context.commit('setAuthData', data)

                return Promise.resolve(data)
            }, ({ response }) => Promise.reject(response))
    }

    @Action({ rawError: true })
    public logout(): Promise<any> {
        return AuthenticationService
            .logout()
            .finally(() => {
                this.context.commit('setAuthData')
            })
    }

    @Action({ rawError: true })
    public register(payload: any): Promise<any> {
        return AuthenticationService
            .register(payload.username, payload.email, payload.password)
            .then(data => {
                this.context.commit('setAuthData', data)

                return Promise.resolve(data)
            }, ({ response }) => Promise.reject(response))
    }
}
