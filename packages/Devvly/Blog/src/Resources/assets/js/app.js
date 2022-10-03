import VueSocialSharing from 'vue-social-sharing'
Vue.use(VueSocialSharing, {
    networks: {
        youtube: 'https://fakeblock.com/share?url=@url&title=@title',
        instagram: 'https://fakeblock.com/share?url=@url&title=@title'
    }
});
//Vue.component('Share', Share);
