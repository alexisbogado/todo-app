import Service from './Service'
import axios from 'axios'
import Task from '../models/Task'

class TaskService extends Service {
    public async loadStatuses(): Promise<any> {
        return await axios
            .get(`${this.url}/tasks/statuses`)
    }

    public async create(boardId: number, status_id: number, description: string): Promise<any> {
        return await axios
            .post(`${this.url}/boards/${boardId}/tasks`, {
                status_id,
                description
            })
    }

    public async delete(boardId: number, taskId: number): Promise<any> {
        return await axios
            .delete(`${this.url}/boards/${boardId}/tasks/${taskId}`)
    }

    public async updateAll(boardId: number, tasks: Task[]): Promise<any> {
        return await axios
            .put(`${this.url}/boards/${boardId}/tasks`, { tasks })
    }

    public async edit(
        boardId: number,
        taskId: number,
        payload: { status_id?: number, description?: string }
    ): Promise<any> {
        return await axios
            .put(`${this.url}/boards/${boardId}/tasks/${taskId}`, payload)
    }
}

export default new TaskService
