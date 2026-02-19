<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script>
// Navbar scroll effect
$(window).scroll(function() {
  if ($(this).scrollTop() > 50) {
    $('.modern-navbar').addClass('scrolled');
  } else {
    $('.modern-navbar').removeClass('scrolled');
  }
});
</script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- CK Editor -->
<script src="bower_components/ckeditor/ckeditor.js"></script>
<script>
  $(function () {
    // Datatable
    $('#example1').DataTable()
    //CK Editor
    CKEDITOR.replace('editor1')
  });
</script>
<!--Magnify -->
<script src="magnify/magnify.min.js"></script>
<script>
$(function(){
	$('.zoom').magnify();
});
</script>
<!-- Custom Scripts -->
<script>
$(function(){
  // Search icon click to show search box
  $('#searchIconBtn').click(function(){
    $('#navbar-search-input').addClass('active').focus();
    $('#searchBtn').show();
    $(this).hide();
  });

  // Search input focus
  $('#navbar-search-input').focus(function(){
    $('#searchBtn').show();
  });

  // Search input blur - hide if empty
  $('#navbar-search-input').blur(function(){
    if($(this).val() == ''){
      setTimeout(function(){
        if($('#navbar-search-input').val() == ''){
          $('#navbar-search-input').removeClass('active');
          $('#searchBtn').hide();
          $('#searchIconBtn').show();
        }
      }, 200);
    }
  });

  getCart();
  
  // Cart Slider Functions
  if($('#cartSliderBtn').length){
    // Open cart slider
    $('#cartSliderBtn').click(function(e){
      e.preventDefault();
      $('#cartSlider').addClass('active');
      $('#cartSliderOverlay').addClass('active');
      $('body').css('overflow', 'hidden');
      loadCartSlider();
    });
    
    // Close cart slider
    $('#cartSliderClose, #cartSliderOverlay').click(function(){
      $('#cartSlider').removeClass('active');
      $('#cartSliderOverlay').removeClass('active');
      $('body').css('overflow', '');
    });
    
    // Prevent slider body click from closing
    $('#cartSlider').click(function(e){
      e.stopPropagation();
    });
    
    // Load cart slider content
    function loadCartSlider(){
      $.ajax({
        type: 'POST',
        url: 'cart_slider.php',
        dataType: 'json',
        success: function(response){
          $('#cartSliderBody').html(response.html);
          $('#cartSliderTotal').text('₹' + parseFloat(response.total).toFixed(2));
        }
      });
    }
    
    // Update cart after actions
    $(document).on('click', '.cart-slider-delete', function(e){
      e.preventDefault();
      var id = $(this).data('id');
      $.ajax({
        type: 'POST',
        url: 'cart_delete.php',
        data: {id:id},
        dataType: 'json',
        success: function(response){
          if(!response.error){
            loadCartSlider();
            getCart();
            getTotal();
          }
        }
      });
    });
    
    $(document).on('click', '.cart-slider-minus', function(e){
      e.preventDefault();
      var id = $(this).data('id');
      var qty = parseInt($('#slider_qty_'+id).text()) - 1;
      if(qty < 1) qty = 1;
      updateCartSliderQty(id, qty);
    });
    
    $(document).on('click', '.cart-slider-plus', function(e){
      e.preventDefault();
      var id = $(this).data('id');
      var qty = parseInt($('#slider_qty_'+id).text()) + 1;
      updateCartSliderQty(id, qty);
    });
    
    function updateCartSliderQty(id, qty){
      $.ajax({
        type: 'POST',
        url: 'cart_update.php',
        data: {id: id, qty: qty},
        dataType: 'json',
        success: function(response){
          if(!response.error){
            loadCartSlider();
            getCart();
            getTotal();
          }
        }
      });
    }
  }

  $('#productForm').submit(function(e){
  	e.preventDefault();
  	var product = $(this).serialize();
  	$.ajax({
  		type: 'POST',
  		url: 'cart_add.php',
  		data: product,
  		dataType: 'json',
  		success: function(response){
  			$('#callout').show();
  			$('.message').html(response.message);
  			if(response.error){
  				$('#callout').removeClass('callout-success').addClass('callout-danger');
  			}
  			else{
				$('#callout').removeClass('callout-danger').addClass('callout-success');
				getCart();
  			}
  		}
  	});
  });

  $(document).on('click', '.close', function(){
  	$('#callout').hide();
  });

});

function getCart(){
	$.ajax({
		type: 'POST',
		url: 'cart_fetch.php',
		dataType: 'json',
		success: function(response){
			$('#cart_menu').html(response.list);
			$('.cart_count').html(response.count);
		}
	});
}
</script>
<!-- Show password toggle handler -->
<script>
$(function(){
  $(document).on('change', '.toggle-pass', function(){
    var target = $(this).data('target');
    var $input = $(target);
    if($input.length === 0){
      // fallback: try name selector
      $input = $("input[name='password']");
    }
    if($(this).is(':checked')){
      $input.attr('type','text');
    }
    else{
      $input.attr('type','password');
    }
  });
});
</script>