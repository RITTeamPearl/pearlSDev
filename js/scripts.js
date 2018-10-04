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
    var rowNum = $(ele).parent().parent().attr("id");
    rowNum = parseInt(rowNum.split("-")[1]);
    thisRow = "#row-" + rowNum;
    nextRow = "#row-" + parseInt(rowNum+1);

    //get class of next row
        //collapsed (hidden)
            //change circle to be up
    if ($(nextRow).attr('class').valueOf() === 'collapsed'){
        console.log("here");
        $(ele).removeClass("fa-chevron-circle-up").addClass("fa-chevron-circle-down");
        $(nextRow).removeClass('collapsed').addClass('un-collapsed').show();
    }

    else if ($(nextRow).attr('class').valueOf() === 'un-collapsed'){
        $(ele).removeClass("fa-chevron-circle-down").addClass("fa-chevron-circle-up");
        $(nextRow).removeClass('un-collapsed').addClass('collapsed').hide();
    }
}

function dropDownModify(ele){
    //get the current and next row based on IDs
    rowNum = $(ele).parent().parent().attr("id");
    rowNum = parseInt(rowNum.split("-")[1]);
    //format so it works with ID
    nextRow = "#row-" + parseInt(rowNum+1);
    //Jquery Element vars
    currRowEle = $("#row-"+rowNum);
    nextRowEle = $(nextRow)[0];
    //If the notification information is not displayed drop it down
    var eleClass = $(ele).attr("class").valueOf().split("-")[3];
    arrowIndicator = $(currRowEle.find('td')[0]).children()[0];
    upOrDown= $(arrowIndicator).attr("class").valueOf().split("-")[3];
    if (upOrDown === "up"){dropDownToggle(arrowIndicator)}

    //Insert a form right below the TR
    currRowEle.append("<form id='form'></form>");
    $("#form").append(currRowEle.children());

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
