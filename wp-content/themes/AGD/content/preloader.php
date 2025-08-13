<style type="text/css">
/*Deafult*/
#preloaderwrap{
	position: fixed;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background-color: #c0dedc;
	z-index: 99999;
	height: 100%;
}
.preloader {
   position:absolute;
   top:50%;
   left:50%;
   transform:translate(-50%,-50%);
   -webkit-transform:translate(-50%,-50%);
}
/*Deafult*/

.preloader3 {
   width:80px;
   height:80px;
   display:inline-block;
   padding:0px;
   border-radius:100%;
   border:3px solid;
   border-top-color:rgba(0,0,0, 0.65);
   border-bottom-color:rgba(0,0,0, 0.65);
   border-left-color:rgba(0,0,0, 0.15);
   border-right-color:rgba(0,0,0, 0.15);
   -webkit-animation: preloader3 0.8s ease-in-out infinite alternate;
   animation: preloader3 0.8s ease-in-out infinite alternate;
}
@keyframes preloader3 {
   from {transform: rotate(0deg);}
   to {transform: rotate(360deg);}
}
@-webkit-keyframes preloader3 {
   from {-webkit-transform: rotate(0deg);}
   to {-webkit-transform: rotate(360deg);}
}

</style>
<script>
	jQuery(window).load(function() {
	    jQuery(".preloader").delay(500).fadeOut('slow');
	    jQuery("#preloaderwrap").delay(500).fadeOut();		
	});
</script>
<div id="preloaderwrap">
	<div class="preloader">
        <div class="preloader3"></div>
    </div>
</div>
