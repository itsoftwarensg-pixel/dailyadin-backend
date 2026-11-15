//ajax loader by class
$(document).ready(function () {
    $(document).ajaxStart(function () {
        $(".ajaxLoader").fadeIn('fast');
        Pace.restart();
    });
    $(document).ajaxComplete(function () {
        $(".ajaxLoader").fadeOut('fast');
    });
});

$(document).ready(function (e) {

    var base_url = $("#base_url").html();

    $("#frm_create_user").on('submit', (function (e) {
        e.preventDefault();
        $.ajax({
            url: base_url + "add_user",       // Url to which the request is send
            type: "POST",                   // Type of request to be send, called as method
            data: new FormData(this),      // Data sent to server, a set of key/value pairs representing form fields and values
            contentType: false,             // The content type used when sending data to the server. Default is: "application/x-www-form-urlencoded"
            cache: false,                   // To unable request pages to be cached
            processData: false,              // To send DOMDocument or non processed data file it is set to false (i.e. data should not be in the form of string)
            success: function (data)         // A function to be called if request succeeds
            {
                //message
                flashMesage(data);
            }
        });
    }));
});

//get getSubCatList
function getSubCatList(category_name) {
    var admin_url = $("#admin_url").html();
    $.post(admin_url + "content/english/getSubCatList.php", { category_name: category_name.value }, function (rtm) {
        var option = '<option value="1">NONE</option>';
        if (rtm.length > 0) {
            for (var i = 0; i < rtm.length; i++) {
                option += '<option value="' + rtm[i]['id'] + '">' + rtm[i]['name'] + '</option>';
            };
            $("#cboSubCategoryID").html('');
            $("#cboSubCategoryID").html(option);
        } else {
            // $("#subCategory").html('<option value="">No Category Found</option>');
            $("#cboSubCategoryID").html('<option value="1">NONE</option>');
        }
    }, 'json');
}

//get getSubCatList Bangla
function getSubCatListBN(category_name) {
    var admin_url = $("#admin_url").html();
    $.post(admin_url + "content/bangla/getSubCatList.php", { category_name: category_name.value }, function (rtm) {
        var option = '<option value="1">NONE</option>';
        if (rtm.length > 0) {
            for (var i = 0; i < rtm.length; i++) {
                option += '<option value="' + rtm[i]['id'] + '">' + rtm[i]['name'] + '</option>';
            };
            $("#cboSubCategoryID").html('');
            $("#cboSubCategoryID").html(option);
        } else {
            // $("#subCategory").html('<option value="">No Category Found</option>');
            $("#cboSubCategoryID").html('<option value="1">NONE</option>');
        }
    }, 'json');
}

// image preview
function getPreview(objectName, previewNameViewer, fileNameViewer) {
    if (fileNameViewer != "none" || fileNameViewer != 'none' || fileNameViewer !== "none" || fileNameViewer !== 'none') {
        //send name
        var name = document.getElementById(objectName).files[0].name;
        if (name != null || name != "" || name != '' || name !== "" || name !== '') {
            document.getElementById(fileNameViewer).value = name;
        } else {
            console.log('Select a photo');
        };
    };
    // get selected file element
    var oFile = document.getElementById(objectName).files[0];
    // get preview element
    var oImage = document.getElementById(previewNameViewer);
    // prepare HTML5 FileReader
    var oReader = new FileReader();
    oReader.onload = function (e) {
        // e.target.result contains the DataURL which we will use as a source of the image
        oImage.src = e.target.result;
    };
    // read selected file as DataURL
    oReader.readAsDataURL(oFile);
}
// image preview
function getPreviewAndSizeValidation(path, previewNameViewer, maxSize, defaultImage='') {
    const maxImgSize = 1024 * maxSize;
    var oImage = document.getElementById(previewNameViewer);
    const placeholderImage = defaultImage ? defaultImage : '';

    if (path.files && path.files[0]) {
        var oFile = path.files[0];
        if (oFile.size <= maxImgSize) {
          // document.getElementById("image_size_validation").innerHTML = '';

            var oReader = new FileReader();
            oReader.onload = function (e) {
                oImage.src = e.target.result;
            };
            // read selected file as DataURL
            oReader.readAsDataURL(oFile);
        }
        else {
            // document.getElementById("image_size_validation").innerHTML = 'Image must be smaller than 250KB'
            alert('The file size exceeds the max size');
            path.value = ''; // Clear the file input
            oImage.src = placeholderImage;
        }
    };
}
