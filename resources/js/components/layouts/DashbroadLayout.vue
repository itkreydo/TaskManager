<template>
    <div style="height:100%">
        <v-navigation-drawer
            v-model="drawer"
            :clipped="$vuetify.breakpoint.lgAndUp"
            :mini-variant="$vuetify.breakpoint.lgAndUp && drawer "
            class="nav-drawer"
            app
            :permanent="$vuetify.breakpoint.lgAndUp"
        >
            <v-list dense>
                <router-link class="router-link" :to="item.link" v-for="item in items">
                    <v-row
                        v-if="item.heading"
                        :key="item.heading"
                        align="center"
                    >
                        <v-col cols="6">
                            <v-subheader v-if="item.heading">
                                {{ item.heading }}
                            </v-subheader>
                        </v-col>
                    </v-row>
                    <v-list-group
                        v-else-if="item.children"
                        :key="item.text"
                        v-model="item.model"
                        :prepend-icon="item.model ? item.icon : item['icon-alt']"
                        append-icon=""
                    >
                        <template v-slot:activator>
                            <v-list-item-content>
                                <v-list-item-title>
                                    {{ item.text }}
                                </v-list-item-title>
                            </v-list-item-content>
                        </template>
                        <v-list-item
                            v-for="(child, i) in item.children"
                            :key="i"
                            link
                        >
                            <v-list-item-action v-if="child.icon">
                                <v-icon>{{ child.icon }}</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title>
                                    {{ child.text }}
                                </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-group>
                    <v-list-item
                        v-else
                        :key="item.title"
                        link
                    >
                        <v-list-item-action>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>
                                {{ item.title }}
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </router-link>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar
            :clipped-left="$vuetify.breakpoint.lgAndUp"
            app
            color="blue-grey"
            dark

        >
            <v-app-bar-nav-icon @click="drawer = !drawer"/>
            <v-toolbar-title
                style="width: 300px"
                class="ml-0 pl-4"
            >
                <span class="hidden-sm-and-down">Task Manager</span>
            </v-toolbar-title>

            <v-spacer/>
            <v-btn icon>
                <v-icon>mdi-bell</v-icon>
            </v-btn>

            <v-menu offset-y >
                <template v-slot:activator="{ on }">
                    <v-btn
                        icon
                        large
                        v-on="on"
                        class="mr-1"
                    >
                        <v-avatar
                            size="36px"
                            item
                        >
                            <v-img
                                :src="`/storage/`+ user.avatar.path"
                                alt="Vuetify"
                            />
                        </v-avatar>
                    </v-btn>
                </template>
                <v-list>
                    <v-list-item

                        @click="logout"
                    >
                        <v-list-item-title>Выйти</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-app-bar>


            <router-view class="main-block"></router-view>

    </div>
</template>

<script>
    import draggable from "vuedraggable";

    export default {
        components: {
            draggable
        },
        props: {
            source: String,
        },
        data: () => ({
            dialog: false,
            drawer_true: true,
            drawer: false,

            items: [
                {icon: 'mdi-contacts', title: 'Сотрудники', link: '/workers'},
                {icon: 'mdi-history', title: 'История активности', link:''},
                {icon: 'mdi-content-copy', title: 'Проекты', link:'/projects'},
                {icon: 'mdi-settings', title: 'Настройки', link:'/settings'},
            ],
        }),
        methods: {
            log(evt) {
                window.console.log(evt);
            },
            logout() {
                this.$store.dispatch('logout').then(res => this.$router.push('/auth/login'));
            }
        },
        computed: {
            user() {
                return this.$store.getters.currentUser;
            },
            toggleNavdraver() {
                return $vuetify.breakpoint.lgAndUp;
            }
        }
    }
</script>

<style>

    .flip-list-move {
        transition: transform 0.5s;
    }

    .no-move {
        transition: transform 0s;
    }

    .ghost {
        opacity: 0.5;
        background: #c8ebfb;
    }

    .list-group {
        min-height: 20px;
    }

    .list-group-item {
        cursor: move;
    }

    .list-group-item i {
        cursor: pointer;
    }

    .full-size {
        width: 100%;
        max-width: 100%;
    }

    .task-col {
        background: rgba(0, 0, 0, 0.05);
    }

    .task-col-header {
        padding:10px;
    }

    .nav-drawer {
        border-right: 1px #fefefe solid;
    }

    .main-block {
        background: #fefefe;
    }

    .router-link {
        text-decoration:none;
    }
</style>
