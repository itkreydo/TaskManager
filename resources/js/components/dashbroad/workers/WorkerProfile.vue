<template>
    <div>
        <v-row>
            <v-col class="info-column" cols="12" sm="4">
                <div class="own-card">
                    <div class="text-center">
                        <v-avatar size="128px">
                            <img
                                class="avatar"
                                :src="`/storage/`+user.avatar.path"
                                alt="John"
                            >
                        </v-avatar>
                        <div class="mt-4">
                            <h3>{{user.surname}} {{user.name}}</h3>
                            <p class="text-muted">{{user.post.name}}</p>
                        </div>
                    </div>

                    <div class="mt-5">
                        <div class="property">
                            <label>Email</label>
                            <div class="">{{user.email}}</div>
                        </div>
                        <div class="property">
                            <label>Дата рождения</label>
                            <div class="">{{user.date_of_birth|date}}</div>
                        </div>
                        <div class="property">
                            <label>Дата регистрации</label>
                            <div class="">{{user.created_at|date}}</div>
                        </div>
                    </div>
                </div>

            </v-col>
            <v-col cols="12" sm="8">
                <v-row>
                    <v-col cols="12" md="6">
                        <h2 class="header-title px-3 pt-3">Задачи пользователя</h2>
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field
                            class="mx-3"
                            v-model="search"
                            append-icon="mdi-magnify"
                            label="Поиск по задачам"
                        ></v-text-field>
                    </v-col>
                </v-row>
                <TasksTable :search="search"></TasksTable>
            </v-col>

        </v-row>

    </div>
</template>

<script>
    import moment from 'moment'
    import TasksTable from "./TasksTable";
    export default {
        name: "WorkerProfile",
        components: {TasksTable},
        data: () => ({
            user: {},
            search: null,
        }),
        mounted() {
            this.$store.dispatch('getWorker', this.$route.params.id)
                .then(res => {
                    this.user = res;
                })
                .catch(err => {

                });
        },
        filters: {
            date: function (value) {
                if (!value) return '';
                return moment(value).format('DD.MM.YYYY');
            }
        }
    }
</script>

<style scoped>
    .own-card {
        width: 100%;
        padding: 10px;
    }

    .info-column {
        background: #fafafa;
        border-right: 1px dotted #ccc;
        height: calc(100vh - 64px);
    }

    .text-muted {
        color: #777;
    }

    .avatar {
        cursor: pointer;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .property label{
        color:#aaa;
        font-size:0.8rem;
        text-transform:uppercase;
    }

    .property + .property {
        margin-top:1rem;
    }

    @media screen and (max-width: 600px) {
        .info-column {
            height: auto;
        }
    }
</style>
