// JavaScript Document
loadForm()
fetchCMS()	 
setMyDetail(myDetail.fullname,myDetail.username,myDetail.avatar,myDetail.id)
var mySwiper2 = myApp.swiper('.swiper-2', {
  pagination:'.swiper-2 .swiper-pagination',
  spaceBetween: 20,
  slidesPerView: 2
});
$(".ms-block").on("click",function(){
	var datacount=$(this).attr("data-cont");
	var personcount=$(this).attr("data-values");
	$(".ms-block").parent("div").removeClass("active")
	$("#travelwith").val(datacount);
	$(this).parent("div").addClass("active")
	$("#adultno").val(personcount);
	$("#childno").val(0);
	if(datacount=="family" || datacount=="group"){
		$("#personqty").show();
	}else{
		$("#personqty").hide();
	}
})
$(".gdivs.right").on("click",function(){
	var myins=parseInt($(this).parent("div").children("input").val());
	$(this).parent("div").children("input").val(myins+1);
})
$(".gdivs.left").on("click",function(){
	var myins=parseInt($(this).parent("div").children("input").val());
	if(myins>0){
		$(this).parent("div").children("input").val(myins-1);
	}
})
$$('img.lazy').trigger('lazy');
ImportJs("language/"+lang+".js");
$('.page-content').on('scroll',function() {
    var scroll = $('.page-content').scrollTop();
    if (scroll >= 100.1) {
		$("body").removeClass("scroll100").addClass("scroll-up");
        //$('.navbar').removeClass('mss-navbar', 1000, "easeInBack");
        //$('.navbar-inner').removeClass('mss-navbar-inner', 1000, "easeInBack");
    } else {
		$("body").addClass("scroll100").removeClass("scroll-up");
        //$('.navbar').addClass('mss-navbar', 1000, "easeInBack");
        //$('.navbar-inner').addClass('mss-navbar-inner', 1000, "easeInBack");
    }
});
function setMyDetail(fullname_u,username_u,avatar_u,id_u){
    var finalAvatar="uploads/avatar/"+avatar_u
    $$(".avatar").attr('src',finalAvatar);
    $$(".fullname").html(fullname_u);
    $$(".username").html(username_u);
    //$$(".email").html(email);
}


function updateReg(reg){
    $$('.reg_id').val(reg);
}
function getPageDetail(id){
    $$("#page-body").html(getCookie("ms-"+id));
    var url="api/cms.php?type=fetch-page&id="+id+"&lang="+lang;
    $$.post(url,function(data){
        setCookie("ms-"+id, data, "365");
        $$("#page-body").html(data);
    })
}

function fetchCMS(){
    $.getJSON("api/cms.php",{"type":"fetch-all-page","lang":lang},function(data){
        if(data.status=="success"){
            $.each(data.list,function(kay,val){
                getPageDetail(val.linkname)
            })
        }
    })
}
$$('.going-button').on('click',function(){
	myApp.popup('#going-screen');
})
$$('.ms-closepop-first').on('click', function () {
    myApp.closeModal("#going-screen")
    myApp.popup('#going-screen-next');
    getAutocomplete('autocomplete_to');
    getAutocomplete('autocomplete_from');
});
$$('.ms-closepop-second').on('click', function () {
    myApp.closeModal("#going-screen-next")
    myApp.popup('#going-screen-second-next');
});
$$(".ms-closepop-second-next").on("click",function(){
    myApp.closeModal("#going-screen-second-next")
    $("#submitsearchbtn").click();
})
var calendarDisabled = myApp.calendar({
    input: '#calendar-disabled',
    dateFormat: 'M dd yyyy',
    rangePicker: true
});

$$(".placecontent a").on("click",function(){
    $(this).parent("li").parent("ul").children("li").children("a").removeClass("active")
    $(this).addClass("active")
    $(this).parent("li").parent("ul").next("input").val($(this).html());
})



