<template>
    <div style="width:100%;height:100%">
        <v-row class="header-tools">
            <v-col style="display:flex;" class="align-center ">
                <div class="px-3 header-title">Все сотрудники</div>
            </v-col>
            <v-spacer></v-spacer>
            <v-col class="header-tools-col">
                <div style="display:flex" class="right px-3 flex-row justify-center align-center">
                    <v-btn @click="dialog = true" tile icon>
                        <v-icon>mdi-plus</v-icon>
                    </v-btn>
                    <!--                    <router-link class="link" :to="`/workers/list`">-->
                    <v-btn @click="showTable = !showTable" tile icon>
                        <v-icon v-if="showTable">mdi-apps</v-icon>
                        <v-icon v-else>mdi-format-list-bulleted</v-icon>
                    </v-btn>
                    <!--                    </router-link>-->
                    <v-text-field
                        class="ml-3"
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Поиск по сотрудникам"
                    ></v-text-field>
                </div>
            </v-col>
        </v-row>
<!--        <v-divider></v-divider>-->
<!--        <v-row>-->
<!--            <v-col class="pa-0">-->
<!--                <h2 class="header-title">Все сотрудники</h2>-->
<!--            </v-col>-->
<!--            <v-spacer></v-spacer>-->
<!--            <v-col class="pa-0">-->
<!--                <div style="display:flex" class="header-title right  flex-row justify-center align-center">-->
<!--                    <v-btn @click="dialog = true" tile icon>-->
<!--                        <v-icon>mdi-plus</v-icon>-->
<!--                    </v-btn>-->
<!--&lt;!&ndash;                    <router-link class="link" :to="`/workers/list`">&ndash;&gt;-->
<!--                        <v-btn @click="showTable = !showTable" tile icon>-->
<!--                            <v-icon v-if="showTable">mdi-apps</v-icon>-->
<!--                            <v-icon v-else>mdi-format-list-bulleted</v-icon>-->
<!--                        </v-btn>-->
<!--&lt;!&ndash;                    </router-link>&ndash;&gt;-->
<!--                    <v-text-field-->
<!--                        class="ml-3"-->
<!--                        v-model="search"-->
<!--                        append-icon="mdi-magnify"-->
<!--                        label="Поиск по сотрудникам"-->
<!--                    ></v-text-field>-->
<!--                </div>-->
<!--            </v-col>-->
<!--        </v-row>-->
        <!--    <v-divider></v-divider>-->

        <div v-if="!showTable" class="dashbroad pa-4">
            <v-row>
                <v-col col='12' sm="4" md="3" v-for ="worker in workers">
                    <router-link class="link" :to="`/workers/${worker.id}`">
                    <v-card max-width="350" style="card">
                        <!--                        <v-card-title>-->
                        <v-list-item class="grow">
                            <v-list-item-avatar color="#eee">
                                <v-img
                                    class="elevation-6"
                                    :src="`/storage/`+worker.avatar.path"
                                ></v-img>
                            </v-list-item-avatar>

                            <v-list-item-content>
                                <v-list-item-title>{{worker.surname}} {{worker.name}}</v-list-item-title>
                                <v-list-item-subtitle>{{worker.post.name}}</v-list-item-subtitle>
                            </v-list-item-content>
                        </v-list-item>

                    </v-card>
                    </router-link>
                </v-col>
            </v-row>
        </div>
        <WorkersTable v-else :users="workers" :search="search">
        </WorkersTable>

        <v-btn
            bottom
            color="pink"
            dark
            fab
            fixed
            right
            @click="dialog = !dialog"
        >
            <v-icon>mdi-plus</v-icon>
        </v-btn>
        <v-dialog
            v-model="dialog"
            width="800px"
        >
            <v-card>
                <v-card-title class="">
                    Добавить сотрудника
                </v-card-title>
                <v-container>
                    <v-row class="mx-2">
                        <v-col
                            class="align-center justify-space-between"
                            cols="12" md="6"
                        >
                            <v-row
                                align="center"
                                class="mr-0"
                            >
                                <v-avatar
                                    size="40px"
                                    class="mx-3"
                                >
                                    <img
                                        src="//ssl.gstatic.com/s2/oz/images/sge/grey_silhouette.png"
                                        alt=""
                                    >
                                </v-avatar>
                                <v-text-field
                                    v-model="addWorkerForm.name"
                                    label="Имя"
                                />
                            </v-row>
                        </v-col>
                        <v-col
                            class="align-center justify-space-between"
                            cols="12" md="6"
                        >
                                <v-text-field
                                    v-model="addWorkerForm.surname"
                                    label="Фамилия"
                                />
                        </v-col>
                        <v-col
                            class="align-center justify-space-between"
                            cols="12"
                        >
                            <template>
                                <v-menu
                                    ref="menu"
                                    v-model="menu"
                                    :close-on-content-click="false"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            v-model="addWorkerForm.date_of_birth"
                                            label="Birthday date"
                                            prepend-icon="event"
                                            readonly
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        ref="picker"
                                        v-model="addWorkerForm.date_of_birth"
                                        :max="new Date().toISOString().substr(0, 10)"
                                        min="1950-01-01"
                                        @change="save"
                                    ></v-date-picker>
                                </v-menu>
                            </template>
