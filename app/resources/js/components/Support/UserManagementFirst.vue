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
                                @submit.prevent="submit"
                                class="mb-3"
                            >
                                <div class="form-group">
                                    <div class="input-group with-focus mb-2">
                                        <input
                                            name="username"
                                            class="form-control"
                                            id="exampleInputEmail1"
                                            type="text"
                                            placeholder="Enter Username e.g 255754000000"
                                            autocomplete="off"
                                            v-model="fields.username"
                                        />
                                        <div class="input-group-append">
                                            <button
                                                class="btn btn-block btn-info"
                                                type="submit"
                                            >
                                                <span
                                                    class="fa fa-search-plus"
                                                ></span>
                                                Search Username
                                            </button>
                                        </div>
                                    </div>
                                    <div
                                        v-if="errors && errors.username"
                                        class="text-danger"
                                    >
                                        {{ errors.username[0] }}
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- END dashboard main content-->
            </div>

            <div class="row" id="user-details" v-if="showDetails">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row myLead">
                                <div class="col-6">
                                    <div class="row mb-2">
                                        <div class="col-6 text-monospace">
                                            USERNAME :
                                        </div>
                                        <div class="col-6">
                                            {{ user.Username }}
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6 text-monospace">
                                            FULL NAME :
                                        </div>
                                        <div class="col-6">
                                            {{ user.FullName }}
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6 text-monospace">
                                            ROLE:
                                        </div>
                                        <div class="col-6">{{ user.Role }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6 text-monospace">
                                            ACCOUNT STATUS :
                                        </div>
                                        <div class="col-6">
                                            {{ user.ActiveYN }}
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6 text-monospace">
                                            MSISDN :
                                        </div>
                                        <div class="col-6">
                                            {{ user.Msisdn }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="showDetails">
                <a
                    v-if="showBlockBtn"
                    v-on:click="goToBlock()"
                    class="btn btn-lg btn-danger"
                >
                    Proceed to block</a
                >
                <a
                    v-if="showUnblockBtn"
                    v-on:click="goToBlock()"
                    class="btn btn-lg btn-info"
                >
                    Proceed to unblock</a
                >
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
            selectedFinger: "Select Finger",
            FingerImage: null,
            showDetails: false,
            showBlockBtn: false,
            showUnblockBtn: false,
            user: {}
        };
    },
    methods: {
        submit() {
            this.errors = {};
            this.message = null;
            this.showDetails = false;
            this.showBlockBtn = false;
            this.showUnblockBtn = false;

            let loader = this.$loading.show({
                // Optional parameters
                container: this.fullPage ? null : this.$refs.formContainer,
                "is-full-page": false,
                loader: "dots",
                color: "red"
            });

            axios
                .post("/api/user/details", this.fields)
                .then(response => {
                    loader.hide();

                    this.showDetails = true;

                    this.user = response.data.getUser;

                    console.log(this.user.ActiveYN);

                    sessionStorage.setItem("status", this.user.ActiveYN);

                    if (
                        this.user.ActiveYN == "Y" ||
                        this.user.ActiveYN == "y"
                    ) {
                        this.showBlockBtn = true;
                        this.showUnblockBtn = false;
                    } else if (
                        this.user.ActiveYN == "N" ||
                        this.user.ActiveYN == "n"
                    ) {
                        this.showUnblockBtn = true;
                        this.showBlockBtn = false;
                    }

                    // console.log(response.data);

                    //this.$router.push({ name: "onboard-aggregator-save" });
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
        goToBlock() {
            this.$router.push({ name: "user-management-second" });
        }
    }
};
</script>

<style></style>
