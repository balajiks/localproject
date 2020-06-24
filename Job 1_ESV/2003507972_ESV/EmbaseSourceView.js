/*
HighlightTerms.js
*/

var mldata_node = null;
var mldata_text = null;
var mldata_json = null;

var previous_sentence_id = null;
var found_sentence_ids = [];
var notfound_sentence_ids = [];

var min_zoom_value = 100;
var max_zoom_value = 200;
// Set initial zoom level
var zoom_level=min_zoom_value;

var tree_jsonData = null;

var $content = null;

// jQuery object to save <mark> elements
var $results = null;

var currentClass = null;

var isSearchTextFound = false;

 $(document).ready(function() 
  {   
	//alert("DEV Mode")	
	
	LoadTermsBar();	
	mldata_node = document.querySelector("script[type='application/json'][id='mldata']");
	mldata_text = mldata_node.textContent;
	mldata_json = JSON.parse(mldata_text);
	//HighlightMLTerms();
	Highlight_CTN();
	Highlight_MSN_Keyword();		  
		
	//Zoom In and OUT Click events
    $("button[class='zoom_in']").click(function() { zoom_page(10, $(this)) });
    $("button[class='zoom_out']").click(function() { zoom_page(-10, $(this)) });
    $('#zoom_reset').click(function() { zoom_page(0, $(this)) });
	$("span[id='current_zoomvalue']").text(zoom_level + "%");
	
	// Search Bar
	// the input field
    var $input = $("input[type='search']"),
    // clear button
    $clearBtn = $("button[data-search='clear']"),
    // prev button
    $prevBtn = $("button[data-search='prev']"),
    // next button
    $nextBtn = $("button[data-search='next']"),            
    // top offset for the jump (the search bar)
    offsetTop = 50,
    // the current index of the focused element
    currentIndex = 0;
	// the class that will be appended to the current focused element  
	currentClass = "current";
	// the context where to search
    $content = $("#page-container");
	
	 /**
   * Searches for the entered keyword in the
   * specified context on input
   */
   $input.on("keypress", function(e) {
	
				if (e.keyCode == 13)
				{	
					if(isSearchTextFound == true)
					{
						searchText_NextPrevBtn();
					}
					else
					{
						find_searchtext();
					}	
				}
				else
				{
					isSearchTextFound = false;
				}					
		
			}	    	
	);
	
   $input.on('keydown', function() 
	{
			var key = event.keyCode || event.charCode;

			if( key == 8 || key == 46 )
			{
				isSearchTextFound = false;				
			}			
				
	});	
	
	 $input.on('keyup', function() 
	{
			var key = event.keyCode || event.charCode;

			if( key == 8 || key == 46 )
			{
				isSearchTextFound = false;
				if($input.val().length == 0)
				{
					$content.unmark({element: "span",className: "searchtext"});
					$input.val("").focus();	
				}				
			}			
				
	});
	
	/**
   * Jumps to the element matching the currentIndex
   */
function jumpTo() 
{
    if ($results.length) 
	{
      //var position,
      var $current = $results.eq(currentIndex);
      $results.removeClass(currentClass);
      if ($current.length) 
	  {
        $current.addClass(currentClass);
        //position = $current.offset().top - offsetTop;		
		//window.scrollTo(0, position);
		//$current.get(0).scrollIntoView();
		scrollToSentence($current);
        
      }
    }
}
	

  /**
   * Clears the search
   */
  $clearBtn.on("click", function() {
    $content.unmark({element: "span",className: "searchtext"});
    $input.val("").focus();
	$("div[id='statusbar']").empty();
  });

  /**
   * Next and previous search jump to
   */
  $nextBtn.add($prevBtn).on("click", function() {searchText_NextPrevBtn();});
  
function searchText_NextPrevBtn()
{

	if ($results.length) 
	{
      currentIndex += $(this).is($prevBtn) ? -1 : 1;
      if (currentIndex < 0) 
	  {
        currentIndex = $results.length - 1;
      }
      if (currentIndex > $results.length - 1) 
	  {
        currentIndex = 0;
      }
      jumpTo();
    }  
	  
}
 
  
	
});


function find_searchtext()
{
	
	var searchVal = $("input[type='search']").val();
    $content.unmark({   
						element: "span",
						className: "searchtext",
						done: function() {
											$content.mark(searchVal, {
																		element:"span",
																		className:"searchtext",
																		separateWordSearch: false,
																		acrossElements: true,
																		ignoreJoiners: true,
																		ignorePunctuation: ":;.,-–—_(){}[]!'\"+= ".split(""),	
																		done: function() 
																			  {
																				
																				$results = $content.find("span[class='searchtext']");
																				if($results.length == 0)
																				{
																				   isSearchTextFound = false;	
																				   $("div[id='statusbar']").empty();
																				   $("div[id='statusbar']").append("<p>No Text '"+ searchVal +"' found</p>");
																				}
																				else
																				{
																				   isSearchTextFound = true;	
																				   $("div[id='statusbar']").empty();
																				   $("div[id='statusbar']").append("<p>" + $results.length + " occurrence(s) found</p>");
																				}																					
																				currentIndex = 0;
																				////jumpTo();
																			  }
																	});
										}
					});
	
	
}

 	

