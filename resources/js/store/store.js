import {getLocalUser} from "../helpers/auth";
import {setAuthorization,deleteAuthorization} from "../helpers/general";
import auth from "../api/auth";

const user = getLocalUser();

export default {
    state: {
        welcomeMessage: "Welcome to my app",
        currentUser: user,
        isLoggedIn: !!user,
        loading: false,
        auth_error: null,
        posts: [],
        workers: [],
        projects: [],
    },
    mutations: {
        login(state) {
            state.loading = true;
            state.auth_error = null;
        },
        loginFailed(state, payload) {
            state.loading = false;
            state.auth_error = payload.error;
        },
        loginSuccess(state, payload) {
            state.loading = false;
            state.isLoggedIn = true;
            state.auth_error = null;
            state.currentUser = payload;
            localStorage.setItem("user", JSON.stringify(state.currentUser));
            setAuthorization(payload.token);
            console.log(payload.token);
        },
        logout(state) {
            localStorage.removeItem("user");
            state.isLoggedIn = false;
            state.currentUser = null;
            deleteAuthorization();
        },
        updateUserInfo(state, payload) {
            state.currentUser = state.currentUser.concat(payload);
        },
        updatePosts(state, payload) {
            state.posts = payload;
        },
        updateWorkers(state, payload) {
            state.workers = payload;
        },
        updateProjects(state, payload) {
            state.projects = payload;
        },
        addProject(state, payload) {
            state.projects.push(payload);
        },
        updateAvatar(state, payload) {
            state.currentUser.avatar = payload;
            localStorage.setItem("user", JSON.stringify(state.currentUser));
        }
    },
    getters: {
        welcome(state) {
            return state.welcomeMessage;
        },
        isLoading(state) {
            return state.loading;
        },
        isLoggedIn(state) {
            return state.isLoggedIn;
        },
        authError(state) {
            return state.auth_error;
        },
        currentUser(state) {
            return state.currentUser;
        },
        posts(state) {
            return state.posts;
        },
        workers(state) {
            return state.workers;
        },
        projects(state) {
            return state.projects;
        }
    },
    actions: {
        login({commit}, data) {
            return new Promise((resolve, reject) => {
                commit("login");

                auth.login(data)
                    .then(response => {
                        commit("loginSuccess", response);
                        resolve(response);
                    }).catch((error) => {
                        commit('loginFailed', {error});
                        reject(error);
                });
            });
        },
        logout({commit}) {
            return new Promise((resolve, reject) => {
                auth.logout()
                    .then(response => {
                        commit('logout');
                        resolve(response);
                    }).catch((error) => {
                        reject(error);
                });
            });
        },
        updateProfile({commit}, userInfo) {
            return new Promise((resolve, reject) => {
                axios.put("/api/profile", userInfo)
                    .then(response => {
                        commit("updateUserInfo", userInfo);
                        resolve(response);
                    }).catch((error) => {
                    reject(error);
                });
            });
        },
        updateProfileAvatar({commit}, avatar) {
            let formData = new FormData();
            formData.append('avatar', avatar);
            return new Promise((resolve, reject) => {
                axios.put("/api/profile/avatar", formData, {headers: {'Content-Type': 'multipart/form-data'}})
                    .then(response => {
                        commit("updateUserInfo", response);
                        resolve(response);
                    }).catch((error) => {
                    reject(error);
                });
            });
        },

        getPosts({commit}) {
            return new Promise((resolve, reject) => {
                axios.get("/api/v1/posts")
                    .then(response => {
                        commit("updatePosts", response.data);
                        resolve(response.data);
                    }).catch((error) => {
                    reject(error);
                });
            });
        },
        //create user
        createUser(state, data) {
            return axios.post('/api/v1/users', data);
        },
        //get all users
        getWorkers({commit}) {
            return new Promise((resolve, reject) => {
                axios.get("/api/v1/users")
                    .then(response => {
                        commit("updateWorkers", response.data);
                        resolve(response.data);
                    }).catch((error) => {
                    reject(error);
                });
            });
        },

        getWorker({commit}, id) {
            return new Promise((resolve, reject) => {
                axios.get(`/api/v1/users/${id}`)
                    .then(response => {
                        resolve(response.data);
                    }).catch((error) => {
                    reject(error);
                });
            });
        },

        // createProject(state, data) {
        //     return axios.post('/api/v1/projects', data);
        // },

        createProject({commit}, data) {
            return new Promise((resolve, reject) => {
                axios.post("/api/v1/projects", data)
                    .then(response => {
                        commit("addProject", response.data);
                        resolve(response.data);
                    }).catch((error) => {
                    reject(error);
                });
            });
        },

        //get all projects
        getProjects({commit}) {
            return new Promise((resolve, reject) => {
                axios.get("/api/v1/projects")
                    .then(response => {
                        commit("updateProjects", response.data);
                        resolve(response.data);
                    }).catch((error) => {
                    reject(error);
                });
            });
        },

        //get all projects
        getProject({commit}, project_id) {
            return new Promise((resolve, reject) => {
                axios.get(`/api/v1/projects/${project_id}`)
                    .then(response => {
                        // commit("updateProjects", response.data);
                        resolve(response.data);
                    }).catch((error) => {
                    reject(error);
                });
            });
        },

        //get tasks by project
        getTasks({commit}, project_id) {
            return new Promise((resolve, reject) => {
                axios.get(`/api/v1/projects/${project_id}/tasks`)
                    .then(response => {
                        // commit("updateProjects", response.data);
                        resolve(response.data);
                    }).catch((error) => {
                    reject(error);
                });
            });
        },

        //get tasks by project
        getTask({commit}, data) {
            return new Promise((resolve, reject) => {
                axios.get(`/api/v1/projects/${data.project_id}/tasks/${data.task_id}`)
                    .then(response => {
                        // commit("updateProjects", response.data);
                        resolve(response.data);
                    }).catch((error) => {
                    reject(error);
                });
            });
        },

        createTask({commit}, data) {
            return new Promise((resolve, reject) => {
                axios.post(`/api/v1/projects/${data.project_id}/tasks`, data)
                    .then(response => {
                        // commit("addProject", response.data);
                        resolve(response.data);
                    }).catch((error) => {
                    reject(error);
                });
            });
        },

        changeTaskStatus({commit}, data) {
            return new Promise((resolve, reject) => {
                axios.put(`/api/v1/projects/${data.project_id}/tasks/${data.task_id}/status`, data)
                    .then(response => {
                        resolve(response.data);
                    }).catch((error) => {
                    reject(error);
                });
            });
        },

        changePassword({commit}, data) {
            return new Promise((resolve, reject) => {
                axios.put(`/api/v1/profile/password`, data)
                    .then(response => {
                        resolve(response.data);
                    }).catch((error) => {
                    reject(error);
                });
            });
        },

        loadAvatar({commit}, data) {
            return new Promise((resolve, reject) => {
                axios.post(`/api/v1/profile/avatar`, data)
                    .then(response => {
                        commit("updateAvatar", response.data);
                        resolve(response.data);
                    }).catch((error) => {
                    reject(error);
                });
            });
        },

        getAccess({commit}, data) {
            return new Promise((resolve, reject) => {
                axios.post(`/api/v1/access`, data)
                    .then(response => {
                        resolve(response.data);
                    }).catch((error) => {
                    reject(error);
                });
            });
        },

        getTwoFaData({commit}, token) {
            return new Promise((resolve, reject) => {
                axios.get(`/api/v1/profile/security/two-fa`, {token: token})
                    .then(response => {
                        resolve(response.data);
                    }).catch((error) => {
                    reject(error);
                });
            });
        },

        verifyTwoFaCode({commit}, data) {
            return new Promise((resolve, reject) => {
                axios.post(`/api/v1/profile/security/two-fa/verify-code`, data)
                    .then(response => {
                        resolve(response.data);
                    }).catch((error) => {
                    reject(error);
                });
            });
        },

        checkTwoFaCode({commit}, data) {
            return new Promise((resolve, reject) => {
                axios.post(`/api/v1/auth/login/code/check`, data)
                    .then(response => {
                        resolve(response.data);
                    }).catch((error) => {
                    reject(error);
                });
            });
        },

        getUserTasks({commit}, user_id) {
            return new Promise((resolve, reject) => {
                axios.get(`/api/v1/users/${user_id}/tasks`)
                    .then(response => {
                        resolve(response.data);
                    }).catch((error) => {
                    reject(error);
                });
            });
        },

        getMessagesByTask({commit}, data) {
            return new Promise((resolve, reject) => {
                axios.get(`/api/v1/projects/${data.project_id}/tasks/${data.task_id}/messages`)
                    .then(response => {
                        resolve(response.data);
                    }).catch((error) => {
                    reject(error);
                });
            });
        },

        sendMessage({commit}, data) {
            return new Promise((resolve, reject) => {
                axios.post(`/api/v1/projects/${data.project_id}/tasks/${data.task_id}/messages`, data)
                    .then(response => {
                        resolve(response.data);
                    }).catch((error) => {
                    reject(error);
                });
            });
        }





    }
}
