

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
        $(ele).removeClass("fa-chevron-circle-up");
        $(ele).addClass("fa-chevron-circle-down");

        var rowNum = $(ele).parent().parent().attr("id");
        rowNum = parseInt(rowNum.split("-")[1]);
        nextRow = "#row-" + parseInt(rowNum+1);
        $(nextRow).show();
    }
    if (eleClass === "down") {
        $(ele).removeClass("fa-chevron-circle-down");
        $(ele).addClass("fa-chevron-circle-up");

        var rowNum = $(ele).parent().parent().attr("id");
        rowNum = parseInt(rowNum.split("-")[1]);
        nextRow = "#row-" + parseInt(rowNum+1);
        $(nextRow).hide();

    }
    else {

    }
    //word == up means it was closed now its being opened
        //change chevron to down
        //get next table row (will have class of un-collapsed with data for the clicked header)
        //change to display = true. (roll down animation?fade over time?)
    //word == down means it was open and now its being closed
        //change chevron to up
        //Get next table rows
        //change to display = false. (roll up animation? fade over time?)






    // <tr id= 'row#' class='collapsed'>
    //     <td><i class='fas fa-chevron-circle-down'></i></td> <!-- Onclick this icon needs to be updated to fas fa-chevron-circle-up -->
    //     <td>Heavy Rain to delay bla bla bla bla bla</td>
    //     <td>Yes</td>
    //     <td><i class='fas fa-pencil-alt'></i></td>
    //     <td><i class='fas fa-trash-alt'></i></td>
    // </tr>
    //
    // <tr class='spacer'><td></td></tr>
    //
    // <!-- Row that is hidden in collapsed row, needs JS to unhide this https://codepen.io/andornagy/pen/gaGBZz -->
    //
    // <tr id= 'row#'>
    //     <td colspan='5' class='un-collapsed'>
    //     <h2>Body</h2>
    //     <p>Lorem ipsum dolor sit amet, consecteur adiposing elit. Sed autor ligula quis ante pretium lacreet.Nuno semper erat dignissim placerate feugiat.
    //     Aenean commodo risus consequeat ligula aliquet portior. Proin turpis vitae commodo mattis, massa felis accumsan.</p>
    //
    //     <h2>Attachment</h2>
    //     <p>document.pdf</p><i></i>
    //
    //     <h2>User Acknowledgements Report</h2>
    //     <p>user_report.csv</p><i></i>
    //     </td>
    // </tr>

}
