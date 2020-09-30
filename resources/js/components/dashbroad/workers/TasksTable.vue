<template>
    <v-card class="elevation-0 " width="100%">
        <v-data-table
            :headers="headers"
            :items="tasks"
            :search="search"
            @click:row="this.$emit('click-row')"
        >
            <template v-slot:item.title="{item}">
                <router-link :to="`/projects/${item.project_id}/tasks/${item.id}`">
                    {{item.title}}
                </router-link>
            </template>

            <template v-slot:item.project.title="{item}">
                {{item.project.title}}
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

<script>
    import moment from 'moment'
    export default {
        name: "TasksTable",
        data: () => ({
            tasks: [],
            props: ['search'],
            headers: [
                { text: '№', value: 'id' },
                {
                    text: 'Название',
                    value: 'title',
                },
                { text: 'Проект', value: 'project.title' },
                { text: 'Статус', value: 'status.title' },
                { text: 'Дата создания', value: 'created_at' },
            ],
        }),
        mounted() {
            this.$store.dispatch('getUserTasks', this.$route.params.id)
                .then(tasks => {
                    this.tasks = tasks;
                });
        },
    filters: {
        datetime: function (value) {
            if (!value) return '';
            return moment(value).format('DD.MM.YYYY HH:mm');
        }
    }
    }
</script>

<style scoped>

</style>
