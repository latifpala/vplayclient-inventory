<?php 
	ob_start();
if(isset($_GET['plan'])){
	$user_id = $_GET['u'];
	echo base64_decode($_GET['plan']);
	die;

}else{
	header("location: https://test.vplayclient.com/dashboard/");
	exit();
}
?>
<html><head>
<title>MightyGiftCards Find your perfect gift</title>
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all">
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script type="text/javascript" async="" defer="" src="//www.juicygiftcards.com/analytics/piwik.js"></script><script src="js/jquery-1.11.0.min.js"></script>
<!-- Custom Theme files -->
<link href="css/style.css" rel="stylesheet" type="text/css" media="all">
<!-- Custom Theme files -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="keywords" content="MightyGiftCards Find your perfect gift">
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!--Google Fonts-->
<link href="//fonts.googleapis.com/css?family=Hind:400,500,300,600,700" rel="stylesheet" type="text/css">
<link href="//fonts.googleapis.com/css?family=Oswald:400,700,300" rel="stylesheet" type="text/css">
<!-- start-smoth-scrolling -->
<script type="text/javascript" src="js/move-top.js"></script>
<script type="text/javascript" src="js/easing.js"></script>
	<script type="text/javascript">
			jQuery(document).ready(function($) {
				$(".scroll").click(function(event){		
					event.preventDefault();
					$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
				});
			});
	</script>
<!-- //end-smoth-scrolling -->
<!--flex slider-->
<script defer="" src="js/jquery.flexslider.js"></script>
<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen">

<script>
// Can also be used with $(document).ready()
$(window).load(function() {
  $('.flexslider').flexslider({
    animation: "slide",
    controlNav: "thumbnails"
  });
});
</script>
<!--flex slider-->
<script src="js/imagezoom.js"></script>
<script src="js/simpleCart.min.js"> </script>
<script src="js/bootstrap.min.js"></script>
</head>
<body style="">

<!-- Matomo -->
		<script type="text/javascript">
		  var _paq = _paq || [];
		  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
		  _paq.push(['trackPageView']);
		  _paq.push(['enableLinkTracking']);
		  (function() {
			var u="//www.juicygiftcards.com/analytics/";
			_paq.push(['setTrackerUrl', u+'piwik.php']);
			_paq.push(['setSiteId', '1']);
			var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
			g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
		  })();
		</script>
<!-- End Matomo Code -->

<!--header strat here-->
<div class="header">
	<div class="container">
		<div class="header-main">
			<div class="top-nav">
				<div class="content white">
	              <nav class="navbar navbar-default" role="navigation">
					    <div class="navbar-header">
					        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						        <span class="sr-only">Toggle navigation</span>
						        <span class="icon-bar"></span>
						        <span class="icon-bar"></span>
						        <span class="icon-bar"></span>
					        </button>
					        <div class="navbar-brand logo">
								<a href=""><img src="images/logo.png" alt=""></a>
							</div>
					    </div>
					    <!--/.navbar-header-->
					 <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					        <!-- <ul class="nav navbar-nav">
					        	   <li><a href="shop.php">Home</a></li>
						           <li><a href="shop.php">Shop Gift Cards</a></li>
								   <li><a href="contact.php">Contact</a></li>
					        </ul> -->
					    </div>
					    <!--/.navbar-collapse-->
					</nav>
					<!--/.navbar-->
				</div>
			</div>
			<div class="header-right">
			<img src="images/paypal-verified.png">
			</div>
		 <div class="clearfix"> </div>
		</div>
	</div>
</div>
<!--header end here-->

<!--single start here-->


<div class="single">
   <div class="container">
   	 <div class="single-main">
   	 	<div class="single-top-main">
	   		<div class="col-md-5 single-top">	
			   <div class="flexslider">
				  
			<div class="flex-viewport" style="overflow: hidden; position: relative;"><ul class="slides" style="width: 1000%; transition-duration: 0.6s; transform: translate3d(-716px, 0px, 0px);"><li data-thumb="images/si3.jpg" class="clone" aria-hidden="true" style="width: 358px; float: left; display: block;">
				       <div class="thumb-image"> <img src="images/s3.jpg" data-imagezoom="true" class="img-responsive" draggable="false"> </div>
				    </li>
				    <li data-thumb="images/si1.jpg" style="width: 358px; float: left; display: block;" class="">
				        <div class="thumb-image"> <img src="images/s1.jpg" data-imagezoom="true" class="img-responsive" draggable="false"> </div>
				    </li>
				    <li data-thumb="images/si2.jpg" class="flex-active-slide" style="width: 358px; float: left; display: block;">
				         <div class="thumb-image"> <img src="images/s2.jpg" data-imagezoom="true" class="img-responsive" draggable="false"> </div>
				    </li>

