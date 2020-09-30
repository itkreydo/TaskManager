<template>
    <div style="width:100%;">
        <v-row class="header-tools">
            <v-col style="display:flex;" class="align-center">

                <div class="px-3 header-title">{{project.title}}</div>
<!--                <h2 class="header-title">{{project.title}}</h2>-->
            </v-col>
            <v-spacer></v-spacer>
            <v-col class="header-tools-col">
                <div style="display:flex" class="right px-3 flex-row justify-center align-center">
                    <v-btn @click="createTaskDialog = true" tile icon>
                        <v-icon>mdi-plus</v-icon>
                    </v-btn>
                    <router-link class="link" :to="`/projects/${project.id}/tasks`">
                        <v-btn tile icon>
                            <v-icon>mdi-format-list-bulleted</v-icon>
                        </v-btn>
                    </router-link>
                    <v-text-field
                        class="ml-3"
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Поиск по задачам"
                    ></v-text-field>
                </div>
            </v-col>
        </v-row>
        <!--    <v-divider></v-divider>-->
        <div class="dashbroad">
            <v-col class="col"  v-for="(column, i) in columns">
                <div class="column_title">
                    {{column.title}}
                </div>
                <div class="column_body mt-2">
                    <draggable class="list-group" v-model="column.tasks" group="people"
                               @change="log">
                            <v-card
                                class="mx-auto mt-1"
                                max-width="350"
                                outlined
                                v-for="(element, index) in column.tasks"
                                :key="element.title"
                            >
                                <v-list-item >
                                    <v-list-item-content>
                                        <div style="margin-top:-5px;" class="overline">#{{ element.id }}</div>
                                        <div class="card-title" style="font-size:1rem" >{{ element.title }}
                                        </div>
                                    </v-list-item-content>


                                </v-list-item>
                                <v-card-actions>

                                    <v-btn v-if="index == 1" icon>
                                        <v-icon color="red">mdi-arrow-up</v-icon>
                                    </v-btn>
                                    <v-btn v-else-if="index == 2" icon>
                                        <v-icon color="green">mdi-arrow-down</v-icon>
                                    </v-btn>
                                    <v-btn v-else icon>
                                        <v-icon color="#ecb30f">mdi-equal</v-icon>
                                    </v-btn>
                                    <v-spacer></v-spacer>

<!--                                    <v-btn icon>-->
<!--                                        <v-icon >mdi-account-multiple</v-icon> {{ element.users.length }}-->
<!--                                    </v-btn>-->

                                    <v-btn icon>
                                        <v-icon>mdi-message-reply-text</v-icon> 3
                                    </v-btn>

                                    <div class="mx-1">
                                    <v-list-item-avatar
                                        size="24"
                                        color="grey"
                                        v-for="user in element.users"
                                        class="card-avatar"
                                    >
                                        <v-img
                                        :src="`/storage/`+user.avatar.path"></v-img>

                                    </v-list-item-avatar>
                                    </div>
                                </v-card-actions>
                            </v-card>
                    </draggable>
                </div>
            </v-col>
        </div>
        <v-dialog
            v-model="createTaskDialog"
            width="800px"
        >
            <CreateTaskForm :project_id="project.id"></CreateTaskForm>
        </v-dialog>
        <div class="d-none">{{hidden}}</div>
    </div>
</template>

<script>
    import draggable from "vuedraggable";
    import CreateTaskForm from "./CreateTaskForm";

    export default {
        components: {
            CreateTaskForm,
            draggable
        },
        name: "Dashbroad",
        data: () => ({
            createTaskDialog: false,
            project: {},
            columns: [],
            hidden:0,
            search: null,
            drag: true,
            settings: {
                drag: true,
                dragOptions: {
                    // animation: 0,
                    // group: "description",
                    // disabled: false,
                    // ghostClass: "ghost"
                }
            },
            list: {l:[{"id":2,"project_id":2,"title":"2","description":"2","owner_id":1,"status_id":2,"created_at":null,"updated_at":null,"status":{"id":2,"title":"В очереди","max":5,"project_id":2,"order_num":1,"hidden":0,"created_at":null,"updated_at":null},"users":[]},{"id":1,"project_id":2,"title":"Новая задача","description":"Описание","owner_id":1,"status_id":2,"created_at":null,"updated_at":null,"status":{"id":2,"title":"В очереди","max":5,"project_id":2,"order_num":1,"hidden":0,"created_at":null,"updated_at":null},"users":[]},{"id":12,"project_id":2,"title":"fdfddfdf","description":"<h2>Hi there,</h2><p>this is a very <em>basic</em> example of tiptap.</p><pre><code>body { display: none; }</code></pre><ul><li><p>A regular list</p></li><li><p>With regular items</p></li></ul><blockquote><p>It's amazing 👏 <br>– mom</p></blockquote>","owner_id":1,"status_id":2,"created_at":"2020-04-26T10:21:05.000000Z","updated_at":"2020-04-26T10:21:05.000000Z","status":{"id":2,"title":"В очереди","max":5,"project_id":2,"order_num":1,"hidden":0,"created_at":null,"updated_at":null},"users":[{"id":1,"name":"Игорь\n","surname":"Крейдо","patronymic":"Витальевич","date_of_birth":"1998-11-30","post_id":1,"status":"active","email":"autodesk@list.ru","email_verified_at":null,"avatar_id":1,"created_at":null,"updated_at":null,"pivot":{"task_id":12,"user_id":1}},{"id":8,"name":"Венера","surname":"Гарифуллина","patronymic":"Не указано","date_of_birth":"1951-08-16","post_id":2,"status":"active","email":"1@1.q","email_verified_at":null,"avatar_id":null,"created_at":"2020-04-20T21:17:59.000000Z","updated_at":"2020-04-20T21:17:59.000000Z","pivot":{"task_id":12,"user_id":8}}]}]}
        }),
        mounted() {
            this.$store.dispatch('getProject', this.$route.params.project_id)
                .then(res => {
                    this.project = res;
                    let cols = this.project.statuses.filter(status => !status.hidden);
                    cols.forEach(col => {
                       col.tasks = []
                    });

                    this.$store.dispatch('getTasks', this.$route.params.project_id)
                        .then(tasks => {
                            tasks.forEach(task => {
                                cols.forEach((column, index) => {
                                    if (task.status_id == column.id) {
                                        column.tasks.push(task);
                                    }
                                })
                            });

                            this.columns = cols;
                            // this.columns = cols
                        });
                })
                .catch(err => alert('error'));


        },
        methods: {
            log: function(evt) {
                if (evt.hasOwnProperty('added')) {
                    this.$set(this.columns);
                    this.hidden += 1;
                } else {

                }
                window.console.log(evt);
            },

        }
    }
</script>

<style scoped>

    .dashbroad {
        display: flex;
        width: 100%;
        overflow-x: auto;
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
        margin-right:-8px;
    }
    .card-avatar:nth-last-child(2) {
        margin-right:-22px;
    }



    .header-title {
        font-size:24px;
    }
    .header-tools {
        margin-top:10px;
    }

    @media screen and (max-width: 600px) {
        .col {
            min-width:100%;
        }
        .header-tools-col {
            padding-top: 0;
        }
    }




</style>
