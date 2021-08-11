{include file="common/subheader.tpl" title=__('ee_ext_price.persile') target="#ee_persile"}
{if $addons.ee_ext_price.ee_ext_price_active == 'Y'}
	<div id="ee_persile"  class="in collapse">
		<div class="control-group">
			<label for="ee_ext_price_persile" id="label_save_directory" class="control-label">{__("enter_data")}:</label>
			<div class="controls">
				<input type="text" name="datafeed_data[ee_persile]" id="ee_ext_price_persile" size="55" value="{$datafeed_data.ee_persile}" class="input-text-large" />
			</div>
		</div>
	</div>
{/if}
