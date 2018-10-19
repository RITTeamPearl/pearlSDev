//var $ = require('jQuery');
function nextStep(num) {
    //first check to make sure (with javascript) that all fields are valid
    if (num == 1) {
        //check to make sure phone number is valid
        // if (confirmPassword()){
        //     $("#formStep1").hide(1000);
        //     $("#formStep2").show(1000);
        // }
        $.ajax({
            type: "POST",
            url: '../business/business_layer.php',
            data: { functionName: 'validateAndSanitize' },

            success: function (obj, valAndSan) {
                if (!('error' in obj)) {
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

    if (num == 2) {
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

function nextStepTwo(num) {
    var formData = [];
    $('input, select').each(
        function (index) {
            var input = $(this);
            formData.push([
                input.attr('name'),
                input.val()
            ]);
        }
    );
    // console.log(JSON.stringify(formData));

    // console.log(formData);
    if (num == 1) {
        formData.push(['pageNumber', num]);
        console.log('starting ajax request');   

        $.ajax({
            type: "POST",
            url: '../business/business_layer.php',
            data: {
                action: 'validateForm',
                formSection : 'screen1',
                formData: JSON.stringify(formData)
            },
            success: function (data) {
                //Data looks like this
                /**
                 * [
                 * [
                 *  'location' = '#phoneSpan',
                 *  'msg' = 'Message'
                 * ],
                 * [
                 *  'location' = '#passwordSpan',
                 *  'msg' = 'Message'
                 * ]
                 * [
                 *  'location' = '#nameSpan',
                 *  'msg' = 'Message'
                 * ]
                 * ]
                 */
                //data.foreach(index) {
                //    $(data[index]['location']).html(data[index]['msg']);
                //}
                console.log(data);
                console.log(JSON.parse(data)[0]['location']);
                //Below may need to be updated. I may not be grabbing the isValidForm
                //correctly 
                
                if (data.includes('isValidForm')){
                    $("#formStep1").hide(1000);
                    $("#formStep2").show(1000);
                    $("#circle1").hide();
                    $("#circle2").show();
                    $("#dot1").show();
                    $("#dot2").hide(); 
                    //return;
                }
                //loop through data, get location, change the message 
                //$('#phoneSpan').val('Phone Number is required');
                var phone = JSON.parse(data)[0]['location'];
                $(phone).html(JSON.parse(data)[0]['msg']);
                $(data[0]['location']).html(data[0]['msg']);

            }
        });

        //Hide current circle-dot, show next
        // $("#circle1").hide();
        // $("#circle2").show();
        // $("#dot1").show();
        // $("#dot2").hide();
    }

    if (num == 2) {
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
    //this row is the parent of the parent (icon -> td -> tr)
    thisRow = $(ele).parent().parent();
    //Use jquery to find the closest tr. Next is a spacer need to do it twice.
    nextRow = $(thisRow).closest('tr').next('tr').next('tr');

    if ($(nextRow).attr('class').valueOf() === 'collapsed'){
        console.log("here");
        $(ele).removeClass("fa-chevron-circle-down").addClass("fa-chevron-circle-up");
        $(nextRow).removeClass('collapsed').addClass('un-collapsed').show();
    }

    else if ($(nextRow).attr('class').valueOf() === 'un-collapsed'){
        $(ele).removeClass("fa-chevron-circle-up").addClass("fa-chevron-circle-down");
        $(nextRow).removeClass('un-collapsed').addClass('collapsed').hide();
    }
}

function dropDownModify(ele,page){
    //this row is the parent of the parent (icon -> td -> tr)
    thisRow = $(ele).parent().parent();
    //Use jquery to find the closest tr. Next is a spacer need to do it twice.
    nextRow = $(thisRow).closest('tr').next('tr').next('tr');

    //only toggle the next row if it needs to be
    if($(nextRow).attr('class').valueOf() === 'collapsed'){
        //Need to pass in the circle element instead of the pencil so it gets changed
        dropDownToggle($(ele).parent().parent().find('i')[0]);
    }
    //switch to the save button
    if (page == 'emp') {
        $('#empEditButton').hide();
        $('#empSaveEditButton').show();
    }
    if (page == 'noti') {
        $('#notiEditButton').hide();
        $('#notiSaveEditButton').show();
    }

    //find all of the disabled inputs and enable them
    //$(thisRow).find(':disabled').each().attr('disabled',false);
    $(thisRow).find(':disabled').each(function(i,ele){
        $(ele).attr('disabled', false);
    });

    $(nextRow).find(':disabled').each(function(i,ele){
        $(ele).attr('disabled', false);
    });

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
