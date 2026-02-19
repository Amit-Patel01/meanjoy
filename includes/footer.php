<footer class="main-footer">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="MeanJoy">Meanjoy 2026</a></strong>
        </div>
        <div class="col-md-6 text-right hidden-xs">
          <div class="social-links">
            <a href="#" class="social-icon"><i class="fa fa-facebook"></i></a>
            <a href="#" class="social-icon"><i class="fa fa-twitter"></i></a>
            <a href="#" class="social-icon"><i class="fa fa-instagram"></i></a>
            <a href="#" class="social-icon"><i class="fa fa-linkedin"></i></a>
          </div>
        </div>
      </div>
    </div>
</footer>

<!-- Cart Slider -->
<div class="cart-slider-overlay" id="cartSliderOverlay"></div>
<div class="cart-slider" id="cartSlider">
  <div class="cart-slider-header">
    <h3>Your Cart</h3>
    <button class="cart-slider-close" id="cartSliderClose">
      <i class="fa fa-times"></i>
    </button>
  </div>
  <div class="cart-slider-body" id="cartSliderBody">
    <div class="cart-loading">
      <i class="fa fa-spinner fa-spin"></i> Loading...
    </div>
  </div>
  <div class="cart-slider-footer">
    <div class="cart-total-section">
      <strong>Total: <span id="cartSliderTotal">₹0.00</span></strong>
    </div>
    <a href="cart_view.php" class="btn btn-block cart-checkout-btn">Go to Cart</a>
  </div>
</div>