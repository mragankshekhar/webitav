$(document).ready(function(){
    $("#p_uname").val(myDetail.fullname);
    $(".username").html(myDetail.username);
    $("#p_username").val(myDetail.username);
    $("#p_email").val(myDetail.email);
    $("#p_phone").val(myDetail.mobile);
    $("#p_gender").val(myDetail.gender);
    $("#p_dob").val(myDetail.dob);
    if(myDetail.is_public=="1")$("#is_public").attr("checked","checked");
    $("#p_avatar").attr("src","uploads/avatar/"+myDetail.avatar);
    $.getJSON("api/profile.php",{"type":"fetch-profile","id":myDetail.id},function(data){
        if(data.status=="success"){
            $("#p_uname").val(data.fullname);
            $("#p_username").val(data.username);
            $("#p_email").val(data.email);
            $("#p_phone").val(data.mobile);
            $("#p_gender").val(data.gender);
            $("#p_dob").val(data.dob);
            if(data.is_public=="1")$("#is_public").attr("checked","checked");
            $("#p_avatar").attr("src","uploads/avatar/"+data.avatar);
        }else{
            showToast(dictionary.CHECKNETWORK,"error")
        }
    })
	
})
$(".profile").on("change",function(){
	var field=$(this).attr("data-val");
	var value=$(this).val();
	$.getJSON("api/profile.php",{"type":"update-profile","value":value,"field":field,"id":myDetail.id},function(data){
		if(data.status=="error")
		showToast(data.message,data.status)
	});
})
$(".profilecheck").on("change",function(){
	var value=0;
	 if($(".profilecheck").is(':checked')){
		 value=1;
	 }
	$.getJSON("api/profile.php",{"type":"update-profile","value":value,"field":"is_public","id":myDetail.id},function(data){
		if(data.status=="error")
		showToast(data.message,data.status)
	});
})