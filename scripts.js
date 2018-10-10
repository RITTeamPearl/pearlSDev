function nextStep(num) {
    //first check to make sure (with javascript) that all fields are valid
    if (num == 1) {
        //check to make sure phone number is valid
        // if (confirmPassword()){
        //     $("#formStep1").hide(1000);
        //     $("#formStep2").show(1000);
        // }
        jQuery.ajax({
            type: "POST",
            url: '../business/business_layer.php',
            dataType: 'json',
            data: {functionName: 'validateAndSanitize'},
        
            success: function (obj, valAndSan) {
                          if( !('error' in obj) ) {
                            $("#formStep1").hide(1000);
                            $("#formStep2").show(1000);
                            $("#circle1").hide();
                            $("#circle2").show();
                            $("#dot1").show();
                            $("#dot2").hide();
                          }
                          else {
                            $("#formStep1").show(1000);
                            $("#formStep2").hide(1000);
                          }
                    }
        });

        //Hide current circle-dot, show next
        // $("#circle1").hide();
        // $("#circle2").show();
        // $("#dot1").show();
        // $("#dot2").hide();
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
    var rowNum = $(ele).parent().parent().attr("id");
    rowNum = parseInt(rowNum.split("-")[1]);
    thisRow = "#row-" + rowNum;
    nextRow = "#row-" + parseInt(rowNum+1);

    //only toggle the next row if it needs to be
    if($(nextRow).attr('class').valueOf() === 'collapsed'){
        //Need to pass in the circle element instead of the pencil so it gets changed
        dropDownToggle($(ele).parent().parent().find('i')[0]);
    }
    //switch to the save button
    $('#editButton').hide();
    $('#saveEditButton').show();



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

//Resizes the text area to fit content
function resizeTextArea(id) {

    //Get the JQuery element of the JavaScript Element
    var textArea = $('#'+String(id.id));

    //Set the DOM element styling height to match the height of the ScrollHeight
    textArea.attr('style', 'height:' + id.scrollHeight + 'px');
}
