jQuery(function(){
    jQuery("input[name=datetimeline]").datepicker({dateFormat: "dd-mm-yy",autoclose:true});
    jQuery("input[name=startdate]").datepicker({dateFormat: "dd-mm-yy",autoclose:true,minDate: 0});
    jQuery("#startdate").change(function(){
        if(jQuery("input[name=datetimeline]").val()!=jQuery(this).val()){
            jQuery("input[name=datetimeline]").val(jQuery(this).val());
            getTimeline();
        }
    });
    jQuery("input[name=datetimeline]").change(function(){
        getTimeline();
    });
    jQuery("#clientname").autocomplete({
        source: "mobility_ajax.php?action=clientauto",minLength: 1,
        select: function( event, ui ) {jQuery('#clientid').val(ui.item.id);getClientDetails(ui.item.id);}
    });
    /*jQuery("#serviceauto").autocomplete({
        source: "mobility_ajax.php?action=serviceauto",minLength: 1,
        select: function( event, ui ) {console.log(ui.item);addServiceTr(ui.item);}
    });*/
    
    jQuery("#category").change(function(){
        if(jQuery(this).val()!=''){
            getServicebyCat(jQuery(this).val());
        }
    });
    jQuery("#serviceauto").change(function(){
        if(jQuery(this).val()!=''){
            addServiceTr(jQuery.parseJSON(jQuery(this).val()));
        }
    });
    
    jQuery("#catauto").change(function(){
        if(jQuery(this).val()!=''){
            var catauto = jQuery("#catauto").val();
            data ="action=getservice&cid="+catauto;
            jQuery.ajax({url:"mobility_ajax.php",type: 'POST',data: data,
                success:function(result){
                  jQuery("#serviceauto").html(result);
                },
                complete: function(){
                   // jQuery('#pageloaddiv').hide();
                }
            });
            
        }
    });
    
    
    jQuery("#trackieauto").autocomplete({
        source: "mobility_ajax.php?action=trackieauto",minLength: 1,
        select: function( event, ui ) {jQuery('#trackieid').val(ui.item.id);}
    });
    jQuery('#tmlTrackInp').keyup(function(){
        var inpval = jQuery(this).val().toLowerCase();
        filterTimeline(inpval);
    });
    jQuery('.bubbleclose').click(function(){
        jQuery('#ajaxBstatus').html('');
        jQuery('#statusChngeBuble').css({"visibility":"hidden"});
    });
    jQuery('#changeStatus').change(function(){
        statChangeAjax(jQuery(this).val(), jQuery('#EditcallID').val());
    });
    jQuery('#discountcode').keyup(function(){
        discountCheck(jQuery(this).val(), jQuery('#clientid').val());
    });
    
    jQuery('.mainTds').live("click",function(e){
        timelineClick(e,this);
    });
    
    jQuery('.allDataCell').live("click",function(){
         var thera = jQuery(this).closest('tr').children('td').eq(0);
        jQuery('#trackieauto').val(thera.html());
        jQuery('#trackieid').val(thera.attr('id'));
        fillCall(this.id);
    });
    
    dragdrop();
});
var expindx = 1;
var costindx = 2;
var skipcnt = 4;
var costunit = 'Rs';
var timeunit = 'Mins';
var discvalue = 0;
var isamount = 1;

