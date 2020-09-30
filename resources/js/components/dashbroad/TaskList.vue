<template>
    <div style="width:100%;height:100%">
        <v-row>
            <v-col class="pa-0">
                <h2 class="header-title">{{project.title}}</h2>
            </v-col>
<!--            <v-spacer></v-spacer>-->
            <v-col class="pa-0">
                <div style="display:flex" class="header-title right  flex-row justify-center align-center">
                    <v-btn @click="createTaskDialog = true" tile icon>
                        <v-icon>mdi-plus</v-icon>
                    </v-btn>
                    <router-link class="link" :to="`/projects/${project.id}/dashboard`">
                    <v-btn tile icon>
                        <v-icon>mdi-apps</v-icon>
                    </v-btn>
                    </router-link>
                    <v-text-field
                        class="ml-3"
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Search"
                    ></v-text-field>
                </div>
            </v-col>
        </v-row>
        <!--    <v-divider></v-divider>-->
        <div class="dashbroad">
            <template>
                <v-card class="elevation-0 " width="100%">
                    <v-data-table
                        :headers="headers"
                        :items="tasks"
                        :search="search"
                        @click:row="viewTask"
                    >
                        <template v-slot:item.title="{item}">
                            <router-link :to="`/projects/${project.id}/tasks/${item.id}`">
                                {{item.title}}
                            </router-link>
                        </template>
                        <template v-slot:item.users="{item}">
<!--                            <div v-if="item.users.length === 1">-->
                                <v-avatar class="card-avatar" v-for="user in item.users" size="32px">
                                    <v-img :src="`/storage/`+user.avatar.path"></v-img>
                                </v-avatar>
                                <span class="ml-4" v-if="item.users.length === 1"> {{item.users[0].surname}} {{item.users[0].name}}</span>
<!--                            </div>-->
                        </template>
                        <template v-slot:item.priority="{item}">
                                <v-icon :color="priority[item.priority].color">{{priority[item.priority].icon}}</v-icon>
                            {{priority[item.priority].title}}
                        </template>
                        <template v-slot:item.created_at="{item}">
                            {{new Date(item.created_at)|datetime}}
                        </template>
                        <template v-slot:item.status.title="{item}">
                            <v-chip :color="`#${item.status.color}`" dark>{{ item.status.title }}</v-chip>
                        </template>
                    </v-data-table>
                </v-card>
            </template>
        </div>

        <v-dialog
            v-model="viewTaskDialog"
            width="1000px"
        >
            <TaskView ref="viewTaskComponent" :project_id="project.id" :task_id="viewTaskId"></TaskView>
        </v-dialog>
    </div>
</template>

<script>
    import draggable from "vuedraggable";
    import CreateTaskForm from "./CreateTaskForm";
    import TaskView from "./TaskView";
    import moment from 'moment'
    export default {
        components: {
            TaskView
        },
        name: "Dashbroad",
        data: () => ({
            viewTaskDialog: false,
            viewTaskId:false,
            createTaskDialog: false,
            project: {},
            tasks: [],
            search: '',
            priority: {
                low: {icon: 'mdi-arrow-down', color: 'green', title:'Низкий'},
                medium: {icon: 'mdi-equal', color: '#ecb30f', title:'Средний'},
                high: {icon: 'mdi-arrow-up', color: 'red', title:'Высокий'}
            },
            headers: [
                { text: '№', value: 'id' },
                {
                    text: 'Название',
                    value: 'title',
                },
                { text: 'Приоритет', value: 'priority' },
                { text: 'Сотрудники', value: 'users' },
                { text: 'Статус', value: 'status.title' },
                { text: 'Дата создания', value: 'created_at' },
            ],

        }),
        mounted() {
            this.$store.dispatch('getProject', this.$route.params.project_id)
                .then(res => {
                    this.project = res;
                })
                .catch(err => alert('error'));

            this.$store.dispatch('getTasks', this.$route.params.project_id)
                .then(tasks => {
                    this.tasks = tasks;
                });


        },
        methods: {
            log: function(evt) {
                window.console.log(evt);
            },
            viewTask(task) {
                this.viewTaskDialog = true;
                this.viewTaskId = task.id;
                if (this.$refs.viewTaskComponent) {
                    this.$refs.viewTaskComponent.viewTask(task.id);
                }

            }
        },
        filters: {
            datetime: function (value) {
                if (!value) return '';
                return moment(value).format('DD.MM.YYYY HH:mm');
            }
        },
        // computed: {
        //     priority(priority) {
        //         return this.priority(priority);
        //     }
        // }
    }
</script>

<style scoped>
    .header-title {
        padding: 2rem 2rem 1rem 2rem;
    }

    .header-title.right {
        float: right;
    }

    .dashbroad {
        display: flex;
    }

    .dashbroad .column_title {
        font-size: 12px;
        color: #777;
        text-transform: uppercase;
        border-bottom: 3px solid #ccc;
        padding: 10px;
        text-overflow: ellipsis;
        word-break: break-word;
        overflow: hidden;
    }

    .dashbroad .column_body {
        background: #eee;
        min-height: calc(100vh - 220px);
        overflow: auto;
        overflow-x: hidden;
        padding-botton:10px;
    }
    .card-avatar:not(:last-child) {
        margin-right:-15px;
    }

</style>
