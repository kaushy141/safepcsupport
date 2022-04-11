<?php if(isset($wc_id) && $wc_id !=0): echo drawCollectionProcedure($wc_id);?>

<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header"> <i class="fa fa-align-justify"></i>Documents of # <?php echo $wc_code?> </div>
      <div class="card-body" style="padding:8px;">
        <div class="col-sm-12">
          <div class="row">
            <div class="col-sm-12 col-md-12" style="margin-top:20px; margin-bottom:50px;">
              <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-3">
                  <div class="card alert-warning">
                    <div class="card-body">
                      <div class="h1 text-muted text-center text-danger mb-4"> <i class="fa fa-file-pdf-o"></i> </div>
                      <div class="h5 text-center mt-4 mb-4">Hazardous Waste Consignment</div>
                      <div class="text-center"><a id="collection_r_hwc_link" target="_blank" href="<?php echo DOC::HWCN($wc_id);?>" class="alert-primary btn-block pdfloc mb-2"><i class="fa fa-download"></i> Download</a></div>
                    </div>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3">
                  <div class="card alert-warning">
                    <div class="card-body">
                      <div class="h1 text-muted text-center text-danger mb-4"> <i class="fa fa-file-pdf-o"></i> </div>
                      <div class="h5 text-center mt-4 mb-4">Waste Collection Report</div>
                      <div class="text-center"><a id="collection_r_wcn_link" target="_blank" href="<?php echo DOC::WCNN($wc_id);?>" class="alert-primary btn-block pdfloc mb-2"><i class="fa fa-download"></i> Download</a></div>
                    </div>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3">
                  <div class="card alert-warning">
                    <div class="card-body">
                      <div class="h1 text-muted text-center text-danger mb-4"> <i class="fa fa-file-pdf-o"></i> </div>
                      <div class="h5 text-center mt-4 mb-4">Duty of Care: Waste Transfer</div>
                      <div class="text-center"><a id="collection_r_doc_link" target="_blank" href="<?php echo DOC::DOCN($wc_id);?>" class="alert-primary btn-block pdfloc mb-2"><i class="fa fa-download"></i> Download</a></div>
                    </div>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3">
                  <div class="card alert-warning">
                    <div class="card-body">
                      <div class="h1 text-muted text-center text-danger mb-4"> <i class="fa fa-file-pdf-o"></i> </div>
                      <div class="h5 text-center mt-4 mb-4">Collection Certificate</div>
                      <div class="text-center"><a id="collection_r_cer_link" target="_blank" href="<?php echo DOC::CERT($wc_id);?>" data-href-append="/N" class="alert-primary btn-block pdfloc mb-2"><i class="fa fa-download"></i> Download</a></div>
                    </div>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3">
                  <div class="card alert-warning">
                    <div class="card-body">
                      <div class="h1 text-muted text-center text-danger mb-4"> <i class="fa fa-file-pdf-o"></i> </div>
                      <div class="h5 text-center mt-4 mb-4">Advance Audit Report</div>
                      <div class="text-center"><a id="collection_r_audit_link" target="_blank" href="<?php echo DOC::CBAR($wc_id);?>" class="alert-primary btn-block pdfloc mb-2"><i class="fa fa-download"></i> Download</a></div>
                    </div>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3">
                  <div class="card alert-warning">
                    <div class="card-body">
                      <div class="h1 text-muted text-center text-danger mb-4"> <i class="fa fa-file-pdf-o"></i> </div>
                      <div class="h5 text-center mt-4 mb-4">Basic Audit Report</div>
                      <div class="text-center"><a id="collection_r_audit_link" target="_blank" href="<?php echo DOC::CBARBASIC($wc_id);?>" class="alert-primary btn-block pdfloc mb-2"><i class="fa fa-download"></i> Download</a></div>
                    </div>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3">
                  <div class="card alert-warning">
                    <div class="card-body">
                      <div class="h1 text-muted text-center text-danger mb-4"> <i class="fa fa-file-pdf-o"></i> </div>
                      <div class="h5 text-center mt-4 mb-4">Collection Asset Code</div>
                      <div class="text-center"><a id="collection_r_asset_link" target="_blank" href="<?php echo DOC::CASSETCODE($wc_id);?>" class="alert-primary btn-block pdfloc mb-2"><i class="fa fa-download"></i> Download</a></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>	
$(document).ready(function(){
	/*
	var i=0;
	var page_no = 1;
	$('.pdfloc').each(function(){
		
	var __PDF_DOC,
		__CURRENT_PAGE,
		__TOTAL_PAGES,
		__CANVAS,
		__CANVAS_CTX = 0;
		
		i++;
		var pdf_url = $(this).attr('href');
		var attr = $(this).attr('data-href-append');
		if(typeof attr !== 'undefined')
		pdf_url += $(this).attr('data-href-append');
		var c = document.createElement('canvas');
			c.setAttribute("id", "pdf_canvas_" +i);
			c.setAttribute("width", $(this).parents('div').innerWidth()-5);
			//$(this).parent('div').css("height", 200);
			//$(this).parent('div').css("overflow", 'hidden');
			
			$(this).html(c);
			
			
			
		__CANVAS = $('#pdf_canvas_' +i).get(0);
		__CANVAS_CTX = __CANVAS.getContext('2d');
		PDFJS.getDocument({ url: pdf_url }).then(function(pdf_doc) {
			__PDF_DOC = pdf_doc;
			__TOTAL_PAGES = __PDF_DOC.numPages;
			
		__CURRENT_PAGE = page_no;
		// Fetch the page
		__PDF_DOC.getPage(page_no).then(function(page) {
			var scale_required = __CANVAS.width / page.getViewport(1).width;
			var viewport = page.getViewport(scale_required);
	
			// Set canvas height
			//__CANVAS.height = viewport.height;
			__CANVAS.height = 200;
	
			var renderContext = {
				canvasContext: __CANVAS_CTX,
				viewport: viewport
			};
			
			// Render the page contents in the canvas
			page.render(renderContext).then(function() {
				__PAGE_RENDERING_IN_PROGRESS = 0;
			});
		});	
			
		
		}).catch(function(error) {
			// If error re-show the upload button
			$("#pdf-loader").hide();
			$("#upload-button").show();
			
			alert(error.message);
		});;
		
	});	*/
});
</script>
<?php endif;?>