</ul></div>
<ul class="flex-direction-nav"><li class="flex-nav-prev"><a class="flex-prev" href="#">Previous</a></li><li class="flex-nav-next"><a class="flex-next" href="#">Next</a></li></ul></div>
			</div>
			<div class="col-md-7 single-top-left simpleCart_shelfItem">
				<h2>Gift Card</h2>
				<h1>Amazon</h1>
				<h3>Baic Membership Us Amazon Gift Card, Monthly Email Delivery</h3>
				<h6 class="item_price">$9.99</h6>			
				<p>Get a monthly $9 Email Delivery Amazon Gift Card. Amazon.com Gift Cards never expire and carry no fees. No returns and no refunds on gift cards. Redeemable towards millions of items store-wide at Amazon.com or certain affiliated websites.</p>
				
					<?php
						
						
						$return 		= 'http://www.mightygiftcards.com';
						$cancel_return 	= 'http://www.mightygiftcards.com';
						$notify_url 	= 'http://www.mightygiftcards.com/ipn.php';
						$paypal_email 	= 'pay.mightygiftcards@gmail.com';
						$order_id 		= isset($_GET['message']) ? $_GET['message'] : '';
						$email 			= isset($_GET['email']) ? $_GET['email'] : '';
						$amount 		= isset($_GET['amount']) ? $_GET['amount'] : '';
						$first_name 	= isset($_GET['first_name']) ? $_GET['first_name'] : '';
						$last_name 		= isset($_GET['last_name']) ? $_GET['last_name'] : '';
						$is_out_of_stock = true;
						$ref_test = md5('https://test.vplayclient.com');
                       
                       if (isset($_GET['ref'])){
                       		if($ref_test==$_GET['ref']){
								$is_out_of_stock = false;
							}
                       }

					?>
					<div class="bs-example" data-example-id="simple-horizontal-form" style="display: none;">
						<form class="form-horizontal">
						  <div class="form-group">
							<label for="inputEmail3" class="col-sm-4 control-label">Email</label>
							<div class="col-sm-8">
							  <input type="email" class="form-control" id="inputEmail3" placeholder="Email" value="<?php echo $email; ?>">
							</div>
						  </div>
						  <div class="form-group amount-nr">
							<label for="inputAmount3" class="col-sm-4 control-label">Amount (USD) $</label>
							<div class="col-sm-8">
							  <input type="number" min="0.00" max="10000.00" step="" class="form-control" id="inputAmount3" placeholder="Amount" value="<?php echo $amount; ?>">
							</div>
						  </div>
						  <div class="form-group">
							<label for="inputMessage3" class="col-sm-4 control-label">Message</label>
							<div class="col-sm-8">
							  <input type="text" class="form-control" id="inputMessage3" placeholder="Message" value="<?php echo $order_id; ?>">
							</div>
						  </div>
						</form>
				    </div>
					
					<div id="paymentForm" style="display:block; margin-top: 30px;">
					    <!--Paypal subscription code start-->
							    
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
		                    <input type="hidden" name="cmd" value="_xclick-subscriptions">
		                    <input type="hidden" name="business" value="pay.mightygiftcards@gmail.com">
		                    <input type="hidden" name="lc" value="US">
		                    <input type="hidden" name="item_name" value="Basic Amazon Gift Card Plan">
		                    <input type="hidden" name="item_number" value="#basic">
		                    <input type="hidden" name="no_note" value="1">
		                    <input type="hidden" name="return"value="https://mightygiftcards.com/process-payment.php" />
		                    <input type="hidden" name="cancel_return"value="https://mightygiftcards.com/cancel-payment.php" />
		                    <input type="hidden" name="notify_url" value="https://mightygiftcards.com/mighty-ipn.php">
		                    <input type="hidden" name="src" value="1">
		                    <input type="hidden" name="a3" value="0.01">
		                    <input type="hidden" name="p3" value="1">
		                    <input type="hidden" name="t3" value="M">
		                    <input type="hidden" name="currency_code" value="USD">
		                    <input type="hidden" name="bn" value="PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHostedGuest">
		                    <input type="hidden" name="custom" value="<?php echo ; ?>" />
								
								<?php if (!$is_out_of_stock) { ?>
								   <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
								<?php } ?>
								<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1" hidden="" style="display: none !important;">
						</form>	
						<!--Paypal subscription code end-->
					</div>
					<ul class="bann-btns">
						<?php 
						if ($is_out_of_stock) { ?>
						   <li id="OutOfStock" style="color:red; font-style: italic; font-weight: 700;">Sorry, OUT OF STOCK!</li>
					    <?php } ?>
					</ul>
			</div>
		   <div class="clearfix"> </div>
	   </div>
	   <div class="singlepage-product">
		   	 <div class="col-md-3 home-grid">
					<div class="home-product-main">
					   <div class="home-product-top">
					      <a href="#"><img src="images/a1.png" alt="" class="img-responsive zoom-img"></a>
					   </div>
						<div class="home-product-bottom">
								<h3><a href="#">Don't know what to get her?</a></h3>
								<p>Buy a gift card</p>						
						</div>
						<div class="srch">
							<span>Gift Cards</span>
						</div>
					</div>
				 </div>
			      <div class="col-md-3 home-grid">
					<div class="home-product-main">
					   <div class="home-product-top">
					      <a href="#"><img src="images/a2.png" alt="" class="img-responsive zoom-img"></a>
					   </div>
						<div class="home-product-bottom">
								<h3><a href="#">Perfect gift for the holidays?</a></h3>
								<p>Gift Cards!</p>						
						</div>
						<div class="srch">
							<span>Gift Cards</span>
						</div>
					</div>
				 </div>
				 <div class="col-md-3 home-grid">
					<div class="home-product-main">
					   <div class="home-product-top">
					      <a href="#"><img src="images/a3.png" alt="" class="img-responsive zoom-img"></a>
					   </div>
						<div class="home-product-bottom">
								<h3><a href="#">Want to make her smile? Do it</a></h3>
								<p>With A Gift Card!</p>						
						</div>
						<div class="srch">
							<span>Gift Cards</span>
						</div>
					</div>
				 </div>
			      <div class="col-md-3 home-grid">
					<div class="home-product-main">
					   <div class="home-product-top">
					      <a href="#"><img src="images/a4.png" alt="" class="img-responsive zoom-img"></a>
					   </div>
						<div class="home-product-bottom">
								<h3><a href="#">How to win her over? Easy..</a></h3>
								<p>With a Gift Card</p>						
						</div>
						<div class="srch">
							<span>Gift Cards</span>
						</div>
					</div>
				 </div>
		  <div class="clearfix"> </div>
	   </div>
   	 </div>
   </div>
