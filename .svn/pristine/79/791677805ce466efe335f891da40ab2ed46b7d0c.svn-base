function loaded()
{
    if($("clientbox"))
    {
        registerSAYT("clientname","services","clientbox",clientselected, noresults, haveresults, addClient);        
    }
    if($("clientextrabox"))
    {
        registerSAYT("cliextra","servicesextra","clientextrabox",clientextraselected, noresults, haveresults, addClient);    
    }
    if($("userfieldbox1"))
    {
        registerSAYT("seruf1","seruf1","userfieldbox1",uf1sel, noresults, haveresults, addClient);        
    }
    if($("userfieldbox2"))
    {
        registerSAYT("seruf2","seruf2","userfieldbox2",uf2sel, noresults, haveresults, addClient);    
    }
}


jQuery(document).ready(function() {
  // Handler for .ready() called.
  loaded();
});
function noresults()
{
}

function haveresults()
{
}

function addClient()
{    
}

function uf1sel(selectedid, selectedtext)
{
    $("seruf1").value = selectedtext;
}

function uf2sel(selectedid, selectedtext)
{
    $("seruf2").value = selectedtext;
}

function clientselected(selectedid, selectedtext)
{
    $("clientname").value = selectedtext;
}

function clientextraselected(selectedid, selectedtext)
{
    $("cliextra").value = selectedtext;
}


    
Calendar.setup(
{
    inputField : "fromdate", // ID of the input field
    ifFormat : "%d-%m-%Y", // the date format
    button : "trigger" // ID of the button
});                

Calendar.setup(
{
    inputField : "todate", // ID of the input field
    ifFormat : "%d-%m-%Y", // the date format
    button : "triggerto" // ID of the button
});                

Calendar.setup(
{
    inputField : "dfromdate", // ID of the input field
    ifFormat : "%d-%m-%Y", // the date format
    button : "dtrigger" // ID of the button
});                

Calendar.setup(
{
    inputField : "dtodate", // ID of the input field
    ifFormat : "%d-%m-%Y", // the date format
    button : "dtriggerto" // ID of the button
});                