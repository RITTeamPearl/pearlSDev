function ajaxUpdate(userID,ele) {
    //this row is the parent of the parent of the parent (icon -> button -> td -> tr)
    var thisRow = $(ele).parent().parent().parent();
    //Use jquery to find the closest tr. Next is a spacer need to do it twice.
    var nextRow = $(thisRow).closest('tr').next('tr').next('tr');
    //Get the first name from the input in the top row
    var fName = $(thisRow).find("input[name='fName']").val();
    //Get the last name from the input in the top row
    var lName = $(thisRow).find("input[name='lName']").val();

    var email = $(nextRow).find("input[name='email']").val();

    var phone = $(nextRow).find("input[name='phone']").val();

    var activeYN = $(nextRow).find("select[name='activeYN'] option:selected").val();

    var deptID = $(nextRow).find("select[name='deptID'] option:selected").val();

    var authID = $(nextRow).find("select[name='authID'] option:selected").val();
    $.ajax({
        type: "POST",
        url: 'csvAjax.php',
        data: {
            userID:userID,
            fName: fName,
            lName: lName,
            email:email,
            phone:phone,
            activeYN: activeYN,
            deptID: deptID,
            authID: authID
        }
    }).done(function(){
        $(thisRow).find("input").attr("disabled", true);
        $(nextRow).find("input").attr("disabled", true);
        $(nextRow).find("select").attr("disabled", true);
        $(thisRow).find('#empEditButton').show();
        $(thisRow).find('#empSaveEditButton').hide();
    }).fail(function(data){
        console.log("fail");
    });
}

function ajaxDelete(userID) {
    $.ajax({
        type:"POST",
        url: 'csvAjax.php',
        data: {userID:userID}
    }).done(function(){
        console.log("it works");
    }).fail(function(){
        console.log("fail");
    });
}
