jQuery.noConflict();
(function( $ ) {
  $(function() {

	  	//Accrodion
		$('.TA_accordion_title').click(function(){
			var tabID = $(this).attr('id');
			if ($(this).hasClass('active')) {
				$(this).removeClass('active');
				$('#'+tabID+'-panel').removeClass('active');

			} else {
				$('.TA_accordion_title').removeClass('active');
				$('.TA_accordion_content').removeClass('active');
				$(this).toggleClass('active');
				$('#'+tabID+'-panel').addClass('active');

			}
		});
		
		//Tabmenu
		function tab_menu(){
					
			$('.TA_tabs > .TA_tab_title_responsive').each(function() {
				var tabIdString = $(this).attr('id');
				var tabId = tabIdString.replace(/tab-/g, '')
                var tab_item = '<li class="TA_tabmenuitem" data-menu="'+tabId+'">'+$(this).html()+'</li>';
				$(this).siblings( '.TA_tabmenu' ).append(tab_item);
            });
		}
		
		$('.TA_tabs').each(function() {
			$(this).find('.TA_tab_content:first').addClass('active');
		});
		setTimeout(function(){
			$('.TA_tabmenu').each(function() {
				$(this).find('li:first').addClass('active');
			});
		}, 1000);
		
		//Tabs Menu Click
		$('.TA_tabmenuitem').live('click', function(){
			var tabID = $(this).attr('data-menu');
			$(this).parent().find('li').removeClass('active');
			$(this).parent().parent().find('.TA_tab_content').removeClass('active');
			$(this).addClass('active');
			$('#tab-'+tabID+'-panel').addClass('active');
		});
		
		$( document ).ready( tab_menu );
		
  });
  
	
})(jQuery);





