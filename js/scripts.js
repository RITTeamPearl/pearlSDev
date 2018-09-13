function nextStep(){
    //first check to make sure (with javascript) that all fields are valid
    $("#formStep1").hide(1000);
    $("#formStep2").show(1000);
}

function confirmPassword(){
    if ($('#password').val() == $('#passwordConfirm').val()) {
        $(".pwIcon").css('color', 'green');
        return true;
    }
    else{
        $('.pwIcon').css('color', 'red');
        return false;
    }
}
