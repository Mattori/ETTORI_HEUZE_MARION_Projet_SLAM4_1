<table id='tblSites' class="ui table"><thead id='' class=""><tr id='htmltablecontent--0' class=""><th id='htmltr-htmltablecontent--0-0' class="">Id</th> <th id='htmltr-htmltablecontent--0-1' class="">Nom</th> <th id='htmltr-htmltablecontent--0-2' class="">Latitude</th> <th id='htmltr-htmltablecontent--0-3' class="">Longitude</th> <th id='htmltr-htmltablecontent--0-4' class="">Ecart</th> <th id='htmltr-htmltablecontent--0-5' class="">Fond d'écran</th> <th id='htmltr-htmltablecontent--0-6' class="">Couleur</th> <th id='htmltr-htmltablecontent--0-7' class="">Ordre</th> <th id='htmltr-htmltablecontent--0-8' class="">Options</th> <th id='htmltr-htmltablecontent--0-9' class="">Actions</th></tr></thead> <tbody id='' class=""><tr id='tblSites-tr-11' class="" data-ajax="11"><td id='htmltr-tblSites-tr-11-0' class="">11</td> <td id='htmltr-tblSites-tr-11-1' class="">Slamwiki</td> <td id='htmltr-tblSites-tr-11-2' class="">8</td> <td id='htmltr-tblSites-tr-11-3' class="">5</td> <td id='htmltr-tblSites-tr-11-4' class="">5</td> <td id='htmltr-tblSites-tr-11-5' class=""></td> <td id='htmltr-tblSites-tr-11-6' class=""></td> <td id='htmltr-tblSites-tr-11-7' class=""></td> <td id='htmltr-tblSites-tr-11-8' class=""></td> <td id='htmltr-tblSites-tr-11-9' class=""><button id='' class="ui button visibleover icon _edit basic" style="visibility:hidden;" data-ajax="11"><i id='icon-' class="icon edit"></i></button><button id='' class="ui button visibleover icon _delete red basic" style="visibility:hidden;" data-ajax="11"><i id='icon-' class="icon remove"></i></button></td></tr> <tr id='tblSites-tr-12' class="" data-ajax="12"><td id='htmltr-tblSites-tr-12-0' class="">12</td> <td id='htmltr-tblSites-tr-12-1' class="">Slamwiki</td> <td id='htmltr-tblSites-tr-12-2' class="">8</td> <td id='htmltr-tblSites-tr-12-3' class="">6</td> <td id='htmltr-tblSites-tr-12-4' class="">5</td> <td id='htmltr-tblSites-tr-12-5' class=""></td> <td id='htmltr-tblSites-tr-12-6' class=""></td> <td id='htmltr-tblSites-tr-12-7' class=""></td> <td id='htmltr-tblSites-tr-12-8' class=""></td> <td id='htmltr-tblSites-tr-12-9' class=""><button id='' class="ui button visibleover icon _edit basic" style="visibility:hidden;" data-ajax="12"><i id='icon-' class="icon edit"></i></button><button id='' class="ui button visibleover icon _delete red basic" style="visibility:hidden;" data-ajax="12"><i id='icon-' class="icon remove"></i></button></td></tr></tbody></table><script type="text/javascript" >
// <![CDATA[
window.defer=function (method) {if (window.jQuery) method(); else setTimeout(function() { defer(method) }, 50);};window.defer(function(){$(document).ready(function() {

	$("#tblSites tr").mouseover(function(event){
		
if(event && event.stopPropagation) event.stopPropagation();
$(event.target).closest('tr').find('.visibleover').css('visibility', 'visible');

	});

	$("#tblSites tr").mouseout(function(event){
		
if(event && event.stopPropagation) event.stopPropagation();
$(event.target).closest('tr').find('.visibleover').css('visibility', 'hidden');

	});

	$("#tblSites ._delete").click(function(event){
		
if(event && event.stopPropagation) event.stopPropagation();

if(event && event.preventDefault) event.preventDefault();
url='http://127.0.0.1/Homepage/homep/SiteController/delete';url=url+'/'+($(this).attr('data-ajax')||'');
var self=this;
$("#divSites").empty();
		$("#divSites").prepend('<div class="ajax-loader"><span></span><span></span><span></span><span></span><span></span></div>');
$.get(url,{}).done(function( data ) {
	$("#divSites").html( data ).transition('fade up in');
	
});

	});

	$("#tblSites ._edit").click(function(event){
		
if(event && event.stopPropagation) event.stopPropagation();

if(event && event.preventDefault) event.preventDefault();
url='http://127.0.0.1/Homepage/homep/SiteController/edit';url=url+'/'+($(this).attr('data-ajax')||'');
var self=this;
$("#divSites").empty();
		$("#divSites").prepend('<div class="ajax-loader"><span></span><span></span><span></span><span></span><span></span></div>');
$.get(url,{}).done(function( data ) {
	$("#divSites").html( data ).transition('slide down in');
	
});

	});
})});
// ]]>
</script>

