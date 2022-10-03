<template>
  <div class="course-item card p-3 mb-3">
    <div class="row">
      <div class="col-auto">
        <img :src="image" alt="" />
      </div>
      <div class="col">
        <p class="font-weight-bold">{{ course.name }}</p>
        <p v-if="next_date" class="course-item__date">Next date: {{next_date}}</p>
        <a :href="`/product/`+course.url_key" class="btn btn-link p-0">See more dates</a>
      </div>
      <div class="col-auto">
        <p class="font-weight-bold text-info-dark mb-2">${{  parseFloat(course.price).toFixed(2) }}</p>
        <a :href="`/product/`+course.url_key" class="btn btn-outline-primary"><i class="far fa-calendar-check mr-2"></i>Register</a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["course"],

  data: () => ({
      image:null,
      next_date:null
  }),

  mounted() {
      this.getBaseImage();
      this.getNextDate();
  },
  methods:{
      getBaseImage: function(){
          this.$http.get(`${this.baseUrl}/getProductBaseImage/${this.course.product_id}`)
              .then(response => {
                  if(response.data.small_image_url!='undefined'){
                      this.image=response.data.small_image_url;
                  }
              })
              .catch(error => {
                  console.log('something went wrong');
              })
      },
      getNextDate:function(){
          var current_date=new Date();
          if(this.course.available_from && this.course.available_to){
              var available_from=new Date(this.course.available_from);
              var available_to=new Date(this.course.available_to);
              const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                  "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
              ];
              if(current_date > available_from && current_date < available_to){
                  var nextDay = new Date(current_date);
                  nextDay.setDate(current_date.getDate() + 1);
                  this.next_date=monthNames[nextDay.getMonth()]+' '+ nextDay.getDate()+ 'th, ' +nextDay.getFullYear();
              }else if(current_date < available_from && current_date < available_to){
                  var nextDay = new Date(available_from);
                  nextDay.setDate(available_from.getDate());
                  this.next_date=monthNames[nextDay.getMonth()]+' '+ nextDay.getDate()+ 'th, ' +nextDay.getFullYear();
              }
          }
      }
  }
};
</script>

<style lang="scss" scoped>
.course-item {
  p {
    margin-bottom: 0;
  }
  img {
    height: 90px;
    width: 90px;
    object-fit: cover;
    border-radius: 4px;
  }
  &__date {}
}
</style>