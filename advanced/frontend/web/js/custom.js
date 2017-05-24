/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function callAlert(){
    $('#div').alert("THIS");
}


$('#productLisingModal').on('show.bs.modal', function (event) {
    var modal = $(this);
    $.ajax({
        url: '/admin/site/tbi',
        method: "POST",
        data: new FormData($('#buy')[0]), // The form with the file inputs.
        processData: false, // Using FormData, no need to process data.
        contentType: false, // Using FormData, no need to process data.
        success: function (data) {
            modal.find('.modal-content').empty();
            modal.find('.modal-content').append(data);
        }
    });

})

function addRating(product_id, rating, lang) {
    $.ajax({
        url: '/admin/site/addrating',
        method: "POST",
        data: {
            product_id: product_id,
            rating: rating,
            lang: lang,
        },
        success: function (data) {
            var data = JSON.parse(data);
            if (data['logged'] == 0) {
                alert(data['resultTxt']);
            } else {
                $.notify(data['resultTxt'], {globalPosition: 'bottom center', className: 'success'});
            }
        }
    });
}

function selectContactMap(address_id) {
    $.ajax({
        url: '/admin/site/selectaddress',
        method: "POST",
        data: {
            address_id: address_id,
        },
        success: function (data) {
            var data = JSON.parse(data);
            $("#contactFrame").attr("src", data['resultAddress']);
            $(".contactAddressBox").removeClass('activeAddress');
            $("#boxAddr_"+address_id).addClass('activeAddress');
            $('html,body').animate({
	        scrollTop: $("#contactFrame").offset().top},
	        'slow');
        }
    });
}

function showShippingBox(country_id) {
    if (country_id != '') {
        $('#shippingBox').show(300);
    } else {
        $('#shippingBox').hide(300);
    }
    $.ajax({
        url: '/admin/site/showshipping',
        method: "POST",
        data: {
            country_id: country_id,
        },
        success: function (data) {
            var data = JSON.parse(data);
            $('#orderShipping').html(data['shipping']);
            $('#cartTotal').html(data['total']);
            $('#orders-delivery_option').html(data['options']);
            //$('input:radio[name="Orders[delivery_option]"][value="2"]').attr('checked',true).trigger("click");
            $('input[name="Orders[delivery_option]"]:radio:first').click();
        }
    });
}

function loadShipping(country_id, delivery_type, weight) {
    $.ajax({
        url: '/admin/site/loadshipping',
        method: "POST",
        data: {
            country_id: country_id,
            delivery_type: delivery_type,
            weight: weight,
        },
        success: function (data) {
            var data = JSON.parse(data);
            $('#orderShipping').html(data['shipping']);
            $('#cartTotal').html(data['total']);
            $('#awayFromFreeShipping').html(data['infoAwayFromFreeShipping']);
            if (delivery_type == 0) {
                $('#shippingBoxInfo').html(data['info']);
            } else {
                $('#shippingBoxInfo').html('');
            }
        }
    });
}

function enterCoupon(coupon, lang) {
    $.ajax({
        url: '/admin/site/entercoupon',
        method: "POST",
        data: {
            coupon: coupon,
            lang: lang,
        },
        success: function (data) {
            var data = JSON.parse(data);
            if (data['reload'] == 1) {
                setTimeout(function () {
                    location.reload();
                }, 1000);
            } else {
                document.getElementById("couponResult").innerHTML = data['message'];
            }
        }
    });
}

function clearCoupon() {
    $.ajax({
        url: '/admin/site/clearcoupon',
        method: "POST",
        success: function (data) {
            location.reload();
        }
    });
}

function setView(val) {
    $('#viewList').val(val);
}

function buyProduct() {
    $.ajax({
        url: '/admin/site/buyproduct',
        method: "POST",
        data: new FormData($('#buy')[0]), // The form with the file inputs.
        processData: false, // Using FormData, no need to process data.
        contentType: false, // Using FormData, no need to process data.
        success: function (data) {
            var data = JSON.parse(data);
            if (data['reload'] == 1) {
                if( !/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
                    $('html, body').animate({
                            scrollTop: $("#productScroll").offset().top
                    }, 500);
                }
                location.reload();
            } else {
               // alert(data['msg']);
                $.notify(data['msg'], {globalPosition: 'bottom center'});
            }
        }
    });
}

function buyProductWithQty(product_id, quantity) {
    $.ajax({
        url: '/admin/site/buyproductwithqty',
        method: "POST",
        data: {
            product_id: product_id,
            qty: quantity,
        },
        success: function (data) {
            var data = JSON.parse(data);
            if (data['reload'] == 1) {
                $.toast({
                    heading: data['label283'],
                    text: data['label284'],
                    position: 'top-right',
                    bgColor:'#3c763d',
                    textColor:'#ffffff',
                    loader:false,
                    textAlign: 'center',
                    hideAfter: 3000
                });
                setTimeout(function(){ location.reload(); }, 2000);
            } else {
                //alert(data['msg']);
                $.notify(data['msg'], {globalPosition: 'bottom center'});
            }
        }
    });
}

