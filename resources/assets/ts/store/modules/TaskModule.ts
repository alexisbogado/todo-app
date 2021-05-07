import { Module, VuexModule, Mutation, Action } from 'vuex-module-decorators'
import Task from '../../models/Task'
import TaskStatus from '../../models/TaskStatus'
import TaskService from '../../services/TaskService'

@Module({
    namespaced: true
})
export default class TaskModule extends VuexModule {
    public allTaskStatuses: TaskStatus[] = [ ]
    public allTasks: Task[] = [ ]

    get taskStatuses(): TaskStatus[] {
        return this.allTaskStatuses
    }

    get tasks(): Task[] {
        return this.allTasks
    }

    @Mutation
    public setTasks(payload: Task[]): void {
        this.allTasks = payload
    }

    @Mutation
    public setTask(payload: Task): void {
        const item = this.allTasks.find(currentTask => currentTask.id === payload.id)
            
        if (!item) {
            return
        }

        Object.assign(item, payload)
    }

    @Mutation
    public setTaskStatuses(payload: TaskStatus[]): void {
        this.allTaskStatuses = payload
    }

    @Mutation
    public setMovedTasks(payload: Task[]): void {
        payload.forEach(task => {
            const item = this.allTasks.find(currentTask => currentTask.id === task.id)
            
            if (!item) {
                return
            }

            Object.assign(item, task)
        })
    }

    @Action
    public loadTasks(payload: Task[]): void {
        this.context.commit('setTasks', payload)
    }

    @Action({ rawError: true })
    public loadStatuses(): Promise<any> {
        return TaskService
            .loadStatuses()
            .then(({ data }) => {
                this.context.commit('setTaskStatuses', data.contents.statuses)

                return Promise.resolve(data)
            }, ({ response }) => Promise.reject(response))
    }

    @Action({ rawError: true })
    public create(payload: any): Promise<any> {
        return TaskService
            .create(payload.board_id, payload.status_id, payload.description)
            .then(({ data }) => {
                this.context.commit('setTasks', [
                    ...this.allTasks,
                    data.contents.task
                ])

                return Promise.resolve(data)
            }, ({ response }) => Promise.reject(response))
    }

    @Action({ rawError: true })
    public delete(payload: any): Promise<any> {
        return TaskService
            .delete(payload.board_id, payload.task_id)
            .then(({ data }) => {
                const tasks = this.tasks.filter(task => task.id !== payload.task_id)
                this.context.commit('setTasks', tasks)

                return Promise.resolve(data)
            }, ({ response }) => Promise.reject(response))
    }

    @Action
    public updateMovedTasks(payload: any): void {
        this.context.commit('setMovedTasks', payload)
    }

    @Action({ rawError: true })
    public edit(payload: any): Promise<any> {
        return TaskService
            .edit(payload.board_id, payload.task_id, payload.contents)
            .then(({ data }) => {
                this.context.commit('setTask', data.contents.task)

                return Promise.resolve(data)
            }, ({ response }) => Promise.reject(response))
    }
}
