<template>
    <div class="content-wrapper">
        <h4 class="content-heading">
            <div>
                M-pesa KYA Portal
                <p class="lead mt-2">User Management</p>
            </div>
        </h4>
        <div class="col-12 vld-parent" ref="formContainer">
            <div class="clearfix"></div>
            <div
                v-show="message"
                class="alert alert-warning alert-block mt-1 text-center"
            >
                <span class="h3">{{ message }}</span>
            </div>
            <!-- START cards box-->
            <!-- END cards box-->
            <div class="row">
                <!-- START dashboard main content-->
                <div class="col-xl-12">
                    <blockquote id="flash-message"></blockquote>
                    <div class="card b">
                        <div class="card-body">
                            <form
                                method="post"
                                @submit.prevent="blockUserFn"
                                class="mb-3"
                                v-if="blockUser"
                            >
                                <div class="row">
                                    <div class="col-xs-8 col-md-8">
                                        <div class="form-group">
                                            <label for="blockReason"
                                                >Choose reason for blocking this
                                                staff</label
                                            >
                                            <select
                                                class="custom-select"
                                                id="blockReason"
                                                name="blockReason"
                                                @change="
                                                    onBlockReasonChange($event)
                                                "
                                            >
                                                <option value="">
                                                    ---------------- Choose
                                                    reason -----------
                                                </option>
                                                <option
                                                    value="Involved in Fraudulent activities"
                                                    >Involved in Fraudulent
                                                    activities</option
                                                >
                                                <option
                                                    value="Not active agent staff"
                                                    >Not active agent
                                                    staff</option
                                                >
                                                <option
                                                    value="Wrongly onboarded"
                                                    >Wrongly onboarded</option
                                                >
                                                <option
                                                    value="Termination of contract/employment"
                                                    >Termination of
                                                    contract/employment</option
                                                >
                                                <option
                                                    value="Others –(Specify)"
                                                    >Others – (Specify)</option
                                                >
                                            </select>
                                            <div
                                                v-if="
                                                    errors && errors.blockReason
                                                "
                                                class="text-danger"
                                            >
                                                {{ errors.blockReason[0] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-xs-8 col-md-8">
                                        <label for="form7">Reference</label>
                                        <textarea
                                            name="blockReference"
                                            class="md-textarea form-control"
                                            rows="3"
                                            placeholder="Describe the refence for blocking this user e.g letter, email !"
                                            v-model="fields.blockReference"
                                        ></textarea>
                                        <div
                                            v-if="
                                                errors && errors.blockReference
                                            "
                                            class="text-danger"
                                        >
                                            {{ errors.blockReference[0] }}
                                        </div>
                                    </div>
                                </div>

                                <!--Material textarea-->
                                <div
                                    class="form-row"
                                    id="blockReasonText"
                                    v-if="showOtherReason"
                                >
                                    <div class="col-xs-8 col-md-8">
                                        <label for="form7">Reason</label>
                                        <textarea
                                            name="block-reason-text"
                                            class="md-textarea form-control"
                                            rows="3"
                                            v-model="fields.otherReason"
                                        ></textarea>
                                        <div
                                            v-if="errors && errors.otherReason"
                                            class="text-danger"
                                        >
                                            {{ errors.otherReason[0] }}
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button
                                        id="blockUserBtn"
                                        type="submit"
                                        class="btn btn-danger"
                                    >
                                        Block User
                                    </button>
                                </div>
                            </form>

                            <form
                                method="post"
                                @submit.prevent="unblockUserFn"
                                class="mb-3"
                                v-if="unblockUser"
                            >
                                <div class="row">
                                    <div class="col-xs-8 col-md-8">
                                        <div class="form-group">
                                            <label for="unblockReason"
                                                >Choose reason for unblocking
                                                this user</label
                                            >
                                            <select
                                                class="custom-select"
                                                id="unblockReason"
                                                name="unblockReason"
                                                @change="
                                                    onUnBlockReasonChange(
                                                        $event
                                                    )
                                                "
                                            >
                                                <option value="">
                                                    ---------------- Choose
                                                    reason -----------
                                                </option>
                                                <option
                                                    value="Request for reactivation of service"
                                                    >Request for reactivation of
                                                    service</option
                                                >
                                                <option value="Contract renewal"
                                                    >Contract renewal</option
                                                >
                                            </select>
                                            <div
                                                v-if="
                                                    errors &&
                                                        errors.unblockReason
                                                "
                                                class="text-danger"
                                            >
                                                {{ errors.unblockReason[0] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-xs-8 col-md-8">
                                        <label for="form7">Reference</label>
                                        <textarea
                                            name="unblockReference"
                                            class="md-textarea form-control"
                                            rows="3"
                                            placeholder="Describe the refence for unblocking this user e.g letter, email"
                                            v-model="fields.unblockReference"
                                        ></textarea>
                                        <div
                                            v-if="
                                                errors &&
                                                    errors.unblockReference
                                            "
                                            class="text-danger"
                                        >
                                            {{ errors.unblockReference[0] }}
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button
                                        id="unblockUserBtn"
                                        type="submit"
                                        class="btn btn-primary"
                                    >
                                        Unblock User
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- END dashboard main content-->
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            fields: {},
            errors: {},
            success: false,
            loaded: true,
            loading: true,
            message: null,
            blockUser: false,
            unblockUser: false,
            showOtherReason: false
        };
    },
    created() {
        let userStatus = sessionStorage.getItem("status");

        if (userStatus == "Y" || userStatus == "y") {
            this.blockUser = true;
            this.unblockUser = false;
        } else if (userStatus == "N" || userStatus == "n") {
            this.blockUser = false;
            this.unblockUser = true;
        }
    },
    methods: {
        blockUserFn() {
            this.errors = {};
            this.message = null;
            this.showDetails = false;
            this.showBlocked = false;

            let loader = this.$loading.show({
                // Optional parameters
                container: this.fullPage ? null : this.$refs.formContainer,
                "is-full-page": false,
                loader: "dots",
                color: "red"
            });

            this.fields.actionID = 0;

            axios
                .post("/api/user/manage", this.fields)
                .then(response => {
                    loader.hide();

                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: "User Blocking successful !"
                    }).then(() => {
                        sessionStorage.clear();
                        window.location = "/home";
                    });
                })
                .catch(error => {
                    this.loaded = true;
                    loader.hide();

                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    } else {
                        this.message = error.response.data.message;
                    }
                });
        },

        unblockUserFn() {
            this.errors = {};
            this.message = null;
            this.showDetails = false;
            this.showBlocked = false;

            let loader = this.$loading.show({
                // Optional parameters
                container: this.fullPage ? null : this.$refs.formContainer,
                "is-full-page": false,
                loader: "dots",
                color: "red"
            });

            this.fields.actionID = 1;

            axios
                .post("/api/user/manage", this.fields)
                .then(response => {
                    loader.hide();

                    console.log(response.data);

                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: "User UnBlocking successful !"
                    }).then(() => {
                        sessionStorage.clear();
                        window.location = "/home";
                    });
                })
                .catch(error => {
                    this.loaded = true;
                    loader.hide();

                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    } else {
                        this.message = error.response.data.message;
                    }
                });
        },

        onBlockReasonChange(e) {
            this.fields.blockReason = e.target.value;

            if (this.fields.blockReason == "Others –(Specify)") {
                this.showOtherReason = true;
            } else {
                this.showOtherReason = false;
            }
        },
        onUnBlockReasonChange(e) {
            this.fields.unblockReason = e.target.value;
        }
    }
};
</script>
