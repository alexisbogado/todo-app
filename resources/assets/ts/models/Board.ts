import Model from './Model'
import User from './User'
import Task from './Task'

export default interface Board extends Model {
    owner: User,
    title: string,
    description: string,
    member_count: number,
    is_member: boolean,
    tasks: Task[]
}
