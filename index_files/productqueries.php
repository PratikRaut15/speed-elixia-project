<div class="bs-sec typ-product-queries" id="prodquery">
    <div class="sec-head">
        <h2 class="sec-title wow fadeIn" data-wow-delay="0.8s" data-wow-duration="1.5s">
            <span class="cm-font-thin">product </span>queries
        </h2>
    </div>
    <div class="sec-cont">

        <div class="bs-form wow fadeIn" data-wow-delay="0.8s" data-wow-duration="1.5s">
            <form name="query" id="query" method="POST" onsubmit="submit_contact_form();">

                <ul class="check-list row" id="query_checkbox">
                    <li class="item">
                        <div class="bs-checkbox">
                            <input type="checkbox" id="intr1" name="intr1" value="Telematics">
                            <label for="intr1" class="">telematics</label>
                        </div>
                    </li>
                    <li class="item">
                        <div class="bs-checkbox">
                            <input type="checkbox" id="intr2"  name="intr2" value="Logistics erp">
                            <label for="intr2" class="">logistics erp</label>
                        </div>
                    </li>
                    <li class="item">
                        <div class="bs-checkbox">
                            <input type="checkbox" id="intr3" name="intr3" value="Control tower">
                            <label for="intr3" class="">control tower</label>
                        </div>
                    </li>
                </ul>

                <div class="form-wrap">
                    <ul class="row">

                        <li class="col-md-12">
                            <div class="form-group">
                                <input type="text" placeholder="name" class="form-control" name="u_name" id="u_name" required>
                            </div>
                        </li>
                        <li class="col-md-12">
                            <div class="form-group">
                                <input type="email" placeholder="email" class="form-control" name="email" id="email" required>
                            </div>
                        </li>
                        <li class="col-md-12">
                            <div class="form-group">
                                <input type="text" placeholder="phone no." class="form-control" name="phone" id="phone">
                            </div>
                        </li>

                        <li class="col-md-12">
                            <div class="form-group">
                                <textarea rows="4" placeholder="query" class="form-control" name="msg" id="msg"></textarea>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="form-foot">
                    <button type="submit" class="btn btn-default">submit</button>
                </div>
            </form>
            <form id="query_res_success" name="query_res_success" style="display: none;">
                                        <div class="desc typ-center typ-big">
                            <p>
                                REQUEST SUCCESSFULLY CAPTURED. THANKYOU FOR CONTACTING US !!
                            </p>
                        </div>
            </form>
            <form id="query_res_fail" name="query_res_fail" style="display: none;">
                                        <div class="desc typ-center typ-big">
                            <p>
                                REQUEST WAS NOT CAPTURED.PLEASE CONTACT US AFTER SOMETIME.
                            </p>
                        </div>
            </form>
        </div>
    </div>
</div>
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<script>
var prod_query='';
$('#query_checkbox').on('click', function() {
        var chkd = $('input:checkbox:checked');
        prod_query='';
        $.each(chkd,function(i,checkbox){
          if(prod_query==''){
            if(!(prod_query.includes(checkbox.value))){
              prod_query = checkbox.value;
            }
          }else{
            prod_query += ","+checkbox.value;
          }
        });
        $('#prod_selected').val(prod_query);
    });
</script>