function buyPromoPacket(packet_id) {
    $.ajax({
        url: '/admin/site/buypromopacket',
        method: "POST",
        data: {
            packet_id: packet_id,
        },
        success: function (data) {
            var data = JSON.parse(data);
            if (data['reload'] == 1) {
                location.reload();
            } else {
               // alert(data['msg']);
                $.notify(data['msg'], {globalPosition: 'bottom center'});
            }
        }
    });
}

function setSpecValue(spec_id, spec_val_id, lang, product_id) {
    var elements = document.querySelectorAll('input[id^="specVal_"]');
    var specValsId = new Array();
    $('#specVal_' + spec_id).val(spec_val_id);
    for (var i = 0; i < elements.length; i++) {
        specValsId.push(elements[i].value);
    }
    $.ajax({
        url: '/admin/site/setspecval',
        method: "POST",
        data: {
            spec_id: spec_id,
            spec_val_id: spec_val_id,
            lang: lang,
            product_id: product_id,
            specValsId: specValsId,
        },
        success: function (data) {
            var data = jQuery.parseJSON(data);
            if (data['success'] == 1) {
                $('#specSpan_' + spec_id).html(data['spec_val']);
                $('#prodPriceCurr').html(data['price'] + ' ' + data['currency']);
                $('#lisPriceUpd').html(data['lisingPrice']);
                $('#productPrice').val(data['price']);
            } else {
                $('#specVal_' + spec_id).val('');
                $('#productPrice').val(data['price']);
                $('#specSpan_' + spec_id).html(data['spec_val']);
                $('#prodPriceCurr').html(data['price'] + ' ' + data['currency']);
            }
        }
    });
}

function clearSelectedSpec(product_id) {
    var elements = document.querySelectorAll('input[id^="specVal_"]');
    var elements2 = document.querySelectorAll('[id^="specSpan_"]');
    var elements3 = document.querySelectorAll('[id^="specChoice_"]');
    $.ajax({
        url: '/admin/site/clearallspecs',
        method: "POST",
        data: {
            product_id: product_id,
        },
        success: function (data) {
            var data = jQuery.parseJSON(data);
            for (var i = 0; i < elements.length; i++) {
                elements[i].value = '';
            }
            for (var j = 0; j < elements2.length; j++) {
                elements2[j].innerHTML = '';
            }
			for (var k = 0; k < elements3.length; k++) {
                elements3[k].value = '0';
            }
            $('#productPrice').val(data['price']);
            $('#prodPriceCurr').html(data['price'] + ' ' + data['currency']);
            $('#lisPriceUpd').html(data['lisingPrice']);
        }
    });
}

function colUp() {
    var currentVal = parseInt($("#inputQty").val());
    if (!isNaN(currentVal)) {
        $("#inputQty").val(currentVal + 1);
    } else {
        $("#inputQty").val(1)
    }
}
function colDown() {
    var currentVal = parseInt($("#inputQty").val());
    if (!isNaN(currentVal) && currentVal >= 2) {
        $("#inputQty").val(currentVal - 1);
    }
}

function validateForm(stringVal) {
    var elements = document.querySelectorAll('input[id^="specVal_"]');
    var flag = 0;
    for (var i = 0; i < elements.length; i++) {
        var res = elements[i].id.split("_");
        if (elements[i].value == "") {
            document.getElementById('buyError').innerHTML = stringVal;
            flag = 1;
        } else {
            document.getElementById('buyError').innerHTML = '';
        }
    }
    if (flag == 1) {
        return false;
    } else {
        return true;
    }
}

function deleteProductFromCart(key) {
    var r = confirm("Are you sure you want to delete product?");
    if (r == true) {
        $.ajax({
            url: '/admin/site/deleteproductfromcart',
            method: "POST",
            data: {
                key: key,
            },
            success: function (data) {
                location.reload();
            }
        });
    }
}

function deleteAllCart() {
    var r = confirm("Are you sure you want to delete all products from your cart?");
    if (r == true) {
        $.ajax({
            url: '/admin/site/deleteallproducts',
            method: "POST",
            success: function (data) {
                location.reload();
            }
        });
    }
}

function showMainNav(){
	if($('#homeMainNav:visible').length == 0){
		$('#homeMainNav').show(300);
	}else{
		$('#homeMainNav').hide(300);
	}
}