function testmsg(val)
{
	
	alert(val);
	
}
  
   
  
  function LoadTermsBar()
  {
	const jsonNode = document.querySelector("script[type='application/json'][id='termstreejson']");  	 	 
	
	const jsonText = jsonNode.textContent; 
	
	tree_jsonData = JSON.parse(jsonText);	
	
	
			$('#termstree').jstree({'core' : 
							{
								'data' : tree_jsonData,
								'themes': {'icons':true},
								'multiple': true,
								'check_callback' : true
							},					
							'contextmenu': {'items': customMenu},
							'types':
							{								
								'MED': {'icon' : 'medical_term'},
								'DRG':{'icon' : 'drug_term'},
								'DIS':{'icon' : 'disease_term'},
								'MEDDEV':{'icon' : 'medicaldevice_term'},		 																
								'link':{'icon':'link_term_icon'},
								'sublink':{'icon':'sublink_term_icon'},
								'checktag':{'icon':'checktag_term_icon'},
								'level1':{'icon':'parentnodeicon'},
								'level2':{'icon':'parentnodeicon'},
								'default':{'icon':'parentnodeicon'} 									
							},
							'plugins' : ['contextmenu','types','checkbox'],				
							'checkbox' : {'whole_node':false,'tie_selection':false, 'three_state': false}				
							});
		
			
			$('#termstree').on('select_node.jstree', function (e, data) 
			{
				//alert(data.instance.get_node(data.selected).id);
				var treeID = data.instance.get_node(data.selected).id;
				if(treeID != "level1" && treeID !="level2")
				  {
					
					$("div[id='statusbar']").empty();
					
					/* Turn OFF highlighted terms */									
					var level1_markElement = $("mark[data-uid='level1']");
					var level2_markElement = $("mark[data-uid='level2']");
					
					level1_markElement.attr("class","highlight_off");
					level2_markElement.attr("class","highlight_off");
										
					/**/
					
					DisplaySentences(treeID);
					//InvokeSentenceHighlight(treeID);
							
				  }	
			});
		   
		   $('#termstree').on('check_node.jstree',function (e, data)
			{
			   				
				var parent_node =  $('#termstree').jstree('get_node', data.node.parent);
				
				if(data.node.type == "sublink" && parent_node != null)
				{
	
					// Only one Sublink types
					var ddth_link_pattern = new RegExp("Drug therapy", "gmi");
					var drout_link_pattern = new RegExp("Route of drug administration", "gmi");
					var ddfr_link_pattern = new RegExp("Dose frequency", "gmi");
					var dcmb_link_pattern = new RegExp("Drug combination", "gmi");
					var ddsc_link_pattern = new RegExp("Drug dosage schedule terms", "gmi");
					var adr_link_pattern = 	new RegExp("Adverse drug reaction", "gmi");
									
					
					if(ddth_link_pattern.test(parent_node.text))
					{
						data.node.data = {sublink_type:"ddth", sublink_term:data.node.text};					
						$('#termstree').jstree('rename_node', data.node, data.node.text + ' (ddth)');
						return false;
					}
					else if(drout_link_pattern.test(parent_node.text))		
					{
						data.node.data = {sublink_type:"drout", sublink_term:data.node.text};					
						$('#termstree').jstree('rename_node', data.node, data.node.text + ' (drout)');
						return false;
					}						
					else if(ddfr_link_pattern.test(parent_node.text))		
					{
						data.node.data = {sublink_type:"ddfr", sublink_term:data.node.text};					
						$('#termstree').jstree('rename_node', data.node, data.node.text + ' (ddfr)');
						return false;
					}					
					else if(dcmb_link_pattern.test(parent_node.text))		
					{
						data.node.data = {sublink_type:"dcmb", sublink_term:data.node.text};					
						$('#termstree').jstree('rename_node', data.node, data.node.text + ' (dcmb)');
						return false;
					}											
					else if(ddsc_link_pattern.test(parent_node.text))		
					{
						data.node.data = {sublink_type:"ddsc", sublink_term:data.node.text};					
						$('#termstree').jstree('rename_node', data.node, data.node.text + ' (ddsc)');
						return false;
					}						
					else if(adr_link_pattern.test(parent_node.text))		
					{
						data.node.data = {sublink_type:"davs", sublink_term:data.node.text};					
						$('#termstree').jstree('rename_node', data.node, data.node.text + ' (davs)');
						return false;
					}
					
					// More than one Sublink types

					var sublink_type_options = [];	
					
					var ddi_link_pattern = 	new RegExp("Drug dose information", "gmi");
					//var adr_link_pattern = 	new RegExp("Adverse drug reaction", "gmi");
					var dc_link_pattern = 	new RegExp("Drug comparison", "gmi");
					var di_link_pattern = 	new RegExp("Drug interaction", "gmi");
					var dp_link_pattern = 	new RegExp("Drug use\-pharmacovigilance", "gmi");
				
														
					if(ddi_link_pattern.test(parent_node.text))
					{
						sublink_type_options.push({text: 'minimum drug dose (ddmi)', value: 'ddmi'});
						sublink_type_options.push({text: 'maximum drug dose (ddma)', value: 'ddma'});
					}
					/*
					else if (adr_link_pattern.test(parent_node.text))
					{
						sublink_type_options.push({text: 'adverse drug reaction - disease  (davs)', value: 'davs'});
						sublink_type_options.push({text: 'adverse drug reaction - medical (davm)', value: 'davm'});
						sublink_type_options.push({text: 'adverse drug reaction - drug (davd)', value: 'davd'});
					}
					*/
					else if (dc_link_pattern.test(parent_node.text))
					{
						sublink_type_options.push({text: 'drug comparison (dcmp)', value: 'dcmp'});
						sublink_type_options.push({text: 'drug comparison (dcmpx placebo)', value: 'dcmpx'});
					}											
					else if (di_link_pattern.test(parent_node.text))
					{
						sublink_type_options.push({text: 'drug interaction (dind)', value: 'dind'});
						sublink_type_options.push({text: 'drug interaction medical (dinm)', value: 'dinm'});
						
					}											
					else if (dp_link_pattern.test(parent_node.text))
					{
						sublink_type_options.push({text: 'special situation for pharmacovigilance (dpvm)', value: 'dpvm'});
						sublink_type_options.push({text: 'unexpected outcome of drug treatment (dpve)', value: 'dpve'});
					}	
					
					PopUp_SublinkType_Dialog(data.node.text, sublink_type_options, data.node);
					
				}
				  	
			});			
	
			
			 $('#termstree').on('uncheck_node.jstree',function (e, data)
			{
				
				if(data.node.data !=null)
				{
					data.node.data.sublink_type = null;
					$('#termstree').jstree('rename_node', data.node, data.node.data.sublink_term);		
				}
								
					
			});
			
  }
  
  function PopUp_SublinkType_Dialog(sublink_term, sublink_type_options, selected_jstreeNode)
  {
	  
	  bootbox.prompt({
			title: "Sublink type",
			message: "<p>Select sublink type for the sublink term '" + sublink_term + "'</p>",
			inputType: 'radio',
			inputOptions: sublink_type_options,
			callback: function (result)
			{
				console.log(result);
				if(result !=null)
				{
					
					selected_jstreeNode.data = {sublink_type:result, sublink_term:selected_jstreeNode.text};					
					$('#termstree').jstree('rename_node', selected_jstreeNode, selected_jstreeNode.text + ' (' + result + ')' );
					
					
				}					
			}
		});
	  
  }
            
  
  function customMenu(node) 
  {
    
	if(node.type == "level1" || node.type == "level2" || node.type == "link" || node.type =="sublink")
	{
		return null;
	}
	
    var items = {
        Item1: {
            label: "Add term as Major",
            action: function(){	
			
								//callbackObj.sendDataToEmbaseIndexApp(node.text);		
								
								//callbackObj.sendDataToEmbaseIndexApp('{"id":"17","parent":"level2","text":"triacylglycerol","state":null}');															
								
								//callbackObj.sendDataToEmbaseIndexApp(GetMainTermJSONByTermID(node.id), 1);								
							
								var selected_nodes = $('#termstree').jstree('get_selected', true);							
																
								var tmp = GetMainTermsJSONByTermID(selected_nodes)
								callbackObj.sendDataToEmbaseIndexApp(tmp, 1);																								
								
								
							 }
				},
        Item2: {
            label: "Add term as Minor",
            action: function(){
								//callbackObj.sendDataToEmbaseIndexApp(GetMainTermJSONByTermID(node.id),2);								
								
								var selected_nodes = $('#termstree').jstree('get_selected', true);
								var tmp = GetMainTermsJSONByTermID(selected_nodes)
								callbackObj.sendDataToEmbaseIndexApp(tmp, 2);
								
							  }
				}
		/*		,		
		Item3: {
            label: "Test3",
            action: function()
						{
							ChangeIconInTree1(node.id, false);
							//alert(node.text);
							//testmsg(node.text);
							//$("#termstree").jstree(true).set_icon(node.id, "medicaldevice_term_added");							
							//$("#termstree").jstree(true).set_icon(node.id, "disease_term_added");					
							//$("#termstree").jstree(true).set_icon(node.id, "drug_term_added");													
							//$("#termstree").jstree(true).set_icon(node.id, "medical_term_added");																					
							
						}
				}
		*/
    };

    return items;
}

