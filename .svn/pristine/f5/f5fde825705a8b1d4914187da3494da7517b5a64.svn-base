/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
Ajax.Request.prototype.abort = function() {
    // prevent and state change callbacks from being issued
    this.transport.onreadystatechange = Prototype.emptyFunction;
    // abort the XHR
    this.transport.abort();
    // update the request counter
    Ajax.activeRequestCount--;
};

function clickinsertsms(){
var i = document.getElementById('insertoptions');
var p = i.options[i.selectedIndex].value;
var q = i.options[i.selectedIndex].text;
var textarea = jQuery( "#msg" );
textarea.val( textarea.val() + p );
}



function submitsmsdata()
{
    
   var text1 = jQuery('#msg').val();  
   var JSONObject= {"msg":text1,"alertname":"depart"};
   
    var params =JSONObject;
     new Ajax.Request('ajaxpulls.php?work=4',
        {
            parameters: params,        
            onSuccess: function(transport)
            {
                
             
            },
            onComplete: function()
            {
            }
        });     
    
    
}
function clickinsertsms2(){
var a = document.getElementById('insertoptions2');
var b = a.options[a.selectedIndex].value;
var c = a.options[a.selectedIndex].text;
var textarea = jQuery('#msg2IFrame').contents().find('#msg').html();
jQuery('#msg2IFrame').contents().find('#msg').html(textarea + b );
}



function submitsmsdata2()
{
    
   var texta = jQuery('#msg2').val();  
   var textb = jQuery('#subject2').val();  
   var JSONObject= {"msg":texta,"header":textb,"alertname":"depart"};
   
    var params =JSONObject;
     new Ajax.Request('ajaxpulls.php?work=5',
        {
            parameters: params,        
            onSuccess: function(transport)
            {
                
               
            },
            onComplete: function()
            {
            }
        });     
    
    
}
function clickinsertsms4(){
var a = document.getElementById('insertoptions4');
var b = a.options[a.selectedIndex].value;
var c = a.options[a.selectedIndex].text;
var textarea = jQuery('#msg4IFrame').contents().find('#msg').html();
jQuery('#msg4IFrame').contents().find('#msg').html(textarea + b );
}



function submitsmsdata4()
{
    
   var texta = jQuery('#msg4').val();  
   var textb = jQuery('#subject4').val();  
   var JSONObject= {"msg":texta,"header":textb,"alertname":"thankyou"};
   
    var params =JSONObject;
     new Ajax.Request('ajaxpulls.php?work=10',
        {
            parameters: params,        
            onSuccess: function(transport)
            {
                
              
            },
            onComplete: function()
            {
            }
        });     
    
    
}
function clickinsertsms3(){
var i = document.getElementById('insertoptions3');
var p = i.options[i.selectedIndex].value;
var q = i.options[i.selectedIndex].text;
var textarea = jQuery( "#msg3" );
textarea.val( textarea.val() + p );
}



function submitsmsdata3()
{
    
   var text1 = jQuery('#msg3').val();  
   var JSONObject= {"msg":text1,"alertname":"thankyou"};
   
    var params =JSONObject;
     new Ajax.Request('ajaxpulls.php?work=9',
        {
            parameters: params,        
            onSuccess: function(transport)
            {
                
              
            },
            onComplete: function()
            {
            }
        });     
    
    
}
function clickinsertsms5(){
var i = document.getElementById('insertoptions5');
var p = i.options[i.selectedIndex].value;
var q = i.options[i.selectedIndex].text;
var textarea = jQuery( "#msg5" );
textarea.val( textarea.val() + p );
}



function submitsmsdata5()
{
    
   var texta = jQuery('#msg5').val();  
   var textb = jQuery('#subject5').val();  
   var JSONObject= {"msg":texta,"header":textb,"alertname":"panic"};
   
    var params =JSONObject;
     new Ajax.Request('ajaxpulls.php?work=5',
        {
            parameters: params,        
            onSuccess: function(transport)
            {
                
               
            },
            onComplete: function()
            {
            }
        });     
    
    
}