</div>
<!--single end here-->

<!--footer strat here-->
<div class="footer">
	<div class="container">
		<div class="footer-main">
			<div class="ftr-grids-block">
				<div class="col-md-3 footer-grid">
					<ul><!-- 
						<li><a href="shop.php">Shop Gift Cards</a></li>
					</ul> -->
				</div>
				<div class="col-md-3 footer-grid">
					<!-- <ul>
						<li><a href="contact.php">Contact Us</a></li>
						<li><a href="storelocator.php">Store Locator</a></li>
						<li><a href="press.php">Press Room</a></li>
					</ul> -->
				</div>
				<div class="col-md-3 footer-grid">
					<!-- <ul>
						<li><a href="terms.php">Website Terms</a></li>
					</ul> -->
				</div>
				<div class="col-md-3 footer-grid-icon">
					<!-- <ul>
						<li><a href="https://www.youtube.com/watch?v=wclM8KzzGBQ"><span class="u-tub"> </span></a></li>
						<li><a href="https://www.instagram.com/explore/tags/giftcards/?hl=en"><span class="instro"> </span></a></li>
						<li><a href="https://twitter.com/hashtag/giftcard?f=news&amp;vertical=default"><span class="twitter"> </span></a></li>
						<li><a href="https://www.facebook.com/FreeGiftCardsOfficial/"><span class="fb"> </span></a></li>
						<li><a href="https://ro.pinterest.com/pin/247768416985462728/"> </span></a></li>
					</ul>
					<form action="newsletter.php">
					<input class="email-ftr" type="text" value="Newsletter" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Newsletter';}">
					<input type="submit" value="Submit"> 
					</form> -->
				</div>
		    <div class="clearfix"> </div>
		  </div>
		  <div class="copy-rights">
		     <p>Mighty Gift Cards. All Rights Reserved | Design by  <a href="http://w3layouts.com/" target="_blank">W3layouts</a> </p>
		   </div>
		</div>
	</div>
</div>
<!--footer end here-->

</body></html>
<style>
.amount-nr{
display:none;
}
</style>
