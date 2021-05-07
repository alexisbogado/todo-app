import Service from './Service'
import axios from 'axios'

class UserService extends Service {
    public async getUser(): Promise<any> {
        return await axios
            .get(`${this.url}/user`)
    }
}

export default new UserService
