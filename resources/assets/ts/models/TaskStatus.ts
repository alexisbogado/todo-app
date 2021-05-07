import Model from './Model'

export default interface TaskStatus extends Model {
    code: string,
    display_name: string,
    order: number,
}
