import Vue from 'vue'
import { Module, VuexModule, Mutation, Action } from 'vuex-module-decorators'
import Board from '../../models/Board'
import BoardService from '../../services/BoardService'

@Module({
    namespaced: true
})
export default class BoardModule extends VuexModule {
    public boards: Board[] = [ ]
    public activeBoard: Board | null = null

    get myBoards(): Board[] {
        return this.boards.filter(board => board.is_member)
    }

    get recommendedBoards(): Board[] {
        return this.boards.filter(board => !board.is_member)
    }

    get board(): Board | null {
        return this.activeBoard
    }

    @Mutation
    public setBoards(payload: Board[]): void {
        this.boards = payload
    }

    @Mutation
    public setBoard(payload: Board): void {
        const boardIndex = this.boards.findIndex(board => board.id === payload.id)
        
        if (boardIndex) {
            Vue.set(this.boards, boardIndex, payload)
        }

        if (this.activeBoard?.id === payload.id) {
            this.activeBoard = payload
        }
    }

    @Mutation
    public setActiveBoard(board: Board | null = null): void {
        this.activeBoard = board
    }

    @Action({ rawError: true })
    public loadBoards(): Promise<any> {
        return BoardService
            .loadAll()
            .then(({ data }) => {
                this.context.commit('setBoards', data.contents.boards)

                return Promise.resolve(data)
            }, ({ response }) => Promise.reject(response))
    }

    @Action
    public activateBoard(id: number): void {
        if (this.board?.id === id) {
            return
        }

        const board = this.boards.find(board => board.id === id)
        this.context.commit('setActiveBoard', board)
    }

    @Action
    public desactivateBoard(): void {
        this.context.commit('setActiveBoard')
    }

    @Action({ rawError: true })
    public loadBoard(id: number): Promise<any> {
        return BoardService
            .load(id)
            .then(({ data }) => {
                const board = { ...data.contents.board }
                delete board.tasks

                this.context.commit('setActiveBoard', board)

                return Promise.resolve(data)
            }, ({ response }) => Promise.reject(response))
    }

    @Action({ rawError: true })
    public delete(): Promise<any> {
        return BoardService
            .delete(this.board?.id || 0)
            .then(({ data }) => {
                const boards = this.boards.filter(board => board.id !== this.board?.id)
                this.context.commit('setBoards', boards)

                return Promise.resolve(data)
            }, ({ response }) => Promise.reject(response))
    }
    
    @Action({ rawError: true })
    public create(payload: any): Promise<any> {
        return BoardService
            .create(payload.title, payload.description)
            .then(({ data }) => {
                this.context.commit('setBoards', [
                    ...this.boards,
                    data.contents.board,
                ])

                return Promise.resolve(data)
            }, ({ response }) => Promise.reject(response))
    }
    
    @Action({ rawError: true })
    public edit(payload: any): Promise<any> {
        return BoardService
            .edit((this.board?.id || 0), payload.title, payload.description)
            .then(({ data }) => {
                this.context.commit('setBoard', data.contents.board)

                return Promise.resolve(data)
            }, ({ response }) => Promise.reject(response))
    }
}