function GetMainTermJSONByTermID(term_id)
{
	var JsonStr = null;
	
	jQuery.each(mldata_json.mainterms, function(i, mainterm) 
	    {
					
			if(mainterm.id == term_id)	
			 { 
				var tmp = jQuery.extend(true, {}, mainterm); 				
				delete tmp.sentences;
				JsonStr = JSON.stringify(tmp);				
				//var base64str = window.btoa(t); //converts string to base 64
				return false;
			 }
			 
		});
		
		return JsonStr;	
}

function GetMainTermsJSONByTermID(treeNodes)
{
	var selected_Mainterm = [];
	
	jQuery.each(treeNodes, function(i, tn)
	{
			/* Main Terms such as Drug term, Disease term, Medical term, Medical Device term */
			jQuery.each(mldata_json.mainterms, function(j, mainterm) 
			{
						
				if(mainterm.id == tn.id)	
				 { 
					var tmp_mainterm = jQuery.extend(true, {}, mainterm); 				
					delete tmp_mainterm.sentences;
						
					var tmp_links = []; 		
										
					/* Link Terms */
					jQuery.each(tmp_mainterm.links, function(k, linkterm) 
					{
														
						
						var linkterm_node = $("#termstree").jstree("get_node", linkterm.id, false);
						
						if(linkterm_node.state.checked == true)
						{
							
							/* SubLink Terms */	
							// var tmp_sublinks = jQuery.extend(true, {}, linkterm.sublinks);  //dont use							
							// var tmp_sublink_terms = jQuery.makeArray(tmp_sublinks);    //dont use
							
                   			// Converting JS object to an array
							var tmp_sublink_terms = $.map(linkterm.sublinks, function(value, index){return [value];});
							
							jQuery.each(linkterm.sublinks, function(l, sublinkterm) 
							{
								
								var sublinkterm_node = $("#termstree").jstree("get_node", sublinkterm.id, false);
								if(sublinkterm_node.state.checked == true)
								{									
									// get the sublink type
									if(sublinkterm_node.data != null)
									{
										sublinkterm.sublinktype = sublinkterm_node.data.sublink_type
									}
									
								}
								else
								{
									// linkterm.sublinks.splice($.inArray(sublinkterm, linkterm.sublinks),1); //dont use									
									// delete sublink term																	
									tmp_sublink_terms.splice($.inArray(sublinkterm,tmp_sublink_terms),1);									
								}	
								
							});
							
							linkterm.sublinks = tmp_sublink_terms;
							
							tmp_links.push(linkterm);
							
						}									
						
					});
					
					tmp_mainterm.links = tmp_links;					
					selected_Mainterm.push(tmp_mainterm);
					return false;
				 }
				 
			});
	});		
	
	var JsonStr = null;
	
	JsonStr = JSON.stringify(selected_Mainterm);	
		
	return JsonStr;	
}


