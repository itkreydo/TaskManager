
<template>
    <v-card class="elevation-0 " width="100%">
        <v-data-table
            :headers="headers"
            :items="projects"
            :search="search"
            @click:row="$emit('click-user')"
        >
            <template  v-slot:item.avatar="{item}">
                <v-avatar size="32px">
                    <v-img :src="`/storage/`+item.avatar.path"></v-img>
                </v-avatar>
            </template>
            <template v-slot:item.status="{item}">

                <v-chip :color="item.status ? `green` : `red`" dark>{{item.status ? 'Активен' : 'Заархивирован'}}</v-chip>
            </template>
            <template v-slot:item.created_at="{item}">
                {{new Date(item.created_at)|datetime}}
            </template>
            <template v-slot:item.owner="{item}">

                <v-avatar size="32px">
                    <v-img :src="`/storage/`+item.owner.avatar.path"></v-img>
                </v-avatar>
                <router-link :to="`/workers/${item.owner.id}`">
                <span class="ml-2">{{ item.owner.surname }} {{ item.owner.name }}</span>
                </router-link>
            </template>

            <template v-slot:item.users="{item}">
                <v-avatar class="card-avatar" v-for="user in item.users" size="32px">
                    <v-img :src="`/storage/`+user.avatar.path"></v-img>
                </v-avatar>
            </template>

        </v-data-table>
    </v-card>
</template>

<script>
    import moment from 'moment'
    export default {
        name: "ProjectsTable",
        props: ['projects', 'search'],
        data: () => ({
            headers: [
                { text: '№', value: 'id' },
                { text: 'Название', value: 'title' },
                {
                    text: 'Руководитель',
                    value: 'owner',
                },

                {
                    text: 'Сотрудники',
                    value: 'users',
                },
                {
                    text: 'Кол-во задач',
                    value: 'tasks_count',
                },
                {
                    text: 'Статус',
                    value: 'status',
                },

                { text: 'Дата Создания', value: 'created_at' },
            ],
        }),
    filters: {
        datetime: function (value) {
            if (!value) return '';
            return moment(value).format('DD.MM.YYYY HH:mm');
        }
    }
    }
</script>

<style scoped>
    .card-avatar:not(:last-child) {
        margin-right:-15px;
    }
</style>
