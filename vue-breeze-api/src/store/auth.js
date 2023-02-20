import {defineStore} from "pinia";
import axios from "axios";

export const useAuthStore = defineStore("auth", {
    state: () => (
     {   authUser: null, authErrors:[]}
    ),
    getters: {
        user: (state) => state.authUser,
        errors: (state) => state.authErrors
    } ,
    actions: {
        async getToken() {
            try {
                await axios.get("/sanctum/csrf-cookie");
            } catch (error) {
                console.log(error);
            }
        },
        async getUser() {
            try {
                this.getToken();
                const data = await axios.get("/api/user");
                this.authUser =data.data
            } catch (error) {
                console.log(error);
            }
        },
        async handleLogin (data) {
            this.authErrors = [];
            this.getToken();
            try {
                await axios.post("/login", {
                    input_type: data.input_type,
                    password: data.password,
                });
                console.log('logged in')
                this.router.push("/");
            } catch (error) {
                if(error.response.status===422) {
                    this.authErrors = error.response.data.errors;
                }
            }

        },
        async handleLogout () {
            await axios.post("/logout");
            this.authUser = null;
            console.log('logout');
        },
        async handleForgotPassword(email) {
            this.authErrors = [];
            this.getToken();
            try {
                await axios.post("/forgot-password", {
                    email: email
                });
            } catch (error) {
                if(error.response.status===422) {
                    this.authErrors = error.response.data.errors;
                }
            }
        }
    }
})
