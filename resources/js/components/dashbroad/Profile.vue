<template>
    <div>
        <v-row>
            <v-col class="info-column" cols="12" sm="4">
                <div class="own-card">
                    <div class="text-center">
                        <v-avatar size="128px">
                            <img
                                class="avatar"
                                @click="changeAvatarDialog = !changeAvatarDialog"
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
                <h2 class="header-title px-3 pt-3">Настройки</h2>
                <div>
                    <change-password class="pa-6"></change-password>
                </div>
            </v-col>
        </v-row>


        <v-dialog
            v-model="changeAvatarDialog"
            width="800px"
        >
            <v-card>
                <v-card-title class="">
                    Изменить аватар
                </v-card-title>
                <v-container>
                    <v-row class="mx-1">
                        <v-col class="d-flex" cols="12">
                            <v-file-input
                                :rules="rules"
                                accept="image/png, image/jpeg, image/bmp"
                                placeholder="Загрузить аватар"
                                prepend-icon="mdi-camera"
                                label="Аватар"
                                v-model="avatar"
                            ></v-file-input>
                        </v-col>
                    </v-row>
                </v-container>
                <v-card-actions>
                    <v-spacer/>
                    <v-btn
                        text
                        color="primary"
                        @click="changeAvatarDialog = false"
                    >Закрыть
                    </v-btn>
                    <v-btn
                        text
                        @click="loadAvatar"
                    >Сохранить
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>

</template>

<script>
    import moment from 'moment'
    import ChangePassword from "./settings/ChangePassword";
    export default {
        name: "Profile",
        components: {ChangePassword},
        data: () => ({
            avatar: null,
            changeAvatarDialog: false,
            rules: [
                value => !value || value.size < 2000000 || 'Avatar size should be less than 2 MB!',
            ],
        }),
        methods: {
            loadAvatar() {
                if (this.avatar) {
                    let formData = new FormData();
                    console.log(this.avatar);
                    formData.append("file", this.avatar, this.avatar.name);

                    this.$store.dispatch('loadAvatar', formData)
                        .then(res => this.changeAvatarDialog = false)
                        .catch(err => alert('avatar load error'));

                } else {
                    console.log("there are no files.");
                }
            }
        },
        computed: {
            user() {
                return this.$store.getters.currentUser;
            },
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
