<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Sales History
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Sales</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Orders List</h3>
              <div class="pull-right">
                <button type="button" class="btn btn-primary btn-sm btn-flat" id="todayBtn" title="Show today's orders"><i class="fa fa-calendar"></i> Today</button>
                <button type="button" class="btn btn-default btn-sm btn-flat" id="allBtn" title="Show all orders"><i class="fa fa-list"></i> All Orders</button>
                <form method="POST" class="form-inline" action="sales_print.php" style="display: inline-block; margin-left: 10px;">
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control pull-right col-sm-8" id="reservation" name="date_range">
                  </div>
                  <button type="submit" class="btn btn-success btn-sm btn-flat" name="print"><span class="glyphicon glyphicon-print"></span> Print</button>
                </form>
              </div>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
                  <th class="hidden"></th>
                  <th>Date</th>
                  <th>Buyer Name</th>
                  <th>Transaction#</th>
                  <th>Amount</th>
                  <th>Full Details</th>
                </thead>
                <tbody>
                  <?php
                    $conn = $pdo->open();
                    
                    // Get today's date for filtering
                    $today = date('Y-m-d');
                    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'today';

                    try{
                      // Filter orders based on parameter
                      if($filter === 'all'){
                        $stmt = $conn->prepare("SELECT *, sales.id AS salesid FROM sales LEFT JOIN users ON users.id=sales.user_id ORDER BY sales_date DESC");
                        $stmt->execute();
                      } else {
                        // Default: show today's orders
                        $stmt = $conn->prepare("SELECT *, sales.id AS salesid FROM sales LEFT JOIN users ON users.id=sales.user_id WHERE DATE(sales.sales_date) = :today ORDER BY sales_date DESC");
                        $stmt->execute(['today' => $today]);
                        
                        // If no orders today, show a message in the first row
                        $todayCount = $stmt->rowCount();
                        if($todayCount == 0){
                          // Fetch today count
                        }
                      }
                      
                      foreach($stmt as $row){
                        $stmt2 = $conn->prepare("SELECT * FROM details LEFT JOIN products ON products.id=details.product_id WHERE details.sales_id=:id");
                        $stmt2->execute(['id'=>$row['salesid']]);
                        $total = 0;
                        foreach($stmt2 as $details){
                          $subtotal = $details['price']*$details['quantity'];
                          $total += $subtotal;
                        }
                        echo "
                          <tr>
                            <td class='hidden'></td>
                            <td>".date('M d, Y', strtotime($row['sales_date']))."</td>
                            <td>".$row['firstname'].' '.$row['lastname']."</td>
                            <td>".$row['pay_id']."</td>
                            <td>₹ ".number_format($total, 2)."</td>
                            <td><button type='button' class='btn btn-info btn-sm btn-flat transact' data-id='".$row['salesid']."'><i class='fa fa-search'></i> View</button></td>
                          </tr>
                        ";
                      }
                      
                      // Show message if no results
                      if($stmt->rowCount() == 0){
                        echo "<tr><td colspan='6' align='center' class='alert alert-info'>";
                        if($filter === 'today'){
                          echo "No orders for today";
                        } else {
                          echo "No orders found";
                        }
                        echo "</td></tr>";
                      }
                    }
                    catch(PDOException $e){
                      echo $e->getMessage();
                    }

                    $pdo->close();
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
     
  </div>
  	<?php include 'includes/footer.php'; ?>
    <?php include '../includes/profile_modal.php'; ?>

</div>
<!-- ./wrapper -->

<?php include 'includes/scripts.php'; ?>
<!-- Date Picker -->
<script>
$(function(){
  //Date picker
  $('#datepicker_add').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
  })
  $('#datepicker_edit').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
  })

  //Timepicker
  $('.timepicker').timepicker({
    showInputs: false
  })

  //Date range picker
  $('#reservation').daterangepicker()
  //Date range picker with time picker
  $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A' })
  //Date range as a button
  $('#daterange-btn').daterangepicker(
    {
      ranges   : {
        'Today'       : [moment(), moment()],
        'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month'  : [moment().startOf('month'), moment().endOf('month')],
        'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      startDate: moment().subtract(29, 'days'),
      endDate  : moment()
    },
    function (start, end) {
      $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
    }
  )
  
});
</script>
<script>
$(function(){
  $(document).on('click', '.transact', function(e){
    e.preventDefault();
    $('#transaction').modal('show');
    var id = $(this).data('id');
    $.ajax({
      type: 'POST',
      url: 'transact.php',
      data: {id:id},
      dataType: 'json',
      success:function(response){
        $('#date').html(response.date);
        $('#transid').html(response.transaction);
        $('#customer_name').html(response.customer_name);
        $('#customer_address').html(response.customer_address);
        $('#customer_contact').html(response.customer_contact);
        $('#detail').prepend(response.list);
        $('#total').html(response.total);
      }
    });
  });

  $("#transaction").on("hidden.bs.modal", function () {
      $('.prepend_items').remove();
  });
  
  // Today's orders filter
  $(document).on('click', '#todayBtn', function(){
    window.location.href = 'sales.php?filter=today';
  });
  
  // All orders filter
  $(document).on('click', '#allBtn', function(){
    window.location.href = 'sales.php?filter=all';
  });
});
</script>
</body>
</html>
