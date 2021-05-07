<template>
    <div class="container mx-auto">
        <page-header title="Sign in" />

        <form @submit.prevent="tryLogin" class="card full-width form" autocomplete="off">
            <form-group type="email" id="email" label="Email" :disabled="isSubmitting" :error-message="getError('email')" @change="value => email = value" />
            
            <form-group type="password" id="password" label="Password" :disabled="isSubmitting" :error-message="getError('password')" @change="value => password = value" />

            <button type="submit" class="button full-width" :disabled="isSubmitting">Sign in</button>

            <div class="mx-auto">
                Don't have an account? <router-link :to="{ name: 'register' }">Create one</router-link>
            </div>
        </form>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { namespace } from 'vuex-class'
import CustomObject from '../../../ts/utils/CustomObject'
import FormGroup from '../../components/FormGroup.vue'
import PageHeader from '../../components/PageHeader.vue'

const Navigation = namespace('Navigation')
const User = namespace('User')

@Component({
    components: {
        'page-header': PageHeader,
        'form-group': FormGroup
    }
})
export default class Login extends Vue {
    @Navigation.Action
    public hideNavigationBar!: () => void

    @User.Action
    public login!: (payload: any) => Promise<any>

    public email: string | null = null
    public password: string | null = null
    public isSubmitting: boolean = false
    public errors: CustomObject | null = null

    public getError(input: string): string | null {
        return this.errors?.[input] || null
    }

    public tryLogin(): void {
        this.isSubmitting = true

        this.login({
            email: this.email,
            password: this.password
        })
        .then(() => this.$router.push({ name: 'boards' }))
        .catch(({ data }) => this.errors = data.contents?.errors)
        .finally(() => this.isSubmitting = false)
    }

    created(): void {
        this.hideNavigationBar()   
    }
}
</script>