function changeQtyFromCartUp(cart_key) {
    $.ajax({
        url: '/admin/site/changeqtyfromcartup',
        method: "POST",
        data: {
            cart_key: cart_key,
        },
        success: function (data) {
            var data = JSON.parse(data);
            if (data['success'] == 1) {
                location.reload();
            }else{
                $.notify(data['result'], {globalPosition: 'bottom center'});
            }
        }
    });
}

function changeQtyFromCartDown(cart_key) {
    $.ajax({
        url: '/admin/site/changeqtyfromcartdown',
        method: "POST",
        data: {
            cart_key: cart_key,
        },
        success: function (data) {
            location.reload();
        }
    });
}

function validateCheckout(stringVal) {
    if (document.getElementById('acceptTerms').checked) {
        return true;
    } else {
        //alert(stringVal);
        $.notify(stringVal, {globalPosition: 'bottom center'});
        return false;
    }
}

function chooseFact() {
	$( "#chooseFact" ).toggle();
}

function addInWishlist(product_id, lang) {
    $.ajax({
        url: '/admin/site/addinwishlist',
        method: "POST",
        data: {
            product_id: product_id,
            lang: lang,
        },
        success: function (data) {
            var data = JSON.parse(data);
            var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
            if (data['logged'] == 0) {
                if(width < 767){
                    $.notify(data['result'], {globalPosition: 'bottom left', className: 'danger'});
                }else{
                    $.notify(data['result'], {globalPosition: 'bottom center', className: 'danger'});
                }
                //alert(data['result']);
            } else {
                if (data['productExist'] == 1) {
                    if(width < 767){
                        $.notify(data['result'], {globalPosition: 'bottom left'});
                    }else{
                        $.notify(data['result'], {globalPosition: 'bottom center'});
                    }
                    //$.notify(data['result'], {globalPosition: 'bottom center'});
                } else {
                    if(width < 767){
                        $.notify(data['result'], {globalPosition: 'bottom left'});
                    }else{
                        $.notify(data['result'], {globalPosition: 'bottom center'});
                    }
                    //$.notify(data['result'], {globalPosition: 'bottom center', className: 'success'});
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                }
            }
        }
    });
}

function deleteFromWishlist(product_id) {
    var r = confirm("Сигурни ли сте?");
    if (r == true) {
        $.ajax({
            url: '/admin/site/deletefromwishlist',
            method: "POST",
            data: {
                product_id: product_id,
            },
            success: function (data) {
                location.reload();
            }
        });
    }
}

function addInComparelist(product_id, lang) {
    $.ajax({
        url: '/admin/site/addincomparelist',
        method: "POST",
        data: {
            product_id: product_id,
            lang: lang,
        },
        success: function (data) {
            var data = JSON.parse(data);
            var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
            if (data['productExist'] == 1) {
                if(width < 767){
                    $.notify(data['result'], {globalPosition: 'bottom left'});
                }else{
                    $.notify(data['result'], {globalPosition: 'bottom center'});
                }
            } else {
                if(width < 767){
                    $.notify(data['result'], {globalPosition: 'bottom left', className: 'success'});
                }else{
                    $.notify(data['result'], {globalPosition: 'bottom center', className: 'success'});
                }
                setTimeout(function () {
                    location.reload();
                }, 500);
            }
        }
    });
}

function deleteFromComparelist(product_id, lang) {
    var r = confirm("Сигурни ли сте?");
    if (r == true) {
        var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
        $.ajax({
            url: '/admin/site/deletefromcomparelist',
            method: "POST",
            data: {
                product_id: product_id,
                lang: lang,
            },
            success: function (data) {
                var data = JSON.parse(data);
                if (data['success'] == 0) {
                    if(width < 767){
                        $.notify(data['result'], {globalPosition: 'bottom left'});
                    }else{
                        $.notify(data['result'], {globalPosition: 'bottom center'});
                    }
                } else {
                    if(width < 767){
                        $.notify(data['result'], {globalPosition: 'bottom left', className: 'success'});
                    }else{
                        $.notify(data['result'], {globalPosition: 'bottom center', className: 'success'});
                    }
                    setTimeout(function () {
                        location.reload();
                    }, 500);
                }
            }
        });
    }
}

function showCatMenu() {
    if ($('#category-widget:visible').length == 0) {
        $('#category-widget').show(300);
    } else {
        $('#category-widget').hide(300);
    }
}

function addForNewsletter(user_email, newsResult, lang) {
    $.ajax({
        url: '/admin/site/addfornewsletter',
        method: "POST",
        data: {
            user_email: user_email,
            lang: lang,
        },
        success: function (data) {
            var data = JSON.parse(data);
            document.getElementById(newsResult).innerHTML = data['textResult'];
        }
    });
}

