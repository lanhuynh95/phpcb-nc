
<style type="text/css" media="screen">
  .all{
    height: 35px;
    background-color: darkblue;
    color: white;
    width: 80px;
    border-radius: 5px;
    padding: 0 10px;
  }
  .btn-s {
    margin-left: -40px;
  }
  
</style>

 <div class="depo">
            <form action="<?= base_url() ?>BoUser/tradeHistory" method="POST" accept-charset="utf-8" id="form">
            <table id="detable">
                        <tr class="de-r">
                              <td class="de-d">From Date  </td>
                              <td><input type="text" class="form-control de-b datepicker hasDatepicker" id="from_date" name="from_date" value="<?php echo $from_date;?>"></td>
                              <td class="de-d">To Date</td>
                              <td><input type="text" class="form-control de-b datepicker hasDatepicker" id="to_date" name="to_date" value="<?php echo $to_date;?>"></td>
                             
                              <td class="de-d">
                                <select name="BtnAll" class="all" id="BtnAll">
                                   <option value=0 <?php if($BtnAll==0){echo "selected";}?> > ALL </option>
                                   <option value=1 <?php if($BtnAll==1){echo "selected";}?> > BUY </option>
                                   <option value=2 <?php if($BtnAll==2){echo "selected";}?> > SELL </option>
                                </select>
                              </td>
                                <td class="de-d"><button id="submit" type="button" class="btn btn-info btn-s">Search</button>
                              </td>
                        </tr>
            </table>
           
            <div class="btn-depo01">
                  <button type="submit" name="btn_pdf" class="btn btn-danger btn-depo2">PDF</button>
                  <button type="submit" id="export_excel" name="export_excel" class="btn btn-success btn-depo1">Exel</button>
            </div>
             </form>
            <table class="table table-bordered myTable" id="faqData ">
                  <thead>
                        <tr>
                              <th class=text-center>S.No</th>
                              <th class=text-center>Order No</th>
                              <th class=text-center>Date & Time</th>
                              <th class=text-center>Type</th>
                              <th class=text-center>Pair</th>
                              <th class=text-center>Price</th>
                              <th class=text-center>Amount</th>
                              <th class=text-center>Status</th>
                        </tr>
                  </thead>
                  <tbody>
                      <tr></tr>
<?php
 
if(!empty($trade_history->result())) {
$i = $page_count;
foreach ($trade_history->result() as $row) {

$i++;

?>
            <tr <?php if($i%2!=0) echo "class='danger'"?>>
                  <td><?php echo $i ?> </td>
                  <td><?php echo $row->order_no ?></td>
                  <td><?php echo $row->each_tradetime ?></td>
                  <td class="<?php echo $row->Type ?>"><?php echo $row->Type ?> </td>
                  <td> <?php echo $row->from_currency_symbol ?>/<?php echo $row->to_currency_symbol ?> </td>
                  <td><?php
                        if ($row->sellorderId < $row->buyorderId) {
                              $price = $row->askPrice;
                        } else {
                              $price = $row->bidPrice;
                        }
                        $amount = number_format($price, 9);
                        echo substr($amount, 0, strlen($amount) -1);?> </td>
                      <td><?php $amount = number_format($row->filledAmount, 9);
                                    echo substr($amount, 0, strlen($amount) -1); ?> </td>
                      <?php
              if ($row->status == "cancelled" || $row->status == "stoporder"
                        ) {?>
                      <td><span class="rdn"><?php echo $row->status ?></span> </td>
                    <?php
              } else {
                              ?>
                    <td><span class="grn"><?php echo $row->status ?></span> </td>
                    <?php
              }
                        ?>
            </tr>
<?php } } else { ?>
      <tr class='danger'><td colspan="8" class=text-center>No Data Available In Table</td></tr>
<?php }?>
                  </tbody>
            </table>
            <div>
                  <ul class="pagination" style="float: right;margin-top: -15px">
                        <?php echo $pagination; ?>
                  </ul>
            </div>

</div>
</section>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>

     $("#export_excel").click(function(){
      var date_start=formatDate($("#from_date").val());
      var date_end=formatDate($("#to_date").val());
            
      
      var url='<?= base_url() ?>/BoUser/tradehistoryExcel/<?=$this->uri->segment(3)?>/'+date_start+'/'+date_end;
      $("#export_excel").attr('href',url);

     })
     $("#submit").click(function() {
       var date_start=$("#from_date").val();
      var date_end=$("#to_date").val();
      var BtnAll=$("#BtnAll").val();
           location.href="<?=base_url()?>BoUser/tradeHistory/<?=$this->uri->segment(3)?>?from_date="+date_start+"&to_date="+date_end+"&BtnAll="+BtnAll;
           $("#form").submit();

     });

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
}
  $(document).ready(function() {
         $('.myTable').DataTable({
          'paging'      : false,
          'lengthChange': true,
          'searching'   : false,
          'ordering'    : true,
          'info'        : true,
          'autoWidth'   : false
        })
    } )


</script>


