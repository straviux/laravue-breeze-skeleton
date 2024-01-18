import {defineStore} from "pinia";
import axios from "axios";
import { stringify } from "qs";
axios.create({
paramsSerializer: {
    serialize: stringify // or (params) => Qs.stringify(params, {arrayFormat: 'brackets'})
}
});

export const BskResultStore = defineStore("bskResult", {
    state: () => (
        {
            // formJpmSummary:null,
            bskResult:[],
            formDistrict:null,
            formMunicipality:[], 
            formBarangay:[],
            formPosition:[],
            limited_access:false,
            loading:false,
            resultErrors:[]
        }
    ),
    getters: {
        // district: (state)=> state.district,\
        has_limited_access:(state)=>state.limited_access,
        is_loading:(state)=>state.loading,
        result: (state) => state.bskResult,
        district: (state)=>state.formDistrict,
        municipality: (state) => state.formMunicipality,
        position: (state)=>state.formPosition,
        barangay: (state) => state.formBarangay,
        errors: (state) => state.resultErrors
    },
    actions: {
        async getResult() {
            const data = await axios.get("/api/v1/bskresult");

            // console.log(data.data);
            this.bskResult = data.data;
        },

        async getBarangay(municipality) {

            if(municipality)
            {
                try {
                    // this.getToken();

                    const data = await axios.get("/api/v1/bsk-barangay-by-municipality", {params: {
                        municipality: municipality
                    }});
                    // console.log(data)
                    this.formBarangay = data.data;

                } catch (error) {
                    // console.log(error);
                }
            }
        },

        async generateResult(formData) {

            if(formData)
            {
                try {
                    this.resultErrors = [];
                    this.loading = true;
                    // this.getToken();

                    const data = await axios.get("/api/v1/bskresult", {params: {
                        // access_code:this.formAccessCode,
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
                    this.bskResult = data.data;
                    this.formReportLevel = formData.report_level;
                    this.formDistrict = formData.district
                    this.formMunicipality = formData.municipalities;
                    this.formPosition = formData.positions;
                    // this.formDistrict = formData.district;
                    this.formBarangay = formData.barangay;

                    console.log(formData.positions);
                    // return false;
                    // console.log(data.data)
                    // this.router.push({name:'ElectionResults'});

                } catch (error) {
                    this.loading = false;
                    // console.log(error);
                }
            }
        },
    }
})