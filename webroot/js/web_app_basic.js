document.addEventListener('DOMContentLoaded', function () {
    //Handle multi language
    $('#option_language').change(function() {
        $.ajax({
            url : location.protocol + "//" + document.domain + '/Twitter/change/' + $('#option_language').val(),
            success: function(res) {
                location.reload();
            }
        });
    });

    //Setup link btn_new_post
    $('#btn_page_post').attr('href', location.protocol + '//' + document.domain + '/Twitter_Post' );
    $('#btn_page_post_face').attr('href', location.protocol + '//' + document.domain + '/Facebook_Post' );

    //Handle Data From and To
    var dateFormat = "mm/dd/yy",
    from = $("#from").datepicker({
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 2
    }).on("change", function() {
        to.datepicker("option", "minDate", getDate(this));
    }),

    to = $("#to").datepicker({
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 2
    }).on("change", function() {
        from.datepicker("option", "maxDate", getDate(this));
    });

    function getDate(element) {
        var date;
        try {
            date = $.datepicker.parseDate( dateFormat, element.value );
        } catch( error ) {
            date = null;
        }
      return date;
    }
});

//function call ajax when request download CSV file
function Download($root) {
    if ($root == 'face') {
        urlGetDataDow = '/Facebook/ajaxGetDataDown';
        urlDownData = '/Facebook/ajaxDownData';
    } else {
        urlGetDataDow = '/Twitter/ajaxGetDataDown';
        urlDownData = '/Twitter/ajaxDownData'
    }
    $.ajax({
        url : location.protocol + '//' + document.domain + urlGetDataDow,
        type: "POST",
        data: {
            dateFrom: $('#from').val(),
            dateTo:  $('#to').val()
        },
        success: function(res) {
            if (res === 'false') {
                alert("Data empty to download.");
            } else {
                window.location = location.protocol + '//' + document.domain + urlDownData;
            }
        }
    });
}

//function call ajax when request Delete a Item 
function delItem($obj, $id_item, $root) {
    if ($root == 'face') {
        urlrun = "/Facebook/ajaxDelFeed/";
    } else {
        urlrun = "/Twitter/ajaxDelStatus/";
    }

    $total_item = $('.body_table_view').attr('total_item');
    if (confirm("Are you sure you want to delete this item?")) {
        $.ajax({
            url : location.protocol + "//" + document.domain + urlrun + $id_item,
            success: function(res) {
                if (res === 'true') {
                    $($obj).parent().parent().remove();
                    $('.body_table_view').attr('total_item' , $total_item - 1 );

                    if ($('.body_table_view').attr('total_item') == 0)  {
                        $('<tr>').append($('<td>').attr({
                            colspan : 6,
                            align : 'center'
                        }).append('No data')).appendTo($('.body_table_view'));
                    }
                } else {
                    alert("Have a some error!");
                }
            }
        });
    }
}

//function call ajax when request Post a Item by API
function postItem($root) {
    var flag = 0;
    var file = null;
    var formData = new FormData();
    if ($root == 'face') {
        urlPost = '/Facebook/ajaxPostFace';
    } else {
        urlPost = '/Twitter/ajaxPostTwet';
    }

    if ($('.file-img-uploader').val() != '') {
        file = $('#file')[0].files[0];
        flag = 1;
        formData.append('image', file);
    }

    if (($('.file-img-uploader').val() != '') || ($('#cap').val()) != '') {
        formData.append('cap', $('#cap').val());
        formData.append('flag', flag);
        $.ajax({
            url: location.protocol + "//" + document.domain + urlPost,
            data: formData,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function(res) {
                if (res === 'true') { 
                   $('#shortModal').modal('show');
                } else {
                   $('#hightModal').modal('show');
                }
            }
        });
    } else {
        alert('Date Post empty!');
    }
}

//Methor show Image to layout
var openFile = function(file) {
    var input = file.target;
    var reader = new FileReader();
    reader.onload = function(){
      var dataURL = reader.result;
      var output = document.getElementById('output');
      output.src = dataURL;
    };
    reader.readAsDataURL(input.files[0]);
  };

//Methor count number text for Textarea
function countChar(val) {
    var len = val.value.length;
    $('#charNum').text(len);
};
