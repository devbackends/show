<fluid-cards />

@push('scripts')
<script type="text/x-template" id="fluid-cards-template">
    <div class="fluid-cards">
        <p class="fluid-cards__title">Credit Cards</p>
        <div v-if="!loading && cards.length == 0">
            <p>You haven't added any credit card yet.</p>
        </div>
        <div class="row fluid-cards__list" v-if="cards.length > 0">
            <div class="col-12 col-md-6" v-for="(card, index) in cards">
                <div class="fluid-cards__list-item">
                    <div class="row align-items-center">
                        <div class="col-auto"><i class="far fa-credit-card-front fluid-cards__list-item-icon"></i></div>
                        <div class="col fluid-cards__list-info">
                            <p class="fluid-cards__list-info-title">@{{getCardFullName(card)}}</p>
                            <p>@{{card.expiration_date}}</p>
                        </div>
                        <div class="col-auto"><a data-toggle="modal"
                               data-target="#editFluidCardPopup"
                               id="add-fluid-credit-card"
                               class="fluid-cards__list-item-delete">
                                <i class="far fa-pencil-alt"></i>
                            </a><a @click="removeCard(card.id)" class="fluid-cards__list-item-delete"><i class="far fa-trash-alt"></i></a></div>
                    </div>
                </div>
                <edit-card :card="card" @update="editCard"/>
            </div>
        </div>
    </div>
</script>
<script type="text/x-template" id="edit-card-template">
    <div
        class="modal fade credit-card-modal"
        id="editFluidCardPopup"
        tabindex="-1"
        role="dialog"
        aria-labelledby="credit-card"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit your card</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="closeModal" id="closeEditCard">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group form-field">
                        <label class="mandatory" for="nickname">Nick name</label>
                        <input
                            placeholder="Nickname"
                            class="form-control"
                            type="text"
                            id="nickname"
                            name="nickname"
                            v-validate="'required'"
                            v-model="nickname"
                            data-vv-as="&quot;Nickname&quot;"
                        />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" ref="closeModal" aria-label="Close" data-dismiss="modal" class="btn btn-primary" @click="update">Update</button>
                </div>
            </div>
        </div>
    </div>
</script>
<script>
    (() => {
        Vue.component('fluid-cards', {
            template: '#fluid-cards-template',
            data: () => ({
                loading: true,
                cards: [],
            }),
            mounted() {
                this.setCards();
            },
            methods: {
                async setCards() {
                    const response = await this.$http.get('{{route('fluid.api.cards.get', $customer->id)}}');
                    if (response.data.status) {
                        this.cards = response.data.data.cards;
                        this.loading = false;
                    }
                },
                async editCard(data) {
                    let card = {};
                    let cards = [...this.cards]
                    for (let index in cards) {
                        if (cards[index].id === data.id) {
                            cards[index].nickname = data.nickname;
                            card = cards[index];
                            break;
                        }
                    }
                    this.cards = cards;
                    if (card.id) {
                        this.$http.post('{{route('fluid.api.cards.update', '777')}}'.replace(
                            '777', card.id
                        ), {
                            nickname: card.nickname
                        })
                    }
                },
                async removeCard(id) {
                    if (confirm('Are you sure you want to delete this card?')) {
                        const response = await this.$http.delete('{{route('fluid.api.cards.delete', '777')}}'.replace(
                            '777', id
                        ))
                        if (response.data.status) {
                            await this.setCards();
                        }
                    }
                },
                getCardFullName(card) {
                    if (card.nickname === '') return card.last_four;
                    if (card.last_four === '') return card.nickname;
                    return `${card.nickname} - ${card.last_four}`;
                }
            },
        });
        Vue.component('edit-card', {
            template: '#edit-card-template',
            props: ['card'],
            data: function () {
                return {
                    nickname: this.card.nickname,
                }
            },
            methods: {
                update() {
                    this.$emit('update', {
                        id: this.card.id,
                        nickname: this.nickname,
                    });
                }
            },
        });
    })()
</script>
@endpush