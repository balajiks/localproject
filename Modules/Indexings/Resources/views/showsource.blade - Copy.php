@extends('layouts.indexersourceapp')
@section('content')
<aside class="aside-lg b-l">
  <section class="vbox">
    <section class="scrollable" id="feeds">
      <div class="slim-scroll padder" data-color="#333333" data-disable-fade-out="true" data-distance="0" data-height="auto" data-size="3px">
	  
	  
	  
	<ul><li id="a"><span item-title="true"> Level A</span><ul><li id="fracture" title=""><span item-title="true"> fracture&nbsp;(DIS)</span></li><li id="injury" title=""><span item-title="true"> injury&nbsp;(DIS)</span></li></ul></li><li id="b"><span item-title="true"> Level B</span><ul><li id="adolescent" title=""><span item-title="true"> adolescent&nbsp;(MED)</span></li><li id="adult" title=""><span item-title="true"> adult&nbsp;(MED)</span></li><li id="age" title=""><span item-title="true"> age&nbsp;(MED)</span></li><li id="aged" title=""><span item-title="true"> aged&nbsp;(MED)</span></li><li id="child" title=""><span item-title="true"> child&nbsp;(MED)</span></li><li id="female" title=""><span item-title="true"> female&nbsp;(MED)</span></li><li id="groups by age" title=""><span item-title="true"> groups by age&nbsp;(MED)</span></li><li id="hospitalization" title=""><span item-title="true"> hospitalization&nbsp;(MED)</span></li><li id="human" title=""><span item-title="true"> human&nbsp;(MED)</span></li><li id="major clinical study" title=""><span item-title="true"> major clinical study&nbsp;(MED)</span></li><li id="male" title=""><span item-title="true"> male&nbsp;(MED)</span></li><li id="maxillofacial injury" title=""><span item-title="true"> maxillofacial injury&nbsp;(DIS)</span></li><li id="middle aged" title=""><span item-title="true"> middle aged&nbsp;(MED)</span></li><li id="pain" title=""><span item-title="true"> pain&nbsp;(DIS)</span></li><li id="retrospective study" title=""><span item-title="true"> retrospective study&nbsp;(MED)</span></li></ul></li></ul>
	  
	   </div>
    </section>
  </section>
</aside>
<aside class="b-l bg" id="">


<div id="results" class="hidden"></div>

<div id="pdf"></div>

<div class="pdfobject-com"><a href="http://pdfobject.com">PDFObject.com</a></div>

<script src="http://localhost:81/embasecloud/PDFObject-gh-pages/js/pdfobject.min.js"></script>
<script>
var options = {
	pdfOpenParams: {
		navpanes: 0,
		toolbar: 1,
		statusbar: 0,
		view: "FitV",
		search: 'April 2007',
		
		page: 1
	},
	forcePDFJS: true,
	PDFJS_URL: "http://localhost:81/embasecloud/PDFObject-gh-pages/pdfjs/web/viewer.html"
};

var myPDF = PDFObject.embed("http://localhost:81/PDFObject-gh-pages/pdf/pdf_open_parameters_acro8.pdf", "#pdf", options);

var el = document.querySelector("#results");
el.setAttribute("class", (myPDF) ? "success" : "fail");
el.innerHTML = (myPDF) ? "PDFObject was successful!" : "Uh-oh, the embed didn't work.";
</script>
   
 
  


</aside>



<aside class="aside-lg b-l">
  <section class="vbox">
    <section class="scrollable" id="feeds">
      <div class="slim-scroll padder" data-color="#333333" data-disable-fade-out="true" data-distance="0" data-height="auto" data-size="3px">
	ASDASDAs
	  
	   </div>
    </section>
  </section>
</aside>
@push('pagescript')



@endpush
@endsection