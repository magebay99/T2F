jQuery(function($) {
	var topElement = "#mainLayout",
		columnPrefix = "col-lg-",
		gridWrapperClass = ".border-dash",
		configEL = "a.btn.poper.active",
		modalEL = '.bs-example-modal-lg',
		placeholderContent = $("#placeholder_content").html();
	MBTitan = {
		chooseColumn : function () {
			var parentContainer, columnNum;
			$(topElement).on("click", ".pagination  li", function() {
				columnNum = parseInt($(this).text());
				parentContainer = $(this).closest(".pagination_setup").parent();
				if (!isNaN(columnNum)) {
					//Remove old setting
					parentContainer.children("div:not(.pagination_setup)").remove();
					parentContainer.prepend(MBTitan.getPlaceholderContent($(this).text()));
					/* MBTitan.reInstancePopover(); */
				}
			});
		}(),
		preview : function() {
			$(".btn-preview").click(function() {
				window.open($("#baseurl").val(),'_blank');
			});
		}(),
		getPlaceholderContent : function(column) {
			var divContent = new Array();
			divContent.push('<div class="col-lg-' + column + ' border-dash">');
			divContent.push('<p>'+ column +' col </p>');
			divContent.push('<a class="btn btn-default poper" data-toggle="modal" data-target=".bs-example-modal-lg" title="Manage Snippets"  ><i class="glyphicon glyphicon-cog"></i> <span>Control</span></a> ');
			divContent.push('<a class="btn btn-default remove-col" data-toggle="tooltip" title="Remove Sub Block"  ><i class="glyphicon glyphicon-remove-circle"></i> <span>Remove</span></a>');
			divContent.push('</div>');
			return divContent.join("");
		},
		updateGridColumn : function() {
			var rootModal = ".control_panel_popup";
			$("body").on("click", rootModal + " .update-grid", function(e) {
				MBTitan.updateGridClass($(configEL).closest(gridWrapperClass), $(rootModal + " .grid-columns").val(), $(rootModal + " .custom-class-input").val());
				$(configEL).closest(gridWrapperClass).find('.child-block').remove();
				$(rootModal + " input[type='checkbox']:checked").each(function() {
					$(configEL).closest(gridWrapperClass).append(MBTitan.getChildBlockHtml($(this).val(), $(this).parent().text()));
				});
				//MBTitan.closePopover();
				MBTitan.enabledSortChildBlock();
				$('.bs-example-modal-lg').modal('hide');
				MBTitan.unSave(e);
				//MBTitan.isAllConfigSaved(); //Save immediately after press Apply
			});
		}(),
		modalConfig : function() {
			$(modalEL).on('show.bs.modal', function (e) {
				$(e.relatedTarget).addClass("active");
				//Reset modal
				$("#find_addon").val("");
				$("#find_addon").keyup();
				$(".control_panel_popup input[type='checkbox']").prop("checked", false);
				$(".control_panel_popup .custom-class-input").val("");
				//set beforeData
				var parent = $(e.relatedTarget).closest(gridWrapperClass),
					childBlocks = MBTitan.getChildBlocks(parent),
					currentPopoverData = MBTitan.anlysicPopoverClass(parent.attr("class"));
				$(modalEL).find("select.grid-columns").val(currentPopoverData.column);
				$(modalEL).find("input.custom-class-input").val(currentPopoverData.custom_class);
				$(modalEL + " input[type='checkbox']").each(function() {
					if ($.inArray($(this).parent().text().trim(), childBlocks) !== -1) {
						this.checked = true;
					}
				});
			});
			$(modalEL).on('hidden.bs.modal', function (e) {
				$('a.btn.poper.active').removeClass("active");
			});
		}(),
		getChildBlockHtml : function(blockJson, title) {
			var childHTML = new Array();
			childHTML.push('<div style="margin: 3px; cursor: move;" class="btn btn-info btn-xs child-block">');
			childHTML.push('<span class="setting" style="display: none;">' + blockJson +'</span>');
			childHTML.push('<span class="block-title">' + title + '</span>');
			childHTML.push('</div>');
			return childHTML.join("");
		},
		preparePopover : function () {
			/* $('body').on('shown.bs.popover', '.poper', function () {
				var parent = $(this).closest(gridWrapperClass),
					childBlocks = MBTitan.getChildBlocks(parent),
					currentPopoverData = MBTitan.anlysicPopoverClass(parent.attr("class"));				
				parent.find("select.grid-columns").val(currentPopoverData.column);
				parent.find("input.custom-class-input").val(currentPopoverData.custom_class);
				$(".popover-content .list-group-item input[type='checkbox']").each(function() {
					if ($.inArray($(this).parent().text().trim(), childBlocks) !== -1) {
						this.checked = true;
					}
				});
			}); */
		}(),
		anlysicPopoverClass : function(gridClass) {
			if (!gridClass || gridClass == "") {
				return null;
			}
			var classes = gridClass.split(" ");
			var customClasses = new Array(),
				currentColumn;
			if (classes.length) {
				try {
					for (var i = 0; i < classes.length; i++) {
						if (classes[i].match(columnPrefix)) {
							currentColumn = classes[i].replace(columnPrefix, "");
						} else if (classes[i].match(gridWrapperClass.replace(".","")) === null) {
							if (classes[i].trim() != "ui-sortable") {
								customClasses.push(classes[i]);
							}
						}
					}
				} catch (error) {
					console.log('Can not found class in grid container');
				}
				return {
					column : currentColumn,
					custom_class : customClasses.join(" ")
				};
			}
			return null;
		},
		getChildBlocks : function(parent) {
			var blocks = new Array(); 
			parent.find(".btn.child-block").each(function() {
				blocks.push($(this).find('span.block-title').text().trim());
			});
			return blocks;
		},
		closePopover : function() {
			$("body").click();
		},
		updateGridClass : function(element, column, customClass) {
			//console.log(element);
			var currentClasses = element.attr("class").split(" "),
				gridInfo = new Array();
			if (currentClasses.length) {
				element.attr("class", "");
				element.addClass(gridWrapperClass.replace(".", ""));
				element.addClass(columnPrefix + column);
				element.addClass(customClass);
				element.children("p").remove();
				//Create grid info for current column
				// gridInfo.push('<p><span><i class="glyphicon glyphicon-move"></i></span>');
				gridInfo.push('<p><span class="column-num">'+ column +' col</span>');
				if (customClass != "") {
					gridInfo.push(' | ');
					gridInfo.push('<span class="class-list">' + customClass.trim().split(" ").join(",") +'</span>');
				}
				gridInfo.push('</p>');
				element.prepend(gridInfo.join(''));
				MBTitan.enabledSortChildBlock();
				/* console.log(currentClasses);
				console.log(customClass.split(" "));
				for (var i = 0; i < currentClasses.length; i++) {
					if (currentClasses[i].match("col-xs-")) {
						element.removeClass(currentClasses[i]);
						element.addClass(columnPrefix + column);
						element.addClass(customClass);
						element.children("p").remove();
						//Create grid info for current column
						// gridInfo.push('<p><span><i class="glyphicon glyphicon-move"></i></span>');
						gridInfo.push('<p><span class="column-num">Grid ('+ column +')</span>');
						if (customClass != "") {
							gridInfo.push(' | ');
							gridInfo.push('<span class="class-list">' + customClass +'</span>');
						}
						gridInfo.push('</p>');
						element.prepend(gridInfo.join(''));
						return;
					}
				} */
			}
		},
		addBlock : function() {
			/* $("body").on("click", ".popover .btn-block", function() {
				if ($(".popover-content .list-group input[type='checkbox']:checked").length) {
					//Remove old block
					$(this).closest(gridWrapperClass).find('.child-block').remove();
					$(".popover-content .list-group input[type='checkbox']:checked").each(function() {
						$(this).closest(gridWrapperClass).append('<button type="button" style="margin: 3px" class="btn btn-info child-block">'+$(this).parent().text()+'</button>')
					});
					MBTitan.closePopover();
				}
			}); */
		}(),
		reInstancePopover : function () {
			/*$('.poper').popover({
				html: true,
				placement: 'left',
				title: function () {
					return  $("#blocks-head").html();
				},
				content: function () {
					return $("#blocks-content").html();
				}
			});*/
		},
		saveLayoutConfig : function() {
			$(".btn-save-config").click(function(e) {
				var finalLayout = {},
					blockData,
					rootBlockData,
					widthConfig = "";
				var parentBlock = $(this).closest("div[data-block]");
				if ($(parentBlock).find("div[data-block]").length) {
					rootBlockData = $(this).closest("div[data-block]").attr("data-block");
					$(parentBlock).find("div[data-block]").each(function() {
						blockData = $(this).attr("data-block");
						if (blockData !== undefined) {
							if (MBTitan.getBlockConfig(blockData).length) {
								finalLayout[blockData] = MBTitan.getBlockConfig(blockData);
							}
						}
					});
				} else {
					blockData = parentBlock.attr("data-block");
					rootBlockData = blockData;
					if (MBTitan.getBlockConfig(blockData).length) {
						finalLayout[blockData] = MBTitan.getBlockConfig(blockData);
					}
				}
				//if (!$.isEmptyObject(finalLayout)) {
					//Check layout width type
					if ($(this).closest("div[data-block]").find(".box-width-type.active").attr("rel") !== undefined) {
						widthConfig = $(this).closest("div[data-block]").find(".box-width-type.active").attr("rel");
					}
					var requestUrl = $("#baseurl").val() + "titan/index/saveblock/";
					var finalBlockData = {
						finalBlockConfig : finalLayout
					}
					MBTitan.sendRequest(requestUrl, JSON.stringify(finalBlockData), rootBlockData, widthConfig, function(response) {
						if (response == "" || response == "null") {
							alert("Something when wrong!");
							return;
						}
						try {
							var responseJson = JSON.parse(response);
							if (responseJson.status == "success") {
								console.log(responseJson.message);
								MBTitan.removeUnSaveClass(e);
								MBTitan.isAllConfigSaved();
								if(!MBTitan.isHasUnSaveClass()) {
									$("#loading-mask").hide();
								}
							} else {
								alert(responseJson.message);
							}
						} catch (error) {
							alert("Response data format is invalid!");
							console.log(error);
						}
					});
				//}
			});
		}(),
		getBlockConfig : function(dataBlock) {
			//var rootBlocks = ["after_main_content", "before_main_content", "header"];
			var blockSelector = $(topElement).find("div[data-block='"+dataBlock+"']"),
				blockInfo,
				blockWidth = "",
				finalSetting = new Array();
			blockSelector.find("div" + gridWrapperClass).each(function(pIndex, pValue) {
				if ($(pValue).find(".child-block").length) {
					$(pValue).find(".child-block").each(function(cIndex, cValue) {
						if ($(this).closest("div[data-block]").find(".box-width").length) {
							blockWidth = $(this).closest("div[data-block]").find(".box-width .box-width-type.active").attr("rel");
						}
						blockInfo = {
							index : cIndex,
							parentIndex : pIndex,
							parentClasses : $(pValue).attr('class'),
							blockDetails : $(this).children(".setting").html(),
							block_width : blockWidth
						};
						finalSetting.push(blockInfo);
					});
				}
			});
			return finalSetting;
		},
		addColumn : function() {
			var parentEl, columnContent;
			$("body").on("click", ".btn-add-column", function(e) {
				columnContent = MBTitan.getPlaceholderContent(12);
				parentEl = $(this).closest("div.block-change");
				parentEl.children().append(columnContent);
				/* MBTitan.reInstancePopover(); */
				MBTitan.unSave(e);
			});
		}(),
		removeColumn : function() {
			$("body").on("click", gridWrapperClass + " .remove-col", function (e) {
				if($(this).closest(gridWrapperClass).find(".btn.child-block").length) {
					if (confirm("Do you want to remove this column?")) {
						MBTitan.unSave(e);
						$(this).closest(gridWrapperClass).remove();
					}
				} else {
					MBTitan.unSave(e);
					$(this).closest(gridWrapperClass).remove();
				}
			});
		}(),
		sendRequest : function (url, config, rootBlock, width, callback) {
        	$.ajax({
				type : "POST",
				url : url,
				data : {
					config : config, 
					root_block : rootBlock,
					width_config : width
				},
				beforeSend : function () {										
					$("#loading-mask").show();
				},
				error : function () {
					console.log('Transfer error!');
				},
				success : function (response) {
					callback(response);
					//$(".progress-bar").hide();										
				}
			});
        },
		setBoxWidth : function() {
			$("body").on("click", "ul.box-width li a", function(e) {
				$(this).closest("ul").find("a").removeClass("active");
				$(this).addClass("active");
				MBTitan.unSave(e);
			});
		}(),
		initializeHeaderAndFooter : function(rootBlockKey) {
			var blockData = $("input[name='data-block[" + rootBlockKey + "]']").val();
			if (blockData == "") {
				return;
			}
			var blockConfigJson = JSON.parse(blockData);
			if (!$.isEmptyObject(blockConfigJson)) {
				$.each(blockConfigJson, function(blockKey, dataBlockObj) {
					if (blockKey == "width_config") {
						//Active width_config for header or footer block
						if (dataBlockObj != "") {
							MBTitan.activeBlockWidth(rootBlockKey, dataBlockObj);
						}
						return;
					}
					MBTitan.renderChildBlocks(blockKey, dataBlockObj);
				});
			}
		},
		renderChildBlocks : function(blockKey, configObj) {
			if (!$.isEmptyObject(configObj)) {
				var	blockEL = $("div[data-block='" + blockKey + "']");
				blockEL.find(gridWrapperClass).remove();
				$.each(configObj, function(index, gridColumn) {
					var blockDataClass = MBTitan.anlysicPopoverClass(gridColumn.custom_class);
					var divContent = new Array(),
						childBlocks = gridColumn.child_blocks;
					divContent.push('<div class="' + gridColumn.custom_class + '">');
					divContent.push('<p>'+ blockDataClass.column +' col');
					if (blockDataClass.custom_class != "") {
							divContent.push(' | ');
							divContent.push('<span class="class-list">' + blockDataClass.custom_class.trim().split(" ").join(",") +'</span>');
					}
					divContent.push("</p>");
					divContent.push('<a class="btn btn-default poper" data-toggle="modal" data-target=".bs-example-modal-lg" title="Manage Snippets"  ><i class="glyphicon glyphicon-cog"></i> <span>Control</span></a> ');
			divContent.push('<a class="btn btn-default remove-col" data-toggle="tooltip" title="Remove Sub Block"  ><i class="glyphicon glyphicon-remove-circle"></i> <span>Remove</span></a>');
					//Append child-block here
					$.each(childBlocks, function(cIndex, cValue) {
						var childBlockInfo = JSON.parse(cValue);
						divContent.push(MBTitan.getChildBlockHtml(cValue, childBlockInfo.title))
					});
					divContent.push('</div>');
					blockEL.append(divContent.join(""));
				});
				MBTitan.enabledSortChildBlock();
			}
		},
		initializeBeforeAndAfterContent : function(rootBlockKey) {
			var blockData = $("input[name='data-block[" + rootBlockKey + "]']").val();
			if (blockData == "") {
				return;
			}
			var rootBlockJson = JSON.parse(blockData);
			var blockConfigJson = rootBlockJson.block_config,
				blockWidth = rootBlockJson.block_width;
			//Active width_config for block 1 to 6
			$.each(blockWidth, function (blockKey, widthConfig) {
				MBTitan.activeBlockWidth(blockKey, widthConfig);
			});
			if (!$.isEmptyObject(blockConfigJson)) {
				$.each(blockConfigJson, function(blockKey, dataBlockObj) {
					MBTitan.renderChildBlocks(blockKey, dataBlockObj);
				});
			}
		},
		initializeAllChildBlocks : function() {
			MBTitan.initializeHeaderAndFooter("header");
			MBTitan.initializeHeaderAndFooter("footer");
			MBTitan.initializeBeforeAndAfterContent("before_main_content");
			MBTitan.initializeBeforeAndAfterContent("after_main_content");
			MBTitan.initializeMainContent();
			MBTitan.initializeHiddenBlocks();
		},
		initializeHiddenBlocks : function () {
			var hiddenBlockData = $("input[name='data-block[hidden_blocks]']").val();
			if (hiddenBlockData != "") {
				var hiddenBlockJson = JSON.parse(hiddenBlockData);
				if (!$.isEmptyObject(hiddenBlockJson)) {
					$.each(hiddenBlockJson, function(index, rootBlockObj) {
						if (!$.isEmptyObject(rootBlockObj)) {
							$.each(rootBlockObj, function(blockKey, dataBlockObj) {
								MBTitan.renderChildBlocks(blockKey, dataBlockObj);
							});
						}
					});
				}
			}
		},
		activeBlockWidth : function(blockKey, widthConfig) {
			$("div[data-block='"+ blockKey +"']").find("ul.box-width a[rel='" + widthConfig +"']").addClass("active");
		},
		initializeMainContent : function() {
			var mainContentData = $("input[name='data-block[maincontent]']").val();
			if (mainContentData != "") {
				var mainContentJson = JSON.parse(mainContentData);
				$.each(mainContentJson, function (blockKey, gridColumn) {
					if (blockKey == "width_config") {
						//Active width_config for maincontent
						if (gridColumn != "") {
							MBTitan.activeBlockWidth("maincontent", gridColumn);
						}
						return;
					}
					MBTitan.renderChildBlocks(blockKey, gridColumn);
				});
			}
		},
		activeCurrentTab : function() {
			var currentTab = $("#current_tab").val();
			if (currentTab != "") {
				$("#mainTab a[href='#" + currentTab +"']").click();
			}
		}(),
		enabledSortChildBlock : function() {
			$(gridWrapperClass).sortable({
				items: "div.btn.child-block"
			});
			$(gridWrapperClass).disableSelection();
		},
		filterByGroup : function() {
			var currentGroupId;
			$("#filter_block li").on("click", function() {
				$("#filter_block li").removeClass("active");
				$(this).addClass("active");
				currentGroupId = $(this).attr("rel");
				if (currentGroupId == "") return false;
				if(currentGroupId == "reset") {
					$(".child-block-wrapper .filtered-by").hide();
					$("#add-on-blocks li").show();
				} else {
					$(".child-block-wrapper .filtered-by").text('Filtered by "' + $(this).text() + '"').show();
					$("#add-on-blocks li").each(function() {
						if ($(this).attr("rel") != "") {
							var groupIds = $(this).attr("rel").split(",");
							if($.inArray(currentGroupId, groupIds) == -1) {
								$(this).hide();
							} else {
								$(this).show();
							}
						}
					});
				}
			});
		}(),
		filterByStore : function() {
			var storeId;
			$("#filter_block_by_store li").on("click", function() {
				$("#filter_block_by_store li").removeClass("active");
				$(this).addClass("active");
				storeId = $(this).attr("rel");
				if (storeId == "reset") {
					$(".child-block-wrapper .filtered-by").hide();
					$("#add-on-blocks li").show();
					return false;
				}
				$(".child-block-wrapper .filtered-by").text('Show in "' + $(this).text() + '"').show();
				$("#add-on-blocks li").each(function() {
					if ($(this).attr("store") !== "") {
						var storeIds = $(this).attr("store").split(",");
						if($.inArray(storeId, storeIds) == -1 && $.inArray("0", storeIds) == -1) {
							$(this).hide();
						} else {
							$(this).show();
						}
					} else {
						//$(this).show();//For old data that have no store view setting.
					}
				});
			});
		}(),
		searchAddons : function() {
			var searchKey;
			$("#find_addon").keyup(function() {
				$(".block-not-found").hide();
				searchKey = this.value.toLowerCase().replace(/\s/g,'');
				$(".control_panel_popup .nav-addons li").show();
				$(".control_panel_popup .nav-addons li label").each(function() {
					if($(this).text() != "") {
						if(!$(this).text().toLowerCase().replace(/\s/g,'').match(searchKey)) {
							$(this).parent().hide();
						}
					}
				});
				if(!$(".control_panel_popup .nav-addons li:visible").length && searchKey != "") {
					$(".block-not-found").show();
				}
			});
		}(),
		unSave : function(e) {
			var element = $(e.target);
			//Check if Appy btn in modal clicked
			if(element.hasClass("update-grid")) {
				element = $(configEL);
			}
			//Find current data block
			var dataBlockId = element.closest(".tab-pane").attr("id");
			if(dataBlockId != "" && dataBlockId !== undefined) {
				$("#block_list li a[href='#" + dataBlockId + "']").closest("li").addClass("un-save");
			}
		},
		removeUnSaveClass : function(e) {
			var element = $(e.target);
			//Find current data block
			var dataBlockId = element.closest(".tab-pane").attr("id");
			if(dataBlockId != "" && dataBlockId !== undefined) {
				$("#block_list li a[href='#" + dataBlockId + "']").closest("li").removeClass("un-save");
			}
		},
		saveAllConfig : function() {
			$("#save_all_config").click(function() {
				if (MBTitan.isHasUnSaveClass()) {
					var blockId = $("#block_list li.un-save:first a").attr("href");
					if(blockId !== undefined) {
						var saveBtn = $(blockId).find(".btn-save-config").click();
					}
				}
			});
		}(),
		isAllConfigSaved : function() {
			if (MBTitan.isHasUnSaveClass()) {
				$("#save_all_config").click();
			}
		},
		isHasUnSaveClass : function() {
			if ($("#block_list li.un-save").length) {
				return true;
			}
			return false;
		},
		openAddBlockIframe : function() {
			$("#add_titan_block").click(function() {
				$("#add_block_iframe").css({
					"top" : 0,
					"left" : 0,
					"right" : 0,
					"width" : "100%",
					"height" : "100%",
					"background" : "#fafafa"
				});
				$("#html-body").css({
					"overflow" : "hidden"
				});
			});
		}()
	}
	MBTitan.initializeAllChildBlocks();
});