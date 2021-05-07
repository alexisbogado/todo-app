<template>
    <div class="task-categories">
        <div class="task-list">
            <task-column v-for="status in taskStatuses" :key="status.id" :task-status="status" :tasks="getTasksByStatus(status.id)" @update="updateTasks" />
        </div>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { namespace } from 'vuex-class'
import vuedraggable from 'vuedraggable'
import Task from '../../../ts/models/Task'
import PageHeader from '../PageHeader.vue'
import TaskCard from '../tasks/TaskCard.vue'
import TaskColumn from '../tasks/TaskColumn.vue'
import TaskStatus from '../../../ts/models/TaskStatus'
import TaskService from '../../../ts/services/TaskService'

const Task = namespace('Task')

@Component({
    components: {
        'page-header': PageHeader,
        'task-card': TaskCard,
        'draggable': vuedraggable,
        'task-column': TaskColumn
    }
})
export default class KanbanBoard extends Vue {
    @Task.Getter
    public taskStatuses!: TaskStatus[]

    @Task.Getter
    public tasks!: Task[]

    @Task.Action
    public updateMovedTasks!: (payload: Task[]) => void

    public getTasksByStatus(id: number): Task[] {
        return this.tasks?.filter(task => task.status_id === id)
    }

    public updateTasks(tasks: Task[]): void {
        if (!tasks.length) {
            return
        }

        this.updateMovedTasks(tasks)
        TaskService.updateAll(parseInt(this.$route.params.id), tasks)
    }
}
</script>
