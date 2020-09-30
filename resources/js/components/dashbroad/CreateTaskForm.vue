<template>
    <v-card>
        <v-card-title class="">
            Новая задача
        </v-card-title>
        <v-container>
            <v-row class="mx-1">

                <v-col cols="12">
                    <v-text-field v-model="formData.title" label = "Заголовок"></v-text-field>
                </v-col>
                <v-col cols="12">
                    Содержание
                    <div class="editor">
                        <editor-menu-bar :editor="editor" v-slot="{ commands, isActive }">
                            <div class="menubar">

                                <button
                                    class="menubar__button"
                                    :class="{ 'is-active': isActive.bold() }"
                                    @click="commands.bold"
                                >
                                    <v-icon class="icon" size="20">mdi-format-bold</v-icon>
                                </button>

                                <button
                                    class="menubar__button"
                                    :class="{ 'is-active': isActive.italic() }"
                                    @click="commands.italic"
                                >
                                    <v-icon class="icon" size="20">mdi-format-italic</v-icon>
                                </button>

                                <button
                                    class="menubar__button"
                                    :class="{ 'is-active': isActive.strike() }"
                                    @click="commands.strike"
                                >
                                    <v-icon class="icon" size="20">mdi-format-strikethrough-variant</v-icon>
                                </button>

                                <button
                                    class="menubar__button"
                                    :class="{ 'is-active': isActive.underline() }"
                                    @click="commands.underline"
                                >
                                    <v-icon class="icon" size="20">mdi-format-underline</v-icon>
                                </button>

                                <button
                                    class="menubar__button"
                                    :class="{ 'is-active': isActive.code() }"
                                    @click="commands.code"
                                >
                                    <v-icon class="icon" size="20">mdi-code-tags</v-icon>
                                </button>

                                <button
                                    class="menubar__button"
                                    :class="{ 'is-active': isActive.heading({ level: 1 }) }"
                                    @click="commands.heading({ level: 1 })"
                                >
                                    <v-icon class="icon" size="20">mdi-format-header-1</v-icon>
                                </button>

                                <button
                                    class="menubar__button"
                                    :class="{ 'is-active': isActive.heading({ level: 2 }) }"
                                    @click="commands.heading({ level: 2 })"
                                >
                                    <v-icon class="icon" size="20">mdi-format-header-2</v-icon>
                                </button>

                                <button
                                    class="menubar__button"
                                    :class="{ 'is-active': isActive.heading({ level: 3 }) }"
                                    @click="commands.heading({ level: 3 })"
                                >
                                    <v-icon class="icon" size="20">mdi-format-header-3</v-icon>
                                </button>

                                <button
                                    class="menubar__button"
                                    :class="{ 'is-active': isActive.bullet_list() }"
                                    @click="commands.bullet_list"
                                >
                                    <v-icon class="icon" size="20">mdi-format-list-bulleted</v-icon>
                                </button>

                                <button
                                    class="menubar__button"
                                    :class="{ 'is-active': isActive.ordered_list() }"
                                    @click="commands.ordered_list"
                                >
                                    <v-icon class="icon" size="20">mdi-format-list-numbers</v-icon>
                                </button>

                                <button
                                    class="menubar__button"
                                    :class="{ 'is-active': isActive.blockquote() }"
                                    @click="commands.blockquote"
                                >
                                    <v-icon class="icon" size="20">mdi-format-quote-close</v-icon>
                                </button>

                                <button
                                    class="menubar__button"
                                    :class="{ 'is-active': isActive.code_block() }"
                                    @click="commands.code_block"
                                >
                                    <v-icon class="icon" size="20">mdi-code-tags</v-icon>
                                </button>

                                <button
                                    class="menubar__button"
                                    @click="commands.horizontal_rule"
                                >
                                    <v-icon class="icon" size="20">mdi-ray-start-end</v-icon>
                                </button>
                                <button
                                    class="menubar__button"
                                    @click="showImagePrompt(commands.image)"
                                >
                                    <v-icon class="icon" size="20">mdi-image</v-icon>
                                </button>

                                <button
                                    class="menubar__button"
                                    @click="commands.undo"
                                >
                                    <v-icon class="icon" size="20">mdi-undo-variant</v-icon>
                                </button>

                                <button
                                    class="menubar__button"
                                    @click="commands.redo"
                                >
                                    <v-icon class="icon" size="20">mdi-redo-variant</v-icon>
                                </button>

                            </div>
                        </editor-menu-bar>

                        <editor-content class="editor__content editor" :editor="editor" />
                    </div>
                </v-col>

                <v-col cols="12">
                    <v-autocomplete
                        v-model="formData.workers"
                        :disabled="isUpdating"
                        :items="availableWorkers"
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
        <v-card-actions>
            <v-spacer/>
            <v-btn @click="createTask"
                text
            >Сохранить
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
    import { Editor, EditorContent, EditorMenuBar } from 'tiptap'
    import {
        Blockquote,
        CodeBlock,
        HardBreak,
        Heading,
        HorizontalRule,
        OrderedList,
        BulletList,
        ListItem,
        TodoItem,
        TodoList,
        Bold,
        Code,
        Italic,
        Link,
        Strike,
        Underline,
        History,
        Image,
    } from 'tiptap-extensions'

    export default {
        name: "CreateTaskForm",
        components: {
            EditorContent,
            EditorMenuBar,
        },
        props: ['project_id'],
        data() {
            return {
                isUpdating: false,
                formData: {
                    workers: [],
                    title: null,
                    description: null,
                },

                editor: new Editor({
                    extensions: [
                        new Blockquote(),
                        new BulletList(),
                        new CodeBlock(),
                        new HardBreak(),
                        new Heading({ levels: [1, 2, 3] }),
                        new HorizontalRule(),
                        new ListItem(),
                        new OrderedList(),
                        new TodoItem(),
                        new TodoList(),
                        new Link(),
                        new Bold(),
                        new Code(),
                        new Italic(),
                        new Strike(),
                        new Underline(),
                        new History(),
                        new Image(),
                    ],
                    content: `
          <h2>
            Hi there,
          </h2>
          <p>
            this is a very <em>basic</em> example of tiptap.
          </p>
          <pre><code>body { display: none; }</code></pre>
          <ul>
            <li>
              A regular list
            </li>
            <li>
              With regular items
            </li>
          </ul>
          <blockquote>
            It's amazing 👏
            <br />
            – mom
          </blockquote>
        `,
                }),
            }
        },
        beforeDestroy() {
            this.editor.destroy()
        },
        mounted() {
            this.$store.dispatch('getWorkers');
        },
        methods: {
            createTask() {
                let data = this.formData;
                data.project_id = this.project_id;
                data.description = this.editor.getHTML();
                this.$store.dispatch('createTask', data)
                    .then(res => alert('Задача создана'))
                    .catch(err => alert('error'));
            },
            showImagePrompt(command) {
                const src = prompt('Введите url картинки')
                if (src !== null) {
                    command({ src })
                }
            },
        },
        computed: {
            availableWorkers(){
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
.editor {

}

.icon {
    color:#eee;
    padding:2px;
    border-radius:2px;
}
    .icon:hover {
        color:#fff;
        background:#777;
    }

    .menubar {
        background:rgba(0,0,0,0.8);
        border-radius:5px;
        padding:3px;
    }
</style>
