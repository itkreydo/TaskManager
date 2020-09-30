export default {

    login(data) {
        return new Promise((resolve, reject) => {
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('/api/v1/auth/login', data)
                    .then(res => {
                        console.log("success auth");
                        console.log(res);
                        resolve(res.data);
                    }).catch(err => reject(err));
            }).catch(e => {
                console.log(e);
                reject(e);
            });
        });
    },

    checkTwoFaCode() {
        return new Promise((resolve, reject) => {
            axios.post('/api/v1/auth/login/code/check', data)
                .then(res => {
                    console.log("code success auth");
                    console.log(res);
                    resolve(res.data);
                }).catch(err => reject(err));
        });
    },

    logout() {
        return new Promise((resolve, reject) => {
            axios.post('/api/v1/auth/logout')
                .then(res => {
                    console.log("success logged out");
                    resolve(res);
                }).catch(err => reject(err));
        });
    },

    getUser() {
        return axios.get('/api/v1/users')
    },

    resetPassword(data) {
        return axios.post('password/reset', data)
    },

}
