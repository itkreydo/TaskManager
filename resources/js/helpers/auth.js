export function login(credentals) {
    return new Promise((res, rej) => {
        axios.post("/api/auth/login", credentals)
            .then((response) => {
                res(response.data);
            })
            .catch((err) => {
                rej('Unknown error email is incorrect');
            });
    });
}

export function getLocalUser() {
    const userStr = localStorage.getItem("user");

    if(!userStr) {
        return null;
    }

    return JSON.parse(userStr);
}
