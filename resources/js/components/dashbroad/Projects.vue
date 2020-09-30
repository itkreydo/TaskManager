<template>
    <div style="width:100%;">
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
        <v-row>
            <v-col class="pa-0">
                <h2 class="header-title">Проекты</h2>
            </v-col>
                        <v-spacer></v-spacer>
            <v-col class="pa-0">
                <div style="display:flex" class="header-title right  flex-row justify-center align-center">
                    <v-btn @click="openAddProjectDialog" tile icon>
                        <v-icon>mdi-plus</v-icon>
                    </v-btn>
                    <v-btn @click="showTable = !showTable" tile icon>
                        <v-icon v-if="showTable">mdi-apps</v-icon>
                        <v-icon v-else>mdi-format-list-bulleted</v-icon>
                    </v-btn>
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
        <div v-if="!showTable" class="dashbroad pa-4">
            <v-row>
                <v-col col='12' xs="12" sm="4" md="3" v-for="project in projects">
                    <router-link class="router-link" :to="`projects/${project.id}/dashboard`">
                    <v-card
                        max-width="350">
                        <v-card-title>
<!--                            <v-img class="project-img" src="/img/yl.jpg"></v-img>-->
<!--                            <v-avatar size="36px"><v-img src="/img/management.png"></v-img></v-avatar>-->
                            <span class="title font-weight-light">{{project.title}}</span>
                        </v-card-title>
                        <v-card-subtitle>{{project.description}}</v-card-subtitle>
                        <v-card-actions>
                            <v-list-item class="grow">
                                <v-list-item-avatar color="#eee">
                                    <v-img
                                        class="elevation-6"
                                        :src="`/storage/`+project.owner.avatar.path"
                                    ></v-img>
                                </v-list-item-avatar>

                                <v-list-item-content>
                                    <v-list-item-title>{{project.owner.surname}} {{project.owner.name}}</v-list-item-title>
                                </v-list-item-content>

                                <v-row
                                    align="center"
                                    justify="end"
                                >
                                    <v-icon class="mr-1">mdi-book-multiple</v-icon>
                                    <span class="subheading mr-2">{{project.tasks_count}}</span>
                                    <span class="mr-1">·</span>
                                    <v-icon class="mr-1">mdi-account-multiple</v-icon>
                                    <span class="subheading">{{project.users_count}}</span>
                                </v-row>
                            </v-list-item>
                        </v-card-actions>

                    </v-card>
                    </router-link>
                </v-col>
            </v-row>

        </div>
        <ProjectsTable v-else :projects="projects" :search="search">
        </ProjectsTable>
<AddProjectDialog ref="addProjectDialog"></AddProjectDialog>
    </div>
</template>

<script>
    import AddProjectDialog from "./AddProjectDialog";
    import ProjectsTable from "./projects/ProjectsTable";
    export default {
        name: "Projects",
        components: {ProjectsTable, AddProjectDialog},
        data: () => ({
            showTable: false,
            dialog: false,
            search:'',
            addProjectForm: {
                name: '',
                users: '',
            }
        }),
        mounted() {
          this.$store.dispatch('getProjects');
        },
        computed: {
            projects() {return this.$store.getters.projects}
        },
        methods: {
            openAddProjectDialog() {
                this.$refs.addProjectDialog.open();
            }
        }
    }
</script>

<style scoped>
    .header-title {
        padding: 2rem 2rem 1rem 2rem;
    }

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
    .project-img {
        height:200px;
    }
</style>
