
    <template>
        <v-card class="elevation-0 " width="100%">
            <v-data-table
                :headers="headers"
                :items="users"
                :search="search"
                @click:row="$emit('click-user')"
            >
                <template v-slot:item.title="{item}">
                    <router-link :to="`/workers/${item.id}`">
                        {{item.surname}} {{item.name}}
                    </router-link>
                </template>
                <template  v-slot:item.avatar="{item}">
                    <v-avatar size="32px">
                        <v-img :src="`/storage/`+item.avatar.path"></v-img>
                    </v-avatar>
                </template>
                <template v-slot:item.created_at="{item}">
                    {{new Date(item.created_at)|date}}
                </template>
                <template v-slot:item.date_of_birth="{item}">
                    {{new Date(item.date_of_birth)|date}}
                </template>
                <template v-slot:item.status="{item}">
                    <v-chip color="green" dark>{{ item.status }}</v-chip>
                </template>

            </v-data-table>
        </v-card>
    </template>

    <script>
        import moment from 'moment'
        export default {
            name: "WorkersTable",
            props: ['users', 'search'],
            data: () => ({
                headers: [
                    { text: '№', value: 'id' },
                    { text: '', value: 'avatar' },
                    {
                        text: 'Сотрудник',
                        value: 'title',
                    },
                    { text: 'Email', value: 'email' },
                    { text: 'Дата рождения', value: 'date_of_birth' },
                    { text: 'Статус', value: 'status' },
                    { text: 'Дата регистраии', value: 'created_at' },
                ],
            }),
            filters: {
                date: function (value) {
                    if (!value) return '';
                    return moment(value).format('DD.MM.YYYY');
                }
            }
        }
    </script>

    <style scoped>

    </style>
