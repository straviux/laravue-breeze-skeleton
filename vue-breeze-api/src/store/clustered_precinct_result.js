import {defineStore} from "pinia";
import axios from "axios";
import { stringify } from "qs";
axios.create({
  paramsSerializer: {
    serialize: stringify // or (params) => Qs.stringify(params, {arrayFormat: 'brackets'})
  }
});
export const clusteredPrecinctStore = defineStore("clusteredPrecinct", {
    state: () => (
        {
            formVerifyAccess:false,
            formAccessCode:null,
            // formDistrict:null,
            formPosition:null,
            formMunicipality:null,
            formReportLevel:null,
            formBarangay:null,
            precinctResult: null,
            formDistrict: null,
            resultErrors:[]
        }
    ),
    persist: true,

    getters: {
        // district: (state)=> state.district,
        access_code: (state)=>state.formAccessCode,
        verify_access: (state)=>state.formVerifyAccess,
        barangay: (state) => state.formBarangay,
        report_level: (state)=>state.formReportLevel,
        district: (state)=>state.formDistrict,
        municipality: (state) => state.formMunicipality,
        position: (state)=>state.formPosition,
        result: (state) => state.precinctResult,
        errors: (state) => state.resultErrors
    } ,
    actions: {
        async getToken() {
            try {
                await axios.get("/sanctum/csrf-cookie");
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
                        municipality: formData.municipalities,
                        position: formData.positions,
                        barangay: formData.barangays,
                        report_level: formData.report_level,
                        district: formData.district
                    }});
                    // console.log(data)
                    this.precinctResult = data.data;
                    this.formReportLevel = formData.report_level;
                    this.formDistrict = formData.district
                    this.formMunicipality = formData.municipalities;
                    this.formPosition = formData.positions;
                    // this.formDistrict = formData.district;
                    // this.formBarangay = formData.barangay;
                    this.router.push({name:'ElectionResults'});

                } catch (error) {
                    console.log(error);
                }
            }
        },

        async getBarangay(municipality) {

            if(municipality)
            {
                try {
                    // this.getToken();

                    const data = await axios.get("/api/v1/barangay-by-municipality", {params: {
                        municipality: municipality
                    }});
                    // console.log(data)
                    this.formBarangay = data.data;

                } catch (error) {
                    console.log(error);
                }
            }
        },

        async verifyAccess (data) {
            this.resultErrors = [];
            try {
                // this.getToken();
                const res = await axios.post("/api/v1/verify-access-code", {
                    access_code: data.access_code
                });
                // console.log(res.data);

                // this.router.push("/");
                if(res.data.success) {
                    this.formVerifyAccess=res.data.success;
                    this.formAccessCode=data.access_code;
                    this.router.push("/");
                } else {
                    this.router.push("/verify-access");
                }

            } catch (error) {

                // if(error.response.status===422) {
                //     console.log(error.response.data)
                //     this.resultErrors = error.response.data.errors;
                // } else {
                //     console.log(error)
                // }
            }

        },
    }
})