function addComment(lang) {
    var product_id = $('#productId').val();
    var comment = $('#commentText').val();
    var captcha = $('#commentCaptcha').val();
    $.ajax({
        url: '/admin/site/addproductcomment',
        method: "POST",
        data: {
            product_id: product_id,
            comment: comment,
            captcha: captcha,
            lang: lang,
        },
        success: function (data) {
            var data = JSON.parse(data);
            if (data['result'] == 0) {
                document.getElementById("textError").innerHTML = data['textError'];
            } else {
                document.getElementById("textError").innerHTML = data['textError'];
                setTimeout(function () {
                    location.reload();
                }, 2000);
            }
        }
    });
}

function addReviewRating(rating) {
    $('#reviewRating').val(rating);
}
(function ($) {
    $('body').on('click', '.ratingList li', function () {
        $(this).siblings().removeClass('active');
        $(this).addClass('active').prevAll().addClass('active');
    });

})(jQuery);
function addReview(lang) {
    var product_id = $('#productId').val();
    var names = $('#reviewNames').val();
    var title = $('#reviewTitle').val();
    var review = $('#reviewText').val();
    var rating = $('#reviewRating').val();
    var captcha = $('#reviewCaptcha').val();
    $.ajax({
        url: '/admin/site/addproductreview',
        method: "POST",
        data: {
            product_id: product_id,
            names: names,
            title: title,
            review: review,
            captcha: captcha,
            rating: rating,
            lang: lang,
        },
        success: function (data) {
            var data = JSON.parse(data);
            if (data['result'] == 0) {
                document.getElementById("textErrorReview").innerHTML = data['textError'];
            } else {
                document.getElementById("textErrorReview").innerHTML = data['textError'];
                $('#productId').val();
                $('#reviewTitle').val();
                $('#reviewText').val();
                $('#reviewCaptcha').val();
                setTimeout(function () {
                    location.reload();
                }, 2000);
            }
        }
    });
}

function selectCountry(country_id, id) {
    $.ajax({
        url: '/admin/site/selectcountry',
        method: "POST",
        data: {
            country_id: country_id,
        },
        success: function (data) {
            var data = JSON.parse(data);
            $('#' + id).html(data['result']);
        }
    });
}

/*
 function shippingPaypal() {
 var paypal = $('#orders-payment_type_id input[type=\'radio\']:checked').val();
 if(paypal == 1){
 //$("#orders-delivery_option input[type='radio'][value=0]").hide();
 $("#orders-delivery_option input[type='radio'][value=0]").prop('checked',false);
 $("#orders-delivery_option input[type='radio'][value=0]").parent().hide();
 $("#orders-delivery_option input[type='radio'][value=0]").attr('disabled',true);
 $("#orders-delivery_option input[type='radio'][value=2]").prop('checked',true).trigger('change');
 }else{
 $("#orders-delivery_option input[type='radio'][value=0]").attr('disabled',false);
 $("#orders-delivery_option input[type='radio'][value=0]").parent().show();
 }
 }*/

function registerMe(input) {
    if (input.checked) {
        $('#userPass').show(300);
    } else {
        $('#userPass').hide(300);
    }
}

function filterFormS() {
    document.getElementById("filterS").submit();
}
function pjaxFilterForm() {
    var dataString = $("#filter-group1, #filter-group2").serialize();
	$.pjax.defaults.timeout = false;
    $.pjax({
        container: "#productGrid",
        url: location.href.split("?")[0],
        data: dataString,
        scrollTo: false
    });
    /*setTimeout(function () {
        $.pjax({
            container: "#productList",
            url: location.href.split("?")[0],
            data: dataString
        });
    }, 1000);*/

	
    return false;
}

function chooseview(val){
    $('#viewProd').val(val);
}

function openLisingModal() {
    $('#productLisingModal').modal();
}

function printBankOrder(elem){
    printWindow($(elem).html());
}

function printWindow(data){
    var mywindow = window.open('', 'my div', 'height=400,width=600');
    mywindow.document.write('<html><head><title>Order</title>');
    mywindow.document.write('<link rel="stylesheet" href="helper.css" type="text/css" />');
    mywindow.document.write('<link rel="stylesheet" href="custom.css" type="text/css" />');
    mywindow.document.write('</head><body >');
    mywindow.document.write(data);
    mywindow.document.write('</body></html>');

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10

    mywindow.print();
    mywindow.close();

    return true;
}

function printBankOrder(divId) {
       var printContents = document.getElementById(divId).innerHTML;
       var originalContents = document.body.innerHTML;
       document.body.innerHTML = "<html><head><title></title></head><body>" + printContents + "</body>";
       window.print();
       document.body.innerHTML = originalContents;
   }


$('.autoPlay').owlCarousel({
    autoplay: true,
    loop: true,
    items: 1,
    nav:true,
    navText: ["<i class='icon fa fa-angle-left'></i>", "<i class='icon fa fa-angle-right'></i>"],
});