function timelineClick(e,dat){
    var divposition = jQuery(dat).offset();
    var clickedwidth = e.pageX-divposition.left;
    var perc = (clickedwidth/jQuery(dat).width())*100;
    var reqmin = perc/minuteWidth;
    var reqhour = (reqmin/60)+starthr;
    var time = phrtohhmm(reqhour.toFixed(2));
    showNewCall();
    var lst = time.h+':'+time.m;
    jQuery('#STime').timepicker('setTime,'+lst);
    setTimeout(function() {
        jQuery('#STime').trigger('click');
    }, 10);
    var thera = jQuery(dat).closest('tr').children('td').eq(0);
    jQuery('#trackieauto').val(thera.html());
    jQuery('#trackieid').val(thera.attr('id'));
}
function phrtohhmm(reqhour){
    var st = reqhour.split(".");
    var min = parseInt((3/5)*parseFloat(st[1]));
    if(min<10){min = "0"+min;}
    var final = {'h':parseInt(st[0]), 'm':min};
    return final;
}
function dragdrop(){
    /*jQuery(".draggable" ).draggable({
       //helper: "clone",
    });
    
    jQuery(".droppable" ).droppable({
    //hoverClass: "activeTd", 
        drop: function( event, ui ) {
            alert('saved');
            //var t = jQuery(ui.draggable);
            console.log(ui);
            var rDiv = "<div class='allDataCell draggable' >"+ui.draggable.text()+"</div>"
            jQuery(this).replaceWith(rDiv);
        }
    });*/
}
function showNewCall(){
    jQuery('#newCallIcon').slideUp();
    jQuery('#newCallForm').slideDown();
    jQuery('#startdate').val(jQuery("input[name=datetimeline]").val());
}
function hideNewCall(){
    jQuery('#newCallIcon').slideDown();
    jQuery('#newCallForm').slideUp();
}
function getClientDetails(cid){
    jQuery.ajax({url:"mobility_ajax.php?action=clientdetails",type: 'POST',data: "clientid="+cid,
        success:function(result){
            var obj = jQuery.parseJSON(result);
            if(obj.Status=='Failure'){jQuery('#cdResponse').html(obj.Error);}
            else{fillClientDetails(obj[0]);fillClientHistory(obj[1]);}
        },
    });
}
function fillClientDetails(obj,add_id){
    
    var data = '<tr><td style="width:75px"><span class="ftd" style="padding:3px;">Cid-Name</span></td><td>'+obj.cno+'-'+obj.cname+'</td></tr>';
    var phone = '';if(obj.phone){phone = "<br/>"+obj.phone;}
    data += '<tr><td><span class="ftd" style="padding:3px;">Contact</span></td><td>'+obj.mobile+phone+'</td></tr>';
    data += '<tr><td colspan=2>';
    var selcted = 'checked="checked"';
    var sr=1;
    
    jQuery(obj.address).each(function(index,val){
        data += '<span class="ftd" style="padding:3px;">Address'+sr+'</span> '+val[1];
        
        if(add_id!=undefined){
            if(add_id==val[0]){ selcted = 'checked="checked"';}
            else{selcted = '';}
            data += ' <input name="addid" type="radio" value="'+val[0]+'" '+selcted+'/><br/>'
        }
        else{
            data += ' <input name="addid" type="radio" value="'+val[0]+'" '+selcted+'/><br/>'
            selcted = '';
        }
        sr++;
    });
    data += '</td></tr>';
    
    
    jQuery('#cdResponse').html(data);
}
function fillClientHistory(obj){
    var data = '';
    jQuery(obj).each(function(i,v){
        data += '<tr><td>';
        data += '<table class="table SngleHist" >';
        data += '<tr><td colspan="100%">Date: '+v.fdate+'; <br/> Cost: '+v.tcost+' '+costunit+';<br/>Time: '+v.ttime+' '+timeunit+'; Therapist: '+v.tname+';<br/>';
        data += 'Services: '+v.services+';</td></tr>';
        data += '</td></tr>';
        data += '</table>';
        data += '</td></tr>';
    });
    jQuery('#clientHistory').html(data);
}
function addServiceTr(curService){
    var data = '<tr><td>'+curService.value+'<input type="hidden" value="'+curService.id+'" /></td>';
    data += '<td>'+curService.exptime+'</td>';
    data += '<td>'+curService.scost+'</td>';
    data += '<td><a href="javascript:void(0);" onclick="removeService(this);">Remove</a></td>';
    data += '</tr>';
    jQuery('#scorderdtls').append(data);
    setTotal();
}
function CallEditServiceTr(curService){
    var data = '';
    jQuery(curService.serviceids).each(function(i,v){
        var loop = parseInt(curService.sqnties[i]);
        while(loop){
            data += '<tr><td>'+curService.snames[i]+'<input type="hidden" value="'+v+'" /></td>';
            data += '<td>'+curService.stime[i]+'</td>';
            data += '<td>'+curService.scosts[i]+'</td>';
            data += '<td><a href="javascript:void(0);" onclick="removeService(this);">Remove</a></td>';
            data += '</tr>';
            loop--;
        }
    });
    jQuery('#scorderdtls').html(data);
    setTotal();
}
function removeService(data){
    var sotble = jQuery(data).parent().parent().index();
    jQuery("#scorderdtls tr").eq(sotble).remove();
    setTotal();
}
function removeallService(){
    jQuery("#scorderdtls").html("");
    setTotal();
}
function setTotal(){
    var texptime = 0;
    var tcost = 0;
    var fexpindx = expindx;
    var fcostindx = costindx;
    jQuery('#scorderdtls td').each(function(index,value){
        var tddata = jQuery(value).html();
        if(index==fexpindx){texptime += parseFloat(tddata);fexpindx += skipcnt;}
        if(index==fcostindx){tcost += parseFloat(tddata);fcostindx += skipcnt;}
        
    });
    
    if(tcost!=0){
        if(isamount==1){
            tcost = tcost-discvalue;
        }
        else{
            var dp = (discvalue*tcost)/100;
            tcost = tcost-dp.toFixed(2);
        }
    }
    jQuery('#totalexpt').html(texptime+' '+timeunit);
    jQuery('#totalcost').html(tcost+' '+costunit);
}

