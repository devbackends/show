!function(e){var t={};function s(n){if(t[n])return t[n].exports;var o=t[n]={i:n,l:!1,exports:{}};return e[n].call(o.exports,o,o.exports,s),o.l=!0,o.exports}s.m=e,s.c=t,s.d=function(e,t,n){s.o(e,t)||Object.defineProperty(e,t,{configurable:!1,enumerable:!0,get:n})},s.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return s.d(t,"a",t),t},s.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},s.p="/",s(s.s=2)}({2:function(e,t,s){e.exports=s("oOI4")},oOI4:function(e,t){var s;(s=jQuery)(".js-toggle-search").on("click",function(e){e.preventDefault();var t=s(this);t.toggleClass("active"),s(".searchbar").toggle(),s(document).on("click",function(e){0==s(e.target).closest(".vc-small-screen").length&&(s(".searchbar").hide(),t.removeClass("active"))})}),s(".js-toggle-nav").on("click",function(e){e.preventDefault();var t=s(this);t.toggleClass("active"),s(".nav-container").toggleClass("active"),s(document).on("click",function(e){0==s(e.target).closest(".nav-container .wrapper").length&&0==s(e.target).closest(".js-toggle-nav").length&&(s(".nav-container").removeClass("active"),t.removeClass("active"))})}),s(".js-close-nav").on("click",function(e){e.preventDefault(),s(".js-toggle-nav").toggleClass("active"),s(".nav-container").toggleClass("active")}),s(".js-toggle-user-nav").on("click",function(e){e.preventDefault(),s(this),s(".js-user-nav").toggleClass("active"),s(document).on("click",function(e){0==s(e.target).closest(".customer-sidebar").length&&0==s(e.target).closest(".js-toggle-user-nav").length&&s(".js-user-nav").removeClass("active")})}),s(document).ready(function(){s("#carouselNewProducts").on("slid.bs.carousel","",function(){var e=s(this);console.log("TEST!!!!!"),e.find(".carousel-control").show(),s(".carousel-inner .carousel-item:first").hasClass("active")?(e.find(".carousel-control-prev").hide(),console.log("first slide")):s(".carousel-inner .carousel-item:nth-last-child(4)").hasClass("active")&&(e.find(".carousel-control-next").hide(),console.log("last slide"))}),document.addEventListener("scroll",function(e){scrollPosition=Math.round(window.scrollY);var t=document.querySelector(".settings-page__header"),s=document.querySelector(".settings-page");scrollPosition>66?(t&&t.classList.add("settings-page__header--fixed"),s&&s.classList.add("settings-page--fixed")):(t&&t.classList.remove("settings-page__header--fixed"),s&&s.classList.remove("settings-page--fixed"))})})}});