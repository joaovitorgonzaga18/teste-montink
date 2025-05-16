function abrir_div(url, div, index, callBack, obj){

    jQuery('#'+div).html('');

	if(typeof(div)=='undefined'){
		div = 'container';
	}

	$('.side-menu li').each(function(index, el) {
		a = $(this).find('a').attr('href');
		if(a != $(obj).attr('href')){
			$(this).removeClass('active');
			$(this).removeClass('current-page');
		}else{
			u = $(this).parent('ul').parent('li');
			$(u).addClass('active');
		}
	});

	console.log("abrir_div('"+url+"', '"+div+"', '"+index+"');");

	jQuery.post(url,{index:index},function(resposta){ 
		jQuery('#'+div).html(resposta);
	}).done(function() {
    	if(typeof(callBack)=='undefined'){
            setTimeout(callBack,200);            
    	}
  	});

}