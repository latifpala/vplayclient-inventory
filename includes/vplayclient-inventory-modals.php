<div class="modal fade modalWrapper" id="reviewModal" role="dialog" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title fusion-responsive-typography-calculated" data-fontsize="18" data-lineheight="37.98px" style="--fontSize:18; line-height: 2.11; --minFontSize:18;">Write a review</h4>
      </div>
      <div class="modal-body">
        <form method="post" id="review-form">
          <div class="fusion-alert alert error alert-danger fusion-alert-center fusion-alert-capitalize alert-dismissable" style="background-color:#f2dede;color:rgba(166,66,66,1);border-color:rgba(166,66,66,1);border-width:1px; display: none;" id="review_error">
            <button type="button" class="close toggle-alert" data-dismiss="alert" aria-hidden="true">×</button>
            <div class="fusion-alert-content-wrapper">
            <span class="alert-icon"><i class="fa-lg fa fa-exclamation-triangle" aria-hidden="true"></i></span>
            <span class="fusion-alert-content"></span>
            </div>
          </div>
          <div class="fusion-alert alert error alert-success fusion-alert-center fusion-alert-capitalize alert-dismissable" style="border-width:1px; display: none;" id="review_success">
            <button type="button" class="close toggle-alert" data-dismiss="alert" aria-hidden="true">×</button>
            <div class="fusion-alert-content-wrapper">
            <span class="alert-icon"><i class="fa-lg fa fa-exclamation-triangle" aria-hidden="true"></i></span>
            <span class="fusion-alert-content"></span>
            </div>
          </div>
          <div class="review-image">
            <label for="rating">Rating</label>
            <span class="fa fa-star rating-form-star" data-star="1" id="star_1"></span>
            <span class="fa fa-star rating-form-star" data-star="2" id="star_2"></span>
            <span class="fa fa-star rating-form-star" data-star="3" id="star_3"></span>
            <span class="fa fa-star rating-form-star" data-star="4" id="star_4"></span>
            <span class="fa fa-star rating-form-star" data-star="5" id="star_5"></span>
            <input type="hidden" name="rating" value="0" id="rating_stars" />
          </div>
          <div class="review-wrap">
            <label for="rating_review">Add a written review</label>
            <textarea placeholder="Write Your Review Here" id="rating_review" name="rating_review"></textarea>
          </div>
          <input type="hidden" name="product_id" value="" id="product_id" /> 
          <input class="fusion-login-button fusion-button button-default button-medium fusion-login-button-no-fullwidth" type="submit" name="submit" value="Submit" id="review-form-submit" />
        </form> 
      </div>
    </div>
  </div>
</div>
<div class="modal fade modalWrapper" id="loadProductModal" role="dialog" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
      </div>
      <div class="modal-body" id="loadProductContent">
        
      </div>
    </div>
  </div>
</div>

<div class="modal fade modalWrapper" id="videoModal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <div class="modal-body" id="loadVideoContent">
        
        <!-- <iframe width="100%" height="100%" src="http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> -->
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="reviewModalWrapper" role="dialog" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title fusion-responsive-typography-calculated" data-fontsize="18" data-lineheight="37.98px" style="--fontSize:18; line-height: 2.11; --minFontSize:18;">Write a review</h4>
      </div>
      <div class="modal-body">
          <div class="fusion-alert alert error alert-danger fusion-alert-center fusion-alert-capitalize alert-dismissable" style="background-color:#f2dede;color:rgba(166,66,66,1);border-color:rgba(166,66,66,1);border-width:1px;">
            <button type="button" class="close toggle-alert" data-dismiss="alert" aria-hidden="true">×</button>
            <div class="fusion-alert-content-wrapper">
            <span class="alert-icon"><i class="fa-lg fa fa-exclamation-triangle" aria-hidden="true"></i></span>
            <span class="fusion-alert-content"><?php _e('You have already added a review', VCI_DOMAIN); ?></span>
            </div>
          </div>
          <div class="review-image">
            <label for="rating">Your Rating</label>
            <span class="fa fa-star" data-star="1" id="staro_1"></span>
            <span class="fa fa-star" data-star="2" id="staro_2"></span>
            <span class="fa fa-star" data-star="3" id="staro_3"></span>
            <span class="fa fa-star" data-star="4" id="staro_4"></span>
            <span class="fa fa-star" data-star="5" id="staro_5"></span>
          </div>
          <div class="review-wrap">
            <label for="rating_review">Your review</label>
            <textarea placeholder="Write Your Review Here" id="old_rating_text" name="rating_text" disabled="disabled"></textarea>
          </div>
      </div>
    </div>
  </div>
</div>