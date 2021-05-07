<template>
    <div class="card task-list--card">
        <page-header :title="taskStatus.display_name" />
        
        <draggable class="tasks" :list="columnTasks" :group="draggableGroup" v-bind="draggableOptions" @change="update" @add="add">
            <task-card v-for="task in columnTasks" :key="task.id" :data-id="task.id" :task="task" />
        </draggable>

        <div class="separator"></div>

        <create-task :status-id="taskStatus.id" @close="isCreatingTaskVisible = false" v-if="isCreatingTaskVisible" />
        <button @click="isCreatingTaskVisible = true" class="button button__transparent text-small full-width" v-else>Add New Task</button>
    </div>
</template>

<script lang="ts">
import { Component, Vue, Prop, Watch } from 'vue-property-decorator'
import { namespace } from 'vuex-class'
import vuedraggable from 'vuedraggable'
import TaskStatus from '../../../ts/models/TaskStatus'
import Task from '../../../ts/models/Task'
import PageHeader from '../PageHeader.vue'
import TaskCard from './TaskCard.vue'
import CreateTask from './CreateTask.vue'
import TaskService from '../../../ts/services/TaskService'

const Task = namespace('Task')

@Component({
    components: {
        'page-header': PageHeader,
        'task-card': TaskCard,
        'draggable': vuedraggable,
        'create-task': CreateTask,
    }
})
export default class TaskColumn extends Vue {
    @Prop({ required: true, type: Object })
    public taskStatus!: TaskStatus

    @Prop({ required: true, type: Array })
    public tasks!: Task[]

    @Task.Action
    public updateMovedTasks!: (payload: any) => void

    public isCreatingTaskVisible: boolean = false
    public columnTasks: Task[] = []

    @Watch('tasks')
    onPropertyChanged(newValue: Task[]): void {
        this.columnTasks = newValue.sort((a, b) => (a.order > b.order) ? 1 : -1)
    }

    get draggableOptions(): Object {
        return {
            ghostClass: 'card--ghost',
        }
    }

    get draggableGroup(): Object {
        return {
            name: this.taskStatus.code,
            put: true,
        }
    }

    public add(event: any): void {
        const taskId: number = event.item.getAttribute('data-id')
        const columnTasks = [ ...this.columnTasks ]
        let task: Task | undefined = columnTasks.find(task => task.id == taskId)

        if (!task) {
            return
        }
        
        const boardId = parseInt(this.$route.params.id)
        const tasks: Task[] = columnTasks
            .map((task, index) => {
                let newTask = { ...task }

                newTask.status_id = this.taskStatus.id || task.status_id
                newTask.order = index + 1

                return newTask
            })
 
        this.updateMovedTasks(tasks)
        
        TaskService.edit(boardId, (task.id || 0), {
            status_id: task.status_id
        })
    }

    public update(): void {
        const tasks: Task[] = this.columnTasks
            .map((task, index) => {
                let newTask = { ...task }

                newTask.order = index + 1

                return newTask
            })

        this.$emit('update', tasks)
    }

    created(): void {
        this.columnTasks = this.tasks
    }
}
</script>
