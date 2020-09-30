<template>
    <v-card >

        <v-card-text style="padding-bottom:0;color:#333">
            <v-row>
                <v-col cols = "12" md="8">
                    <v-row>
                        <v-col cols="12" sm="7">
                            <v-card-title style="word-break: normal;">
                                {{task.title}}
                            </v-card-title>
                        </v-col>
                        <v-col class="d-flex pa-6" cols="12" sm="5">
                            <v-select
                                :items="project.statuses"
                                item-text="title"
                                item-value="id"
                                v-model="task.status_id"
                                solo
                                dense
                                @change="changeStatus"
                                label="Статус"
                            ></v-select>
                        </v-col>
                    </v-row>

                    <v-row class="mx-1">

                        <v-col cols="12">
                            <div class="editor">
                                <div v-html="task.description"></div>

                            </div>
                        </v-col>

                        <v-col cols="12">
                            <div>
                                <div>Сотрудники</div>
                                <v-chip class="user-model" v-for="worker in task.users">
                                    <v-avatar left>
                                        <v-img :src="`/storage/`+worker.avatar.path"></v-img>
                                    </v-avatar>
                                    {{ worker.surname }} {{ worker.name }}
                                </v-chip>
                            </div>
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols = "12" style="border-left:1px solid #eee;padding:0;" md="4">
                    <div style="display:flex;" class="chat flex-column">
                        <div class="header ">Онлайн-чат</div>
                        <div class="messages-container">

                            <div class="message-wrap" v-for="message in messages">
                                <div v-if="message.user_id !== user.id" class="message to">
                                    <v-avatar size="24" left>
                                        <v-img :src="`/storage/${message.user.avatar.path}`"></v-img>
                                    </v-avatar>
                                    <div class="body">
                                        {{message.text}}
                                    </div>
                                    <div class="time">
                                        {{message.created_at|time}}
                                    </div>
                                </div>
                                <div v-else class="message me">
                                    <div class="time">
                                        {{message.created_at|time}}
                                    </div>
                                    <div class="body">
                                        {{message.text}}
                                    </div>
                                    <v-avatar size="24" right>
                                        <v-img :src="`/storage/${message.user.avatar.path}`"></v-img>
                                    </v-avatar>
                                </div>
                            </div>
                        </div>
                        <div class="input-message-block" style="">
                            <v-text-field v-model="chat.text" placeholder = "Сообщение"></v-text-field>
                            <v-btn @click="sendMessage" icon><v-icon>mdi-send</v-icon></v-btn>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>

<script>
    import {Editor, EditorContent, EditorMenuBar} from 'tiptap'
    import moment from "moment"
    import {
        Blockquote,
        CodeBlock,
        HardBreak,
        Heading,
        HorizontalRule,
        OrderedList,
        BulletList,
        ListItem,
        TodoItem,
        TodoList,
        Bold,
        Code,
        Italic,
        Link,
        Strike,
        Underline,
        History,
    } from 'tiptap-extensions'

    export default {
        name: "TaskView",
        props: ['project_id', 'task_id'],
        data() {
            return {
                isUpdating: false,
                task: {},
                project: {statuses: []},
                select: null,
                messages: [],
                chat: {
                    text: ''
                }
            }
        },
        mounted() {
            this.$store.dispatch('getProject', this.project_id)
                .then(res => {
                    this.project = res;
                });
            this.viewTask();
        },
        methods: {
            viewTask(task_id = null) {
                this.$store.dispatch('getTask', {
                    task_id: task_id || this.task_id,
                    project_id: this.project_id
                }).then(task => {
                    this.task = task;
                });

                this.getMessages(task_id);

                this.initSocket(task_id);
            },
            changeStatus() {
                this.$store.dispatch('changeTaskStatus', {
                    task_id: this.task.id,
                    project_id: this.project_id,
                    status_id: this.task.status_id,
                }).then(task => {
                    this.$set(this.task, task);
                }).catch(err => alert('Ошибка изменения статуса задачи.'));
            },
            initSocket(task_id = null) {
                let id = task_id || this.task_id;
                window.Echo.private(`chat.${id}`)
                    .listen('Chat\\NewMessage', (e) => {
                        this.messages.push(e.message);
                    });
            },
            getMessages(task_id = null) {
                let id = task_id || this.task_id;
                let data = {
                    task_id: id,
                    project_id: this.project_id,
                };
                this.$store.dispatch('getMessagesByTask', data)
                    .then(res => {
                        this.messages = res;
                    }).catch(err => alert('Ошибка получения сообщений.'));
            },
            sendMessage() {
                let data = {
                    task_id: this.task_id,
                    project_id: this.project_id,
                    text: this.chat.text
                };

                this.$store.dispatch('sendMessage', data)
                    .then(res => {
                        this.messages.push(res);
                        this.chat.text = null;
                    }).catch(err => alert('Ошибка отправки сообщений.'));
            }
        },
        computed: {
            availableWorkers() {
                let usersData = [];
                this.$store.getters.workers.forEach((user) => {
                    usersData.push({
                        id: user.id,
                        name: `${user.surname} ${user.name}`,
                        group: user.post.name,
                        avatar: 'https://cdn.vuetifyjs.com/images/lists/1.jpg'
                    })
                });
                return usersData;
            },
            user() {
                return this.$store.getters.currentUser;
            },
        },
        filters: {
            time: function (value) {
                if (!value) return '';
                return moment(value).format('HH:mm');
            }
        }


    }
</script>

<style scoped>
    .editor {

    }

    .icon {
        color: #eee;
        padding: 2px;
        border-radius: 2px;
    }

    .icon:hover {
        color: #fff;
        background: #777;
    }

    .menubar {
        background: rgba(0, 0, 0, 0.8);
        border-radius: 5px;
        padding: 3px;
    }

    .user-model + .user-model {
        margin-left: 10px;
    }

    .chat {
        width:100%;
        height:100%;
        /*max-height:500px;*/
        overflow-y:auto;
    }

    .message {

    }
    .message .body {
        font-size:0.7rem;
        line-height:1.2;
        border-radius:10px;
        padding:6px;
        color:#000;
        display: inline-block;
        max-width:80%;
    }
    .message.to .body {
        background:#eee;
        color:#000;
    }
    .message .time {
        color:#ccc;
        font-size:10px;
        display: inline-block;
    }

    .message.me .body {
        background:#26359cde;
        color:#fff;
    }
    .message.me {
        text-align:right;

    }

    /*.message + .message {*/
    /*    margin-top:5px;*/
    /*}*/
    .message-wrap + .message-wrap {
        margin-top:5px;
    }

    .header {
        text-align:center;
        padding: 10px;
        border-bottom:1px #eee solid;
    }

    .input-message-block {
        display:flex;
        align-items: center;
        border-top:1px #eee solid;
        padding-left:1rem
    }

    .messages-container {
        max-height:inherit;
        overflow-y:visible;
        flex-grow: 1;
        padding:5px 0;
        display:flex;
        flex-direction:column;
        justify-content:flex-end;

    }
</style>
