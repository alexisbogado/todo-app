<template>
    <div class="container mx-auto">
        <page-header title="Sign up" />

        <form @submit.prevent="tryRegister" class="card full-width form" autocomplete="off">
            <form-group type="text" id="name" label="Username" :disabled="isSubmitting" :error-message="getError('username')" @change="value => username = value" />

            <form-group type="email" id="email" label="Email" :disabled="isSubmitting" :error-message="getError('email')" @change="value => email = value" />

            <form-group type="password" id="password" label="Password" :disabled="isSubmitting" :error-message="getError('password')" @change="value => password = value" />

            <button type="submit" class="button full-width" :disabled="isSubmitting">Sign up</button>

            <div class="mx-auto">
                Already have an account? <router-link :to="{ name: 'login' }">Sign in</router-link>
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
export default class Register extends Vue {
    @Navigation.Action
    public hideNavigationBar!: () => void

    @User.Action
    public register!: (payload: any) => Promise<any>

    public username: string | null = null
    public email: string | null = null
    public password: string | null = null
    public isSubmitting: boolean = false
    public errors: CustomObject | null = null

    public getError(input: string): string | null {
        return this.errors?.[input] || null
    }

    public tryRegister(): void {
        this.isSubmitting = true

        this.register({
            username: this.username,
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
