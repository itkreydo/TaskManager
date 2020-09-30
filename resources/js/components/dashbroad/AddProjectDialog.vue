<template>
    <v-dialog
        v-model="isOpen"
        width="800px"
    >
        <template>
            <v-card :loading="isUpdating">
                <template v-slot:progress>
                    <v-progress-linear
                        absolute
                        color="green lighten-3"
                        height="4"
                        indeterminate
                    ></v-progress-linear>
                </template>
                <v-img
                    height="200"
                    src="/img/projects.jpg"
                >
                    <v-row>
                        <v-col
                            class="text-right"
                            cols="12"
                        >
                            <v-menu
                                bottom
                                left
                                transition="slide-y-transition"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-btn
                                        icon
                                        v-on="on"
                                    >
                                        <v-icon>mdi-dots-vertical</v-icon>
                                    </v-btn>
                                </template>
                                <v-list>
                                    <v-list-item @click="isUpdating = true">
                                        <v-list-item-action>
                                            <v-icon>mdi-settings</v-icon>
                                        </v-list-item-action>
                                        <v-list-item-content>
                                            <v-list-item-title>Update</v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </v-col>
                        <v-row
                            class="pa-4"
                            align="center"
                            justify="center"
                        >
                            <v-col class="text-center">
                                <h3 class="headline">{{ formData.title }}</h3>
                                <span class="grey--text text--lighten-1">{{ formData.description }}</span>
                            </v-col>
                        </v-row>
                    </v-row>
                </v-img>
                <v-form>
                    <v-container>
                        <v-row>
                            <v-col
                                cols="12"
                                md="6"
                            >
                                <v-text-field
                                    v-model="formData.title"
                                    :disabled="isUpdating"
                                    filled
                                    color="blue-grey lighten-2"
                                    label="Название"
                                ></v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                md="6"
                            >
                                <v-text-field
                                    v-model="formData.description"
                                    :disabled="isUpdating"
                                    filled
                                    color="blue-grey lighten-2"
                                    label="Описание"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-autocomplete
                                    v-model="formData.workers"
                                    :disabled="isUpdating"
                                    :items="people"
                                    filled
                                    chips
                                    color="blue-grey lighten-2"
                                    label="Сотрудники"
                                    item-text="name"
                                    item-value="id"
                                    multiple
                                >
                                    <template v-slot:selection="data">
                                        <v-chip
                                            v-bind="data.attrs"
                                            :input-value="data.selected"
                                            close
                                            @click="data.select"
                                            @click:close="remove(data.item)"
                                        >
                                            <v-avatar left>
                                                <v-img :src="`/storage/`+data.item.avatar"></v-img>
                                            </v-avatar>
                                            {{ data.item.name }}
                                        </v-chip>
                                    </template>
                                    <template v-slot:item="data">
                                        <template v-if="typeof data.item !== 'object'">
                                            <v-list-item-content v-text="data.item"></v-list-item-content>
                                        </template>
                                        <template v-else>
                                            <v-list-item-avatar>
                                                <img :src="`/storage/`+data.item.avatar">
                                            </v-list-item-avatar>
                                            <v-list-item-content>
                                                <v-list-item-title v-html="data.item.name"></v-list-item-title>
                                                <v-list-item-subtitle v-html="data.item.group"></v-list-item-subtitle>
                                            </v-list-item-content>
                                        </template>
                                    </template>
                                </v-autocomplete>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-form>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        :disabled="false"
                        :loading="isUpdating"
                        text
                        color="primary"
                        @click="createProject()"
                    >
                        <v-icon left>mdi-plus</v-icon>
                        Создать
                    </v-btn>
                </v-card-actions>
            </v-card>
        </template>
    </v-dialog>
</template>

<script>
    export default {
        name: "AddProjectDialog",
        // props: {
        //     isOpen: {
        //         type: Boolean,
        //         default: false
        //     }
        // },
        data: () => ({
                isOpen: false,
                friends: ['Sandra Adams', 'Britta Holt'],
                isUpdating: false,
                formData: {
                    title: 'Новый проект',
                    description: 'Описание нового проекта',
                    workers: [],
                },
                // people: [
                //     { header: 'Group 1' },
                //     { name: 'Sandra Adams', group: 'Group 1', avatar: srcs[1] },
                //     { name: 'Ali Connors', group: 'Group 1', avatar: srcs[2] },
                //     { name: 'Trevor Hansen', group: 'Group 1', avatar: srcs[3] },
                //     { name: 'Tucker Smith', group: 'Group 1', avatar: srcs[2] },
                //     { divider: true },
                //     { header: 'Group 2' },
                //     { name: 'Britta Holt', group: 'Group 2', avatar: srcs[4] },
                //     { name: 'Jane Smith ', group: 'Group 2', avatar: srcs[5] },
                //     { name: 'John Smith', group: 'Group 2', avatar: srcs[1] },
                //     { name: 'Sandra Williams', group: 'Group 2', avatar: srcs[3] },
                // ],

        }),
        mounted() {
            this.$store.dispatch('getWorkers');
        },
        //
        // watch: {
        //     isUpdating (val) {
        //         if (val) {
        //             setTimeout(() => (this.isUpdating = false), 3000)
        //         }
        //     },
        // },

        methods: {
            remove (item) {
                const index = this.friends.indexOf(item.name)
                if (index >= 0) this.friends.splice(index, 1)
            },
            createProject() {
                this.isUpdating = true;
                this.$store.dispatch('createProject', this.formData)
                    .then(res => {
                        console.log(res);
                    })
                    .catch(err => {
                        console.log('error');
                    })
                    .finally(() => this.isUpdating = false);
            },
            open() {
                this.isOpen = true;
            }
        },

        computed: {
            people(){
                let usersData = [];
                this.$store.getters.workers.forEach((user) => {
                    usersData.push({id:user.id, name: `${user.surname} ${user.name}`, group: user.post.name, avatar: user.avatar.path})
                });
                return usersData;
            }
        }
    }
</script>

<style scoped>

</style>
