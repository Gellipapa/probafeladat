$(document).ready(function () {
    $("#createButton").click(function (e) {
        e.preventDefault();
        var title = $("#title").val()
        var description = $("#description").val()
        var status = $('#statusSelect').find(":selected").val();
        var contactName = $("#contactName").val()
        var contactEmail = $("#contactEmail").val()
        
        if (checkInputEmpty(title,description,status,contactName,contactEmail)) {
            $.ajax('create.php', {
                type: 'POST',
                data: {
                    title: title,
                    description: description,
                    status: status,
                    contactName: contactName,
                    contactEmail: contactEmail
                },
                success: function (data, status, xhr) {
                    console.log(data)
                    if (data) {
                        console.log("OK")
                            $("#alertContainer").css("width", "50%");
                            $("#alertContainer").css("margin", "0 auto");
                            $("#alertContainer").append(`<div class="alert alert-success" role="alert">
                            Sikeres volt az adatbevitel!</div>`);
                            setTimeout(function () { window.location.replace("index.php");}, 100);
                        
                    } else {
                        console.log("NEM OK")
                    }
                },
                error: function (jqXhr, textStatus, errorMessage) {
                console.log(errorMessage)
                }
            });
        } else {
            $("#alertContainer").css("width", "50%");
            $("#alertContainer").css("margin", "0 auto");
            $("#alertContainer").append(`<div class="alert alert-danger" role="alert">
                Minden mező kitöltése kötelező!</div>`);
        }

    });

    function checkInputEmpty(title, description, status, contactName, contactEmail) {
        if (title == "" || title == null) {
            return false
        }
        if (description == "" || description == null) {
            return false
        }
        if (status == "default") {
            return false
        }
        if (contactName == "" || contactName == null) {
            return false
        }
        if (contactEmail == "" || contactEmail == null) {
            return false
        }
        if (!validateEmail(contactEmail)) {
            return false;
        }

        return true
    }

    function validateEmail($email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        return emailReg.test( $email );
    }

    $("#updateButton").click(function (e) {
        e.preventDefault();
        var title = $("#updateTitle").val()
        var description = $("#updateDescription").val()
        var status = $('#statusSelect').find(":selected").val();
        var contactName = $("#updateContactName").val();
        var contactEmail = $("#updateContactEmail").val();
        var updateID =  $('#updateID').val()
        var ownerID = $("#ownerID").val();
        
        if (checkInputEmpty(title,description,status,contactName,contactEmail)) {
            $.ajax('update.php', {
                type: 'POST',
                data: {
                    update: 1,
                    title: title,
                    ownerID: ownerID,
                    updateID: updateID,
                    description: description,
                    status: status,
                    contactName: contactName,
                    contactEmail: contactEmail
                },
                success: function (data, status, xhr) {
                    console.log(data)
                    if (data) {
                        console.log("OK")
                        $("#alertContainer").css("width", "50%");
                        $("#alertContainer").css("margin", "0 auto");
                        $("#alertContainer").append(`<div class="alert alert-success" role="alert">
                    Sikeres volt a módosítás!</div>`);
                        setTimeout(function () { window.location.replace("index.php"); }, 100);
                    } else {
                        console.log("NEM OK")
                    }
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    console.log(errorMessage)
                }
            });
        } else {
            $("#alertContainer").css("width", "50%");
            $("#alertContainer").css("margin", "0 auto");
            $("#alertContainer").append(`<div class="alert alert-danger" role="alert">
            Minden mező kitöltése kötelező vagy helytelen az email cím!</div>`);
        }


    });

    $(".deleteButton").click(function (e) {
        e.preventDefault();
        var ID = $(this).data("id");
        var owID = $(this).data("ownerid");

        $.ajax('delete.php', {
            type: 'POST',
            data: {
                ownerID: owID,
                removeID: ID,
            },
            success: function (data, status, xhr) {
                
                if (data) {
                    console.log(data)
                    console.log("OK")
                    $("#alertContainer").css("width", "50%");
                    $("#alertContainer").css("margin", "0 auto");
                    $("#alertContainer").append(`<div class="alert alert-success" role="alert">
                    Sikeres volt a törlés!</div>`);
                    setTimeout(function () { window.location.replace("index.php");}, 100);
                } else {
                    console.log("NEM OK")
                }
            },
            error: function (jqXhr, textStatus, errorMessage) {
                console.log(errorMessage)
            }
        });
        

    });

    $("#filterButton").click(function (e) {
        e.preventDefault();
        var stid = $("#fSelect option:selected").data('stid');

        $.ajax('index.php', {
            type: 'POST',
            data: {
                statusID: stid,
            },
            success: function (data, status, xhr) {
                
                if (data) {
                    console.log(data)
                    console.log("OK")
                    $("#alertContainer").css("width", "50%");
                    $("#alertContainer").css("margin", "0 auto");
                    $("#alertContainer").append(`<div class="alert alert-danger" role="alert">
                    Jelenleg nem müködik!</div>`);
                    // setTimeout(function () { window.location.replace("index.php");}, 100);
                } else {
                    console.log("NEM OK")
                }
            },
            error: function (jqXhr, textStatus, errorMessage) {
                console.log(errorMessage)
            }
        });

    });

})