function addNewCall(){
    var clid = jQuery('#clientid').val();
    var sdte = jQuery('#startdate').val();
    var stme = jQuery('#STime').val();
    var tkid = jQuery('#trackieid').val();
    var statid = jQuery('#changeStatus').val();
    var addid = jQuery("input[type='radio'][name='addid']:checked").val();
    var discode = jQuery('#discountcode').val();
    var sdata = getServiceData();
    
    if(clid==''){callStatus('Client details not found','call-err');return false;}
    if(sdte=='' || stme==''){callStatus('Please fill expected start-date.','call-err');return false;}
    if(tkid==''){callStatus('Please enter stylist details.','call-err');return false;}
    if(jQuery.isEmptyObject(sdata)){callStatus('Service not added','call-err');return false;}
    
    var data = {'clid':clid,'statid':statid,'sdte':sdte,'stme':stme,'tkid':tkid,'sdata':sdata,'addid':addid, 'discountcode': discode};
    data = JSON.stringify(data);
    jQuery.ajax({url:"mobility_ajax.php?action=addCall",type: 'POST',data: "call="+data,
        success:function(result){
            var obj = jQuery.parseJSON(result);
            if(obj.Status=='Failure'){callStatus(obj.Error,'call-err');}
            else{
                callStatus(obj.Msg,'call-success');
                setTimeout(function () { hideNewCall(); resetCall();}, 1000);
                jQuery("input[name=datetimeline]").val(sdte);
                getTimeline();
            }
        },
    });
}
function getServiceData(){
    var sdata = [];
    jQuery('#scorderdtls tr').each(function(index){
        jQuery(this).find('td').each(function(tdindex){
            if(tdindex==0){
                var id = jQuery(this).find("input").val();
                sdata[index] = id;
            }
        });
    });
    return sdata;
}
function callStatus(data,stat){
    var id='#newCallStatus'; 
    jQuery(id).show();
    jQuery(id).addClass(stat);
    jQuery(id).html(data);
    jQuery('#mainCallDv').click(function(){
        jQuery(id).hide();
    });
}
function resetCall(){
    jQuery('#newCallForm input').each(function(){
        if(jQuery(this).attr('type')!='button'){
            jQuery(this).val('');
        }
    });
    jQuery('#cdResponse').html('');
    jQuery('#scorderdtls').html('');
    jQuery('#newCallStatus').html('');
    jQuery('#clientHistory').html('');
    jQuery('#discCalc').html(0);
    jQuery('#newCallStatus').removeClass('call-err');
    jQuery('#newCallStatus').removeClass('call-success');
    jQuery('#STime').val('08:00');
    jQuery('.bootstrap-timepicker-hour').html('08');
    jQuery('#totalexpt').html(0);
    jQuery('#totalcost').html(0);
}
function fillServiceModal(){
    var sval = jQuery('#serviceauto').val();
    jQuery("input[name=servicename]").val(sval);
}
function date_change(todo){
    var date_str = jQuery("input[name=datetimeline]").val();
    var parts = date_str.split("-");
    var dt = new Date(
      parseInt(parts[2], 10),      // year
      parseInt(parts[1], 10) - 1,  // month (starts with 0)
      parseInt(parts[0], 10)       // date
    );
    if(todo=='incre'){dt.setDate(dt.getDate() + 1);}
    else{dt.setDate(dt.getDate() - 1);}
    parts[2] = "" + dt.getFullYear();
    parts[1] = "" + (dt.getMonth() + 1);
    if (parts[1].length < 2) {parts[1] = "0" + parts[1];}
    parts[0] = "" + dt.getDate();
    if (parts[0].length < 2) {parts[0] = "0" + parts[0];}
    var datetest = parts.join("-");
    jQuery("input[name=datetimeline]").val(parts.join("-"));
    getTimeline();
}
function getTimeline(){
    var date_str = jQuery("input[name=datetimeline]").val();
    jQuery('#pageloaddiv').show();
    jQuery.ajax({url:"mobility_ajax.php?action=getTimeline",type: 'POST',data: "of="+date_str, async:false,
        success:function(result){jQuery('#timelineBody').html(result);},
        complete:function(){jQuery('#pageloaddiv').hide();}
    });
    dragdrop();
}
function filterTimeline(tkname){
    var row='';
    jQuery('#timelineBody tr').find('td:first').each(function(){
        row = jQuery(this).html().toLowerCase();
        if(row.match(new RegExp(tkname))) {jQuery(this).parent().show();}
        else{jQuery(this).parent().hide();}
    });
}

