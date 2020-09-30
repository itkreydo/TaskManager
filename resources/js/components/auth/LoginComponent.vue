<template>
    <v-container
        fluid
        fill-height
        class="main-login-container"
    >
        <v-layout
            align-center
            justify-center
        >

            <v-flex
                xs12
                sm8
                md4
            >
                <v-card class="elevation-0" style="background:none">
                    <v-card-title class="justify-center mb-10">
                        <img src="/img/logo/logo-white.png" style="width:100%;max-width:250px;">
                    </v-card-title>
                    <div v-if="step == 1">
                        <v-card-text class="mt-12">
                            <v-text-field
                                outlined
                                dense
                                dark
                                label="Login"
                                name="login"
                                type="text"
                                v-model="formData.email"
                                :error = "Boolean(errors.email)"
                                :error-messages="errors.email"
                                background-color="rgba(255,255,255,0.5)"
                            ></v-text-field>

                            <v-text-field
                                dense
                                outlined
                                dark
                                id="password"
                                label="Password"
                                name="password"
                                v-model="formData.password"

                                :error-message="errors.password"
                                type="password"
                                background-color="rgba(255,255,255,0.5)"
                            ></v-text-field>
                        </v-card-text>
                        <v-card-actions class="pa-4">

                            <v-btn block @click="login">Войти</v-btn>
                        </v-card-actions>
                    </div>
                    <div v-if="step == 2">
                        <v-card-text class="mt-12">
                            <v-text-field
                                autocompletion="off"
                                outlined
                                dense
                                dark
                                label="Код подтверждения"
                                name="code"
                                type="text"
                                v-model="formData.code"
                                background-color="rgba(255,255,255,0.5)"
                            ></v-text-field>

                        </v-card-text>
                        <v-card-actions class="pa-4">

                            <v-btn block @click="checkTwoFaCode">Подтвердить</v-btn>
                        </v-card-actions>
                    </div>

                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import auth from "../../api/auth";

    export default {
        name: "LoginComponent",
        data: () => ({
            formData: {
                email: null,
                password: null,
                code: null,
            },
            errors: {
                email: null
            },
            step: 1,
            token: null,
        }),

        methods: {
            login() {
                this.$store.dispatch('login', this.formData)
                    .then((response) => {
                        if (response.two_fa_auth) {
                            this.step = 2;
                            this.token = response.token;
                        }
                        else {
                            this.$router.push('/projects');
                            this.$store.commit('loginSuccess', response);
                        }
                    })
                    .catch(err => {
                        this.errors = err.response.data.errors;
                    });
            },
            checkTwoFaCode() {
                let data = {
                    token: this.token,
                    code: this.formData.code,
                };
                this.$store.dispatch('checkTwoFaCode', data)
                    .then((response) => {
                        this.$store.commit('loginSuccess', response);
                        this.$router.push('/projects')
                    })
                    .catch(err => alert(err.response.data.error));
            }
        }
    }
</script>

<style scoped>
    .main-login-container {
        background: url("/img/bg_login.png");
        background-size: cover;
        /*background: #eee;*/
    }

    .logo {

        width: 190px;
    }

    .input {
        background: rgba(255, 255, 255, 0.5);
    }

</style>
