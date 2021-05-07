import Model from './Model'
import User from './User'

export default interface Task extends Model {
    status_id?: number,
    description: string,
    order: number,
    user: User,
}