function ChangeIconInTree(mainterm_id, link_id, sublink_id, isTermAdded)
{
	var icon_classname = null;
	
	if(mainterm_id)
	{	
	
	jQuery.each(mldata_json.mainterms, function(i, mainterm) 
	    {
						
			if(mainterm.id == mainterm_id)	
			 { 
				
				console.log('mainterm - ' + mainterm.term); 
								
				var mainterm_node = $("#termstree").jstree("get_node", mainterm_id, false);				
				
				//UnCheck  the mainterm node if it is not added in Embase Index				
				if(isTermAdded == false)
				{
					$('#termstree').jstree('uncheck_node', mainterm_node);			
				}				
				
				if(mainterm.ischecktag == true)
				{
					if(isTermAdded == true)
					{
					   icon_classname = "checktag_term_added";
					   console.log('mainterm - ' + mainterm.term + ' [check tag] added'); 
					}
					else
					{
						icon_classname = "checktag_term_icon";
					}						
					
				}								
				else
				{
					if(mainterm.termType == 'MED')
					{
						if(isTermAdded == true)
						{
						   icon_classname = "medical_term_added";	
						   console.log('mainterm - ' + mainterm.term + ' [medical term] added'); 
						}
						else
						{
							icon_classname = "medical_term";
						}					
						
					}
					else if(mainterm.termType == 'DRG')
					{
						if(isTermAdded == true)
						{
						   icon_classname = "drug_term_added";
						   console.log('mainterm - ' + mainterm.term + ' [drug term] added'); 
						}
						else
						{
							icon_classname = "drug_term";
						}	
						
					}
					else if(mainterm.termType == 'DIS')
					{
						if(isTermAdded == true)
						{
						   icon_classname = "disease_term_added";
						   console.log('mainterm - ' + mainterm.term + ' [disease term] added'); 
						}
						else
						{
							icon_classname = "disease_term";
						}	
						
					}
					else if(mainterm.termType == 'MEDDEV')
					{
						if(isTermAdded == true)
						{
						   icon_classname = "medicaldevice_term_added";
						   console.log('mainterm - ' + mainterm.term + ' [medicaldevice term] added'); 
						}
						else
						{
						   icon_classname = "medicaldevice_term";
						}	
						
					}
				}
				
				$("#termstree").jstree(true).set_icon(mainterm_id, icon_classname);								
				return false;					
				
			 }			 
				
		
		});
		
	}
		
		if(link_id)					
		{
			
			console.log('link id - ' + link_id); 
			
			if(isTermAdded == true)
			{
				$("#termstree").jstree(true).set_icon(link_id, "link_term_added");
				console.log('link id - ' + link_id + ' added');
			}
			else
			{
				var linkterm_node = $("#termstree").jstree("get_node", link_id, false);
				$("#termstree").jstree(true).set_icon(link_id, "link_term_icon");
				$('#termstree').jstree('uncheck_node', linkterm_node);		
			}
		}
		
		if(sublink_id)
		{
			console.log('sublink id - ' + sublink_id); 		
				
			if(isTermAdded == true)
			{				
				$("#termstree").jstree(true).set_icon(sublink_id, "sublink_term_added");
				console.log('sublink id - ' + sublink_id + ' added'); 
			}
			else
			{
				$("#termstree").jstree(true).set_icon(sublink_id, "sublink_term_icon");
				
				var sublinkterm_node = $("#termstree").jstree("get_node", sublink_id, false);
				
				if(sublinkterm_node !=null && sublinkterm_node.data !=null && sublinkterm_node.data.sublink_term !=null )
				{
					sublinkterm_node.data.sublink_type = null;
					$('#termstree').jstree('rename_node', sublinkterm_node, sublinkterm_node.data.sublink_term);		
					$('#termstree').jstree('uncheck_node', sublinkterm_node);		
				}
			}						
					
		}
		
}

 
	function DisplaySentences(termid)
	{
		$("div[id='sentences_panel']").empty();		
		jQuery.each(mldata_json.mainterms, function(i, mainterm) 
	    {
			 if (mainterm.id == termid)	
			 { 
				
				if(mainterm.sentences.length == 0)
				{
					$("div[id='sentences_panel']").append("<p>No Sentence(s) exists for term '"+ mainterm.term +"'</p>");
					return false;
				}
				
				var sentence_cnt = 0;
		 
				if(mainterm.weight == 'a')
				{					
				    					
					$("div[id='sentences_panel']").append("<p class='term_name'>Level 1 Term : " + mainterm.term + "</p>");
				}	
				else if(mainterm.weight == 'b')
				{					
					
					$("div[id='sentences_panel']").append("<p class='term_name'>Level 2 Term : " + mainterm.term + "</p>");
				}		        				
				
				$("div[id='sentences_panel']").append("<p class='term_score'>Score : " + mainterm.score + "</p>");
				
				var $found_sentences = $("<div class='found_sentence_grp'></div>");
				var $notfound_sentences = $("<div class='notfound_sentence_grp'></div>");
				var $HaveToFound_sentences = $("<div class='havetofound_sentence_grp'></div>");
				
				jQuery.each(mainterm.sentences, function(j, sentence)
					 {
						sentence_cnt+=1; 
						var ts_id = "term" + mainterm.id + "_s" + sentence_cnt;
						
						//$("div[id='sentences_panel']").append("<div class='term_sentence' id='" + ts_id + "'>" + sentence + "</div>");
						
						var $ts_div = $("<div class='term_sentence' id='" + ts_id + "'>" + sentence + "</div>");											
						
						if(jQuery.inArray(ts_id, found_sentence_ids) !== -1)
						{
							
							$ts_div.append("<div class='okwrong_placement'><img class='sentence_found'/></div>");
                            $found_sentences.append($ts_div); 							
							
							/* Turn ON highlighted terms */									
							var level1_markElement = $("mark[data-uid='level1'][data-shid='"+ ts_id +"']");
							var level2_markElement = $("mark[data-uid='level2'][data-shid='"+ ts_id +"']");
					
							level1_markElement.attr("class","highlight_level1");
							level2_markElement.attr("class","highlight_level2");
												
							/**/								   
						}
						else if(jQuery.inArray(ts_id, notfound_sentence_ids) !== -1)
						{
							
							$ts_div.append("<div class='okwrong_placement'><img class='sentence_notfound'/></div>");
							$notfound_sentences.append($ts_div);
						}
						else
						{
							$HaveToFound_sentences.append($ts_div);
						}					
						
						//$("div[id='sentences_panel']").append($ts_div);
						
						//$("div[id='sentences_panel'] ul").append("<li class='term_sentence' data-tsid='" + ts_id + "'>" + sentence + "</li>");
					 });	
					 
				if($found_sentences.children().length !== 0)
				{					
					$("div[id='sentences_panel']").append($found_sentences);	 
				}				
				
				if($notfound_sentences.children().length !== 0)
				{					
					$("div[id='sentences_panel']").append($notfound_sentences);	
				}				
				
				if($HaveToFound_sentences.children().length !== 0)
				{					
					$("div[id='sentences_panel']").append($HaveToFound_sentences);	
				}
									 
				$("div[class='term_sentence']").click(function()
				{
					//alert(this);
					OnSentence_Click(this,mainterm);
				});	 
			 }
		 
		});		
		
	}
	
	function OnSentence_Click(e,mainterm)
	{
		//alert(e.id);
		//alert(e.innerText);
		//alert(mainterm.weight);		
					
		if(previous_sentence_id !=null)
		{
		   $("div[class='term_sentence'][id="+ previous_sentence_id + "]").removeAttr("style");
		}
		
		e.style.backgroundColor = "LightGray";
		
		/*
		var markelements = $("mark[data-shid='"+ e.id +"']");
		
		if (markelements.length > 0)
		{
		   $("mark[data-shid='"+ e.id +"']").get(0).scrollIntoView();		     		   
		}
		else
		{
			HighlightSentence(e.innerText,e.id,mainterm,e);		
		}
		*/		
		
		previous_sentence_id = e.id;
				
		
		if(jQuery.inArray(e.id, found_sentence_ids) !== -1)
		{
					
			$("div[id='statusbar']").empty();
			
			//$("div[id='statusbar']").append("<p> mark(linkid) count '"+ $("mark[data-linkid='"+ e.id +"']").length +"'</p>");
			
			//$("mark[data-linkid='"+ e.id +"']").get(0).scrollIntoView(true);						
			
			/*			
			var markElement = $("mark[data-linkid='"+ e.id +"']");			
			//var div_parent =  $("mark[data-linkid='"+ e.id +"']").parent(); //$("mark[data-linkid='"+ e.id +"']").parentsUntil("[data-page-no]");
			
			var div_parent =  $("mark[data-linkid='"+ e.id +"']").closest("div[class='pf w0 h0']");
									
			scroll(markElement.get(0),div_parent);	
			
			//$('html, body').animate({scrollTop: ($("mark[data-linkid='"+ e.id +"']").offset().top)},500);
			*/
			
			scrollToSentence($("mark[data-linkid='"+ e.id +"']"));
						
			
		}
		else if(jQuery.inArray(e.id, notfound_sentence_ids) !== -1)
		{				
		    $("div[id='statusbar']").empty();
			$("div[id='statusbar']").append("<p>Unable to find Sentence for term '"+ mainterm.term +"'</p>");
			return;			
		}
		else
		{
		   HighlightSentence(e.innerText,e.id,mainterm,e);
		}			
		
		
	}		
	
	function HighlightSentence(sentence, sentence_id, mainterm, parentdiv)
	{
		
		//alert(sentence);
		var IsFirstNodeFound = false;	
		var mark_classname = null;		
		var termtype = null;
		
		if(mainterm.weight == 'a')
		  {					
			termtype = 'level1';					
			mark_classname = 'highlight_level1';
		  }	
		else if(mainterm.weight == 'b')
	     {					
			termtype = 'level2';													
			mark_classname = 'highlight_level2';			
		 }			
    						
		var SentenceHighlight_options = {
											"element": "mark",
											"className": mark_classname,
											"separateWordSearch": false,
											//"accuracy": {"value": "partially", "limiters": [",", ".", "-"]},					
											//"accuracy": {"value": "exactly"},					
											"acrossElements": true,
											"ignoreJoiners": true,
											"ignorePunctuation": ":;.,-–—_(){}[]!'\"+= ".split(""),					
											"each": function(node)
												    {
														
														if(IsFirstNodeFound == false)
														{
														  IsFirstNodeFound = true;																	  
														  node.setAttribute("data-linkid",sentence_id);															  
														}														
														node.setAttribute("data-shid",sentence_id);														
														node.setAttribute("data-uid",termtype);																	
																	
													},
											"done": function(counter)
													{
														//alert(counter);
																	
														if(counter == 0)
														{									
															$("div[id='statusbar']").empty();	
															$("div[id='statusbar']").append("<p>Unable to find Sentence for term '"+ mainterm.term +"'</p>");
															//$(parentdiv).append("<div class='okwrong_placement'><img src='https://img.icons8.com/flat_round/24/000000/delete-sign.png'/></div>");
															$(parentdiv).append("<div class='okwrong_placement'><img class='sentence_notfound'/></div>");
															notfound_sentence_ids.push(sentence_id);		
														}																
														else																	
														{
														   $("div[id='statusbar']").empty();	
														   $("div[id='statusbar']").append("<p>Sentence found for term '"+ mainterm.term +"'</p>");
														   //$("div[id='statusbar']").append("<p> mark(linkid) count '"+ $("mark[data-linkid='"+ sentence_id +"']").length +"'</p>");
														   
														   //$("mark[data-linkid='"+ sentence_id +"']").get(0).scrollIntoView(true);
														   	
															scrollToSentence($("mark[data-linkid='"+ sentence_id +"']"));
																							
														   //$(parentdiv).append("<div class='okwrong_placement'><img src='https://img.icons8.com/color/24/000000/ok.png'/></div>");															   
														   
														   $(parentdiv).append("<div class='okwrong_placement'><img class='sentence_found'/></div>");
														   found_sentence_ids.push(sentence_id);
															
														}
													},
										"debug": false,
										"log": window.console															
										};
				
		$("#page-container").mark(sentence,SentenceHighlight_options);  						
	
	}
	

