import User from '../../models/User'

export default interface AuthData {
    token: string,
    user: User
}