function statusChange(sdate,services,mobile,cdetails,callID,address,cost,time,discount){
        
    jQuery("#statusChngeBuble").css({"visibility":"visible"});
    jQuery('#clid').val(callID);
    jQuery('#clientVal').html(cdetails);
    jQuery('#whenVal').html(sdate);
    var sdetails = "Services: "+services+";<br/>";
    if(discount!=''){
        sdetails += "Actual cost: "+cost+" Rs; Time: "+time+" Mins;<br/>"+discount;
    }
    else{
        sdetails += "Cost: "+cost+" Rs; Time: "+time+" Mins";
    }
    jQuery('#whatVal').html(sdetails);
    //var statclr = '<span  class="Statlabel" id="popStatSpan" style="background-color:'+allstats[stat][1]+';">'+allstats[stat][0]+'</span>';
    //jQuery('#statusVal').html(statclr);
    jQuery('#whereVal').html(address);
    jQuery('#mobile').html(mobile)
}
function statChangeAjax(newStat,scid){
    jQuery('#pageloaddiv').show();
    jQuery.ajax({url:"mobility_ajax.php",type: 'POST',data:'stat='+newStat+'&scid='+scid+'&action=changeStat',
        success:function(result){
            var obj = jQuery.parseJSON(result);
            if(obj.Status === "Success"){
                showBsuccess(obj.Msg);
                jQuery("#popStatSpan").css({"background-color":allstats[newStat][1]});
                jQuery("#popStatSpan").html(allstats[newStat][0]);
                jQuery("#"+scid).css({"background-color":allstats[newStat][1]});
            }else{showBerror(obj.Error);}
        },
        complete: function(){jQuery('#pageloaddiv').hide();}
    });
}
function discountCheck(code,scid){
    jQuery('#discCalc').html(0);
    isamount = 1;discvalue = 0;
    setTotal();
    if(code=='' || code.length<3){return false;}
    if(scid==''){callStatus('Please select client','call-err');return false;}
    jQuery('#pageloaddiv').show();
    jQuery.ajax({url:"mobility_ajax.php",async:false,type: 'POST',data:'discountcode='+code+'&clientid='+scid+'&action=chkdiscount',
        success:function(result){
            var obj = jQuery.parseJSON(result);
            
            if(obj.Status === "Success"){
                isamount = obj.data['isamount'];
                discvalue = obj.data['discvalue'];
                jQuery('#discCalc').html(discvalue);
            }else{callStatus(obj.Error,'call-err');jQuery('#discCalc').html(0);}
        },
        complete: function(){jQuery('#pageloaddiv').hide();}
    });
    setTotal();
    
}
function removeDisc(){
    jQuery('#discCalc').html(0);
    discAmt = 0;
    discPctage = 0;
    setTotal();
}

