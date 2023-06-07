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
            // formJpmSummary:null,
            formVerifyAccess:false,
            formAccessCode:null,
            formControlCode:null,
            accessCodes:null,
            accessHistory:[],
            formVisitCount:0,
            formProvince:null,
            formPosition:null,
            formMunicipality:null,
            formReportLevel:null,
            formBarangay:null,
            precinctResult: null,
            formDistrict: null,
            limited_access:false,
            loading:false,
            resultErrors:[]
        }
    ),
    persist: {
        // storage: sessionStorage,
        paths: [
            'formAccessCode',
            'formControlCode','formVerifyAccess',
            'formProvince','formPosition',
            'formMunicipality','formReportLevel',
            'formBarangay','precinctResult',
            'formDistrict','limited_access'],
    },

    getters: {
        // district: (state)=> state.district,\
        has_limited_access:(state)=>state.limited_access,
        access_codes:(state)=>state.accessCodes,
        // jpm_summary:(state)=>state.formJpmSummary,
        is_loading:(state)=>state.loading,
        province:(state)=>state.formProvince,
        access_code: (state)=>state.formAccessCode,
        access_history: (state)=>state.accessHistory,
        control_code: (state)=>state.formControlCode,
        visit_count: (state)=>state.formVisitCount,
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
                        access_code:this.formAccessCode,
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

                    }

                    // console.log(jpm_members);
                    // console.log(data)
                    if(data.status===200) {
                        this.loading = false;
                    }
                    this.formVisitCount++;
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
                    if(!res.data.data.municipality) {
                        this.getMunicipality(res.data.data.province);
                    } else {
                        this.formMunicipality = res.data.data.municipality;
                        this.limited_access = true;
                    }
                    this.loading = false;
                    this.formVerifyAccess=res.data.success;
                    this.formProvince=res.data.data.province;
                    this.formAccessCode=res.data.data.access_code;
                    this.formVisitCount=res.data.data.visit_count;
                    if(this.router.currentRoute.value.name!=='Home'&&this.router.currentRoute.value.name!=='Results') {
                        this.router.push("/");
                    }

                } else {
                    if(this.router.currentRoute.value.name==='verify-access') {
                        this.resultErrors.push(res.data.message);
                    } else {
                        this.router.push("/verify-access");
                    }

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

        async verifyControlAccess(data) {
            this.resultErrors = [];
            this.loading = true;
            try {
                // this.getToken();
                const res = await axios.post("/api/v1/verify-control-code", {
                    control_code: data.control_code
                });
                // console.log(res.data.data);

                // this.router.push("/");
                if(res.data.success) {
                    this.loading = false;
                    this.formVerifyAccess=res.data.success;
                    this.formControlCode=data.control_code;

                    if(this.router.currentRoute.value.name!=='control-panel-home') {
                        this.router.push("/control001");
                    }

                } else {
                    if(this.router.currentRoute.value.name==='control-panel-login') {
                        this.resultErrors.push(res.data.message);
                    } else {
                        this.router.push("/control001/login");
                    }

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

        async getAccessCodes() {
            this.loading = true;
            try {
                // this.getToken();

                const data = await axios.get("/api/v1/show-access-code", {params: {
                    control_code: this.formControlCode
                }});
                // console.log()
                this.accessCodes = data.data.data;
                this.loading = false;
                // this.formBarangay = data.data;

            } catch (error) {
                this.loading = false;
                // console.log(error);
            }
        },

        async updateAccessCode(formData) {
            this.resultErrors = [];
            this.loading = true;
            try {
                // this.getToken();


                    const res = await axios.post("/api/v1/update-access-code", {
                        access_code: formData.access_code,
                        is_accessible: formData.has_access?1:0
                    });

                    if(res.data.success) {
                        // console.log(res.data)
                        this.getAccessCodes();
                    } else {
                        // console.log(res.data)
                    }
                    this.loading = false;
                console.log(res);
                // this.accessCodes = data.data.data;
                // this.formBarangay = data.data;

            } catch (error) {
                // console.log(error);
                this.loading = false;
            }
        },

        async showAccessHistory(access_code_id) {
            this.loading = true;
            try {
                // this.getToken();

                const data = await axios.get("/api/v1/show-access-history", {params: {
                    access_code_id: access_code_id
                }});
                // console.log(data.data.data);
                this.accessHistory = data.data.result;
                this.formVisitCount = data.data.visit_count;
                // this.formBarangay = data.data;
                this.loading = false;
            } catch (error) {
                // console.log(error);
                this.loading = false;
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
