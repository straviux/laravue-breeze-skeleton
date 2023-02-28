import {defineStore} from "pinia";
import axios from "axios";
import { stringify } from "qs";
const api = axios.create({
  paramsSerializer: {
    serialize: stringify // or (params) => Qs.stringify(params, {arrayFormat: 'brackets'})
  }
});
export const clusteredPrecinctStore = defineStore("clusteredPrecinct", {
    state: () => (
     {   precinctResult: null, resultErrors:[]}
    ),
    persist: true,
    getters: {
        result: (state) => state.precinctResult,
        errors: (state) => state.resultErrors
    } ,
    actions: {
        async getToken() {
            try {
                await api.get("/sanctum/csrf-cookie");
            } catch (error) {
                console.log(error);
            }
        },
        async getResult(formData) {

            if(formData)
            {
                try {
                    // this.getToken();

                    const data = await axios.get("/api/v1/clustered-precinct-results", {params: {
                        municipality: formData.cities,
                        position: formData.positions
                    }});
                    // console.log(data)
                    this.precinctResult = data.data;
                    localStorage.setItem('municipality', JSON.stringify(formData.cities));
                    localStorage.setItem('position', JSON.stringify(formData.positions));
                    this.router.push("/results");

                } catch (error) {
                    console.log(error);
                }
            }
        },
    }
})