function deleteSC(){
    var scid = jQuery('#clid').val();
    jQuery('#pageloaddiv').show();
    jQuery.ajax({url:"mobility_ajax.php",type: 'POST',data:'scid='+scid+'&action=deleteSC',
        success:function(result){
            var obj = jQuery.parseJSON(result);
            if(obj.Status === "Success"){
                showBsuccess(obj.Msg);
                jQuery("#"+scid).html('&nbsp;');
            }else{showBerror(obj.Error);}
        },
        complete: function(){jQuery('#pageloaddiv').hide();}
    });
}

function getServicebyCat(catid){
    jQuery.ajax({url:"mobility_ajax.php",type: 'POST',data:'catid='+catid+'&action=getsc_bycat',
        success:function(result){
            var obj = jQuery.parseJSON(result);
            var sopt = "<option value='' >-Select services-</option>";
            if(obj.Status === "Success"){
                jQuery(obj.data).each(function(i,v){
                    sopt += "<option value='"+JSON.stringify(v)+"'>"+v.value+"</option>";
                });
            }else{callStatus(obj.Error,'call-err');}
            jQuery("#serviceauto").html(sopt);
        },
        complete: function(){jQuery('#pageloaddiv').hide();}
    });
}
function prepopulate_status(statid){
    jQuery('#changeStatus option').each(function (){
        if(jQuery(this).val()==statid){
            jQuery(this).prop('selected', true);
        }
    });
}
function fillCall(id){
    jQuery('#pageloaddiv').show();
    jQuery('#EditcallID').val(id);
    jQuery.ajax({url:"mobility_ajax.php",type: 'POST',data:'callid='+id+'&action=getCallDetails',
        success:function(result){
            var obj = jQuery.parseJSON(result);
            if(obj.Status === "Success"){
                jQuery('#clientid').val(obj.data[0]['cid']);
                jQuery('#clientname').val(obj.data[0]['cname']);
                jQuery('#clientID').val(obj.data[0]['scid']);
                if(obj.data[2]['discid']!=0){
                    jQuery('#discountcode').val(obj.data[2]['discode']);
                    jQuery('#discountcode').trigger('keyup');
                }
                fillClientDetails(obj.data[0],obj.data['cur_address']);
                fillClientHistory(obj.data[1]);
                CallEditServiceTr(obj.data[2]['services']);
                prepopulate_status(obj.data[2]['sstatus']);
                jQuery('#STime').timepicker('setTime,'+obj.data[2]['callTime']);
            }else{callStatus(obj.Error,'call-err');}
        },
        complete: function(){jQuery('#pageloaddiv').hide();}
    });
    showNewCall();
}