<!--                            <v-text-field-->
<!--                                v-model="addWorkerForm.patronymic"-->
<!--                                label="Отчество"-->
                        </v-col>
                        <v-col cols="12">
                            <v-text-field
                                prepend-icon="mdi-mail"
                                label="Email"
                                v-model="addWorkerForm.email"
                            />
                        </v-col>
                        <v-col cols="12">
                            <v-text-field
                                type="tel"
                                prepend-icon="mdi-phone"
                                v-model="addWorkerForm.phone"
                                label="Телефон"
                            />
                        </v-col>
                        <v-col cols="12">
                            <v-text-field
                                v-model="addWorkerForm.password"
                                prepend-icon="mdi-text"
                                label="Пароль"
                            >
                                <v-icon slot="append" @click="fillNewPassword()" class="pointer" color="green">mdi-lock-outline</v-icon>
                            </v-text-field>
                        </v-col>
                        <v-col class="d-flex" cols="12">
                            <v-select
                                v-model="addWorkerForm.post_id"
                                :items="posts"
                                item-text="name"
                                item-value="id"
                                prepend-icon="mdi-post"
                                label="Должность"
                            ></v-select>

                        </v-col>
                    </v-row>
                </v-container>
                <v-card-actions>
                    <v-spacer/>
                    <v-btn
                        text
                        color="primary"
                        @click="dialog = false"
                    >Закрыть
                    </v-btn>
                    <v-btn
                        text
                        @click="createUser()"
                    >Сохранить
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import WorkersTable from "./workers/WorkersTable";
    export default {
        name: "Workers",
        components: {WorkersTable},
        data: () => ({
            showTable: false,
            search: null,
            dialog: false,
            menu:false,
            addWorkerForm: {
                name: '',
                surname: '',
                patronymic: 'Не указано',
                date_of_birth: '',
                post_id: '',
                phone: '',
                email: '',
                password: '',
            }
        }),
        watch: {
            menu (val) {
                val && setTimeout(() => (this.$refs.picker.activePicker = 'YEAR'))
            },
        },
        mounted() {
            this.$store.dispatch('getPosts');
            this.$store.dispatch('getWorkers');
        },
        methods: {
            generatePassword(len = 10) {
                len = len * (-1);
                return Math.random().toString(36).slice(len);
            },
            fillNewPassword() {
                this.addWorkerForm.password = this.generatePassword();
            },
            createUser() {
                this.$store.dispatch('createUser', this.addWorkerForm)
                    .then(res => {
                        this.dialog = false;
                        console.log(res.data);
                    })
                    .catch(err => {
                        console.log(err.data);
                    });
            },
            save (date) {
                this.$refs.menu.save(date)
            },
        },
        computed: {
            posts() {return this.$store.getters.posts},
            workers() {return this.$store.getters.workers}
        }
    }
</script>

<style scoped>

    .dashbroad {
        display: flex;
    }

    .dashbroad v-card {
        margin-left: 10px;
    }

    .dashbroad.empty {
        background: #eee;
        color: #777;
        height: 100%;
    }

    .project-card {
        height: 10rem;
        border-radius: 4px;
        min-width: 250px;
        max-width: 250px;
        cursor: pointer;
    }


    .project-card + .project-card {
        margin-left: 10px;
    }

    .project-card.new {
        text-align: center;
        padding-top: 2.5rem;
        background: #efefef;
    }

    .create-icon {
        color: #aaa;
    }

    .fab {
        margin: 0 16px 30px 0;
    }

    .pointer {
        cursor: pointer;
    }
    .header-title {
        font-size:24px;
    }
    .header-tools {
        margin-top:10px;
    }
</style>
