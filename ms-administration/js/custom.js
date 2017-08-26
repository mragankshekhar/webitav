function include(scriptUrl) {
    $.getScript(scriptUrl);
}

function printMe(type, id) {
    $.get("print/index.php?type=" + type + "&" + id, function (contents) {
        var styles = "<script type='test/javascript' src='" + PATH_ADMIN + "js/jquery.min.js'><script type='test/javascript' src='" + PATH_ADMIN + "js/custom.js'></script><style>button{display:none}</style><link href='" + PATH_ADMIN + "css/print.css' rel='stylesheet'><link href='" + PATH_ADMIN + "css/bootstrap.min.css' rel='stylesheet'>";
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({"position": "absolute", "top": "-1000000px"});
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html><head><title></title></head><body>');
        //Append the external CSS file.
        frameDoc.document.write(styles);
        //Append the DIV contents.
        frameDoc.document.write(contents);
        frameDoc.document.write('</body></html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);
    })
}
window.fbAsyncInit = function() {
	FB.init({
		appId: '545856762164387',
		cookie: true,
		xfbml: true,
		oauth: true
	});
};
(function(d){
  var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
  js = d.createElement('script'); js.id = id; js.async = true;
  js.src = "//connect.facebook.net/en_US/all.js";
  d.getElementsByTagName('head')[0].appendChild(js);
 }(document));

//FB.init({appId: "545856762164387", status: true, cookie: true});
function postToFeed(description,message,link,image,name) {
	// calling the API ...
	FB.api('/548262958576601/feed', 'post', 
		  { 
			  // for the newer versions of the Facebook API you need to add the access token
			  access_token: 'EAACEdEose0cBAOMzYfUe2ZCa21PliMRmR9YXIZBy9p90sLF0ZAb9qVxrPwpC9I4vr3S5adWOgF474U7CxJfaPQSCdsfobrncsCXWNOfmlQzmJZBSDvCZAKT5bJHIZCU5UqeRm8rPBY8Tfyfhw2sfZA8jlIi146jKvXJuFyeX29r2Ii9JEHIjbzB9jjGVbBZB0REZD',
			  message     : message,
			  link        : link,
			  picture     : image,
			  name        : name,
			  to: '548262958576601',
			  from: '548262958576601',
			  description : description
	  }, 
	  function(response) {

		  if (!response || response.error) {
			  alert(JSON.stringify(response.error));
		  } else {
			  console.log('Post ID: ' + response.id);
		  }
	  });
}
function pushNotification(message, image, url){
	$.post(URL_ROOT+"ajax/ajax.php",{type:"send_notification",body:message,image:image,url:url},function(data){
		$.gritter.add({
			title: 'Opration Successfull ',
			text: "Notification has been send to "+dta+" users"
		});
	})
}
$(document).ready(function () {
    //fetchNotification();
    var progressbar = $('#progressbar');
    var statustxt = $('#progresstext');
    var submitbutton = $(".submit");
    var myform = $(".form_ajax");
    var title = document.title;
    var progressDiv = $("#progressDiv");
    var completed = '0%';
    $(myform).ajaxForm({
        beforeSend: function () { //brfore sending form
            submitbutton.attr('disabled', ''); // disable upload button
            progressDiv.show();
            document.title = " Processing Please wait..."
            progressbar.width(completed);
            var unique_id = $.gritter.add({
                // (string | mandatory) the heading of the notification
                title: 'Please wait !',
                // (string | mandatory) the text inside the notification
                text: '<div class="progress progress-striped active"  id="progressDiv"><div class="progress-bar"  role="progressbar" aria-valuemin="0" id="progress" aria-valuemax="100" style="width:0%"><span class="sr-only" id="progresstext">100% Complete</span> </div></div>',
                // (string | optional) the image to display on the left
                // (bool | optional) if you want it to fade out on its own or just sit there
                sticky: true,
                // (string | optional) the class name you want to apply to that specific message
                class_name: 'my-sticky-class'
            });
        },
        uploadProgress: function (event, position, total, percentComplete) { //on progress
            $("#progress").width(percentComplete + '%');//update progressbar percent complete
            //$(".statustxt").html(percentComplete + '% completed'); //update status text
            //addNotice(percentComplete+ "% Completed...", "fa fa-refresh", "");
            document.title = percentComplete + "% Completed..."
            //submitbutton.html(percentComplete+' % Processing...');
        },
        complete: function (response) { // on complete
            //x$.gritter.removeAll();
            document.title = title;
            submitbutton.removeAttr('disabled'); //enable submit button
            progressDiv.slideUp(); // hide progressbar
			//success==>msg==>asdasdaa dasd asdjas 
			//success==>notify==>description goes here===>message==>http://www.mssinfotech.in/whispersinthecorridors/#121==>http://www.mssinfotech.in/whispersinthecorridors/uploads/media/logo.GIF==>whispersinthecorridors==>redirecturl
            var data = (response.responseText).split("==>");
            if ($.trim(data[0]) == "success" || data[0] == "success" || data[0].indexOf('success') > -1) {
				if($.trim(data[1]) == "msg" || data[1] == "msg" || data[1].indexOf('msg') > -1){
					progressDiv.hide();
					$.gritter.add({
						title: 'Opration Successfull ',
						text: data[2]
					});
				}else if($.trim(data[1]) == "notify" || data[1] == "notify" || data[1].indexOf('notify') > -1){
					progressDiv.hide();
					postToFeed(data[2],data[3],data[4],data[5],data[6])
					//pushNotification(uto, message, image, url)
					$.gritter.add({
						title: 'Opration Successfull ',
						text: data[2]
					});
					window.location = data[7];
				}else{
                	window.location = data[1];
				}
            } else {
                progressDiv.hide();
                $.gritter.add({
                    title: 'Check Required Field ',
                    text: data[1]
                });
            }
            setTimeout(function () {
                $.gritter.removeAll();
            }, 5000);
        }
    });


    $('.datepicker').datepicker()
    if ($("select").length > 0)
        $("select").chosen();
    // Example animations for Header Dropdown Menus - For Demo Purposes
    $('.header-btns > div').on('show.bs.dropdown', function () {
        $(this).children('.dropdown-menu').addClass('animated animated-short flipInY');
    });
    $('.header-btns > div').on('hide.bs.dropdown', function () {
        $(this).children('.dropdown-menu').removeClass('animated flipInY');
    });
    // Init Datatables
    if ($('#datatable').length > 0) {
        ViewAll();
    }
    if ($('#mssresulttable').length > 0) {
        ViewAllRecord();
        var exportMe = "";
        var data_export = $('#mssresulttable').attr("data-export");

        if (typeof data_export != 'undefined')
            exportMe = "<i onclick='$(\"#mssresulttable\").tableExport({type:\"excel\",ignoreColumn: \"" + data_export + "\",filename: \"table\",escape:\"false\"});' class='fa fa-download'></i>";
        include(PATH_ADMIN + "js/tableExport.js");
        include(PATH_ADMIN + "js/jquery.base64.js")
        $(".searchData").html("<div class='row'><div class='col-md-4'>Show Record <select  class='form-control' onchange='$(\"#endV\").val($(this).val()); ViewAllRecord()'><option>10</option><option selected>30</option><option>50</option><option>100</option><option>All</option></select></div><div class='col-md-4 text-center'><div class='mini-action-icons margin-right-sm'> <i onclick='window.history.go(-1); return false;' class='fa fa-arrow-circle-left'></i> <i onclick='ViewAllRecord()' class='fa fa-refresh'></i> <i onclick='fullPage()' class='fa fa-arrows-alt'></i> " + exportMe + " </div></div><div class='col-md-4 text-right'>Search <input id='searchinput' class='form-control' type='search' placeholder='Search.... ' onkeyup='ViewAllRecord()'></div></div>");
    }
});
function applyfilter() {
    var dataextra=$("#mssresulttable").attr("data-extra");
    var QStringUrl = [];
    $(".searchFilter").each(function (index, element) {
        var key = $(this).attr("name");
        var val = $(this).val()
        if (val != "" && typeof val != 'undefined') {
            var strs = key + "=" + encodeURI(val);
            QStringUrl.push(strs);
        }
    });
	if(dataextra!="")
    $("#mssresulttable").attr("data-extra", dataextra+"&"+QStringUrl.join("&"));
	else
	$("#mssresulttable").attr("data-extra", QStringUrl.join("&"));
    ViewAllRecord()
}
function loadControls() {
    loadtooltip()
    if ($('.tooltip-demo').length > 0) {
        $('.tooltip-demo').tooltip({
            selector: "[data-toggle=tooltip]",
            container: "body"
        })
    }
    if ($(".daterange").length > 0) {
        $('.daterange').daterangepicker();
    }
    if ($("#mssresulttable").length > 0) {
        $("#mssresulttable").rowSorter({
            handler: "span.sort-handler",
            onDrop: function (tbody, row, index, oldIndex) {
                //console.log(tbody + "\n" + row + "\n" + $(row).find(".checkbox").val());
                updatePosition($("#mssresulttable").attr("data-table"), (index + 1), $(row).find(".checkbox").val())
                //updatePosition($)
                //$(tbody).parent().find("tfoot > tr > td").html((oldIndex + 1) + ". row moved to " + (index + 1));
            }
        });
    }

}

function ViewAllRecord() {
    var startV = $('#startV').val();
    var endV = $('#endV').val();
    var page_id = $('#mssresulttable').attr('data-page');
    var page_action = $('#mssresulttable').attr('data-action');
    var page_extra = $('#mssresulttable').attr('data-extra');
    if ($("#searchinput").length > 0) {
        var q = $("#searchinput").val();
    } else {
        var q = "";
    }
    $("#resultBody").html('<tr><td colspan="5"><div class="loader"><i class="fa fa-repeat fa-spin"></i> Loading Please wait...</td></div></tr>');
    $(".paginationData").html('')
    var PostedData = new Array;
    var pagging = '';
    var ii = "";
    $.getJSON(PATH_ADMIN + "manage/" + page_id + ".php?" + page_extra, {"action": page_action, "page_id": page_id, "startV": startV, "endV": endV, "q": q}, function (data) {

        if (data.ncount > 0) {
            $.each(data.Result, function (key, val) {
                posts = "<tr>";
                $.each(val, function (key1, val1) {
                    posts += "<td>" + val1 + "</td>";
                })
                posts += "</tr>";
                PostedData.push(posts)
            });
            getPaging(data.totPost)
        } else {
            PostedData.push('<tr><td colspan="' + data.tcolumn + '">No record Found...!!!</td></tr>');
        }
        $("#resultBody").html(PostedData.join(""));
        loadControls()
    })
}

function getPaging(totPost) {

    var pagging = '';
    var ii = "";
    var adjacents = 3;
    var CurrentPage = $("#CurrentPage").val();
    var end = $("#endV").val();

    if (totPost > 0) {
        var paginationCount = parseInt(totPost / end);
        var paginationModCount = totPost % end;
        if (paginationModCount != "") {
            paginationCount++;
        }
        var lpm1 = paginationCount - 1;
        pagging += '<div class="pagination-bar text-center"><ul class="pagination"><li><a href="javascript:void(0)" onclick="numberPage(1)" class="first">First</a></li>';
        if (CurrentPage > 1) {
            pagging += '<li class="previous"><a href="javascript:void(0)" onclick="previousPage()">Previous</a></li>';
        }
        if (paginationCount <= 7 + (adjacents * 2)) {
            for (ii = 1; ii <= paginationCount; ii++) {
                var cclass = "";
                if (CurrentPage == ii)
                    cclass = "active";
                pagging += '<li class="' + cclass + '"><a href="javascript:void(0)" onclick="numberPage(' + ii + ')">' + ii + '</a></li>';
            }
        } else if (paginationCount >= 5 + (adjacents * 2)) {
            if (CurrentPage < 1 + (adjacents * 2)) {
                for (var counter = 1; counter < 4 + (adjacents * 2); counter++)
                {
                    if (counter == CurrentPage)
                        pagging += "<li class='active'><a href=''>" + counter + "</a></li>";
                    else
                        pagging += "<li><a href='javascript:void(0)' onclick='numberPage(" + counter + ")'>" + counter + "</a>";
                }
                pagging += "<li><a>...</a></li>";

                pagging += "<li><a  href='javascript:void(0)' onclick='numberPage(" + lpm1 + ")'>" + lpm1 + "</a></li>";

                pagging += "<li><a  href='javascript:void(0)' onclick='numberPage(" + paginationCount + ")'>" + paginationCount + "</a></li>";
            } else if (paginationCount - (adjacents * 2) > CurrentPage && CurrentPage > (adjacents * 2)) {
                pagging += "<li><a href='javascript:void(0)'  onclick='numberPage(1)'>1</a></li>";
                pagging += "<li><a href='javascript:void(0)'  onclick='numberPage(2)'>2</a></li>";
                pagging += "<li><a>...</a></li>";
                var checkOutv = parseInt(CurrentPage) + parseInt(adjacents);

                for (var Mycounter = CurrentPage - adjacents; Mycounter <= checkOutv; Mycounter++)
                {
                    if (Mycounter == CurrentPage)
                        pagging += "<li class='active'><a href='javascript:void(0)'>" + Mycounter + "</a></li>";
                    else
                        pagging += "<li><a href='javascript:void(0)'  onclick='numberPage(" + Mycounter + ")'>" + Mycounter + "</a></li>";
                }
                pagging += "<li><a>...</a></li>";
                pagging += "<li><a href='javascript:void(0)'  onclick='numberPage(" + lpm1 + ")'>" + lpm1 + "</a></li>";
                pagging += "<li class='last'><a class='nextal' href='javascript:void(0)' onclick='numberPage(" + paginationCount + ")'>" + paginationCount + "</a></li>";
            } else {
                pagging += "<li><a href='javascript:void(0)'  onclick='numberPage(1)'>1</a></li>";
                pagging += "<li><a href='javascript:void(0)'  onclick='numberPage(2)'>2</a></li>";
                pagging += "<li><a>...</a></li>";
                for (var counter = paginationCount - (2 + (adjacents * 2)); counter <= paginationCount; counter++)
                {
                    if (counter == CurrentPage)
                        pagging += "<li class='active'><a href='javascript:void(0)'>" + counter + "</a></li>";
                    else
                        pagging += "<li><a href='javascript:void(0)' onclick='numberPage(" + counter + ")'>" + counter + "</a></li>";
                }
            }
        }
        if (CurrentPage < (paginationCount)) {
            pagging += '<li class="next"><a href="javascript:void(0)" onclick="nextPage()">Next</a></li>';
        }
        pagging += '<li class="last"><a href="javascript:void(0)" onclick="numberPage(' + paginationCount + ')">Last</a></li>';
        pagging += '</ul><span class="link">' + CurrentPage + ' of ' + paginationCount + '  Pages Total Record ' + totPost + '</span>';
    }
    $(".paginationData").html(pagging);
}
function nextPage() {
    var nextStart = parseInt($("#endV").val()) + parseInt($("#startV").val());
    $("#startV").val(nextStart);
    $("#CurrentPage").val(parseInt($("#CurrentPage").val()) + 1);
    ViewAllRecord()
}
function previousPage() {
    var nextStart = parseInt($("#startV").val()) - parseInt($("#endV").val())
    $("#startV").val(nextStart);
    $("#CurrentPage").val(parseInt($("#CurrentPage").val()) - 1);
    ViewAllRecord()
}
function numberPage(no) {
    var nextStart = parseInt(no - 1) * parseInt($("#endV").val())
    $("#startV").val(nextStart);
    $("#CurrentPage").val(no);
    ViewAllRecord()
}

function ViewAll() {
    var page_id = $('#datatable').attr('data-page');
    var page_action = $('#datatable').attr('data-action');
    var page_extra = $('#datatable').attr('data-extra');
    var oTable = $('#datatable').dataTable({
        bJQueryUI: false,
        "aLengthMenu": [[5, 10, 15, 25, 50, 100, -1], [5, 10, 15, 25, 50, 100, "All"]],
        "iDisplayLength": 25,
        "oTableTools": {
            "sSwfPath": "vendor/plugins/datatables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf"}
    });
}
function hide_underof(valu) {
    if (valu == 1) {
        $('#section_underof').hide('slow');
        $("#under").val('0');
    } else if (valu == 2) {
        $('#section_underof').show('slow');
    } else {
        alert("please select Page type");
        $('#section_underof').hide('slow');
        $("#under").val('0');
    }
}

function show_sub_cat(valu){
//alert(id);
	if(valu=='2'){
		$('#sub_cat').show();
		}
		if(valu=='1'){
		$('#sub_cat').hide();
		}
}

function fullPage() {
    document.fullScreenElement && null !== document.fullScreenElement || !document.mozFullScreen && !document.webkitIsFullScreen ? document.documentElement.requestFullScreen ? document.documentElement.requestFullScreen() : document.documentElement.mozRequestFullScreen ? document.documentElement.mozRequestFullScreen() : document.documentElement.webkitRequestFullScreen && document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT) : document.cancelFullScreen ? document.cancelFullScreen() : document.mozCancelFullScreen ? document.mozCancelFullScreen() : document.webkitCancelFullScreen && document.webkitCancelFullScreen()
}
function proceedForApprove(url) {
    if (confirm("Are you sure you want to Send for Approval?"))
    {
        window.location = url;
    }
}
function del(url) {
    if (confirm("Are you sure you want to delete it?"))
    {
        window.location = url;
    }
}
function featured_no(url) {
    if (confirm("Are you sure you want to remove featured?"))
    {
        window.location = url;
    }
}
function featured_yes(url) {
    if (confirm("Are you sure you want to add featured?"))
    {
        window.location = url;
    }
}
function loc(url) {
    if (confirm("Are you sure?"))
    {
        window.location = url;
    }
}
function getMessage(idtype) {
    var items = new Array()

    $.getJSON(PATH_ADMIN + "ajax.php?type=notice&itype=" + idtype, function (data) {
        //var items = [];
        if (data.count > 0) {
            $.each(data.data, function (key, val) {
                items.push('<li id="' + key + '"><div class="item-icon"><i style="color: #f0ad4e" class="fa fa-user"></i> </div><div class="item-message"><a href="#">' + val + '</a></div></li>');
            });
        } else {
            items.push('<li id="0"><div class="item-icon"><i style="color: #f0ad4e" class="fa fa-user"></i> </div><div class="item-message"><a href="#">No Record Found</a></div></li>');
        }
        var myclass = "tag";
        if (idtype == "users") {
            myclass = "comment"
        } else if (idtype == "message") {
            myclass = "comment";
        }
        $("#count_" + idtype).html('<span class="glyphicon glyphicon-' + myclass + '"></span> <b>' + data.count + '</b>')
        $("#Recent_" + idtype).html(items.join(""));
        //items.length=0;
        //getMessage(idtype)
    });
}
function removeMessage(idtype) {
    $.getJSON(PATH_ADMIN + "ajax.php?type=deleteNotice&itype=" + idtype, function (data) {
        $("#count_" + idtype).html('<span class="glyphicon glyphicon-comment"></span> <b>0</b>')
    });
}
function editData(id, page_id, action) {
    window.location = '';
}
$(document).ready(function () {
    $("#searchmenu_here").keyup(function () {
        var filter = $(this).val();
        $("#sidebar-menu ul li").each(function () {
            if (jQuery(this).text().search(new RegExp(filter, "i")) < 0) {
                jQuery(this).hide();
            } else {
                jQuery(this).show()
            }
        });
    });
});
function hide_underof(valu)
{
    if (valu == 1) {
        $('#section_underof').hide('slow');
        $("#under").val('0');
    } else if (valu == 2) {
        $('#section_underof').show('slow');
        $('.chosen-container').attr("style", "width:100% !important");
    } else {
        alert("please select Page type");
        $('#section_underof').hide('slow');
        $("#under").val('0');
    }
}
function checkAll(checkboxa) {
    if ($('#' + checkboxa).is(':checked')) {
        $('.' + checkboxa).attr('checked', 'checked');
        //$('.'+checkboxa).parent('span').addClass('checked');
        $("#datatable tbody tr").addClass("task-checked");
    } else {
        //$('.'+checkboxa).parent('span').removeClass('checked');
        $('.' + checkboxa).removeAttr('checked');
        $("#datatable tbody tr").removeClass("task-checked");
    }
    //alert($('.'+checkboxa).val());
}
function add_action(type, page_id) {
    var ids = [];
    $('.row-checkbox:checked').each(function (index, element) {
        ids[index] = $(this).val();
    });
    if (ids == "") {
        $.gritter.add({
            title: 'Some Error !',
            text: 'Plesae check atleast one checkbox to continue',
        });
    } else {
        $.ajax({
            type: "POST",
            data: {ids: ids},
            url: "index.php?page_id=" + page_id + "&action=check" + type,
            success: function (msg) {
                if (type == "delete") {
                    $.gritter.add({
                        title: 'Deleted successfully',
                        text: ''
                    });
                    for (var i = 0; i < ids.length; i++) {
                        $("#tr_" + ids[i]).remove();
                    }
                } 
				else if (type == "active") {
                    $.gritter.add({
                        title: 'Activate successfully' + msg,
                        text: ''
                    });
                    for (var i = 0; i < ids.length; i++) {
                        $(".status_" + ids[i]).html('<span class="label label-success margin-right-sm">Active</span>');
                    }
                } 
				else if (type == "inactive") {
                    $.gritter.add({
                        title: 'Inactive successfully' + msg,
                        text: ''
                    });
                    for (var i = 0; i < ids.length; i++) {
                        $(".status_" + ids[i]).html('<span class="label label-danger margin-right-sm">Inactive</span>');
                    }
                }
				
				else if (type == "isnewactive") {
                    $.gritter.add({
                        title: 'Activate successfully' + msg,
                        text: ''
                    });
                    for (var i = 0; i < ids.length; i++) {
                        $(".status_" + ids[i]).html('<span class="label label-success margin-right-sm">Isnew Active</span>');
                    }
                } 
				else if (type == "isnewinactive") {
                    $.gritter.add({
                        title: 'Inactive successfully' + msg,
                        text: ''
                    });
                    for (var i = 0; i < ids.length; i++) {
                        $(".status_" + ids[i]).html('<span class="label label-danger margin-right-sm">Isnew Inactive</span>');
                    }
                }

            }
        });
    }
    //alert(ids[0]);
}
function updateStatus(table, id, field, myvalue) {
    if (myvalue == 1) {
        $("." + field + "_" + id).replaceWith('<span onclick="updateStatus(\'' + table + '\', \'' + id + '\', \'' + field + '\', \'0\')" class="label label-success margin-right-sm btn ' + field + '_' + id + '">Active</span>');
    } else {
        $("." + field + "_" + id).replaceWith('<span onclick="updateStatus(\'' + table + '\', \'' + id + '\', \'' + field + '\', \'1\')" class="label label-danger margin-right-sm btn ' + field + '_' + id + '">Inactive</span>');
    }
    $.ajax({
        type: "POST",
        data: {id: id, type: "updateStatus", table: table, field: field, myvalue: myvalue},
        url: "ajax.php",
        success: function (msg) {
        }
    });
}
function updateisnew(table, id, field, myvalue) {
	//alert("fdgd");
    if (myvalue == 1) {
        $("." + field + "_" + id).replaceWith('<span onclick="updateisnew(\'' + table + '\', \'' + id + '\', \'' + field + '\', \'0\')" class="label label-success margin-right-sm btn ' + field + '_' + id + '">Yes</span>');
    } else {
        $("." + field + "_" + id).replaceWith('<span onclick="updateisnew(\'' + table + '\', \'' + id + '\', \'' + field + '\', \'1\')" class="label label-danger margin-right-sm btn ' + field + '_' + id + '">No</span>');
    }
    $.ajax({
        type: "POST",
        data: {id: id, type: "updateisnew", table: table, field: field, myvalue: myvalue},
        url: "ajax.php",
        success: function (msg) {
        }
    });
}
function updatePosition(table, index, id) {
    $.ajax({
        type: "POST",
        data: {type: "updatePosition", table: table, index: index, id: id},
        url: "ajax.php",
        success: function (msg) {
        }
    })
}
//setInterval(fetchNotification,5000);
function fetchNotification() {
    $.getJSON("ajax.php?type=fetchNotification", function (t) {
        if (t.count > 0) {
            $.each(t.user, function (t, e) {
                playSound(URL_ROOT + "uploads/sound/ring.mp3");
                var unique_id = $.gritter.add({
                    // (string | mandatory) the heading of the notification
                    title: e.username,
                    // (string | mandatory) the text inside the notification
                    text: e.notice,
                    // (string | optional) the image to display on the left
                    image: e.avatar,
                    // (bool | optional) if you want it to fade out on its own or just sit there
                    sticky: true,
                    // (int | optional) the time you want it to be alive for before fading out
                    time: '',
                    // (string | optional) the class name you want to apply to that specific message
                    class_name: 'my-sticky-class'
                });
                return false;
            })
        }
    })
}
function playSound(t) {
    $("body").append('<audio id="audio" style="position:absolute; top:-1000px" src="' + t + '" preload="auto" autoplay />'),
            setTimeout(function () {
                $("audio").remove()
            }, 9000)
}

function loadtooltip() {
    var tooltips = document.querySelectorAll('.hover');
    for (var i = 0; i < tooltips.length; i++) {
        tooltips[i].addEventListener('mousemove', function fn(e) {
            this.tooltip.style.left = e.pageX + 'px';
            this.tooltip.style.top = e.pageY + 'px';
        });
    }
}
$(document).ready(function () {
    if ($('.more').length > 0) {
        // Configure/customize these variables.
        var showChar = 100;  // How many characters are shown by default
        var ellipsestext = "...";
        var moretext = "Show more >";
        var lesstext = "Show less";


        $('.more').each(function () {
            var content = $(this).html();

            if (content.length > showChar) {

                var c = content.substr(0, showChar);
                var h = content.substr(showChar, content.length - showChar);

                var html = c + '<span class="moreellipses">' + ellipsestext + ' </span><span class="morecontent"><span>' + h + '</span>  <a href="" class="morelink">' + moretext + '</a></span>';

                $(this).html(html);
            }

        });

        $(".morelink").click(function () {
            if ($(this).hasClass("less")) {
                $(this).removeClass("less");
                $(this).html(moretext);
            } else {
                $(this).addClass("less");
                $(this).html(lesstext);
            }
            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
        });
    }
});