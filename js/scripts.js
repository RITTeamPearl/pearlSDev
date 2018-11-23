function nextStep(num) {
    //first check to make sure (with javascript) that all fields are valid
    if (num == 1) {
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
    $("#phone").mask("000-000-0000");//.addClass('className');
    $(".phoneMask").mask("000-000-0000");//.addClass('className');
}

/**
 * used for showing second buttons when admin clicks edit button for notification on news page
 */
function displayOptions(ele){
    $(ele).parent().find(".buttonOptions").toggle();
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

    //resizeTextArea(thisRow.find('#bodyContent'));
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
    if (page == 'noti') {
        $(thisRow).find('#notiEditButton').hide();
        $(thisRow).find('#notiSaveEditButton').show();
    }
    if (page == 'emp') {
        $(thisRow).find('#empEditButton').hide();
        $(thisRow).find('#empSaveEditButton').show();
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

//Resizes the text area to fit content
function resizeTextArea(id) {

    console.log(id);
    //Get the JQuery element of the JavaScript Element
    var textArea = $('#'+String(id.id));
    console.log(textArea);
    console.log(textArea[0].scrollHeight);

    //Set the DOM element styling height to match the height of the ScrollHeight
    textArea.attr('style', 'height:' + id.scrollHeight + 'px');
}

//Handles file upload in Admin Console
function initCsvListener() {
    //make button open file upload
    $("#csvFileUploadButton").click(function() {
        $('#fileUpload').trigger('click');
    });

    //update view to show selected file like file input
    $("#fileUpload").on('change', function() {
        var val = $(this).val().split('\\').pop();//get the last one (file name)
        if(val.length > 0) {
           $(this).siblings('span').text(val);
        } else {
            $(this).siblings('span').text('No file selected');
        }
    });
}

function addNewVid(){
    //when this is clicked display the box to add a new video
    $('.addVideo').show();
}

function updateVid(videoID){
    //first find the edit form and append the videoID to the action
    $('.editVideo').find('form').attr('action',function(i,currVal){
        return currVal + videoID;
    });
    $('.editVideo').show();
}

function deleteVid(videoID){
    $("#delform_"+videoID).submit();
}
