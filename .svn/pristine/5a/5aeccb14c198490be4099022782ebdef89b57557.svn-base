function GetParameterValues(param) {
    var url = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for (var i = 0; i < url.length; i++) {
        var urlparam = url[i].split('=');
        if (urlparam[0] == param) {
            return urlparam[1];
        }
    }
}

function isMobileNo(strMobileNo) {
    var isValidMobileNo = false;
    if ((/^[7-9][0-9]{9}$/).test(strMobileNo)) {
        isValidMobileNo = true;
    }
    return isValidMobileNo;
}

function isVehicleRegNo(strVehRegNo) {
    var isValidVehicleRegNo = false;
    if ((/^[A-Za-z]{2}[0-9]{1,2}(?:[A-Za-z])?(?:[A-Za-z]{0,2})?[0-9]{4}$/).test(strVehRegNo)) {
        isValidVehicleRegNo = true;
    }
    return isValidVehicleRegNo;
}

function isVariableDefined(variable) {
    var isVarDefined = false;
    if(typeof variable != 'undefined'){
        isVarDefined =  true;
    }
    return isVarDefined;
}
