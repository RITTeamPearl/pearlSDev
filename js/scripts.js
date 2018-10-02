function nextStep(num){
    //first check to make sure (with javascript) that all fields are valid
    if (num==1){
        //check to make sure phone number is valid
        if (confirmPassword()){
            $("#formStep1").hide(1000);
            $("#formStep2").show(1000);
        }

        //Hide current circle-dot, show next
        $("#circle1").hide();
        $("#circle2").show();
        $("#dot1").show();
        $("#dot2").hide();
    }

    if (num==2){
        //check to make sure names are valid
        $("#formStep2").hide(1000);
        $("#formStep3").show(1000);

        //Hide current circle-dot, show next
        $("#circle2").hide();
        $("#circle3").show();
        $("#dot2").show();
        $("#dot3").hide();
    }
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

function addMask(){
    $("#phoneNumber").mask("000-000-0000");//.addClass('className');
}

function dropDownToggle(ele){
    //get ele class.
    var eleClass = $(ele).attr("class").valueOf().split("-")[3];


    if (eleClass === "up") {
        //the circle was up so switch it to down
        $(ele).removeClass("fa-chevron-circle-up");
        $(ele).addClass("fa-chevron-circle-down");

        //get the current and next row based on IDs
        var rowNum = $(ele).parent().parent().attr("id");
        rowNum = parseInt(rowNum.split("-")[1]);
        nextRow = "#row-" + parseInt(rowNum+1);

        //show the next row because it has the data for this header
        $(nextRow).show();
    }
    if (eleClass === "down") {
        //the circle was down so switch it to up
        $(ele).removeClass("fa-chevron-circle-down");
        $(ele).addClass("fa-chevron-circle-up");

        //get the current and next row based on IDs
        var rowNum = $(ele).parent().parent().attr("id");
        rowNum = parseInt(rowNum.split("-")[1]);
        nextRow = "#row-" + parseInt(rowNum+1);

        //hide the next row because it has the data for this header
        $(nextRow).hide();

    }
}

function updateAdminView(ele){
    var whichButton = $(ele).attr("id").valueOf().split("_")[0];
    //find current active and remove it
    $(".active").removeClass("active");
    //add it to the clicked button
    $(ele).addClass("active");

    //show news hide others
    if (whichButton === "news"){
        $("#employees").hide();
        $("#pending").hide();
        $("#news").show();
    }

    //show employee hide others
    if (whichButton === "employee"){
        $("#pending").hide();
        $("#news").hide();
        $("#employees").show();

    }

    //show pending hide others
    if (whichButton === "pending"){
        $("#news").hide();
        $("#employees").hide();
        $("#pending").show();

    }
}
