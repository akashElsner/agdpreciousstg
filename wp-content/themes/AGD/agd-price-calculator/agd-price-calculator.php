<div class="AGD_Calculator">
    <div class="Calc_Header">
    	<div class="Calc_Header_Cell">
        	<button class="calcButton" id="refreshCalc"><i class="fa fa-refresh"></i><span>Refresh</span></button>
        </div>
        <div class="Calc_Header_Cell">
            <select id="PreciousMetal">
                <option value="Gold">Gold</option>
                <option value="Silver">Silver</option>
                <option value="Platinum">Platinum</option>
                <option value="Palladium">Palladium</option>
            </select>
        </div>
        <div class="Calc_Header_Cell">  
        
        
   
        <?php
			$xml = simplexml_load_file("https://agdglobal.com.au/app/xmlparsedcontent.php") or die("Error: Cannot create object");
		?>	
			<?php foreach ($xml->children() as $metals){ ?>
            
				<?php if($metals['Name'] == 'Gold'): ?>		
                <select id="Gold_CurrencyList" class="currencyList">
                    <option value="<?php $gd_aud = $metals->Price[1]['Bid']; echo $gd_aud*0.92; ?>"><?php echo $metals->Price[1]['Currency']; ?></option>
                    <option value="<?php $gd_usd = $metals->Price[0]['Bid']; echo $gd_usd*0.92; ?>"><?php echo $metals->Price[0]['Currency']; ?></option>
                    <option value="<?php $gd_eur = $metals->Price[2]['Bid']; echo $gd_eur*0.92; ?>"><?php echo $metals->Price[2]['Currency']; ?></option>
                    <option value="<?php $gd_gbp = $metals->Price[3]['Bid']; echo $gd_gbp*0.92; ?>"><?php echo $metals->Price[3]['Currency']; ?></option>
                </select>
                <?php endif; ?>
                
                <?php if($metals['Name'] == 'Silver'): ?>		
                <select id="Silver_CurrencyList" class="currencyList">
                    <option value="<?php $sl_aud = $metals->Price[1]['Bid']; echo $sl_aud*0.80; ?>"><?php echo $metals->Price[1]['Currency']; ?></option>
                    <option value="<?php $sl_usd = $metals->Price[0]['Bid']; echo $sl_usd*0.80; ?>"><?php echo $metals->Price[0]['Currency']; ?></option>
                    <option value="<?php $sl_eur = $metals->Price[2]['Bid']; echo $sl_eur*0.80; ?>"><?php echo $metals->Price[2]['Currency']; ?></option>
                    <option value="<?php $sl_gbp = $metals->Price[3]['Bid']; echo $sl_gbp*0.80; ?>"><?php echo $metals->Price[3]['Currency']; ?></option>
                </select>
                <?php endif; ?>
                
                <?php if($metals['Name'] == 'Platinum'): ?>		
                <select id="Platinum_CurrencyList" class="currencyList">
                    <option value="<?php $pl_aud = $metals->Price[1]['Bid']; echo $pl_aud*0.75; ?>"><?php echo $metals->Price[1]['Currency']; ?></option>
                    <option value="<?php $pl_usd = $metals->Price[0]['Bid']; echo $pl_usd*0.75; ?>"><?php echo $metals->Price[0]['Currency']; ?></option>
                    <option value="<?php $pl_eur = $metals->Price[2]['Bid']; echo $pl_eur*0.75; ?>"><?php echo $metals->Price[2]['Currency']; ?></option>
                    <option value="<?php $pl_gbp = $metals->Price[3]['Bid']; echo $pl_gbp*0.75; ?>"><?php echo $metals->Price[3]['Currency']; ?></option>
                </select>
                <?php endif; ?>
                
                <?php if($metals['Name'] == 'Palladium'): ?>		
                <select id="Palladium_CurrencyList" class="currencyList">
                    <option value="<?php $pa_aud = $metals->Price[1]['Bid']; echo $pa_aud*0.75; ?>"><?php echo $metals->Price[1]['Currency']; ?></option>
                    <option value="<?php $pa_usd = $metals->Price[0]['Bid']; echo $pa_usd*0.75; ?>"><?php echo $metals->Price[0]['Currency']; ?></option>
                    <option value="<?php $pa_eur = $metals->Price[2]['Bid']; echo $pa_eur*0.75; ?>"><?php echo $metals->Price[2]['Currency']; ?></option>
                    <option value="<?php $pa_gbp = $metals->Price[3]['Bid']; echo $pa_gbp*0.75; ?>"><?php echo $metals->Price[3]['Currency']; ?></option>
                </select>
                <?php endif; ?>
                
			<?php }	?>       
           
            <input type="hidden" id="Active_Bid" currency="" value="">
        </div>
    </div>
    
    <ul id="Unit_Tabs" class="Unit_Tabs">
        <li id="Ounce_Tab" bidvalue="" class="active">TROY OUNCE</li>
        <li id="Gram_Tab" bidvalue="">GRAM</li>
        <li id="Kilo_Tab" bidvalue="">KILO</li>
    </ul>
    
    <h6 class="bidprice_title">Bid Price</h6>
    <div id="Display_Bid_Feed" class="bid_feed" bidvalue=""></div>
    
    <div id="Calc_Panel_Gold" class="Calc_Panel">
        <form id="GoldPriceCalc">
            <div class="PriceCalc_Table">
                <div class="PriceCalc_Row">
                    <div class="PriceCalc_Cell purity_cell">9k</div>
                    <div class="PriceCalc_Cell unit_cell"><input class="unitBox" id="9k" type="text" percentage="0.375" /></div>
                    <div class="PriceCalc_Cell total_cell"><input class="unitPrice" id="9k_price" type="text" value="0.00" readonly /></div>
                </div>
                <div class="PriceCalc_Row">
                    <div class="PriceCalc_Cell purity_cell">14k</div>
                    <div class="PriceCalc_Cell unit_cell"><input class="unitBox" id="14k" type="text" percentage="0.5833333" /></div>
                    <div class="PriceCalc_Cell total_cell"><input class="unitPrice" id="14k_price" type="text" value="0.00" readonly /></div>
                </div>
                <div class="PriceCalc_Row">
                    <div class="PriceCalc_Cell purity_cell">18k</div>
                    <div class="PriceCalc_Cell unit_cell"><input class="unitBox" id="18k" type="text" percentage="0.75" /></div>
                    <div class="PriceCalc_Cell total_cell"><input class="unitPrice" id="18k_price" type="text" value="0.00" readonly /></div>
                </div>
                <div class="PriceCalc_Row">
                    <div class="PriceCalc_Cell purity_cell">21k</div>
                    <div class="PriceCalc_Cell unit_cell"><input class="unitBox" id="21k" type="text" percentage="0.875" /></div>
                    <div class="PriceCalc_Cell total_cell"><input class="unitPrice" id="21k_price" type="text" value="0.00" readonly /></div>
                </div>
                <div class="PriceCalc_Row">
                    <div class="PriceCalc_Cell purity_cell">22k</div>
                    <div class="PriceCalc_Cell unit_cell"><input class="unitBox" id="22k" type="text" percentage="0.9166666" /></div>
                    <div class="PriceCalc_Cell total_cell"><input class="unitPrice" id="22k_price" type="text" value="0.00" readonly /></div>
                </div>
                <div class="PriceCalc_Row">
                    <div class="PriceCalc_Cell purity_cell">24k</div>
                    <div class="PriceCalc_Cell unit_cell"><input class="unitBox" id="24k" type="text" percentage="1.00" /></div>
                    <div class="PriceCalc_Cell total_cell"><input class="unitPrice" id="24k_price" type="text" value="0.00" readonly /></div>
                </div>
                <div class="PriceCalc_Row">
                    <div class="PriceCalc_Cell purity_cell"><input type="reset" class="calcButton" value="Reset" /></div>
                    <div class="PriceCalc_Cell unit_cell">TOTAL</div>
                    <div class="PriceCalc_Cell total_cell"><input id="GoldPriceTotal" type="text" value="0.00" readonly /></div>
                </div>
            </div>
        </form>
    </div>
    
    <div id="Calc_Panel_Silver" class="Calc_Panel">
        <form id="SilverPriceCalc">
            <div class="PriceCalc_Table">
                <div class="PriceCalc_Row">
                    <div class="PriceCalc_Cell purity_cell">.500</div>
                    <div class="PriceCalc_Cell unit_cell"><input class="unitBox" id="s500" type="text" percentage="0.500" /></div>
                    <div class="PriceCalc_Cell total_cell"><input class="unitPrice" id="s500_price" type="text" value="0.00" readonly /></div>
                </div>
                <div class="PriceCalc_Row">
                    <div class="PriceCalc_Cell purity_cell">.800</div>
                    <div class="PriceCalc_Cell unit_cell"><input class="unitBox" id="s800" type="text" percentage="0.800" /></div>
                    <div class="PriceCalc_Cell total_cell"><input class="unitPrice" id="s800_price" type="text" value="0.00" readonly /></div>
                </div>
                <div class="PriceCalc_Row">
                    <div class="PriceCalc_Cell purity_cell">.925</div>
                    <div class="PriceCalc_Cell unit_cell"><input class="unitBox" id="s925" type="text" percentage="0.925" /></div>
                    <div class="PriceCalc_Cell total_cell"><input class="unitPrice" id="s925_price" type="text" value="0.00" readonly /></div>
                </div>
                <div class="PriceCalc_Row">
                    <div class="PriceCalc_Cell purity_cell">.999</div>
                    <div class="PriceCalc_Cell unit_cell"><input class="unitBox" id="s999" type="text" percentage="0.999" /></div>
                    <div class="PriceCalc_Cell total_cell"><input class="unitPrice" id="s999_price" type="text" value="0.00" readonly /></div>
                </div>
                <div class="PriceCalc_Row">
                    <div class="PriceCalc_Cell purity_cell"><input type="reset" class="calcButton" value="Reset" /></div>
                    <div class="PriceCalc_Cell unit_cell">TOTAL</div>
                    <div class="PriceCalc_Cell total_cell"><input id="SilverPriceTotal" type="text" value="0.00" readonly /></div>
                </div>
            </div>
        </form>
    </div>
    
    <div id="Calc_Panel_Platinum" class="Calc_Panel">
        <form id="PlatinumPriceCalc">
            <div class="PriceCalc_Table">
                <div class="PriceCalc_Row">
                    <div class="PriceCalc_Cell purity_cell">.950</div>
                    <div class="PriceCalc_Cell unit_cell"><input class="unitBox" id="plt950" type="text" percentage="0.950" /></div>
                    <div class="PriceCalc_Cell total_cell"><input class="unitPrice" id="plt950_price" type="text" value="0.00" readonly /></div>
                </div>
                <div class="PriceCalc_Row">
                    <div class="PriceCalc_Cell purity_cell">.9999</div>
                    <div class="PriceCalc_Cell unit_cell"><input class="unitBox" id="plt9999" type="text" percentage="0.9999" /></div>
                    <div class="PriceCalc_Cell total_cell"><input class="unitPrice" id="plt9999_price" type="text" value="0.00" readonly /></div>
                </div>
                <div class="PriceCalc_Row">
                    <div class="PriceCalc_Cell purity_cell"><input type="reset" class="calcButton" value="Reset" /></div>
                    <div class="PriceCalc_Cell unit_cell">TOTAL</div>
                    <div class="PriceCalc_Cell total_cell"><input id="PlatinumPriceTotal" type="text" value="0.00" readonly /></div>
                </div>
            </div>
        </form>
    </div>
    
    <div id="Calc_Panel_Palladium" class="Calc_Panel">
        <form id="PalladiumPriceCalc">
            <div class="PriceCalc_Table">
                <div class="PriceCalc_Row">
                    <div class="PriceCalc_Cell purity_cell">1.000</div>
                    <div class="PriceCalc_Cell unit_cell"><input class="unitBox" id="pldm1" type="text" percentage="1.000" /></div>
                    <div class="PriceCalc_Cell total_cell"><input class="unitPrice" id="pldm1_price" type="text" value="0.00" readonly /></div>
                </div>
                <div class="PriceCalc_Row">
                    <div class="PriceCalc_Cell purity_cell"><input type="reset" class="calcButton" value="Reset" /></div>
                    <div class="PriceCalc_Cell unit_cell">TOTAL</div>
                    <div class="PriceCalc_Cell total_cell"><input id="PalladiumPriceTotal" type="text" value="0.00" readonly /></div>
                </div>
            </div>
        </form>
    </div>
    
</div>