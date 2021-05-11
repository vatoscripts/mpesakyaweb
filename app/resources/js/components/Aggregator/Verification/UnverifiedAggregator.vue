<template>
    <div class="content-wrapper">
        <h4 class="content-heading">
            <div>
                M-pesa KYA Portal
                <p class="lead mt-2">Wakala Details</p>
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
            <div class="row">
                <div class="col-lg-4">
                    <div class="card card-default">
                        <div class="card-body text-center">
                            <div class="py-4">
                                <img
                                    class="img-fluid rounded-circle img-thumbnail"
                                    :src="
                                        'data:image/png;base64,' +
                                            aggregator.Photo
                                    "
                                    alt="Contact"
                                />
                            </div>
                            <h3 class="m-0 text-bold">
                                {{ aggregator.FullName }}
                            </h3>
                            <div class="my-3">
                                <p>Created : {{ aggregator.CreatedDate }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="card card-default d-none d-lg-block">
                        <div class="card-header">
                            <div class="text-bold card-title text-center">
                                Attachments
                            </div>
                        </div>
                        <div class="card-body">
                            <a
                                class="mb-2 text-info d-flex align-items-center link-unstyled"
                                :href="
                                    'data:application/jpg;base64,' +
                                        aggregator.AgregatorBusinessLicenseDoc
                                "
                                :download="
                                    aggregator.AgregatorBusinessName +
                                        '-BusinessLicenceDoc.jpg'
                                "
                                ><span
                                    ><em
                                        class="far fa-file fa-fw mr-2 text-inverse"
                                    ></em
                                    ><span>Business Licence File</span></span
                                ><em
                                    class="ml-auto fa fa-download fa-fw text-info"
                                ></em></a
                            ><a
                                class="mb-2 text-info d-flex align-items-center link-unstyled"
                                :href="
                                    'data:application/jpg;base64,' +
                                        aggregator.AgregatorTinDoc
                                "
                                :download="
                                    aggregator.AgregatorBusinessName +
                                        '-TINLicence.jpg'
                                "
                                ><span
                                    ><em
                                        class="far fa-file fa-fw mr-2 text-inverse"
                                    ></em
                                    ><span>TIN Licence File</span></span
                                ><em
                                    class="ml-auto fa fa-download fa-fw text-info"
                                ></em
                            ></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card card-default">
                        <div class="card-header d-flex align-items-center">
                            <div class="d-flex justify-content-center col">
                                <div class="h4 m-0 text-center">
                                    Contact Information
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row py-4 justify-content-center">
                                <div class="col-12 col-sm-10">
                                    <form
                                        class="form-horizontal"
                                        ref="aggregatorDetailsform"
                                    >
                                        <div class="form-group row">
                                            <label
                                                class="text-bold col-4 col-form-label text-right"
                                                for="inputContact1"
                                                >Business Name</label
                                            >
                                            <div class="col-8">
                                                <input
                                                    class="form-control"
                                                    name="businessName"
                                                    type="text"
                                                    :placeholder="[
                                                        [
                                                            aggregator.AgregatorBusinessName
                                                        ]
                                                    ]"
                                                    v-model="
                                                        aggregator.AgregatorBusinessName
                                                    "
                                                />
                                                <div
                                                    v-if="
                                                        errors &&
                                                            errors.businessName
                                                    "
                                                    class="text-danger"
                                                >
                                                    {{ errors.businessName[0] }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label
                                                class="text-bold col-4 col-form-label text-right"
                                                for="inputContact3"
                                                >Short Code</label
                                            >
                                            <div class="col-8">
                                                <input
                                                    class="form-control"
                                                    name="shortCode"
                                                    type="text"
                                                    :placeholder="[
                                                        [
                                                            aggregator.AgregatorShortCode
                                                        ]
                                                    ]"
                                                    v-model="
                                                        aggregator.AgregatorShortCode
                                                    "
                                                />
                                                <div
                                                    v-if="
                                                        errors &&
                                                            errors.shortCode
                                                    "
                                                    class="text-danger"
                                                >
                                                    {{ errors.shortCode[0] }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="text-bold col-4 col-form-label text-right"
                                                for="inputContact4"
                                                >Business Licence Number</label
                                            >
                                            <div class="col-8">
                                                <input
                                                    class="form-control"
                                                    name="businessLicenceNo"
                                                    type="text"
                                                    :placeholder="[
                                                        [
                                                            aggregator.AgregatorBusinessLicense
                                                        ]
                                                    ]"
                                                    v-model="
                                                        aggregator.AgregatorBusinessLicense
                                                    "
                                                />
                                                <div
                                                    v-if="
                                                        errors &&
                                                            errors.businessLicenceNo
                                                    "
                                                    class="text-danger"
                                                >
                                                    {{
                                                        errors
                                                            .businessLicenceNo[0]
                                                    }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="text-bold col-4 col-form-label text-right"
                                                for="inputContact5"
                                                >TIN Number</label
                                            >
                                            <div class="col-8">
                                                <input
                                                    class="form-control"
                                                    name="tinNo"
                                                    type="text"
                                                    :placeholder="[
                                                        [
                                                            aggregator.AgregatorTin
                                                        ]
                                                    ]"
                                                    v-model="
                                                        aggregator.AgregatorTin
                                                    "
                                                />
                                                <div
                                                    v-if="
                                                        errors && errors.tinNo
                                                    "
                                                    class="text-danger"
                                                >
                                                    {{ errors.tinNo[0] }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="text-bold col-4 col-form-label text-right"
                                                for="inputContact6"
                                                >Adress</label
                                            >
                                            <div class="col-8">
                                                <input
                                                    class="form-control"
                                                    name="adress"
                                                    type="text"
                                                    :placeholder="[
                                                        [aggregator.Address]
                                                    ]"
                                                    v-model="aggregator.Address"
                                                />
                                                <div
                                                    v-if="
                                                        errors && errors.adress
                                                    "
                                                    class="text-danger"
                                                >
                                                    {{ errors.adress[0] }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label
                                                class="text-bold col-4 col-form-label text-right"
                                                for="inputContact8"
                                                >Phone Number
                                            </label>
                                            <div class="col-8">
                                                <input
                                                    class="form-control"
                                                    name="phoneNumber"
                                                    type="text"
                                                    :placeholder="[
                                                        [aggregator.Phonenumber]
                                                    ]"
                                                    v-model="
                                                        aggregator.Phonenumber
                                                    "
                                                />
                                                <div
                                                    v-if="
                                                        errors &&
                                                            errors.phoneNumber
                                                    "
                                                    class="text-danger"
                                                >
                                                    {{ errors.phoneNumber[0] }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="text-right">
                                                    <a
                                                        class="btn btn-lg btn-success text-white"
                                                        @click="
                                                            acceptAggregator
                                                        "
                                                    >
                                                        <i
                                                            class="far fa-check-circle"
                                                        ></i>
                                                        Accept
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            agentID: null,
            brela: false,
            wakalas: null,
            aggregator: {},
            errors: {},
            fields: {},
            success: false,
            loaded: true,
            loading: true,
            message: null,
            reason: false,
            showBrela: false,
            showMemart: false
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
                .get(
                    "/api/aggregator/unverified/" +
                        sessionStorage.getItem("agentID")
                )
                .then(response => {
                    console.log(response.data);
                    if (response.data) {
                        this.aggregator = response.data;
                    } else {
                        this.message =
                            "An error occured while fetching Aggregators !";
                    }

                    loader.hide();
                })
                .catch(error => {
                    loader.hide();
                    this.message =
                        "An error occured while fetching Aggregators !";
                    // Toast.fire({
                    //     icon: "error",
                    //     title: "An error occured while fetching Aggregators "
                    // });
                });
        }, 2000);
    },
    methods: {
        declineWakala() {
            if (this.loaded) {
                this.loaded = false;
                this.success = false;
                this.errors = {};
                this.message = null;

                let loader = this.$loading.show({
                    // Optional parameters
                    container: this.fullPage ? null : this.$refs.formContainer,
                    "is-full-page": true,
                    loader: "dots",
                    color: "red"
                });

                this.fields.MpesaAgentID = this.wakala.MpesaAgentID;
                this.fields.VerificationStatus = 3;

                axios
                    .post("/api/wakala/verify", this.fields)
                    .then(response => {
                        loader.hide();
                        this.fields = {}; //Clear input fields.
                        this.loaded = true;
                        this.success = true;

                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: response.data.message
                        }).then(() => {
                            sessionStorage.clear();
                            window.location = "/home";
                        });
                    })
                    .catch(error => {
                        this.loaded = true;
                        loader.hide();
                        console.log(error);
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors || {};
                        } else {
                            this.message = error.response.data.message;
                        }
                    });
            }
        },
        acceptAggregator() {
            if (this.loaded) {
                this.loaded = false;
                this.success = false;
                this.errors = {};
                this.message = null;

                let loader = this.$loading.show({
                    // Optional parameters
                    container: this.fullPage ? null : this.$refs.formContainer,
                    "is-full-page": true,
                    loader: "dots",
                    color: "red"
                });

                this.fields.MpesaAgentID = this.aggregator.AgregatorID;
                this.fields.VerificationStatus = 1;

                //console.log(this.$refs.aggregatorDetailsform);

                this.fields.businessName = this.$refs.aggregatorDetailsform.businessName.value;
                this.fields.shortCode = this.$refs.aggregatorDetailsform.shortCode.value;
                this.fields.businessLicenceNo = this.$refs.aggregatorDetailsform.businessLicenceNo.value;

                this.fields.tinNo = this.$refs.aggregatorDetailsform.tinNo.value;
                this.fields.adress = this.$refs.aggregatorDetailsform.adress.value;
                this.fields.phoneNumber = this.$refs.aggregatorDetailsform.phoneNumber.value;

                axios
                    .post("/api/aggregator/verify", this.fields)
                    .then(response => {
                        console.log(response.data);
                        loader.hide();
                        this.fields = {}; //Clear input fields.
                        this.loaded = true;
                        this.success = true;

                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: response.data.message
                        }).then(() => {
                            sessionStorage.clear();
                            window.location = "/home";
                        });
                    })
                    .catch(error => {
                        this.loaded = true;
                        loader.hide();
                        console.log(error);
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors || {};
                        } else {
                            this.message = error.response.data.message;
                        }
                    });
            }
        }
    },
    beforeDestroy() {
        console.log(
            `At this point, watchers, child components, and event listeners have not been teared down yet.`
        );

        sessionStorage.removeItem("agent");
    }
};
</script>
