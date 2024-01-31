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
            formReportLevel:null,
            selectBarangay:[],
            selectMunicipality:[],
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
        select_municipality:(state)=>state.selectMunicipality,
        select_barangay:(state)=>state.selectBarangay, 
        report_level: (state)=>state.formReportLevel,
        errors: (state) => state.resultErrors
    },
    actions: {
        async getResult() {
            const data = await axios.get("/api/v1/bskresult");

            this.bskResult = data.data;
            
        },

        async getBarangay(municipality) {
            if(municipality)
            {
                try {
                    const data = await axios.get("/api/v1/bsk-barangay-by-municipality", {params: {
                        municipality: municipality
                    }});
                    this.selectBarangay = data.data;
                    this.formBarangay=data.data;
                    // console.log(data.data)

                } catch (error) {
                    console.log(error);
                }
            }
        },

        async generateResult(formData) {
            if(formData)
            {

                try {
                    this.resultErrors = [];
                    this.loading = true;

                    const data = await axios.get("/api/v1/bskresult", {params: {
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
                            this.getBarangay(formData.municipalities[i]);
                        }
                    } else if(formData.report_level=='barangay') {
                        
                        this.formBarangay=formData.barangays.map((res)=>{
                            return {'barangay_name':res}
                        });
                        
                    }

                    if(data.status===200) {
                        this.loading = false;
                    }
                    // console.log(data.data.result)
                    this.bskResult = data.data.result;
                    this.formReportLevel = formData.report_level;
                    this.formDistrict = formData.district
                    this.formMunicipality = typeof formData.municipalities == "string"?[formData.municipalities]:formData.municipalities;
                    this.formPosition = formData.positions;

                } catch (error) {
                    this.loading = false;
                }
            }
        },
    }
})