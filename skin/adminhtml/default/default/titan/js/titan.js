jQuery(function($) {
	var baseUrl = $("#mst_base_url").val();
	MBTitan = {
		openPopup : function(url, title, eventHandler) {
			/* if ($('browser_window') && typeof(Windows) != 'undefined') {
				Windows.focus('browser_window');
				return;
			} */
			var dialogWindow = Dialog.info(null, {
				closable:true,
				resizable:true,
				draggable:true,
				className:'magento',
				windowClassName:'popup-window',
				title: title,
				top:20,
				width:1200,
				height:800,
				zIndex:1000,
				recenterAuto:false,
				hideEffect:Element.hide,
				showEffect:Element.show,
				id:'browser_window',
				url:url,
				onClose:function (param, el) {
					eventHandler();
				}
			});
		},
		closePopup : function() {
			Windows.close('browser_window');
		},
		closeHandler : function() {
			titanBlockGridJsObject.resetFilter();
		},
		addBlock : function() {
			var addBlockUrl = baseUrl + "titan/block/addBlock/new/yes";
			MBTitan.openPopup(addBlockUrl, "Add Block", MBTitan.closeHandler);
		},
		editBlock : function(inputId) {
			var blockId = inputId.id.split("_")[1];
			var editBlockUrl = baseUrl + "titan/block/addBlock/id/" + blockId;
			MBTitan.openPopup(editBlockUrl, "Edit Block", MBTitan.closeHandler);
		}
	}
});