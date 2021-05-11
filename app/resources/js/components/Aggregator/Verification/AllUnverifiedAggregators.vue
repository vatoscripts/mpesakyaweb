<template>
    <div class="content-wrapper">
        <h4 class="content-heading">
            <div>
                M-pesa KYA Portal
                <p class="lead mt-2">Unverified Aggregator/TDR List</p>
            </div>
        </h4>
        <div class="vld-parent" ref="formContainer">
            <div class="clearfix"></div>
            <div
                v-show="message"
                class="alert alert-warning alert-block mt-1 text-center"
            >
                <span class="h3">{{ message }}</span>
            </div>
            <div class="table-responsive" v-show="!message">
                <vue-table-dynamic :params="params" ref="table">
                    <template v-slot:column-5="{ props }">
                        <span>
                            <a
                                v-on:click="doSomethingCool(props.cellData)"
                                ref="agentID"
                                :id="props.cellData"
                                href="#"
                                ><span>View</span></a
                            >
                        </span>
                    </template>
                </vue-table-dynamic>
            </div>
        </div>
    </div>
</template>

<script>
import VueTableDynamic from "vue-table-dynamic";

export default {
    data() {
        return {
            wakalas: "",
            message: "",
            fields: {},
            agents: null,
            params: {
                data: [
                    [
                        "Contact Name",
                        "Business Name",
                        "Short Code",
                        "Created Date",
                        "Territory",
                        "Action"
                    ]
                ],
                header: "row",
                border: true,
                stripe: true,
                pagination: true,
                pageSize: 25,
                sort: [0, 1, 2, 3, 4]
                // pageSizes: [5, 10, 20, 50]
            }
        };
    },
    mounted() {
        let loader = this.$loading.show({
            // Optional parameters
            container: this.fullPage ? null : this.$refs.formContainer,
            "is-full-page": true,
            loader: "dots",
            color: "red"
        });

        setTimeout(() => {
            axios
                .get("/api/aggregator/unverified")
                .then(response => {
                    if (response.data) {
                        let items = response.data;
                        console.log(items);
                        if (items.length > 0) {
                            items.forEach(el => {
                                this.params.data.push([
                                    el.FullName,
                                    el.AgregatorBusinessName,
                                    el.AgregatorShortCode,
                                    el.CreatedDate,
                                    el.TerritoryName,
                                    el.AgregatorID
                                ]);
                            });
                        } else {
                            this.message =
                                "No avaliable Aggregator to verify !";
                        }
                    } else {
                        this.message =
                            "An error occured while fetching Aggregators !";
                    }

                    loader.hide();
                })
                .catch(error => {
                    loader.hide();
                    Toast.fire({
                        icon: "error",
                        title: "An error occured while fetching Aggregators "
                    });
                });
        }, 2000);
    },
    methods: {
        doSomethingCool(e) {
            //   let loader = this.$loading.show({
            //     // Optional parameters
            //     container: this.fullPage ? null : this.$refs.formContainer,
            //     "is-full-page": true,
            //     loader: "dots",
            //     color: "red",
            //   });

            sessionStorage.setItem("agentID", JSON.stringify(e));

            console.log(sessionStorage.getItem("agentID"));

            this.$router.push({
                name: "view-single-aggregator-unverified"
            });

            // axios
            //     .post("/api/wakala/unverified", this.fields)
            //     .then(response => {
            //         //console.log(response.data);
            //         //loader.hide();
            //         //this.$root.$emit("agent", response.data);

            //         sessionStorage.setItem(
            //             "agent",
            //             JSON.stringify(response.data)
            //         );

            //         console.log(sessionStorage.getItem("agent"));

            //         this.$router.push({
            //             name: "view-wakala-single-unverified"
            //         });
            //     })
            //     .catch(error => {
            //         //loader.hide();
            //         console.log(error);

            //         if (error.response.status === 422) {
            //             this.errors = error.response.data.errors || {};
            //         } else if (error.response.status === 400) {
            //             this.message = error.response.data.message;
            //         }
            //     });
        }
    },
    components: { VueTableDynamic }
};
</script>
