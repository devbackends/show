@php $selected_categories=app('Webkul\Product\Repositories\ProductRepository')->sortCategories($product->categories); @endphp

@if ($categories->count())
    @php array_push($GLOBALS['groups'],['id'=>'categories_box','title'=>'Categories']); @endphp
    <div class="create-product__box" id="categories_box">
        <span id="categories_box" class="create-product__box-spy-anchor"></span>
        <p class="create-product__box-title form-label-required">Categories</p>
        {!! view_render_event('marketplace.sellers.account.catalog.product.edit_form_accordian.categories.controls.before', ['product' => $product]) !!}
        {{--<tree-view behavior="normal" value-field="id" name-field="categories" input-type="checkbox" items='@json($categories)' value='@json($product->categories->pluck("id"))'></tree-view>--}}
        <categories></categories>
        {!! view_render_event('marketplace.sellers.account.catalog.product.edit_form_accordian.categories.controls.after', ['product' => $product]) !!}
    </div>
  @push('scripts')
      <script type="text/x-template" id="product-categories">
         <div>
             <div class="row " v-for="(catobj,index) in categories" :key="index">
                 <input type="hidden" id="formated_categories" name="formated_categories" :value="getSelectedCategories" />
                 <div class="col-12 col-lg-4">
                            <div class="form-group" :class="[errors.has(`categories_${index}`) ? 'has-error' : '']">
                                <label for="productMainCategory1" class="form-label-required">Category</label>
                                <Select2 v-validate="'required'"   :name="`categories_${index}`" :id="`categories_${index}`"  data-vv-as="'&quot;Category&quot;'" v-model="categories[index].category"  :options="available_categories" >
                                </Select2>
                                <span class="control-error" v-if="errors.has(`categories_${index}`)">@{{ errors.first(`categories_${index}`) }}</span>
                            </div>
                 </div>
                              <div class="col-12 col-lg-4" v-if="categories[index].category && getSubcategories(categories[index].category).length > 0 ">
                                   <div class="form-group"  :class="[errors.has('subcategories_' + index) ? 'has-error' : '']"  >
                                       <label for="productMainCategory1" class="form-label-required"><span style="visibility: hidden;">S</span></label>
                                       <Select2 :id="`subcategories_${index}`" :name="`subcategories_${index}`"  :data-vv-as="'&quot;Category&quot;'"  v-validate="'required'" v-model="categories[index].subcategory" :options="getSubcategories(categories[index].category)" ></Select2>
                                       <span class="control-error" v-if="errors.has('subcategories_' + index)">@{{ errors.first('subcategories_' + index) }}</span>
                                   </div>
                               </div>
                            <div class="col-12 col-lg-4" v-if="categories[index].subcategory && getSubsubcategories(categories[index].category,categories[index].subcategory).length > 0" >
                                <div class="form-group"   :class="[errors.has('subsubcategories_' + index) ? 'has-error' : '']"  >
                                    <label for="productMainCategory1" class="form-label-required"></label>
                                    <Select2 :id="`subsubcategories_${index}`" :name="`subsubcategories_${index}`"  :data-vv-as="'&quot;Category&quot;'"  v-validate="'required'" v-model="categories[index].subsubcategory" :options="getSubsubcategories(categories[index].category,categories[index].subcategory)" ></Select2>
                                    <span class="control-error" v-if="errors.has('subsubcategories_' + index)">@{{ errors.first('subcategories_' + index) }}</span>
                                </div>
                            </div>
             </div>
             <a v-on:click="addNewCategory()" href="javascript:;" class="create-product__add-button"><i class="far fa-plus"></i>Add category</a>
             <br>
             <a v-on:click="suggest_category=true" href="javascript:;" class="create-product__add-button"><i class="far fa-plus"></i>Suggest new category</a>
             <div v-if="suggest_category" class="col-12">
                 <div class="form-group textarea" :class="[errors.has(`suggested-category`) ? 'has-error' : '']">
                     <label for="productMainCategory1" class="form-label-required">Describe Suggested Category</label>
                     <div class="input-group">
                         <textarea style="height: 70px;min-height: unset;" id="suggested-category" name="suggested-category" v-model="suggested_category" class="form-control" ></textarea>
                     </div>
                     <span class="control-error" v-if="errors.has('suggested-category')">@{{ errors.first('suggested-category') }}</span>
                 </div>
                 <div class="form-group">
                     <button type="button" v-on:click="suggestNewCategory" id="" class="submit-button btn mb-3 btn-primary"><span class="">Suggest</span></button>
                 </div>
             </div>
         </div>

      </script>
      <script>
          Vue.component('categories', {

              data: () => ({
                  categories: @json($selected_categories),
                  available_categories:@json($categories[0]['children']),
                  suggest_category: false,
                  suggested_category: ''


              }),

              template: '#product-categories',
              mounted: function(){

              },
              created() {
                for(let i=0;i<this.available_categories.length;i++){
                    this.available_categories[i].text=this.available_categories[i].name;
                    this.available_categories[i].childs=this.available_categories[i].children;
                    delete this.available_categories[i].children;
                    for(let j=0;j<this.available_categories[i].childs.length;j++){
                        this.available_categories[i].childs[j].text=this.available_categories[i].childs[j].name;
                        this.available_categories[i].childs[j].childs=this.available_categories[i].childs[j].children;
                        delete this.available_categories[i].childs[j].children;
                        for(let k=0;k<this.available_categories[i].childs[j].childs.length;k++){
                            this.available_categories[i].childs[j].childs[k].text=this.available_categories[i].childs[j].childs[k].name;
                            delete this.available_categories[i].childs[j].childs[k].children;
                        }
                    }
                }
              },

              methods: {
                  addNewCategory: function(){
                      this.categories.push({category: null,
                          subcategory: null,
                          subsubcategory: null
                      });
                  },
                  resetSubcategories: function(index){
                      this.categories[index].subcategory=null;
                      this.categories[index].subsubcategory=null;
                  },
                  resetSubsubcategories: function(index){
                      this.categories[index].subsubcategory=null;
                  },
                  getSubcategories(category_id){
                      for(let i=0;i<this.available_categories.length;i++){
                          if(this.available_categories[i].id==category_id){
                           return    this.available_categories[i].childs;
                          }
                      }
                      return '';
                 },
                  getSubsubcategories(category_id,subcategory_id){
                      for(let i=0;i<this.available_categories.length;i++){
                          if(this.available_categories[i].id==category_id){
                              for(let j=0;j<this.available_categories[i].childs.length;j++){
                                  if(this.available_categories[i].childs[j].id==subcategory_id){
                                      return    this.available_categories[i].childs[j].childs;
                                  }
                              }
                          }
                      }
                      return '';
          },
                  suggestNewCategory(){
                      if(this.suggested_category) {
                          var this_this = this;
                          this_this.$http.post("{{ route('marketplace.product.suggest-by-seller') }}", {'suggestion':this_this.suggested_category,'type':'category'})
                              .then(function(response) {
                                 if(response.status==200){
                                     if(response.data.status=='success'){

                                     }else{

                                     }
                                 }else{

                                 }
                                  this_this.suggest_category=false;
                              })
                              .catch(function (error) {
                                  this_this.suggest_category=false;
                              })
                      }
                  }

              },
              computed: {
                  getSelectedCategories: function(){
                      const categoriesArray=[];
                      for(let i=0;i<this.categories.length;i++){
                          if(this.categories[i].category){
                              categoriesArray.push(this.categories[i].category);
                          }
                          if(this.categories[i].subcategory){
                              categoriesArray.push(this.categories[i].subcategory);
                          }
                          if(this.categories[i].subsubcategory){
                              categoriesArray.push(this.categories[i].subsubcategory);
                          }
                      }
                      return categoriesArray;
                  }
              }
          });

      </script>
  @endpush

@endif