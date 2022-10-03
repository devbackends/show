<template>
    <div>
        <div id="selectAddressPopup" tabindex="-1" role="dialog" class="modal fade modal--select-address" style="display: none;" aria-hidden="true">
            <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close shipping-modal">
                            <i aria-hidden="true" class="far fa-times"></i>
                        </button>
                        <div class="modal-head">
                            <h3>Select shipping address</h3>
                        </div>
                        <div class="row d-flex align-items-stretch">
                            <div :key="index" class="col-lg-6 holder_item checkout__form-customer-item" v-for="(address, index) in addresses">
                                <div :class="`card checkout__form-customer-card ${selectedAddress.id === address.id ? 'active' : ''}`">
                                    <div class="card-body">
                                        <div class="checkout__form-customer-card-radio">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="billing"
                                                                :value="address.id" :id="`billing-${address.id}`"
                                                                @change="setAddress(index)"
                                                                :checked="selectedAddress.id === address.id" class="custom-control-input">
                                                <label class="custom-control-label" :for="`billing-${address.id}`">
                                                <p class="card-title checkout__form-customer-card-name">{{address.first_name}} {{address.last_name}}</p>
                                                <ul type="none" class="checkout__form-customer-card-address">
                                                    <li>{{ address.address1[0] }}</li>
                                                    <li>{{ address.city }}, {{ address.state }} {{ address.postcode }}</li>
                                                    <li>{{ address.country }}</li>
                                                    <li>Contact : {{ address.phone }}</li>
                                                </ul>

                                                </label>
                                            </div>
                                            <div>
                                                <span class="control-error"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 checkout__form-customer-item">
                                <div data-toggle="modal" data-target="#newAddressFormPopup2" data-dismiss="modal" aria-label="Close" class="card h-100 cursor-pointer">
                                    <div class="card-body add-address-button text-center d-flex align-items-center justify-content-center">
                                        <div>
                                            <i class="far fa-plus"></i>
                                            <p>Add a new address</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-primary" @click="emitResult" data-dismiss="modal">Select shipping address</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <address-form-popup el-id="newAddressFormPopup2" @change="onNewAddressAdd"></address-form-popup>
    </div>
</template>

<script>
import AddressFormPopup from "./AddressFormPopup";

export default {
    name: "AddressPopup",
    components: {AddressFormPopup},
    data: () => {
        return {
            addresses: [],
            selectedAddress: {id: null},
        }
    },
    mounted() {
        this.addresses = this.$store.getters.getCustomerAddresses;
    },
    methods: {
        emitResult() {
            this.$emit('done', this.selectedAddress);
        },
        setAddress(index) {
            this.selectedAddress = this.addresses[index];
        },
        onNewAddressAdd(addr) {
            this.addresses.push(addr);
            this.selectedAddress = addr;
            this.emitResult();
        },
    },
}
</script>

<style scoped>

</style>