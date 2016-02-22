//In this javascript file you can find a bunk of functional functions
//Calling Actions in ajax. Can be used easily on views
function connectPerson(connectUserId, callback) 
{
	console.log("connect Person");
	$.ajax({
		type: "POST",
		url: baseUrl+"/"+moduleId+'/person/connect',
		dataType : "json",
		data : {
			connectUserId : connectUserId,
		}
	})
	.done(function (data) {
		$.unblockUI();
		if (data &&  data.result) {
			var name = $("#newInvite #ficheName").text();
			toastr.success('You are now following '+name);
			if (typeof callback == "function") callback(data.invitedUser);
		} else {
			$.unblockUI();
			toastr.error('Something Went Wrong !');
		}
		
	});
	
}


/*function disconnectPerson(idToDisconnect, typeToDisconnect, nameToDisconnect, callback) 
{

	bootbox.confirm(trad.areyousure+" <span class='text-red'>"+nameToDisconnect+"</span> "+trad.connection+" ?", 
		function(result) {
			if (!result) {
				return;
			}
			var urlToSend = baseUrl+"/"+moduleId+"/person/disconnect/id/"+idToDisconnect+"/type/"+typeToDisconnect+"/ownerLink/knows";
			$.ajax({
				type: "POST",
				url: urlToSend,
				dataType: "json",
				success: function(data){
					if ( data && data.result ) {               
						toastr.info("You are not following this person anymore.");
						if (typeof callback == "function") callback(idToDisconnect, typeToDisconnect, nameToDisconnect);
					} else {
						toastr.error(data.msg);
					}
				},
				error: function(data) {
					toastr.error("Something went really bad !");
				}
			});
		}
	);
}*/

function disconnectTo(parentType,parentId,childId,childType,connectType){
	$(".disconnectBtnIcon").removeClass("fa-unlink").addClass("fa-spinner fa-spin");
	var formData = {
		"childId" : childId,
		"childType" : childType, 
		"parentType" : parentType,
		"parentId" : parentId,
		"connectType" : connectType,
	};
	bootbox.confirm(trad["removeconnection"], 
		function(result) {
			if (!result) {
			$(".disconnectBtnIcon").removeClass("fa-spinner fa-spin").addClass("fa-unlink");
			return;
		}
		console.log(formData);
		$.ajax({
			type: "POST",
			url: baseUrl+"/"+moduleId+"/link/disconnect",
			data : formData,
			dataType: "json",
			success: function(data){
				if ( data && data.result ) {
					removeFloopEntity(data.parentId, data.parentType);
					loadByHash(location.hash);
				} else {
				   toastr.error("You leave succesfully");
				}
			}
		});
	});
}

