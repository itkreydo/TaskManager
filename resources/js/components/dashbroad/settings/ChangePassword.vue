<template>
    <div>
        <v-row>
            <v-col cols="12" md="6">

                <h4>Изменение пароля</h4>
                <v-alert
                    dense
                    v-if="!success && !error"
                    type="info"
                >
                    Рекоммендуется менять пароль каждые 3 месяца
                </v-alert>

                <v-text-field type="password" v-model="changePasswordForm.password_old" label="Старый пароль"></v-text-field>
                <v-text-field type="password" v-model="changePasswordForm.password" label="Новый пароль"></v-text-field>
                <v-text-field type="password" v-model="changePasswordForm.password_confirm" label="Подтвердите пароль"></v-text-field>
                <v-alert
                    dense
                    v-if="success"
                    type="success"
                >
                    Пароль успешно изменён
                </v-alert>
                <v-alert
                    dense
                    v-if="error"
                    type="error"
                >
                    {{error}}
                </v-alert>
                <v-btn @click="changePassword" color="primary">Изменить пароль</v-btn>
            </v-col>
            <v-col cols="12" md="6">
                <h4>Безопасность</h4>
                <v-row class="align-center">
                    <v-col class="" cols="10">
                        Двухфакторная аутентификация
                        <div class="property-description">Защитите свой аккаунт от взлома с помощью мобильного
                            телефона
                        </div>
                    </v-col>
                    <v-col cols="2">
                        <v-switch v-model="twoFactorSwitch" @change="twoFactorDialog = true"></v-switch>
                    </v-col>
                </v-row>
                <!--                        <v-row>-->
                <!--                            <v-switch-->
                <!--                                v-model="twoFactorSwitch"-->
                <!--                                :label="`Двухфакторная аутентификация`"-->
                <!--                            ></v-switch>-->
                <!--                        </v-row>-->
            </v-col>
        </v-row>
        <v-dialog
            v-model="twoFactorDialog"
            width="800px"
        >
            <v-card>
                <v-card-title>Двухфакторная аутентификация</v-card-title>
                <template>
                    <v-stepper class="elevation-0" v-model="e1">
                        <v-stepper-header class="elevation-0">
                            <v-stepper-step :complete="e1 > 1" step="1">Подтверждение пароля</v-stepper-step>

                            <v-divider></v-divider>

                            <v-stepper-step :complete="e1 > 2" step="2">Настройка 2FA</v-stepper-step>

                            <v-divider></v-divider>

                            <v-stepper-step step="3">Успех</v-stepper-step>
                        </v-stepper-header>

                        <v-stepper-items class="elevation-0">
                            <v-stepper-content class="elevation-0" step="1">
                                <v-card
                                    class="mb-12 elevation-0"
                                >

                                    <div class="password-block">
                                        Для подтверждения операции введите пароль от вашего аккаунта
                                        <v-text-field v-model="password" label="Ваш пароль">
                                        </v-text-field>
                                    </div>
                                </v-card>
                                <div class="text-right">
                                    <v-btn
                                        color="primary"
                                        @click="verifyPassword"
                                    >
                                        Подтвердить
                                    </v-btn>

                                    <v-btn @click="twoFactorDialog = false"  text>Отмена</v-btn>
                                </div>
                            </v-stepper-content>

                            <v-stepper-content step="2">
                                <v-card
                                    class="text-center elevation-0"
                                >
                                    <v-avatar tile size="200px">
                                        <v-img :src="twoFa.qrCodeUrl"></v-img>
                                    </v-avatar>
                                        <p>{{twoFa.secret}}</p>
                                    <p class="text-left">
                                    Введите код, полученный через Google Authenticator
                                    </p>
                                    <v-text-field v-model="code" label="Код подтверждения">
                                    </v-text-field>
                                </v-card>
                                <div class="text-right">
                                <v-btn
                                    color="primary"
                                    @click="verifyCode"
                                >
                                    Подтвердить
                                </v-btn>

                                <v-btn @click="twoFactorDialog = false"  text>Отмена</v-btn>
                                </div>
                            </v-stepper-content>

                            <v-stepper-content step="3">
                                <v-card
                                    class="mb-12 elevation-0 text-center"
                                    height="200px"
                                >
                                    <v-avatar tile size="200px">
                                        <v-img src="/img/security.svg"></v-img>
                                    </v-avatar>
                                    <p class="mt-4" style="color:green">Операция выполнена успешно</p>
                                </v-card>

                            <div class="text-right">
                                <v-btn @click="twoFactorDialog = false" text>Закрыть</v-btn>
                            </div>
                            </v-stepper-content>
                        </v-stepper-items>
                    </v-stepper>
                </template>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    export default {
        name: "ChangePassword",
        data: () => ({
            success:false,
            error: null,
            changePasswordForm: {
                password_old: null,
                password: null,
                password_confirm: null,
            },
            code: null,
            twoFactorSwitch: false,
            twoFactorDialog: false,
            password: null,
            accessToken: null,
            e1: 1,
            twoFa: {
                qrCodeUrl: '',
                secret: '',
            }
        }),
        methods: {
            changePassword() {
                this.$store.dispatch('changePassword', this.changePasswordForm).then(res => {
                    this.error = null;
                    this.success= true;
                }).catch(err => this.error = err.response.data.error);
            },
            verifyPassword() {
                let data = {
                    password: this.password,
                    access_type: 'enable-two-fa'
                };
                this.$store.dispatch('getAccess', data).then(res => {
                    this.accessToken = res.token;
                    console.log(res);
                    this.$store.dispatch('getTwoFaData', this.accessToken).then(result => {
                        this.e1 = 2;
                        this.twoFa.qrCodeUrl = result.qrCodeUrl;
                        this.twoFa.secret = result.secret;
                        console.log(result);
                    }).catch(err => alert('ошибка получения данных'));
                }).catch(err => alert('Доступ запрещён'));

            },

            verifyCode() {
                let data = {
                  token: this.accessToken,
                  code: this.code,
                  secret: this.twoFa.secret,
                };
                this.$store.dispatch('verifyTwoFaCode', data).then(result => {
                    this.e1 = 3;

                    console.log(result);
                }).catch(err => alert('ошибка получения данных'));
            },

            enableTwoFa() {
                let data = {
                    token: this.accessToken,
                };
                this.$store.dispatch('enableTwoFa', data).then(res => {
                    this.el = 2;
                    this.accessToken = res.token;
                    console.log(res);
                })
                    .catch(err => alert('Доступ запрещён'));

            }
        }
    }
</script>

<style scoped>
    .property-description {
        color: #aaa;
        font-size: 0.9rem;
    }
</style>
