@extends('marketplace::shop.layouts.account')

@section('page_title')
    Pay Cashsale
@endsection

@section('content')

    <div class="account-layout">

        <div class="account-head">
            <h1 class="h3"><a href="/marketplace/account/sales/orders">Pay Cash sale</a>

            </h1>

        </div>
        <div class="account-table-content">
            <form method="post" action="{{ route('marketplace.account.orders.pay-cashsale.store', $order_id) }}"
                  @submit.prevent="onSubmit" enctype="multipart/form-data">

                <div class="row">
                    <div class="col-12">


                        <div class="form-group" :class="[errors.has('cashsale_transaction_number') ? 'has-error' : '']">
                            <label
                                for="note">Check/Transaction #</label>
                            @csrf
                            <input  type="text" class="form-control"  id="cashsale_transaction_number" name="cashsale_transaction_number"  v-validate="'required'" data-vv-as=" Check/Transaction # ;&quot;"/>

                            <span class="control-error" v-if="errors.has('cashsale_transaction_number')">@{{ errors.first('cashsale_transaction_number') }}</span>
                        </div>

                        <div class="form-group" :class="[errors.has('note') ? 'has-error' : '']">
                            <label
                                for="note">Note</label>

                            <textarea  class="form-control" name="note"  data-vv-as="&quot; Note &quot;">
                    </textarea>
                            <span class="control-error" v-if="errors.has('note')">@{{ errors.first('note') }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Receive Payment</button>
                    </div>
                </div>


            </form>
        </div>


    </div>

@endsection

