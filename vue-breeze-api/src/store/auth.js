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
                const response = await axios.post("/login", {
                    input_type: data.input_type,
                    password: data.password,
                });

                this.router.push("/");

            } catch (error) {
                if(error.response.status===422) {
                    this.authErrors = error.response.data.errors;
                } else {
                    console.log(error)
                }
            }

        },
        async handleLogout () {
            try {
                this.getToken();
                const response = await axios.post("/logout");
                console.log(response);
                if(response.status===204) {
                    this.authUser = null;
                    localStorage.clear();
                    window.location.href = '/login';
                }
            } catch (error) {
                console.error(error);
            }
            // console.log('logout');

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