function scrollToSentence(markElement)
{
	var div_parent =  markElement.closest("div[class='pf w0 h0']");
	
	/*
	$("div[id='statusbar']").append("<p> mark parent element : '"+ div_parent[0].tagName +"'</p>");
	$("div[id='statusbar']").append("<p> id : '"+ div_parent.attr("id") +"'</p>");
	$("div[id='statusbar']").append("<p> class : '"+ div_parent.attr("class") +"'</p>");
	*/
	
	scroll(markElement.get(0),div_parent);	
}
	
function scroll(element, parent)
{
    $(parent)[0].scrollIntoView(true);
    $(parent).animate({ scrollTop: $(parent).scrollTop() + $(element).offset().top - $(parent).offset().top }, { duration: 'slow', easing: 'swing'});
		
	$("mark[data-shid='"+ $(element).attr("data-linkid") +"']").fadeOut(500).fadeIn(500);	
}	



// Zoom function
function zoom_page(step, trigger)
        {
            //https://stackoverflow.com/questions/21668635/how-do-i-zoom-out-a-whole-website-using-jquery-or-css
			
			// Zoom just to steps in or out
            if(zoom_level>=max_zoom_value && step>0 || zoom_level<=min_zoom_value && step<0) return;

            // Set / reset zoom
            if(step==0) zoom_level=100;
            else zoom_level=zoom_level+step;

			$("span[id='current_zoomvalue']").text(zoom_level + "%");
			
            var scale = zoom_level / 100;

            // Set page zoom via CSS
			
		    /*
			For Firefox [Have to text]
			$("div[class='pf w0 h0']").css({
                "-moz-transform" : scale(scale,scale), //Moz-browsers
                "transform" : "scale(" + scale + ")", // set zoom
                "transformOrigin" : "50% 0" //set transform scale base
            });
			
			/* */	
			//-moz-transform: scale(0.8, 0.8); /* Moz-browsers */
			//zoom: 0.8; /* Other non-webkit browsers */
			//zoom: 120%; /* Webkit browsers */	
			/* */
			
			//Standard Property [Have to test]			
			//$("div[class='pf w0 h0']").css("transform", "scale(" + scale + "," + scale +")");
			//$("div[class='pf w0 h0']").css("transform-origin", "0 0");
					
            //For IE
			//At Zoom 130% While scrolling, page content disappears				
			//$("div[class='pf w0 h0']").css("zoom", zoom_level + "%");
			
			//For Safari And Chrome
			//$("div[class='pf w0 h0']").css("-webkit-transform","scale(" + scale + ")");
			//$("div[class='pf w0 h0']").css("-webkit-transform-origin","50% 0%");
			
			//$("div[id='page-container']").css("-webkit-transform","scale(" + scale + ")");
			//$("div[id='page-container']").css("-webkit-transform-origin","50% 0%");
			
			//Working
			$("div[id='page-container']").css("zoom", zoom_level + "%");			
            
            // Activate / deactivate trigger (use CSS to make them look different)			
			/*
            if(zoom_level>=max_zoom_value || zoom_level<=min_zoom_value) trigger.addClass('disabled');
            else trigger.parents('ul').find('.disabled').removeClass('disabled');
            if(zoom_level!=min_zoom_value) $('#zoom_reset').removeClass('disabled');
            else $('#zoom_reset').addClass('disabled');
			*/
			
			
        }
	
	

