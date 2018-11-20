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

    console.log(formData);

    //checks it screen 1 of the create acct form is valid before going to the next screen
    if (num == 1) {
        formData.push(['pageNumber', num]);
        console.log('starting ajax request');

        $.ajax({
            type: "POST",
            url: '../business/business_layer.php',
            data: {
                action: 'validateForm',
                formSection: 'screen1',
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
                //console.log(JSON.parse(data));
                //Below may need to be updated. I may not be grabbing the isValidForm
                //correctly 

                if (data.includes('isValidForm')) {
                    //check to make sure names are valid
                    $("#formStep1").hide(1000);
                    $("#formStep2").show(1000);
                    //Hide current circle-dot, show next
                    $("#circle1").hide();
                    $("#circle2").show();
                    $("#dot1").show();
                    $("#dot2").hide();
                    //return;
                }
                //lines below prints the error message to the page
                //loop through data, get location, change the message 
                //$('#phoneSpan').val('Phone Number is required');

                //below 2 lines work (manual)
                //var phone = JSON.parse(data)[0]['location'];
                //$(phone).html(JSON.parse(data)[0]['msg']);

                //below line not working
                //$(data[0]['location']).html(data[0]['msg']);

                //dynamically shows data on page. Works!
                $.each(JSON.parse(data), function (i) {
                    var info = JSON.parse(data)[i]['location']
                    $(info).html(JSON.parse(data)[i]['msg'])
                });
            }
        });//end of ajax call for screen 1
    }//end of checks for screen 1

    //checks it screen 2 of the create acct form is valid before going to the next screen
    if (num == 2) {
        formData.push(['pageNumber', num]);
        console.log('starting ajax request');

        $.ajax({
            type: "POST",
            url: '../business/business_layer.php',
            data: {
                action: 'validateForm',
                formSection: 'screen2',
                formData: JSON.stringify(formData)
            },
            success: function (data) {
                console.log(data);
                //console.log(JSON.parse(data));

                if (data.includes('isValidForm')) {
                    //check to make sure names are valid
                    $("#formStep2").hide(1000);
                    $("#formStep3").show(1000);

                    //Hide current circle-dot, show next
                    $("#circle2").hide();
                    $("#circle3").show();
                    $("#dot2").show();
                    $("#dot3").hide();
                    //return;
                }
                //lines below prints the error message to the page
                //dynamically shows data on page.
                $.each(JSON.parse(data), function (i) {
                    var info = JSON.parse(data)[i]['location']
                    $(info).html(JSON.parse(data)[i]['msg'])
                });
            }
        });//end of ajax call for screen 2
    }//end of checks for screen 2

    if (num == 3) {
        formData.push(['pageNumber', num]);
        console.log('starting ajax request');

        $.ajax({
            type: "POST",
            url: '../business/business_layer.php',
            data: {
                action: 'validateForm',
                formSection: 'screen3',
                formData: JSON.stringify(formData)
            },
            success: function (data) {
                //console.log(data);
                console.log(JSON.parse(data));

                if (data.includes('isValidForm')) {

                    $("createAcctForm").submit(function (event) {
                        if (!($("email").val()) == "" && !($("deptID").val()) == "") {
                            $("span").text("Account Request has been sent!").show();
                            return;
                        }

                        //$("span").text("Not valid!").show().fadeOut(1000);
                        event.preventDefault();
                    });
                    //document.createAcctForm.submit();
                    //check to make sure names are valid
                    // $("#formStep2").hide(1000);
                    // $("#formStep3").show(1000);

                    // //Hide current circle-dot, show next
                    // $("#circle2").hide();
                    // $("#circle3").show();
                    // $("#dot2").show();
                    // $("#dot3").hide();
                    //return;
                }
                //lines below prints the error message to the page
                //dynamically shows data on page.
                $.each(JSON.parse(data), function (i) {
                    var info = JSON.parse(data)[i]['location']
                    $(info).html(JSON.parse(data)[i]['msg'])
                });
            }
        });//end of ajax call for screen 3
    }//end of checks for screen 3
}//end of nextStepTwo...it works

function confirmPassword() {
    if ($('#password').val() == $('#passwordConfirm').val()) {
        $(".pwIcon").css('color', 'green');
        return true;
    }
    else {
        $('.pwIcon').css('color', 'red');
        return false;
    }
}

function addMask() {
    $("#phoneNumber").mask("000-000-0000");//.addClass('className');
}