function editCall(){
    var clid = jQuery('#clientid').val();
    var sdte = jQuery('#startdate').val();
    var stme = jQuery('#STime').val();
    var tkid = jQuery('#trackieid').val();
    var statid = jQuery('#changeStatus').val();
    var addid = jQuery("input[type='radio'][name='addid']:checked").val();
    var discode = jQuery('#discountcode').val();
    var callid = jQuery('#EditcallID').val();
    var sdata = getServiceData();
    
    if(callid==''){callStatus('Call-id not found','call-err');return false;}
    if(clid==''){callStatus('Client details not found','call-err');return false;}
    if(sdte=='' || stme==''){callStatus('Please fill expected start-date.','call-err');return false;}
    if(tkid==''){callStatus('Please enter stylist details.','call-err');return false;}
    if(jQuery.isEmptyObject(sdata)){callStatus('Service not added','call-err');return false;}
    
    var data = {'callid':callid,'clid':clid,'statid':statid,'sdte':sdte,'stme':stme,'tkid':tkid,'sdata':sdata,'addid':addid, 'discountcode': discode};
    data = JSON.stringify(data);
    jQuery.ajax({url:"mobility_ajax.php?action=editCall",type: 'POST',data: "call="+data,
        success:function(result){
            var obj = jQuery.parseJSON(result);
            if(obj.Status=='Failure'){callStatus(obj.Error,'call-err');}
            else{
                callStatus(obj.Msg,'call-success');
                setTimeout(function () { hideNewCall(); resetCall();}, 1000);
                jQuery("input[name=datetimeline]").val(sdte);
                getTimeline();
            }
        },
    });
}


function showBerror(text){jQuery("#ajaxBstatus").html(text);jQuery("#ajaxBstatus").css('color','red');jQuery("#ajaxBstatus").show();}
function showBsuccess(text){jQuery("#ajaxBstatus").html(text);jQuery("#ajaxBstatus").css('color','green');jQuery("#ajaxBstatus").show();}


 jQuery(document).ready(function(){
      jQuery('.select-row').click(function()
      {
         jQuery(this).removeAttr("onclick");
         return false;
      });
      
      
      jQuery('#addclient').click(function()
      {
        reset_clientpopup();
        jQuery('#addClientBuble').css({"visibility":"visible"});
      });
      
      jQuery('.bubbleclose').click(function(){
        jQuery('#ajaxBstatus').html('');
        jQuery('#addClientBuble').css({"visibility":"hidden"});
        reset_clientpopup();
    });
    
    jQuery("#addclientdata").click(function(){
        var cname = jQuery("#cname").val();
        var clientno = jQuery("#clientno").val();
        var cmob = jQuery("#cmob").val();
        var cpassword = jQuery("#cpassword").val();
          if(cname==""||clientno==""||cmob==""||cpassword==""){
                showBerror("All fields are mandatory.");
                return false;
            }else{
                data ="action=addclientpop&cname="+cname+"&clno="+clientno+"&mob="+cmob+"&pass="+cpassword;
                jQuery.ajax({url:"mobility_ajax.php",type: 'POST',data:data,
                    success:function(result){
                        var data = jQuery.parseJSON(result);
                        if(data.Status=="Failure"){
                            showBerror(data.Error);return false;
                        }
                        if(data.Status=="Sucess"){
                            showBsuccess(data.Error);
                            reset_clientpopup();
                        }
                        
                    },
                    complete: function(){
                       // jQuery('#pageloaddiv').hide();
                    }
                });
            }
        });
    
    });
    
   
  function reset_clientpopup(){
         jQuery("#cname").val(''); jQuery("#clientno").val(''); jQuery("#cmob").val(''); jQuery("#cpassword").val('');
  }