/*                */
  
  function HighlightMLTerms()
  {	
	const jsonNode = document.querySelector("script[type='application/json'][id='mlterms']");  	 
	
	//alert(jsonNode);
	
	if(jsonNode == null)
	{
	  disable_legendlabels("level1");
	  disable_legendlabels("level2");		  
	  disable_legendlabels("emmans");	
	  return;			  
	}  
	
	const jsonText = jsonNode.textContent; 
	
	if(isBlank(jsonText))
	{
	  disable_legendlabels("level1");
	  disable_legendlabels("level2");		  
	  disable_legendlabels("emmans");	
	  return;			   
	}
			
	const jsonData = JSON.parse(jsonText);	
    
	if(jQuery.isEmptyObject(jsonData)) 	
	{						
	  disable_legendlabels("level1");
	  disable_legendlabels("level2");		  
	  disable_legendlabels("emmans");	
	  return;			   
	}
	
    jQuery.each(jsonData, function(i, ty) 
	 {
	  
	  var istermfound = false;
	  //alert(ty.typename)
	    
	  var mark_classname = '';	  
	  
	  if(ty.typename == 'level1')
	  {
		mark_classname = 'base_highlight highlight_level1';				
	  }
	  else if(ty.typename == 'level2')
	  {
		mark_classname = 'base_highlight highlight_level2';		
	  }
	  else if(ty.typename == 'emmans')
	  {
		mark_classname = 'base_highlight highlight_emmans';		
	  }      	 
	  
	  CheckTermsExists(ty);					
	  
	  //========================================
	  
	  var options = {
					"element": "mark",
					"className": mark_classname,
					"separateWordSearch": false,
					//"accuracy": {"value": "partially", "limiters": [",", ".", "-"]},										
					"acrossElements": true,															
					"each": function(node)
							{
								node.setAttribute("data-uid",ty.typename)
							},
					"done": function(counter)
							{
								//alert(counter);
								if(counter != 0)
								{									
									istermfound = true;									
								}								
							}		  
					};
	  
	  jQuery.each(ty.terms, function(i, term) 
	    {
		  //alert(term)		
		  
		  $("#page-container").mark(term,options);   	  
		  		  
		});
		
		if(istermfound)
		{
			document.getElementById(ty.typename).checked = true;
		}
		else
		{
			document.getElementById(ty.typename).disabled = true;			
			document.getElementById(ty.typename).checked = false;
			$("span#" + ty.typename).css({"text-decoration": "line-through"});						
		}
		
		//============================
		
	 
	  
	});
	
	

  } 
  
   function CheckTermsExists(ty)
  {

		if(jQuery.isEmptyObject(ty.terms)) 	
		{						
			
			document.getElementById(ty.typename).disabled = true;			
			$("span#" + ty.typename).css({"background-color": "#283747"});
			
			//$("span." + span_classname).css({"text-decoration": "line-through"});						
			//document.getElementById(termtype).style.visibility = "hidden";			
			//$("input#" + termtype).css({"display": "none"});			
			//$("span." + span_classname).css({"display": "none"});			
						
		}
		else
		{
			//$("input#" + termtype).css({"display": "block"});			
			//$("span." + span_classname).css({"display": "block"});			
			document.getElementById(ty.typename).checked = true;
		}
  
  }  

  function Highlight_CTN()
  {
	  
	 const jsonNode = document.querySelector("script[type='application/json'][id='ctnpatterns']");  	
	
	if(jsonNode == null)
	{		
		disable_legendlabels("ctn")
		return;		
	} 
	
	const jsonText = jsonNode.textContent; 
		
	if(isBlank(jsonText))
	{
		disable_legendlabels("ctn")
		return;		
	}
	
	const ctn_pattern_obj = JSON.parse(jsonText);			
	
	var options = {
					"element": "mark",
					"className": "ctn",
					"acrossElements": true,
					"each": function(node)
							{
								node.setAttribute("data-uid","ctn")
							},
					"done": function(counter)
							{
								//alert("CTN cnt: " + counter);
								if(counter == 0)
								{									
									document.getElementById("ctn").disabled = true;			
									document.getElementById("ctn").checked = false;
									$("span#ctn").css({"text-decoration": "line-through"});						
								}
								else
								{
									document.getElementById("ctn").checked = true;
								}
							}
				  };	 			      	
	
	//var patt = new RegExp("NCT\\d+", "g");		
	//var ctn_pattern = new RegExp("(Australian New Zealand Clinical Trials Registry|ClinicalTrials.gov|The Netherlands National Trial Register|Brazilian Clinical Trials Registry|Chinese Clinical Trial Registry|Clinical Research Information Service, Republic of Korea|Clinical Trials Registry\\s*-\\s*India|Cuban Public Registry of Clinical Trial|EU Clinical Trials Register|German Clinical Trials Register|Iranian Registry of Clinical Trials|Japan Primary Registries Network|Pan African Clinical Trial Registry|Sri Lanka Clinical Trials Registry|Thai Clinical Trials Register|Peruvian Clinical Trials Registry|(ANZCTR|NCT|ISRCTN|UMIN-CTR|NTR|EudraCT|ReBec|ChiCTR|CRiS|CTRI|RPCEC|EU-CTR|DRKS|IRCT|JPRN|PACTR|SLCTR|TCTR|REPEC)(\\d+)?)", "g");	
    
	
	//alert(ctn_pattern_obj.patternstr); 
	
	if(jQuery.isEmptyObject(ctn_pattern_obj)) 		
	{						
		disable_legendlabels("ctn")
		return;	
	}		
	else
	{
		var ctn_pattern = new RegExp(ctn_pattern_obj.patternstr, "g");
		//$("#page-container").markRegExp(/NCT(\d+)/g, options);    
		$("#page-container").markRegExp(ctn_pattern, options);    			
	}
	
  } 
    
  function Highlight_MSN_Keyword()
  {
	
	const jsonNode = document.querySelector("script[type='application/json'][id='msnkwdpatterns']");  	
	
	if(jsonNode == null)
	{	
		disable_legendlabels("msnkwd");
		$("span#msnkwd").css({"color": "black"});
		return;		
	}  
		
	const jsonText = jsonNode.textContent; 
	
	if(isBlank(jsonText))
	{
		disable_legendlabels("msnkwd");
		$("span#msnkwd").css({"color": "black"});
		return;		
	}
	
	const msnkwd_pattern_obj = JSON.parse(jsonText);
	
	var options = {
					"element": "mark",
					"className": "msnkwd",
					"acrossElements": true,
					"each": function(node)
							{
								node.setAttribute("data-uid","msnkwd")
							},
					"done": function(counter)
							{
								//alert("MSN kwd cnt: " + counter);
								if(counter == 0)
								{									
									
									document.getElementById("msnkwd").disabled = true;			
									document.getElementById("msnkwd").checked = false;
									$("span#msnkwd").css({"text-decoration": "line-through"});						
									$("span#msnkwd").css({"color": "black"});
									
									//disable_legendlabels("msnkwd")
								}
								else
								{
									document.getElementById("msnkwd").checked = true;
								}
							}
				  };

	if(jQuery.isEmptyObject(msnkwd_pattern_obj)) 
	{
		disable_legendlabels("msnkwd");
		$("span#msnkwd").css({"color": "black"});
		return;
	}
    else
	{
		var msnkwd_pattern = new RegExp(msnkwd_pattern_obj.patternstr, "g");
		$("#page-container").markRegExp(msnkwd_pattern, options);    	
	}
	
	
	//$("#page-container").markRegExp(/(submitted|deposited|submit|deposit)/g, options);    	
	    
  }
	
   
   function disable_legendlabels(idval)
   {
	   document.getElementById(idval).disabled = true;			
	   $("span#" + idval).css({"background-color": "#283747"});
   }
	
  /*
  function test() 
  {
	alert('Hello');
  }
  */
  
  function highlight_term_ONOFF(termtype) 
  {
	
	//alert('Hello');	
	
	var data_uid = '';
	var markElement ='';
	var mark_classname = '';
		
	if(termtype=='level1')
	{
		  
	  data_uid='highlight_t1';
	  //mark_classname = 'base_highlight highlight_level1';
	  mark_classname = 'highlight_level1';
	  markElement = 'mark[data-uid=\'level1\']';
	 
	  
	}
	else if(termtype=='level2')
	{
	  data_uid='highlight_t2';
      //mark_classname = 'base_highlight highlight_level2';
	  mark_classname = 'highlight_level2';
	  markElement = 'mark[data-uid=\'level2\']';
	}
	else if(termtype=='emmans')
	{
		data_uid='emmans';
		mark_classname = 'base_highlight highlight_emmans';
		markElement = 'mark[data-uid=\'emmans\']';
	}
	else if(termtype=='ctn')
	{
		data_uid='ctn';
		mark_classname = 'base_highlight ctn';
		markElement = 'mark[data-uid=\'ctn\']';
	}
	else if(termtype=='msnkwd')
	{
		data_uid='ctn';
		mark_classname = 'base_highlight msnkwd';
		markElement = 'mark[data-uid=\'msnkwd\']';
	}
				
	var checkBox = document.getElementById(termtype);
   
	  if (checkBox.checked == true)
	  {
		$(markElement).attr("class",mark_classname);
	  }
	  else 
	  {
		$(markElement).attr("class","highlight_off");
	  }
	
  }
  