function dropDownToggle(ele) {
    //this row is the parent of the parent (icon -> td -> tr)
    thisRow = $(ele).parent().parent();
    //Use jquery to find the closest tr. Next is a spacer need to do it twice.
    nextRow = $(thisRow).closest('tr').next('tr').next('tr');

    if ($(nextRow).attr('class').valueOf() === 'collapsed') {
        console.log("here");
        $(ele).removeClass("fa-chevron-circle-down").addClass("fa-chevron-circle-up");
        $(nextRow).removeClass('collapsed').addClass('un-collapsed').show();
    }

    else if ($(nextRow).attr('class').valueOf() === 'un-collapsed') {
        $(ele).removeClass("fa-chevron-circle-up").addClass("fa-chevron-circle-down");
        $(nextRow).removeClass('un-collapsed').addClass('collapsed').hide();
    }

    //resizeTextArea(thisRow.find('#bodyContent'));
}

function dropDownModify(ele, page) {
    //this row is the parent of the parent (icon -> td -> tr)
    thisRow = $(ele).parent().parent();
    //Use jquery to find the closest tr. Next is a spacer need to do it twice.
    nextRow = $(thisRow).closest('tr').next('tr').next('tr');

    //only toggle the next row if it needs to be
    if ($(nextRow).attr('class').valueOf() === 'collapsed') {
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
    $(thisRow).find(':disabled').each(function (i, ele) {
        $(ele).attr('disabled', false);
    });

    $(nextRow).find(':disabled').each(function (i, ele) {
        $(ele).attr('disabled', false);
    });

}

function updateAdminView(ele) {
    var whichButton = $(ele).attr("id").valueOf().split("_")[0];
    //find current active and remove it
    $(".active").removeClass("active");
    //add it to the clicked button
    $(ele).addClass("active");

    //show news hide others
    if (whichButton === "news") {
        $("#employees").hide();
        $("#pending").hide();
        $("#news").show();
        $("#compare").hide();
    }

    //show employee hide others
    if (whichButton === "employee") {
        $("#pending").hide();
        $("#news").hide();
        $("#employees").show();
        $("#compare").hide();
    }

    //show pending hide others
    if (whichButton === "pending") {
        $("#news").hide();
        $("#employees").hide();
        $("#pending").show();
        $("#compare").hide();
    }

    //show compare hide others
    if (whichButton === "compare") {
        $("#news").hide();
        $("#employees").hide();
        $("#pending").hide();
        $("#compare").show();
    }
}

//Resizes the text area to fit content
function resizeTextArea(id) {

    console.log(id);
    //Get the JQuery element of the JavaScript Element
    var textArea = $('#' + String(id.id));
    console.log(textArea);
    console.log(textArea[0].scrollHeight);

    //Set the DOM element styling height to match the height of the ScrollHeight
    textArea.attr('style', 'height:' + id.scrollHeight + 'px');
}

//Handles file upload in Admin Console
function initCsvListener() {
    //make button open file upload
    $("#csvFileUploadButton").click(function () {
        $('#fileUpload').trigger('click');
    });

    //update view to show selected file like file input
    $("#fileUpload").on('change', function () {
        var val = $(this).val().split('\\').pop();//get the last one (file name)
        if (val.length > 0) {
            $(this).siblings('span').text(val);
        } else {
            $(this).siblings('span').text('No file selected');
        }
    });
}

function setNavBar() {
    var numNotifications = (($("#news").find('tr')).length - 4) / 4;
    $("#news_Button").html('News(' + numNotifications + ")");
    $("#news").find(".pagination").find('div.number').html(numNotifications ? ("1-" + numNotifications + " out of " + numNotifications) : ("None"));

    var numEmps = (($("#employees").find('tr')).length - 4) / 3;
    $("#employee_Button").html('Employees(' + numEmps + ")");
    $("#employees").find(".pagination").find('div.number').html(numEmps ? ("1-" + numEmps + " out of " + numEmps) : ("None"));


    var numPendEmps = (($("#pending").find('tr')).length - 1) / 4;
    $("#pending_Button").html('Pending(' + numPendEmps + ")");
    $("#pending").find(".pagination").find('div.number').html(numPendEmps ? ("1-" + numPendEmps + " out of " + numPendEmps) : ("None"));


    if (screen.width < 700) {
        $("#compare_Button").hide();
    }
    else {
        $("#compare_Button").show();
    }
    var url = window.location.href;
    if (url.indexOf('#') > -1) {
        var page = url.split("#").pop();
        if (page == "e") {
            $("#employee_Button").trigger('onclick');
        }
        else if (page == "p") {
            $("#pending_Button").trigger('onclick');
        }
        else if (page == "c") {
            $("#compare_Button").trigger('onclick');
        }
        else if (page == "n") {
            $("#news_Button").trigger('onclick');
        }

    }
}
