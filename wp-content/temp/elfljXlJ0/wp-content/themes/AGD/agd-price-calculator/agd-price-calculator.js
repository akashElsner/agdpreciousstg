jQuery.noConflict();
(function( $ ) {
	$(function() {
		
		/***General>>>***/
		function addCommas(x) {
			var parts = x.toString().split('.');
			parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
			return parts.join('.');
		}
		
		/*Defaults>>>*/
		function Default_Dataset(){
			$('.currencyList').removeClass('active');
			$('.Calc_Panel').removeClass('active');
			getTab = $('#PreciousMetal').val();
			$('#'+getTab+'_CurrencyList').addClass('active');
			$('#Calc_Panel_'+getTab).addClass('active');
		
			getBid = $('.currencyList.active').val();
			$('#Active_Bid').val(getBid);
			$('#Active_Bid').attr('currency','$');
		}
		$('#refreshCalc').click(function(){
			window.location.reload(true);
		});
		/*<<<Defaults*/
		
		$('#PreciousMetal').change(function(){
			var Metal = $(this).val();
			$('.Calc_Panel').removeClass('active');
        	$('#Calc_Panel_' + Metal).addClass('active');
			$('.currencyList').removeClass('active');
			$('#'+Metal+'_CurrencyList').addClass('active');
			var Active_Feed = $('#'+Metal+'_CurrencyList').val();
			$('#Active_Bid').val(Active_Feed);
			
			var Currency = $('#'+Metal+'_CurrencyList').find(":selected").text();
			if(Currency == 'USD' || Currency == 'AUD'){
				var Symbol = '$';
			}else if (Currency == 'EUR'){
				var Symbol = '€';
			}else if (Currency == 'GBP'){
				var Symbol = '£';
			}
			$('#Active_Bid').attr('currency', Symbol);
			BidSetup();
		});
		
		$('.currencyList').change(function(){
			var Active_Feed = $(this).val();
			var Currency = $(this).find(":selected").text();
			if(Currency == 'USD' || Currency == 'AUD'){
				var Symbol = '$';
			}else if (Currency == 'EUR'){
				var Symbol = '€';
			}else if (Currency == 'GBP'){
				var Symbol = '£';
			}
			$('#Active_Bid').val(Active_Feed);
			$('#Active_Bid').attr('currency', Symbol);
			BidSetup();
		});
		
		/***<<<General***/
		
		/***General***/
		function BidSetup(){
			var Bid_Active = Number($('#Active_Bid').val().replace(/[^\d\.\-\ ]/g, ''));
			var Bid_Active_Adjust = Bid_Active;
			var Bid_Ounce = (Bid_Active_Adjust).toFixed(2);
			var Bid_Gram = (Bid_Active_Adjust/31.1035).toFixed(2);
			var Bid_Kilo = (Bid_Active_Adjust/0.0311035).toFixed(2);
			
			$('#Ounce_Tab').attr('bidvalue',Bid_Ounce);
			$('#Gram_Tab').attr('bidvalue',Bid_Gram);
			$('#Kilo_Tab').attr('bidvalue',Bid_Kilo);
			
			var ActiveTabValue = $('#Unit_Tabs > li.active').attr('bidvalue');
			var ActiveCurrency = $('#Active_Bid').attr('currency');
			$('#Display_Bid_Feed').attr('bidvalue',ActiveTabValue);
			$('#Display_Bid_Feed').html(ActiveCurrency+addCommas(ActiveTabValue));
			
			PriceCalc();
			
		}
		$('#Unit_Tabs > li').click(function(){
			$('#Unit_Tabs > li').removeClass('active');
			$(this).addClass('active');
			var currentBidValue = $(this).attr('bidvalue');
			var ActiveCurrency = $('#Active_Bid').attr('currency');
			$('#Display_Bid_Feed').attr('bidvalue',currentBidValue);
			$('#Display_Bid_Feed').html(ActiveCurrency+addCommas(currentBidValue));
			
			PriceCalc();
			
		});
		
		/**Gold Price Calc>>>>**/
		function PriceCalc(){
			ActiveMetal = $('#PreciousMetal').val();
			$('#'+ActiveMetal+'PriceCalc .unitBox').each(function(){
				var NewUnit = $(this).val();
				var FieldId = $(this).attr('id');
				var FieldPercentage = $(this).attr('percentage');
				var BidPrice = $('#Display_Bid_Feed').attr('bidvalue');
				var FieldPrice = (NewUnit*FieldPercentage*BidPrice).toFixed(2);
				var ActiveCurrency = $('#Active_Bid').attr('currency');
				$('#'+FieldId+'_price').val(ActiveCurrency+FieldPrice);
			});
			var total = 0;
			$('#'+ActiveMetal+'PriceCalc .unitPrice').each(function(){
				total += +$(this).val().replace(/[^\d\.]/g, '');;
			});
			$('#'+ActiveMetal+'PriceTotal').val('$'+total.toFixed(2));
		}		
		/*function resetPrice(){
			ActiveMetal = $('#PreciousMetal').val();
			$('#'+ActiveMetal+'PriceCalc .unitBox').val('');
			$('#'+ActiveMetal+'PriceCalc .unitPrice').val('$0.00');
			$('#'+ActiveMetal+'PriceTotal').val('$0.00');
		}*/
		/**<<<<Gold Price Calc**/
									
		$('body').on('DOMSubtreeModified', '#Active_Bid', function() {
			BidSetup();
		});
		
		$(document).on('change paste keyup','.unitBox', PriceCalc);
		
		$(document)
		.ready(Default_Dataset)
		.ready(BidSetup)
		//.ready(resetPrice);
	});
})(jQuery);