/*Search Bar Functions [Starts Here] */



/*Search Bar Functions [Ends Here] */
  
  function isBlank(str) 
	{
		return (!str || /^\s*$/.test(str));
	}
  
  function escapeRegExp(string) 
  {
	return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); // $& means the whole matched string
  }
  
  /*
  $(this).each(function() 
	{
		$.each(this.attributes, function() {
			// this.attributes is not a plain object, but an array
			// of attribute nodes, which contain both the name and value
			if(this.specified) 
			{
				console.log(this.name, this.value);		
				
			}
										});
	});
	
	*/
  
  /* OLD Functions*/
  /*
  function HighlightSentences(termid)
	{
		//alert(termid);
		
		jQuery.each(mldata_json.mainterms, function(i, mainterm) 
	    {
		 if (mainterm.id == termid && mainterm.shid_list == null)	
		 { 
				//alert(mainterm.term);
				
				if(mainterm.sentences.length == 0)
				{
					$("div[id='statusbar']").append("<p>No Sentence(s) exists under term '"+ mainterm.term +"'</p>");
					return false;
				} 
								
				var sentence_cnt = 0;
				var shid_list = [];
				var	mark_classname = null;	
				var termtype = null;		
				
				if(mainterm.weight == 'a')
				{
					mark_classname = 'highlight_level1';
				    termtype = 'level1';					
				}	
				else if(mainterm.weight == 'b')
				{
					mark_classname = 'highlight_level2';
					termtype = 'level2';										
				}	
				
				jQuery.each(mainterm.sentences, function(j, sentence)
				 {
					 	
					sentence_cnt +=1;
					var shid_str = "term" + mainterm.id + "_s" + sentence_cnt;
					shid_list.push(shid_str);																	  
						
				 });
				 
			if(shid_list.length > 0)
			{
				//alert(shid_list[0]);
				//alert($("mark[data-shid='"+  shid_list[0] +"']").get(0).nodeName);						
				mainterm.shid_list = shid_list
				$("mark[data-shid='"+  shid_list[0] +"']").get(0).scrollIntoView();			
			}
			
										 
		 }		 
					
		 
		});
				
	}
	
	function InvokeSentenceHighlight(termid)
	{
		
		jQuery.each(mldata_json.mainterms, function(i, mainterm) 
	    {
			 if (mainterm.id == termid && mainterm.shid_list != null && mainterm.shid_list.length > 0)	
			 { 
				$("mark[data-shid='"+ mainterm.shid_list[0] +"']").get(0).scrollIntoView();			
				return false;
			 }			 
		});
		HighlightSentences(termid);
		  
    }
  */