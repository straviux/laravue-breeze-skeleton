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
            formJpmSummary:null,
            formVerifyAccess:false,
            formAccessCode:null,
            formProvince:null,
            formPosition:null,
            formMunicipality:null,
            formReportLevel:null,
            formBarangay:null,
            precinctResult: null,
            formDistrict: null,
            loading:false,
            resultErrors:[]
        }
    ),
    persist: true,

    getters: {
        // district: (state)=> state.district,\
        jpm_summary:(state)=>state.formJpmSummary,
        is_loading:(state)=>state.loading,
        province:(state)=>state.formProvince,
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
                // console.log(error);
            }
        },
        async getResult(formData) {

            if(formData)
            {
                try {
                    this.resultErrors = [];
                    this.loading = true;
                    // this.getToken();

                    const data = await axios.get("/api/v1/clustered-precinct-results", {params: {
                        municipality: formData.municipalities,
                        position: formData.positions,
                        barangay: formData.barangays,
                        report_level: formData.report_level,
                        district: formData.district
                    }});
                    if (formData.report_level=='municipality') {
                        let queryParam;
                        for(let i=0;i<formData.municipalities.length;i++) {
                            queryParam +=`municipalities[]=${formData.municipalities[i]}&`;
                        }
                        // fetch("http://assistance.jpmpalawan.org/mobi/jpm/ajax_get_member_summary_by_municipality?"+ queryParam).then((response) => {
                        //     return response.json();
                        // })
                        // .then((res) => {
                        //     // console.log(res);
                        //     this.formJpmSummary = res;
                        // })
                    }

                    // console.log(jpm_members);
                    // console.log(data)
                    if(data.status===200) {
                        this.loading = false;
                    }
                    this.precinctResult = data.data;
                    this.formReportLevel = formData.report_level;
                    this.formDistrict = formData.district
                    this.formMunicipality = formData.municipalities;
                    this.formPosition = formData.positions;
                    // this.formDistrict = formData.district;
                    // this.formBarangay = formData.barangay;

                    // console.log(data);
                    // return false;
                    this.router.push({name:'ElectionResults'});

                } catch (error) {
                    this.loading = false;
                    // console.log(error);
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
                    // console.log(error);
                }
            }
        },

        async getMunicipality(province) {
            if(province)
            {
                try {
                    // this.getToken();

                    const data = await axios.get("/api/v1/municipality-by-province", {params: {
                        province: province
                    }});
                    // console.log(data)
                    this.formMunicipality = data.data;

                } catch (error) {
                    // console.log(error);
                }
            }
        },

        async verifyAccess (data) {
            this.resultErrors = [];
            this.loading = true;
            try {
                // this.getToken();
                const res = await axios.post("/api/v1/verify-access-code", {
                    access_code: data.access_code
                });
                // console.log(res.data.data);

                // this.router.push("/");
                if(res.data.success) {
                    this.getMunicipality(res.data.data.province);
                    this.loading = false;
                    this.formVerifyAccess=res.data.success;
                    this.formProvince=res.data.data.province;
                    this.formAccessCode=res.data.data.access_code;
                    if(this.router.currentRoute.value.name!=='Home'&&this.router.currentRoute.value.name!=='Results') {
                        this.router.push("/");
                    }

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

        logout () {
            // this.resultErrors = [];
            this.loading = true;
            try {
                // this.getToken();

                // console.log(res.data.data);
                this.$reset();
                this.router.push("/verify-access");

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
