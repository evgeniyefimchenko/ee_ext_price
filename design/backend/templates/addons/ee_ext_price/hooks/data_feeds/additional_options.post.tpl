{include file="common/subheader.tpl" title=__('ee_ext_price.persile') target="#ee_persile"}
{if $addons.ee_ext_price.ee_ext_price_active == 'Y'}
	<div id="ee_persile"  class="in collapse">
		<div class="control-group">
			<label for="ee_ext_price_persile" id="label_save_directory" class="control-label">{__("ee_ext_price_persile")}:</label>
			<div class="controls">
				<input type="text" name="datafeed_data[ee_persile]" id="ee_ext_price_persile" size="55" value="{$datafeed_data.ee_persile}" class="input-text-large" />
			</div>
		</div>
		<div class="control-group">
			<label for="ee_ext_price_ee_add_opt1_text" id="label_save_directory" class="control-label">{__("ee_ext_price_ee_add_opt1_text")}:</label>
			<div class="controls">
				<input type="text" name="datafeed_data[ee_add_opt1_text]" id="ee_ext_price_ee_add_opt1_text" size="55" value="{$datafeed_data.ee_add_opt1_text}" class="input-text-large" />
			</div>
		</div>
		<div class="control-group">
			<label for="ee_ext_price_ee_add_opt1" id="label_save_directory" class="control-label">{__("ee_ext_price_ee_add_opt1")}:</label>
			<div class="controls">
				<input type="text" name="datafeed_data[ee_add_opt1]" id="ee_ext_price_ee_add_opt1" size="55" value="{$datafeed_data.ee_add_opt1}" class="input-text-large" />
			</div>
		</div>
		<div class="control-group">
			<label for="ee_ext_price_ee_add_opt2_text" id="label_save_directory" class="control-label">{__("ee_ext_price_ee_add_opt2_text")}:</label>
			<div class="controls">
				<input type="text" name="datafeed_data[ee_add_opt2_text]" id="ee_ext_price_ee_add_opt2_text" size="55" value="{$datafeed_data.ee_add_opt2_text}" class="input-text-large" />
			</div>
		</div>
		<div class="control-group">
			<label for="ee_ext_price_ee_add_opt2" id="label_save_directory" class="control-label">{__("ee_ext_price_ee_add_opt2")}:</label>
			<div class="controls">
				<input type="text" name="datafeed_data[ee_add_opt2]" id="ee_ext_price_ee_add_opt2" size="55" value="{$datafeed_data.ee_add_opt2}" class="input-text-large" />
			</div>
		</div>
	</div>
{/if}