// Javascript function used to validate a link between parent and child (ex : member, admin...)
function validateConnection(parentType, parentId, childId, childType, linkOption, callback) {
	var formData = {
		"childId" : childId,
		"childType" : childType, 
		"parentType" : parentType,
		"parentId" : parentId,
		"linkOption" : linkOption,
	};

	$.ajax({
		type: "POST",
		url: baseUrl+"/"+moduleId+"/link/validate",
		data: formData,
		dataType: "json",
		success: function(data) {
			if (data.result) {
				if (typeof callback == "function") callback(parentType, parentId, childId, childType, linkOption);
			} else {
				toastr.error(data.msg);
			}
		},
	});  
}
function follow(parentType, parentId, childId, childType){
	$(".followBtn").removeClass("fa-link").addClass("fa-spinner fa-spin");
	var formData = {
		"childId" : childId,
		"childType" : childType, 
		"parentType" : parentType,
		"parentId" : parentId,
	};
	$.ajax({
		type: "POST",
		url: baseUrl+"/"+moduleId+"/link/follow",
		data: formData,
		dataType: "json",
		success: function(data) {
			if(data.result){
				//addFloopEntity(data.parent["_id"]["$id"], data.parentType, data.parent);
				toastr.success(data.msg);	
				loadByHash(location.hash);
			}
			else
				toastr.error(data.msg);
		},
	});
}
function connectTo(parentType, parentId, childId, childType, connectType, parentName, actionAdmin) {
	$(".becomeAdminBtn").removeClass("fa-user-plus").addClass("fa-spinner fa-spin");
	//e.preventDefault();
	var formData = {
		"childId" : childId,
		"childType" : childType, 
		"parentType" : parentType,
		"parentId" : parentId,
		"connectType" : connectType,
	};
	if(actionAdmin=="true"){
		formData.adminAction=true;
	}
	console.log(formData);
	
	if(connectType!="admin" && connectType !="attendee"){
		bootbox.dialog({
                title: trad["suretojoin"+parentType]+" "+trad["as"+connectType]+" ?",
                onEscape: function() {
	                $(".becomeAdminBtn").removeClass("fa-spinner fa-spin").addClass("fa-user-plus");
                },
                message: '<div class="row">  ' +
                    '<div class="col-md-12"> ' +
                    '<form class="form-horizontal"> ' +
                    '<label class="col-md-4 control-label" for="awesomeness">'+trad["areyouadmin"]+'?</label> ' +
                    '<div class="col-md-4"> <div class="radio"> <label for="awesomeness-0"> ' +
                    '<input type="radio" name="awesomeness" id="awesomeness-0" value="admin"> ' +
                    trad["yes"]+' </label> ' +
                    '</div><div class="radio"> <label for="awesomeness-1"> ' +
                    '<input type="radio" name="awesomeness" id="awesomeness-1" value="'+connectType+'" checked="checked"> '+trad["no"]+' </label> ' +
                    '</div> ' +
                    '</div> </div>' +
                    '</form></div></div>',
                buttons: {
                    success: {
                        label: "Ok",
                        className: "btn-primary",
                        callback: function () {
                            var role = $('#role').val();
                            var answer = $("input[name='awesomeness']:checked").val();
                            if(role!=""){
	                            formData.roles=role;
                            }
                            formData.connectType=answer;
                            console.log(formData);
                            $.ajax({
								type: "POST",
								url: baseUrl+"/"+moduleId+"/link/connect",
								data: formData,
								dataType: "json",
								success: function(data) {
									if(data.result){
										//console.log("saveMembre");
										addFloopEntity(data.parent["_id"]["$id"], data.parentType, data.parent);
										//$("#linkBtns").html('<a href="javascript:;" class="removeMemberBtn tooltips " data-name="'+data.parent.name+'"'+ 
										//					'data-memberof-id="'+contextData["_id"]["$id"]+'" data-member-type="<?php echo Person::COLLECTION ?>" data-member-id="<?php echo Yii::app()->session["userId"] ?>" data-placement="left" '+
										//					'data-original-title="<?php echo Yii::t('organization','Remove from my Organizations') ?>" >'+
										//					'<i class=" disconnectBtnIcon fa fa-unlink"></i><?php echo Yii::t('organization','NOT A MEMBER') ?></a>');
										//bindFicheInfoBtn();
										//if (data.notification && data.notification=="toBeValidated")
										toastr.success(data.msg);	
										//else
										//	toastr.success("<?php echo Yii::t('organization','You are now a member of the organization : ') ?>"+contextData.name);
										loadByHash(location.hash);
									}
									else
										toastr.error(data.msg);
								},
							});  
                        }
                    }
                }
            }
        );
    }
	else{
		messageBox=trad["suretojoin"+parentType];
		if (connectType=="admin")
			messageBox += trad["as"+connectType];
		bootbox.confirm( messageBox+" ?", 
		function(result) {
			if (!result) {
				$(".becomeAdminBtn").removeClass("fa-spinner fa-spin").addClass("fa-user-plus");
				return;
			}
			console.log(formData);
			$.ajax({
				type: "POST",
				url: baseUrl+"/"+moduleId+"/link/connect",
				data: formData,
				dataType: "json",
				success: function(data) {
					if(data.result){
						//console.log("saveMembre");
						addFloopEntity(data.parent["_id"]["$id"], data.parentType, data.parent);
						//$("#linkBtns").html('<a href="javascript:;" class="removeMemberBtn tooltips " data-name="'+data.parent.name+'"'+ 
						//					'data-memberof-id="'+contextData["_id"]["$id"]+'" data-member-type="<?php echo Person::COLLECTION ?>" data-member-id="<?php echo Yii::app()->session["userId"] ?>" data-placement="left" '+
						//					'data-original-title="<?php echo Yii::t('organization','Remove from my Organizations') ?>" >'+
						//					'<i class=" disconnectBtnIcon fa fa-unlink"></i><?php echo Yii::t('organization','NOT A MEMBER') ?></a>');
						//bindFicheInfoBtn();
						//if (data.notification && data.notification=="toBeValidated")
							toastr.success(data.msg);	
						//else
						//	toastr.success("<?php echo Yii::t('organization','You are now a member of the organization : ') ?>"+contextData.name);
							loadByHash(location.hash);
					}
					else
						toastr.error(data.msg);
				},
			});  
		});             
	}
}		
var urlParams = {
	"#person.directory" : {title:"PERSON DIRECTORY ", icon : "share-alt"},
	"#organization.directory" : {title:"ORGANIZATION MEMBERS ", icon : "users"},
	"#project.directory" : {title:"PROJECT CONTRIBUTORS ", icon : "users"},
	"#city.directory" : {title:"CITY DIRECTORY ", icon : "bookmark fa-rotate-270"},
	"#city.opendata" : {title:'STATISTICS ', icon : 'line-chart' },
    "#person.detail" : {title:'PERSON DETAIL ', icon : 'user' },
    "#event.detail" : {title:'EVENT DETAIL ', icon : 'calendar' },
    "#project.detail" : {title:'PROJECT DETAIL ', icon : 'lightbulb-o' },
    "#organization.detail" : {title:'ORGANIZATION DETAIL ', icon : 'users' },
    "#city.detail" : {title:'CITY ', icon : 'university' },
    "#survey.entry.id" : {title:'VOTE LOCAL ', icon : 'legal'},
    "#rooms" : {title:'ACTION ROOMS ', icon : 'cubes'},
    "#admin.checkgeocodage" : {title:'CHECKGEOCODAGE ', icon : 'download'},
    "#admin.openagenda" : {title:'OPENAGENDA ', icon : 'download'},
    "#admin.importdata" : {title:'IMPORT DATA ', icon : 'download'},
    "#admin.index" : {title:'IMPORT DATA ', icon : 'download'},
    "#admin.directory" : {title:'IMPORT DATA ', icon : 'download'},
    "#default.directory" : {title:'COMMUNECTED DIRECTORY', icon : 'connectdevelop',"urlExtraParam":"&isSearchDesign"},
    "#default.news" : {title:'COMMUNECTED NEWS ', icon : 'rss' },
    "#default.agenda" : {title:'COMMUNECTED AGENDA ', icon : 'calendar'},
	"#default.home" : {title:'COMMUNECTED HOME ', icon : 'home'},
	"#home" : {"alias":"#default.home"},
	"#default.login" : {title:'COMMUNECTED AGENDA ', icon : 'calendar'}
};
function replaceAndShow(hash,params){
	res = false;
	$.each( urlParams, function(urlIndex,urlObj)
	{
		console.log("replaceAndShow2",urlIndex);
		if( hash.indexOf(urlIndex) >= 0 )
		{
			endPoint = urlParams[urlIndex];
			if( endPoint.alias )
				endPoint = replaceAndShow(endPoint.alias,params);
			extraParams = (endPoint.urlExtraParam) ? endPoint.urlExtraParam : "";
			showAjaxPanel( '/'+hash.replace( "#","" ).replace( /\./g,"/" )+params+extraParams, endPoint.title,endPoint.icon );
			res = true;
			return false;
		}
	});
	return res;
}
function loadByHash( hash , back ) { 
    console.log("loadByHash",hash);
    params = ( hash.indexOf("?") < 0 ) ? '?tpl=directory2&isNotSV=1' : "";

    if( replaceAndShow(hash,params) )
    	console.warn("loadByHash replaceAndShow",hash);
   
    else if( hash.indexOf("#panel") >= 0 ){
        if(hash.substr(7) == "box-add")
            title = 'ADD SOMETHING TO MY NETWORK';
        else
            title = "WELCOM MUNECT HEY !!!";
        showPanel(hash.substr(7),null,title);
    }
        
    else if( hash.indexOf("#organization.addorganizationform") >= 0 )
        showAjaxPanel( '/organization/addorganizationform?isNotSV=1', 'ADD AN ORGANIZATION','users' );
    else if( hash.indexOf("#person.invitesv") >= 0 )
        showAjaxPanel( '/person/invitesv?isNotSV=1', 'INVITE SOMEONE','share-alt' );
    else if( hash.indexOf("#event.eventsv") >= 0 )
        showAjaxPanel( '/event/eventsv?isNotSV=1', 'ADD AN EVENT','calendar' );
    else if( hash.indexOf("#project.projectsv") >= 0 )    
        showAjaxPanel( '/project/projectsv/id/'+userId+'/type/citoyen?isNotSV=1', 'ADD A PROJECT','lightbulb-o' );
    else if( hash.indexOf("#project.addcontributorsv") >= 0 ) {
	    hashT = hash.split(".");
        showAjaxPanel( '/'+hash.replace( "#","" ).replace( /\./g,"/" )+'?&isNotSV=1', 'Add contributors','plus' );
	}
	else if( hash.indexOf("#organization.addmember") >= 0 ) {
	    hashT = hash.split(".");
        showAjaxPanel( '/'+hash.replace( "#","" ).replace( /\./g,"/" )+'?&isNotSV=1', 'Add members','plus' );
	}
	else if( hash.indexOf("#event.addattendeesv") >= 0 ) {
	    hashT = hash.split(".");
        showAjaxPanel( '/'+hash.replace( "#","" ).replace( /\./g,"/" )+'?&isNotSV=1', 'ADD ATTENDEES','plus' );
	}
    else if( hash.indexOf("#rooms.index.type") >= 0 ){
        hashT = hash.split(".");
        showAjaxPanel( '/'+hash.replace( "#","" ).replace( /\./g,"/" )+'?&isNotSV=1', 'ACTIONS in this '+typesLabels[hashT[3]],'rss' );
    }

    else if( hash.indexOf("#news.index.type") >= 0 ){
        hashT = hash.split(".");
        showAjaxPanel( '/'+hash.replace( "#","" ).replace( /\./g,"/" )+'?&isNotSV=1', 'KESS KISS PASS in this '+typesLabels[hashT[3]],'rss' );
    } else if(userId != "")
        showAjaxPanel( '/news?isNotSV=1', 'KESS KISS PASS ','rss' );
    else
        showPanel('box-communecter',null,"WELCOM MUNECT HEY !!!",null);

    location.hash = hash;
    if( !back )
      history.pushState( { "hash" :hash} , null, hash );
    console.warn("pushState22",hash);

    if( isMapEnd )
    	showMap();


}

function showDefinition( id ){
	console.log("showDefinition",id);
	$(".main-col-search").animate({ opacity:0.3 }, 400 );
	$(".hover-info").css("display" , "inline");
	toggle( "."+id , ".explain" );
	return false;
}

var timeoutHover = setTimeout(function(){}, 0);
var hoverPersist = false;
var positionMouseMenu = "out";

function activateHoverMenu () { 
	//console.log("enter all");
	positionMouseMenu = "in";
	$(".main-col-search").animate({ opacity:0.3 }, 400 );
	$(".lbl-btn-menu-name").show(200);
	$(".lbl-btn-menu-name").css("display", "inline");
	$(".menu-button-title").addClass("large");

	showInputCommunexion();

	hoverPersist = false;
	clearTimeout(timeoutHover);
	timeoutHover = setTimeout(function(){
		hoverPersist = true;
	}, 1000);
}
