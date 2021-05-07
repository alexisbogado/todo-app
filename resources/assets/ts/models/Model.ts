import CustomObject from '../utils/CustomObject'

export default interface Model extends CustomObject {
    id?: number,
    created_at?: Date,
    updated_at?: Date
}
