import Service from './Service'
import axios from 'axios'

class AuthenticationService extends Service {
    public async login(email: string, password: string): Promise<any> {
        return await axios
            .post(`${this.url}/auth/login`, {
                email,
                password
            })
            .then(({ data }) => this.authenticationSuccess(data))
    }

    public async logout(): Promise<any> {
        return await axios
            .post(`${this.url}/auth/logout`)
            .finally(() => {
                localStorage.removeItem('authData')
            })
    }

    public async register(username: string, email: string, password: string): Promise<any> {
        return await axios
            .post(`${this.url}/auth/register`, {
                username,
                email,
                password
            })
            .then(({ data }) => this.authenticationSuccess(data))
    }

    private authenticationSuccess(data: any): void {
        if (data.contents?.token) {
            localStorage.setItem('authData', JSON.stringify(data.contents))

            return data.contents
        }
        
        return data
    }
}

export default new AuthenticationService
