import Service from './Service'
import axios from 'axios'

class BoardService extends Service {
    public async loadAll(): Promise<any> {
        return await axios
            .get(`${this.url}/boards`)
    }

    public async load(boardId: number): Promise<any> {
        return await axios
            .get(`${this.url}/boards/${boardId}`)
    }

    public async delete(boardId: number): Promise<any> {
        return await axios
            .delete(`${this.url}/boards/${boardId}`)
    }

    public async create(title: string, description: string): Promise<any> {
        return await axios
            .post(`${this.url}/boards`, {
                title,
                description
            })
    }

    public async edit(boardId: number, title: string, description: string): Promise<any> {
        return await axios
            .put(`${this.url}/boards/${boardId}`, {
                title,
                description
            })
    }

    public async join(boardId: number): Promise<any> {
        return await axios
            .post(`${this.url}/boards/${boardId}/users`)
            .then(async () => await this.load(boardId))
    }

    public async leave(boardId: number): Promise<any> {
        return await axios
            .delete(`${this.url}/boards/${boardId}/users`)
            .then(async () => await this.load(boardId))
    }
}

export default new BoardService
